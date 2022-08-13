<?php

namespace App\Entity;

use App\Repository\RankRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=RankRepository::class)
 * @ORM\Table(name="ranking")
 */
class Rank extends AbstractRemoteEntity
{
    /**
     * @ORM\Column(type="smallint")
     */
    private $type;

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    function getSearchFields(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->getColumnOne(),
        ];
    }
}
