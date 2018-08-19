<?php
/**
 * Created by PhpStorm.
 * User: lachlan
 * Date: 19/8/18
 * Time: 10:13 AM
 */

namespace App\Repository;


use App\Entity\GenusScientist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenusScientistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenusScientist::class);
    }

    static public function createExpertCriteria()
    {
        return $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gt('yearsStudied', 20))
            ->orderBy(['yearsStudied' => 'DESC']);
    }
}