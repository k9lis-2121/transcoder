<?php
/**
 * @copyright 2024 Ожерельев Валерий Александрович
 */
namespace App\Service\FfmpegHandler;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Базовый класс для загрузки параметров во все подкласы
 */
class TranscoderBaseService{
    protected $logDirectory;
    protected $supportedExtensions;
    protected $baseInputDir;
    protected $baseTargetDir;
    protected $transcodeDebug;
    protected $masterPlaylist;
    protected $contentMaker;

    /**
     * Конструктор
     *
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->logDirectory = $parameterBag->get('kernel.logs_dir');
        $this->supportedExtensions = $parameterBag->get('SUPPORTED_EXTENSIONS');
        $this->baseInputDir = $parameterBag->get('INPUT_DIR');
        $this->baseTargetDir = $parameterBag->get('TARGET_DIR');
        $this->masterPlaylist = $parameterBag->get('MASTER_PLAYLIST');
        $this->contentMaker = $parameterBag->get('CONTENT_MAKER_ADDR');
        
        if($parameterBag->get('TRANSCODE_DEBUG') == 'true'){
            $this->transcodeDebug = true;
        }else{
            $this->transcodeDebug = false;
        }
    }
}