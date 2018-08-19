<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function createIsScientistQueryBuilder()
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.isScientist = :isScientist')
            ->setParameter('isScientist', true);
    }
}
