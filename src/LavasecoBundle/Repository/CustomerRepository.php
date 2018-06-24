<?php

namespace LavasecoBundle\Repository;

/**
 * CustomerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CustomerRepository extends \Doctrine\ORM\EntityRepository
{
        public function getCustomersByName($name) {
        $qb = $this->createQueryBuilder('c');

        $query = $qb->where($qb->expr()->like('c.name', ':name'))
                ->setParameter('name', '%' . $name . '%')
                ->getQuery();

        return $query->getResult();
    }

    public function getCustomersByEmail($email) {
        $qb = $this->createQueryBuilder('c');

        $query = $qb->where($qb->expr()->like('c.email', ':email'))
                ->setParameter('email', '%' . $email . '%')
                ->getQuery();
        
        return $query->getResult();
    }

    public function getCustomersByPhoneNumber($phoneNumber) {
        $qb = $this->createQueryBuilder('c');

        $query = $qb->where($qb->expr()->like('c.phoneNumber', ':phoneNumber'))
                ->setParameter('phoneNumber', '%' . $phoneNumber . '%')
                ->getQuery();

        return $query->getResult();
    }
    
    public function login($email, $password){
        $customer = $this->getCustomersByEmail($email);
        if(count($customer) > 0){
            $customer = $customer[0] ;
            
            if($customer->getPassword() ==  hash('sha256', $password)){
                return $customer;
            }
        }
        
        return null;
    }
}
