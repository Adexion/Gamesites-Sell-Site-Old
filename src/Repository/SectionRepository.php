<?php

namespace App\Repository;

use App\Entity\Customer\Section;
use Doctrine\ORM\EntityRepository;

class SectionRepository extends EntityRepository
{
    public function add(Section $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Section $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
