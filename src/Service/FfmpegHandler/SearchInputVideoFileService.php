<?php
/**
 * @copyright 2024 Ожерельев Валерий Александрович
 */
namespace App\Service\FfmpegHandler;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Данный класс рекурсивно ищет видеофайлы в указанной директории
 */
class SearchInputVideoFileService extends TranscoderBaseService
{

    public function __construct(ParameterBagInterface $parameterBag){
        
        parent::__construct($parameterBag);
    }

    /**
     * Метод реализует поиск видеофайлов
     *
     * @param string $rootPath
     * @return array
     */
    public function __invoke(string $rootPath): array
    {
        try {
            $finder = new Finder();
            $finder->files()->in($rootPath)->name($this->supportedExtensions);

            $errorLogger = new Logger('error');
            $errorLogger->pushHandler(new StreamHandler($this->logDirectory . '/search_video_error.log', Logger::ERROR));

            $videos = [];
            foreach ($finder as $file) {
                $videos[] = $file->getRealPath();
            }

            if (empty($videos)) {
                $errorMessage = 'Не удалось найти видео файлы в директории ' . $rootPath;
                $errorLogger->error($errorMessage);
                throw new \Exception($errorMessage);
            }
        } catch (ProcessFailedException $exception) {
            $errorLogger->error($exception->getMessage());
            throw $exception;
        }

        return $videos;
    }
}
