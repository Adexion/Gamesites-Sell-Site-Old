<?php

namespace App\Entity;

use App\Repository\BansRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BansRepository::class))
 */
class Bans extends AbstractRemoteEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $columnTwo;

    public function setColumnTwo(?string $columnTwo): self
    {
        $this->columnTwo = $columnTwo;

        return $this;
    }

    public function getColumnTwo(): ?string
    {
        return $this->columnTwo;
    }
}
