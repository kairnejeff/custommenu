<?php
namespace PrestaShop\Module\CustomMenu\Grid\Filters;

use PrestaShop\Module\CustomMenu\Grid\Definition\Factory\CustomMenuDefinitionFactory;
use PrestaShop\PrestaShop\Core\Search\Filters;


class ItemFilters extends Filters
{
    /**
     * {@inheritdoc}
     */
    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }

}