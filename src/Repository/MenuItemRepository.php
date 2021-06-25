<?php
declare(strict_types=1);

namespace PrestaShop\Module\CustomMenu\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use PrestaShop\Module\CustomMenu\Entity\MenuItemBlock;

class MenuItemRepository extends EntityRepository
{
    public function getCountItem(){
        /** @var QueryBuilder $qb */
        $qb = $this
            ->createQueryBuilder('i')
            ->select('count(i.id)')
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getBlocks(int $idItem){
        /** @var QueryBuilder $qb */
        $qb = $this
            ->createQueryBuilder('i')
            ->select('i.block')
            ->from('MenuItemBlock', 'i')
            ->where("i.item=$idItem")
        ;
        return $qb->getQuery()->getResult();
    }

}