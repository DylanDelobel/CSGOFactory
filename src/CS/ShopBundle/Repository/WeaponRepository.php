<?php

namespace CS\ShopBundle\Repository;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * WeaponRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WeaponRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByCollectionId($id){
        $dql = $this->createQueryBuilder('w');

        $dql
            ->where('w.collection IN (:id)')
            ->setParameter('id', $id)
        ;


        return $dql->getQuery()->getResult();
    }

    public function findAllPagine($page, $max){
        if(!is_numeric($page)) {
            throw new \InvalidArgumentException(
                '$page must be an integer ('.gettype($page).' : '.$page.')'
            );
        }

        if(!is_numeric($page)) {
            throw new \InvalidArgumentException(
                '$max must be an integer ('.gettype($max).' : '.$max.')'
            );
        }

        $dql = $this->createQueryBuilder('w');


        $firstResult = ($page - 1) * $max;

        $query = $dql->getQuery();
        $query->setFirstResult($firstResult);
        $query->setMaxResults($max);

        $paginator = new Paginator($query);

        if(($paginator->count() <=  $firstResult) && $page != 1) {
            throw new NotFoundHttpException('Page not found');
        }

        return $paginator;
    }

    public function findSearchPagine($priceMin, $priceMax, $page, $max){
        if(!is_numeric($page)) {
            throw new \InvalidArgumentException(
                '$page must be an integer ('.gettype($page).' : '.$page.')'
            );
        }

        if(!is_numeric($page)) {
            throw new \InvalidArgumentException(
                '$max must be an integer ('.gettype($max).' : '.$max.')'
            );
        }

        $dql = $this->createQueryBuilder('w');
        $dql
            ->where('w.price BETWEEN :min AND :max')
            ->setParameter('min', $priceMin)
            ->setParameter('max', $priceMax);

        $firstResult = ($page - 1) * $max;

        $query = $dql->getQuery();
        $query->setFirstResult($firstResult);
        $query->setMaxResults($max);

        $paginator = new Paginator($query);

        if(($paginator->count() <=  $firstResult) && $page != 1) {
            throw new NotFoundHttpException('Page not found');
        }

        return $paginator;
    }

    public function findArray($array){
        $dql = $this->createQueryBuilder('w');

        $dql
            ->where('w.id IN (:array)')
            ->setParameter('array', $array)
        ;

        return $dql->getQuery()->getResult();
    }

    public function findByModelId($id){
        $dql = $this->createQueryBuilder('w');

        $dql
            ->where('w.model IN (:id)')
            ->setParameter('id', $id)
        ;
        $page = 1;
        $max = 5;
        $firstResult = ($page - 1) * $max;

        $query = $dql->getQuery();
        $query->setFirstResult($firstResult);
        $query->setMaxResults($max);

        $paginator = new Paginator($query);

        if(($paginator->count() <=  $firstResult) && $page != 1) {
            throw new NotFoundHttpException('Page not found');
        }

        return $paginator;
    }


    
}
