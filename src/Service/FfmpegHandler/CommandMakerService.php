<?php

namespace App\Service\FfmpegHandler;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


/**
 * Данный класс генерирует комманду для ffmpeg
 */
class CommandMakerService extends TranscoderBaseService
{

    public function __construct(ParameterBagInterface $parameterBag){
        
        parent::__construct($parameterBag);
    }

    /**
     * Создание целевой директории
     *
     * @param string $directory
     * @return void
     */
    private function createDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    /**
     * Генерация шаблона
     *
     * @param string $template
     * @param string $inputFile
     * @param string $outputFile
     * @param string $resolution
     * @param integer $width
     * @param integer $height
     * @param integer $bitrate
     * @param integer $bufsize
     * @param string $audioBitrate
     * @return array
     */
    public function __invoke(string $template = 'default', string $inputFile, string $outputFile, string $resolution, int $width, int $height, int $bitrate, int $bufsize, string $audioBitrate): array
    {
        $outputDirectory = $outputFile . '/' . $resolution;
        // Создание директории
        $this->createDirectory($outputDirectory);

        if ($template == 'default') {
            // Команда FFMPEG
            $command = [
                'ffmpeg',
                '-y',
                '-hwaccel', 'cuda',
                '-vsync', '0',
                '-async', '0',
                '-i',  $inputFile,
                '-filter:v:0', 'scale=w=' . $width . ':h=' . $height . ':force_original_aspect_ratio=1,pad=' . $width . ':' . $height . ':\(ow-iw\)\/2:\(oh-ih\)\/2,setsar=1',
                '-map', '0:v:0',
                '-map', '0:a:0',
                '-c:v', 'h264_nvenc',
                '-profile:v', 'high',
                '-sc_threshold', '0',
                '-g', '25',
                '-keyint_min', '25',
                '-level', '4.1',
                '-c:a', 'aac',
                '-ar', '48000',
                '-ac', '2',
                '-ab', $audioBitrate,
                '-maxrate:v:0', $bitrate . 'k',
                '-b:v:0', $bitrate . 'k',
                '-bufsize:v:0', $bufsize . 'k',
                '-f', 'hls',
                '-hls_time', '10',
                '-hls_playlist_type', 'vod',
                '-metadata', 'service_provider=CentraTranscoder',
                '-hls_segment_filename', $outputDirectory . '/' . $resolution . '_%03d.ts',
                $outputDirectory . '/' . $resolution . '.m3u8'
            ];
        }

        return $command;
    }
}
