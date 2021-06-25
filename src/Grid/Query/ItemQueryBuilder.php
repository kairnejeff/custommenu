<?php

namespace PrestaShop\Module\CustomMenu\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

class ItemQueryBuilder extends AbstractDoctrineQueryBuilder
{
    private $searchCriteriaApplicator;

    public function __construct(
        Connection $connection,
        $dbPrefix,
        DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator
    ) {
        parent::__construct($connection, $dbPrefix);

        $this->searchCriteriaApplicator = $searchCriteriaApplicator;
    }


    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());
        $qb
            ->select('i.id, i.name, i.is_single_link,position');

        $this->searchCriteriaApplicator
            ->applySorting($searchCriteria, $qb)
            ->applyPagination($searchCriteria, $qb)
        ;

        return $qb;
    }

    public function getCountQueryBuilder(\PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters())
            ->select('COUNT(DISTINCT i.id)');

        return $qb;
    }

    /**
     * Get generic query builder.
     *
     * @param array $filters
     *
     * @return QueryBuilder
     */
    private function getQueryBuilder(array $filters)
    {
        $allowedFilters = [
            'id',
            'name',
            'is_single_link',
        ];

        $qb = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . 'menu_item', 'i')
        ;

        foreach ($filters as $name => $value) {
            if (!in_array($name, $allowedFilters, true)) {
                continue;
            }

            if ('id' === $name) {
                $qb->andWhere('i.`id` = :' . $name);
                $qb->setParameter($name, $value);

                continue;
            }

            $qb->andWhere("$name LIKE :$name");
            $qb->setParameter($name, '%' . $value . '%');
        }

        return $qb;
    }
}