<?php

namespace App\Repository;

use App\Entity\Question;
use App\Repository\Traits\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    use Paginator;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function selectAll($page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('q')
            ->select('q')
            ->orderBy('q.id', 'DESC');

        return $this->createPaginator($qb->getQuery(), $page, Question::NUM_ITEMS);
    }
}
