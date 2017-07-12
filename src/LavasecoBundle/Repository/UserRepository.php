<?php

namespace LavasecoBundle\Repository;

/**
 * ServiceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository {

    public function getUserByNameOrEmail($nameOrEmail) {
        $qb = $this->createQueryBuilder('u');

        $query = $qb->where($qb->expr()->like('u.firstName', ':nameOrEmail'))
                ->orWhere($qb->expr()->like('u.lastName', ':nameOrEmail'))
                ->orWhere($qb->expr()->like('u.email', ':nameOrEmail'))
                ->setParameter('nameOrEmail', '%' . $nameOrEmail . '%')
                ->getQuery();

        return $query->getResult();
    }

}