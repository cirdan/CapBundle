<?php

namespace SF\CapBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use SF\CapBundle\Entity\DateTimeFrench;
/**
 * SortieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SortieRepository extends EntityRepository
{
    public function totalBetween($beginDate=null,$endDate=null,\SF\CapBundle\Entity\CapRunner $runner=null){
        if($endDate===null){
            $endDate=new \Datetime();
        }
        if($beginDate===null){
            $beginDate=new \Datetime('0000-01-01');
        }
        $em=$this->getEntityManager();
        $query="SELECT SUM(s.distance) FROM SFCapBundle:Sortie s WHERE s.date BETWEEN '".$beginDate->format( 'Y-m-d')."' AND '".$endDate->format( 'Y-m-d')."'";
        if($runner){
            $query.=" AND s.runner=".$runner->getId();
        }
        return $em
            ->createQuery($query)
            ->getResult(Query::HYDRATE_SINGLE_SCALAR)/1000;
    }
    public function minPerWeek($endDate=null,$nbWeeks=4,\SF\CapBundle\Entity\CapRunner $runner=null){
        if($endDate===null){
            $endDate=new \Datetime();
        }
        // We can consider only passed week : what is the last sunday ?
        $lastSunday=strtotime ( "last sunday", $endDate->format("U") );
        $firstDay=strtotime ( "last sunday -4 weeks + 1 days", $endDate->format("U") );
        $endDate=new \Datetime(date("Y/m/d",$lastSunday));
        $beginDate=new \Datetime(date("Y/m/d",$firstDay));
        $em=$this->getEntityManager();
        $sql = "SELECT MIN(d.dist) as distance
                FROM 
                    (
                    SELECT
                        SUM(s.distance) dist
                    FROM
                        Sortie s
                    WHERE 
                        runner_id = ".$runner->getId()." 
                        AND date <= ".$endDate->format("Ymd")."
                        AND date >= ".$beginDate->format("Ymd")."
                    GROUP BY WEEK(date)
                    ) d";
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult("distance", "distance");
        $query = $em->createNativeQuery($sql,$rsm);
        return (int)$query->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    public function totalDurationBetween($beginDate=null,$endDate=null,\SF\CapBundle\Entity\CapRunner $runner=null){
        if($endDate===null){
            $endDate=new \Datetime();
        }
        if($beginDate===null){
            $beginDate=new \Datetime('0000-01-01');
        }
        $em=$this->getEntityManager();
        $query="SELECT SUM(s.duration) FROM SFCapBundle:Sortie s WHERE s.date BETWEEN '".$beginDate->format( 'Y-m-d')."' AND '".$endDate->format( 'Y-m-d')."'";
        if($runner){
            $query.=" AND s.runner=".$runner->getId();
        }
        return $em
            ->createQuery($query)
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    public function totalLastDays($nbDays=7,$runner=null){
        $today=new \Datetime("now");
        $startDate=new \Datetime("now");
        $startDate->sub(new \DateInterval('P'.$nbDays.'D'));
        return $this->totalBetween($startDate,$today,$runner);
    }
    public function totalDurationLastDays($nbDays=7,$runner=null){
        $today=new \Datetime("now");
        $startDate=new \Datetime("now");
        $startDate->sub(new \DateInterval('P'.$nbDays.'D'));
        return $this->totalDurationBetween($startDate,$today,$runner);
    }
    public function getDailyData(DateTimeFrench $beginDate=null,DatetimeFrench $endDate=null,\SF\CapBundle\Entity\CapRunner $runner=null, $locale="fr_FR"){
        if($endDate===null){
            $endDate=new \DateTimeFrench();
        }
        if($beginDate===null){
            $beginDate=new \DateTimeFrench('0000-01-01');
        }
        $plotterData = new \stdClass();
        $plotterData->maxValue=0;
        $plotterData->beginDate=$beginDate->format("Y-m-d");
        $plotterData->endDate=$endDate->format("Y-m-d");

        $interval=$endDate->diff($beginDate);
        $em=$this->getEntityManager();
        $query="SELECT (DATE_DIFF(s.date,'".$beginDate->format('Y-m-d')."')) theDay,SUM(s.distance)/1000 kmForDay FROM SFCapBundle:Sortie s WHERE s.date BETWEEN '".$beginDate->format('Y-m-d')."' AND '".$endDate->format('Y-m-d')."'";
        if($runner){
            $query.=" AND s.runner=".$runner->getId();
        }
        $query.=" GROUP BY theDay";

        $results=$em
            ->createQuery($query)
            ->getArrayResult();
        foreach($results as $result){
            $data[$result["theDay"]]=$result["kmForDay"];
        }
        $hrefs=array();
        for($i=0;$i<=$interval->days;$i++){
            if(isset($data[$i])){
                $val[]=number_format($data[$i],2);
                $plotterData->maxValue=max($plotterData->maxValue,$data[$i]);
            }else{
                $val[]=0;
            }
            //$xax[]=$result["theDate"]->format('j');
            $xax[]=$i;
            $yax[]=0;
            if(isset($data[$i]) && $data[$i]>0 && $runner){
                $hrefs[]="javascript:loadResume('".$beginDate->format("Y-m-d")."',".$runner->getId().");";
            }else{
                $hrefs[]="";
            }
            setlocale(LC_TIME, $locale);
            $labels[]=strftime("%a %e %b",$beginDate->format("U"));
            $beginDate->add(new \DateInterval("P1D"));
        }
        if(true || $runner->getOption("displayLogic")=="forward"){
            //$xax=array_reverse($xax);
            //$yax=array_reverse($yax);
            //$val=array_reverse($val);
            //$labels=array_reverse($labels);
            //$hrefs=array_reverse($hrefs);
        }
        $plotterData->xaxis=$xax;
        $plotterData->yaxis=$yax;
        $plotterData->values=$val;
        $plotterData->labels=$labels;
        $plotterData->hrefs=$hrefs;
        $plotterData->nbValues=$interval->days;
        
        return $plotterData;

    }
    public function getLastDailyData($nbDays=7,\SF\CapBundle\Entity\CapRunner $runner=null){
        $today=new DateTimeFrench("now");
        $startDate=new DateTimeFrench("now");
        $startDate->sub(new \DateInterval('P'.$nbDays.'D'));
        return $this->getDailyData($startDate,$today,$runner);
    }

    public function getLastRun(\SF\CapBundle\Entity\CapRunner $runner=null){
        if($runner){
            $em=$this->getEntityManager();
            $qb = $em->createQueryBuilder()
                ->add('select', 's')
                ->add('from', 'SFCapBundle:Sortie s')
                ->add('orderBy', 's.date DESC, s.time DESC')
                ->setMaxResults( 1 );
                $qb->add('where', 's.runner = :runner')
                ->setParameter('runner', $runner);
            $query = $qb->getQuery();
            $results=$query->getResult();
            if($results){
                return array_pop($results);
            }
        }
        return null;
    }
    public function getDayRuns(\Datetime $date,\SF\CapBundle\Entity\CapRunner $runner=null){
        $em=$this->getEntityManager();
        $qb = $em->createQueryBuilder()
                ->add('select', 's')
                ->add('from', 'SFCapBundle:Sortie s')
                ->add('orderBy', 's.date DESC, s.time DESC')
                ->add('where', 's.date = :date',true)
                ->setParameter('date', $date);
        if($runner){
            $qb->andWhere('s.runner = :runner')
            ->setParameter('runner',$runner);
        }
        $query = $qb->getQuery();
        return $query->getResult();
    }


}
