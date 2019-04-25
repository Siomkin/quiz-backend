<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Traits\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    use Paginator;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function selectAll($page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.id', 'DESC');

        return $this->createPaginator($qb->getQuery(), $page, User::NUM_ITEMS);
    }
}
