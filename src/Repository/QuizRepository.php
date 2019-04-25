<?php

namespace App\Repository;

use App\Entity\Quiz;
use App\Repository\Traits\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    use Paginator;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getItem($id, $visible = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('q')
            ->select('q')
            ->andWhere('q.visible = :visible')->setParameter('visible', $visible)
            ->andWhere('q.id = :id')->setParameter('id', $id);

        return $qb->getQuery()->getResult();
    }

    public function selectAll($page = 1, $query = [], $visible = null): Pagerfanta
    {
        $qb = $this->createQueryBuilder('q')
            ->select('q')
            ->orderBy('q.id', 'DESC');

        if (null !== $visible) {
            $qb->andWhere('q.visible = :visible')->setParameter('visible', $visible);
        }

        return $this->createPaginator($qb->getQuery(), $page, Quiz::NUM_ITEMS);
    }
}
