<?php
/**
 * @copyright 2024 Ожерельев Валерий Александрович
 */
namespace App\Message;

class TranscodeProcessingMessage
{
    public function __construct(
        private string $filmName,
        private string $targetDir,
        private array $otherData,
    ){

    }

    public function getFilmName(): string{
        return $this->filmName;
    }

    public function getTargetDir(): string{
        return $this->targetDir;
    }

    public function getOtherData(): array{
        return $this->otherData;
    }
}