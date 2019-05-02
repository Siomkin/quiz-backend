<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getVariantsForQuestion(int $id): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.id', 'a.description')
            ->andWhere('a.question = :id')->setParameter('id', $id);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param int $id Question Id
     *
     * @throws NonUniqueResultException
     *
     * @return mixed
     */
    public function getRightAnswerId(int $id): int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.id')
            ->andWhere('a.correct = 1')
            ->andWhere('a.question = :id')->setParameter('id', $id);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
