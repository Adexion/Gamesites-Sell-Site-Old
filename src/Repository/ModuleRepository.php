<?php

namespace App\Repository;

use App\Entity\Customer\Module;
use Doctrine\ORM\EntityRepository;

class ModuleRepository extends EntityRepository
{
    public function add(Module $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Module $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
