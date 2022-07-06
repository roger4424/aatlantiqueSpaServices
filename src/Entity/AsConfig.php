<?php

namespace App\Entity;

use App\Repository\AsConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AsConfigRepository::class)]
class AsConfig
{
    #[ORM\Id]
    #[ORM\Column(type: 'ascii_string', length: 255)]
    private $asc_key;

    #[ORM\Column(type: 'string', length: 255)]
    private $asc_label;

    #[ORM\Column(type: 'text', nullable: true)]
    private $asc_value;

    public function getAscKey(): ?string
    {
        return $this->asc_key;
    }

    public function setAscKey(string $asc_key): self
    {
        $this->asc_key = $asc_key;
        return $this;
    }

    public function getAscValue(): ?string
    {
        return $this->asc_value;
    }

    public function setAscValue(?string $asc_value): self
    {
        $this->asc_value = $asc_value;
        return $this;
    }

    public function getAscLabel(): ?string
    {
        return $this->asc_label;
    }

    public function setAscLabel(string $asc_label): self
    {
        $this->asc_label = $asc_label;
        return $this;
    }
}
