<?php

namespace SF\CapBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * CapGoalRepository
 *
 */
class CapGoalRepository extends EntityRepository
{
    private function baseGoalsQuery(\SF\CapBundle\Entity\CapRunner $runner){
        $em=$this->getEntityManager();
    	$qb = $em->createQueryBuilder();
    	$qb ->select('g goal,s.achievementDate isAchieved,s.id isSubscribed')
   			->from('SFCapBundle:CapGoal', 'g')
   			->leftJoin('SFCapBundle:CapGoalSubscription', 's', 'WITH',$qb->expr()->andx(
					$qb->expr()->eq('s.runner', '?1'),
					$qb->expr()->eq('s.goal', 'g')
				)
			)
			->orderBy('g.distance DESC,g.duration','DESC')
	    	->setParameter(1, $runner);
	    return $qb;
    }
    public function findPotentialForRunner(\SF\CapBundle\Entity\CapRunner $runner){
		$qb=$this->baseGoalsQuery($runner);
		$qb->where(
	    		$qb->expr()->andX(
				    $qb->expr()->orX(
				       $qb->expr()->eq('g.owner', '?2'),
				       $qb->expr()->eq('g.isPublic', 1)
	   				),
	   				$qb->expr()->isNull('g.automatic')
	   			)
	    	)
			->setParameter(2, true);
	    $query = $qb->getQuery();
		return $query->getResult();
    }
    public function findSubscribedByRunner(\SF\CapBundle\Entity\CapRunner $runner){
        $em=$this->getEntityManager();
		$qb = $em->createQueryBuilder();
    	$qb ->select('g goal,s.achievementDate isAchieved,s.id isSubscribed')
   			->from('SFCapBundle:CapGoal', 'g')
   			->innerJoin('SFCapBundle:CapGoalSubscription', 's')
	    	->where('s.goal = g')
	    	->andWhere('s.runner = ?1')
	    	->setParameter(1, $runner);
	    $query = $qb->getQuery();
		return $query->getResult();
    }
    public function findPotentialBadgesByRunner(\SF\CapBundle\Entity\CapRunner $runner){
		$qb=$this->baseGoalsQuery($runner);
		$qb->where(
	    		$qb->expr()->andX(
				    $qb->expr()->orX(
				       $qb->expr()->eq('g.owner', '?2'),
				       $qb->expr()->eq('g.isPublic', 1)
	   				),
	   				$qb->expr()->isNotNull('g.automatic'),
	   				$qb->expr()->isNull('s.achievementDate')
	   			)
	    	)
			->setParameter(2, true);
	    $query = $qb->getQuery();
		return $query->getResult();
    }
    public function findAchievedBadgesByRunner(\SF\CapBundle\Entity\CapRunner $runner){
		$qb=$this->baseGoalsQuery($runner);
		$qb->where(
	    		$qb->expr()->andX(
				    $qb->expr()->orX(
				       $qb->expr()->eq('g.owner', '?2'),
				       $qb->expr()->eq('g.isPublic', 1)
	   				),
	   				$qb->expr()->isNotNull('g.automatic'),
	   				$qb->expr()->isNotNull('s.achievementDate')
	   			)
	    	)
			->setParameter(2, true);
	    $query = $qb->getQuery();
		return $query->getResult();
    }
    public function findBadgesByRunner(\SF\CapBundle\Entity\CapRunner $runner){
		$qb=$this->baseGoalsQuery($runner);
		$qb->where(
	    		$qb->expr()->andX(
				    $qb->expr()->orX(
				       $qb->expr()->eq('g.owner', '?2'),
				       $qb->expr()->eq('g.isPublic', 1)
	   				),
	   				$qb->expr()->isNotNull('g.automatic')
	   			)
	    	)
			->setParameter(2, true);
	    $query = $qb->getQuery();
		return $query->getResult();
    }

}
