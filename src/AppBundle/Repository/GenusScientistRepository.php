<?php
/**
 * Created by PhpStorm.
 * User: lachlan
 * Date: 19/8/18
 * Time: 10:13 AM
 */

namespace AppBundle\Repository;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class GenusScientistRepository extends EntityRepository
{
    static public function createExpertCriteria()
    {
        return $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gt('yearsStudied', 20))
            ->orderBy(['yearsStudied' => 'DESC']);
    }
}