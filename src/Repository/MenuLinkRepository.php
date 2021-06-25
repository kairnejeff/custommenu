<?php
declare(strict_types=1);
namespace PrestaShop\Module\CustomMenu\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class MenuLinkRepository extends EntityRepository
{
    public function getAllPagesNoCustom()
    {
        /** @var QueryBuilder $qb */
        $qb = $this
            ->createQueryBuilder('l')
            ->select('l.id')
            ->andWhere("l.type != 'custom'")
        ;
        return $qb->getQuery()->getResult();
    }


}