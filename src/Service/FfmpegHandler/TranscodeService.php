<?php
/**
 * @copyright 2024 Ожерельев Валерий Александрович
 */
namespace App\Service\FfmpegHandler;

use App\Service\FfmpegHandler\CommandMakerService;
use App\Service\FfmpegHandler\SearchInputVideoFileService;
use App\Service\FfmpegHandler\TranscodeProcessingService;
use App\Service\FfmpegHandler\ReturnCompliteService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Транскодер
 */
class TranscodeService extends TranscoderBaseService
{
    private $debug;
    private $commandMaker;
    private $transcodeProcessing;
    private $searchInputVideoFile;
    private $returnComplite;

    /**
     * Конструктор
     *
     * @param CommandMakerService $commandMaker
     * @param SearchInputVideoFileService $searchInputVideoFile
     * @param TranscodeProcessingService $transcodeProcessing
     */
    public function __construct(CommandMakerService $commandMaker, SearchInputVideoFileService $searchInputVideoFile, TranscodeProcessingService $transcodeProcessing, ParameterBagInterface $parameterBag, ReturnCompliteService $returnComplite)
    {
        parent::__construct($parameterBag);
        $this->returnComplite = $returnComplite;
        $this->commandMaker = $commandMaker;
        $this->transcodeProcessing = $transcodeProcessing;
        $this->searchInputVideoFile = $searchInputVideoFile;
    }
    

    /**
     * Метод инициализации транскодирования
     *
     * @param string $name
     * @return void
     */
    public function transcode(string $name, string $targetDir, array $otherData):void
    {

        $files = $this->searchInputVideoFile->__invoke($this->baseInputDir.$name);

        $inputFile = $files[0];
        $outputDirectory = $targetDir;

        $command = $this->commandMaker->__invoke('default',$inputFile, $outputDirectory, '360p', 640, 360, 800, 1200, '96k');

        $this->transcodeProcessing->processing($command, $name, '360p', $outputDirectory, $otherData['user'], $otherData['kinopoiskId']);
        if (!$this->debug) {
            $command = $this->commandMaker->__invoke('default', $inputFile, $outputDirectory, '480p', 842, 480, 1400, 2100, '128k');
            $this->transcodeProcessing->processing($command, $name, '480p', $outputDirectory, $otherData['user'], $otherData['kinopoiskId']);
            $command = $this->commandMaker->__invoke('default', $inputFile, $outputDirectory, '720p', 1280, 720, 2800, 4200, '128k');
            $this->transcodeProcessing->processing($command, $name, '720p', $outputDirectory, $otherData['user'], $otherData['kinopoiskId']);
            $command = $this->commandMaker->__invoke('default', $inputFile, $outputDirectory, '1080p', 1920, 1080, 5000, 7500, '192k');
            $this->transcodeProcessing->processing($command, $name, '1080p', $outputDirectory, $otherData['user'], $otherData['kinopoiskId']);
        }

        $this->returnComplite->setSmarty($otherData);

    }

}