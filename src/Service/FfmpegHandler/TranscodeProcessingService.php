<?php
/**
 * @copyright 2024 Ожерельев Валерий Александрович
 */
namespace App\Service\FfmpegHandler;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Entity\TranscodingProcesses;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Данный класс реализует запуск транскодирования
 */
class TranscodeProcessingService extends TranscoderBaseService
{


    private $entityManager;
    private $transcodingProcess;

    /**
     * Конструктор
     *
     * @param EntityManagerInterface $entityManager
     * @param TranscodingProcesses $transcodingProcess
     */
    public function __construct(EntityManagerInterface $entityManager, TranscodingProcesses $transcodingProcess, ParameterBagInterface $parameterBag)
    {
        parent::__construct($parameterBag);
        $this->entityManager = $entityManager;
        $this->transcodingProcess = $transcodingProcess;
    }

    
    /**
     * Небольшая автоматизация обновления записи в статусах
     *
     * @param string $status
     * @param string $resolution
     * @param string|null|null $message
     * @return void
     */
    public function setUpdate(string $status, string $resolution, string|null $message = null):void
    {
        if ($status == 'Ошибка') {
            $this->transcodingProcess->setStatus('Ошибка');
            $this->transcodingProcess->setStage($resolution);
            $this->transcodingProcess->setErrorMessage($message);
        } elseif ($status == 'Завершено') {
            $this->transcodingProcess->setStatus('Завершено');
            $this->transcodingProcess->setStage($resolution);
            $this->transcodingProcess->setEndTime(new \DateTimeImmutable());
        }

        $this->transcodingProcess->setUpdateAt(new \DateTimeImmutable());
        $this->entityManager->persist($this->transcodingProcess);
        $this->entityManager->flush();
    }

    /**
     * Копирование заранее заготовленного мастер плейлиста в соответствии с имеющимися разрещениями
     *
     * @param string $outputDir
     * @param string $resolution
     * @return void
     */
    private function postProcessing(string $outputDir, string $resolution):void
    {
        // Скрипт: Копируем плейлист
        // $sourceFolder = $this->appBaseDir . 'public/playlist_all' . '/' . $resolution;

        $sourceFolder = '/TRANSCODE/ffmpegphp/public/playlist_all' . '/' . $resolution;
        // Проверяем, существует ли исходный файл
        if (!file_exists($sourceFolder . '/' . $this->masterPlaylist)) {
            throw new \Exception('Error Not Found: ' . $sourceFolder . '/' . $this->masterPlaylist);
        }

        // Копируем плейлист в целевую папку
        copy($sourceFolder . '/' . $this->masterPlaylist, $outputDir . '/' . $this->masterPlaylist);
    }

    /**
     * Класс запускающий процесс транскодирования
     *
     * @param array $command
     * @param string $name
     * @return void
     */
    public function processing(array $command, string $name, string $resolution, string $targetDir, string $user, $kpId): void
    {

        $errorLogger = new Logger('error');
        $errorLogger->pushHandler(new StreamHandler($this->logDirectory . '/error.log', Logger::ERROR));

        $successLogger = new Logger('success');
        $successLogger->pushHandler(new StreamHandler($this->logDirectory . '/success.log', Logger::INFO));

        $uniqueLogFile = $this->logDirectory . '/process_' . $name . '.log';

        $process = new Process($command);
        $process->setTimeout(172800);

        try {
            $process->start();

            // Регулярное выражение для поиска номера диска
            $pattern = '#/HDD/(\d+)/VOD/#';

            if (preg_match($pattern, $targetDir, $matches)) {
                $hdd = $matches[1];
            } 

            //Костыль, вынести куданибудь
            $path = $targetDir;
            $segments = explode('/', trim($path, '/'));
            $lastSegment = end($segments);


            $this->transcodingProcess->setFilmName($lastSegment);
            $this->transcodingProcess->setStartTime(new \DateTime());
            $this->transcodingProcess->setCtreatedAt(new \DateTimeImmutable());
            $this->transcodingProcess->setStatus('Запущен!');
            $this->transcodingProcess->setStage($resolution);
            $this->transcodingProcess->setOrigTorrentFileName($name);
            $this->transcodingProcess->setKpId($kpId);
            $this->transcodingProcess->setHdd($hdd);
            $this->transcodingProcess->setEndTime(null);
            
            $this->transcodingProcess->setUserSubmittedBy($user);
            $this->transcodingProcess->setPID($process->getPid());

            $this->entityManager->persist($this->transcodingProcess);
            $this->entityManager->flush();


            $process->wait(function ($type, $buffer) use ($uniqueLogFile) {
                file_put_contents($uniqueLogFile, $buffer, FILE_APPEND);
            });

            $this->postProcessing($targetDir, $resolution);

            if ($process->isSuccessful()) {
                $successLogger->info('Transcoding successful: ' . $process->getOutput());
                $this->setUpdate('Завершено', $resolution);
            } else {
                $errorMessage = 'Transcoding failed: ' . $process->getErrorOutput();
                $errorLogger->error($errorMessage);
                $this->setUpdate('Ошибка', $resolution, $errorMessage);

                throw new \Exception($errorMessage);
            }
        } catch (ProcessFailedException $exception) {

            $errorLogger->error($exception->getMessage());
            $this->setUpdate('Ошибка', $resolution, $exception->getMessage());

            throw $exception;
        } catch (\Throwable $error) {

            $errorLogger->error($error->getMessage());
            $this->setUpdate('Ошибка', $resolution, $error->getMessage());

            throw $error;
        }
    }
}
