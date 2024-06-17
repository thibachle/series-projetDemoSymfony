<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findBestSeries(int $page)
    {
        //en DQL
//        //récupération de l'entitymanager
//        $em = $this->getEntityManager();

//        $dql = "SELECT s FROM App\Entity\Serie AS s
//                WHERE s.popularity > 200
//                ORDER BY s.vote DESC ";
//        //cre&tion de la query
//        $query = $em->createQuery($dql);

        //pagination
        //page = 0; o->19
        //page = 2; 20->39

        $limit = Serie::SERIES_PER_PAGE;
        $offset = ($page -1)* $limit;

        //en QureyBuider
        $qb = $this->createQueryBuilder('s');
        $qb->leftJoin('s.seasons', 'seas');
        $qb->addSelect('seas');

//        $qb->andWhere("s.popularity > 200")
//            ->addOrderBy("s.vote", "DESC");

        $qb->addOrderBy("s.popularity", "DESC");
        $query = $qb->getQuery();

        //pareil pour les 2 possibilité
        //set la la limite
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);


        //ajoute de paginator pou gérer les différents dû à lajointure
        $paginator = new Paginator($query);

        //retourne les résultats de la requête
        // return $query->getResult();

        return $paginator;
    }







//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
