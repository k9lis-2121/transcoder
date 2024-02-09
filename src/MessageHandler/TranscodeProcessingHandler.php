<?php
/**
 * @copyright 2024 Ожерельев Валерий Александрович
 */
namespace App\MessageHandler;

use App\Message\TranscodeProcessingMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Service\FfmpegHandler\TranscodeService;
use App\Service\FfmpegHandler\SearchInputVideoFileService;

#[AsMessageHandler]
class TranscodeProcessingHandler
{
    private $transcodeHandler;
    public function __construct(TranscodeService $transcodeHandler){
        $this->transcodeHandler = $transcodeHandler;
    }
    public function __invoke(TranscodeProcessingMessage $message)
    {
        $this->transcodeHandler->transcode($message->getFilmName(), $message->getTargetDir(), $message->getOtherData());
    }
}