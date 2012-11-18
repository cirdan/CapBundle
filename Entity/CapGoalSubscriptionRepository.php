<?php

namespace SF\CapBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * CapGoalSubscriptionRepository
 *
 */
class CapGoalSubscriptionRepository extends EntityRepository
{
    public function findPendingByRunner(\SF\CapBundle\Entity\CapRunner $runner){
        $em=$this->getEntityManager();
		$qb = $em->createQueryBuilder();
    	$qb ->select('s')
   			->from('SFCapBundle:CapGoalSubscription', 's')
	    	->andWhere('s.runner = ?1')
	    	->andWhere('s.achievementDate IS NULL')
	    	->setParameter(1, $runner);
	    $query = $qb->getQuery();
		return $query->getResult();
    }

}
