<?php

namespace App\Entity;

use App\Repository\TranscodingProcessesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TranscodingProcessesRepository::class)]
class TranscodingProcesses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $FilmName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $StartTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $EndTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Status = null;

    #[ORM\Column(nullable: true)]
    private ?int $PID = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Progress = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $CtreatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $UpdateAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $TranscodingSettings = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $UserSubmittedBy = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ErrorMessage = null;

    #[ORM\Column(nullable: true)]
    private ?int $hdd = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $origTorrentFileName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $kpId = null;

    #[ORM\Column(nullable: true)]
    private ?int $smartyId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilmName(): ?string
    {
        return $this->FilmName;
    }

    public function setFilmName(?string $FilmName): static
    {
        $this->FilmName = $FilmName;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->StartTime;
    }

    public function setStartTime(?\DateTimeInterface $StartTime): static
    {
        $this->StartTime = $StartTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->EndTime;
    }

    public function setEndTime(?\DateTimeInterface $EndTime): static
    {
        $this->EndTime = $EndTime;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(?string $Status): static
    {
        $this->Status = $Status;

        return $this;
    }

    public function getPID(): ?int
    {
        return $this->PID;
    }

    public function setPID(?int $PID): static
    {
        $this->PID = $PID;

        return $this;
    }

    public function getProgress(): ?string
    {
        return $this->Progress;
    }

    public function setProgress(?string $Progress): static
    {
        $this->Progress = $Progress;

        return $this;
    }

    public function getCtreatedAt(): ?\DateTimeImmutable
    {
        return $this->CtreatedAt;
    }

    public function setCtreatedAt(?\DateTimeImmutable $CtreatedAt): static
    {
        $this->CtreatedAt = $CtreatedAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->UpdateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $UpdateAt): static
    {
        $this->UpdateAt = $UpdateAt;

        return $this;
    }

    public function getTranscodingSettings(): ?string
    {
        return $this->TranscodingSettings;
    }

    public function setTranscodingSettings(?string $TranscodingSettings): static
    {
        $this->TranscodingSettings = $TranscodingSettings;

        return $this;
    }

    public function getUserSubmittedBy(): ?string
    {
        return $this->UserSubmittedBy;
    }

    public function setUserSubmittedBy(?string $UserSubmittedBy): static
    {
        $this->UserSubmittedBy = $UserSubmittedBy;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->ErrorMessage;
    }

    public function setErrorMessage(?string $ErrorMessage): static
    {
        $this->ErrorMessage = $ErrorMessage;

        return $this;
    }

    public function getHdd(): ?int
    {
        return $this->hdd;
    }

    public function setHdd(?int $hdd): static
    {
        $this->hdd = $hdd;

        return $this;
    }

    public function getOrigTorrentFileName(): ?string
    {
        return $this->origTorrentFileName;
    }

    public function setOrigTorrentFileName(?string $origTorrentFileName): static
    {
        $this->origTorrentFileName = $origTorrentFileName;

        return $this;
    }

    public function getStage(): ?string
    {
        return $this->stage;
    }

    public function setStage(?string $stage): static
    {
        $this->stage = $stage;

        return $this;
    }

    public function getKpId(): ?string
    {
        return $this->kpId;
    }

    public function setKpId(?string $kpId): static
    {
        $this->kpId = $kpId;

        return $this;
    }

    public function getSmartyId(): ?int
    {
        return $this->smartyId;
    }

    public function setSmartyId(?int $smartyId): static
    {
        $this->smartyId = $smartyId;

        return $this;
    }
}
