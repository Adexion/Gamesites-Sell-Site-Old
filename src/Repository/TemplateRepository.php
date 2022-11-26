<?php

namespace App\Repository;

use App\Entity\Customer\Template;
use Doctrine\ORM\EntityRepository;

class TemplateRepository extends EntityRepository
{
    public function add(Template $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Template $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
