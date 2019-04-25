<?php

namespace App\Repository\Traits;

use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

trait Paginator
{
    /**
     * @param Query $query
     * @param int   $page
     * @param int   $maxPerPage
     *
     * @return Pagerfanta
     */
    private function createPaginator(Query $query, int $page, $maxPerPage = 10): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
