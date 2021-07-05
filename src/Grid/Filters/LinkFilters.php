<?php
declare(strict_types=1);
namespace PrestaShop\Module\CustomMenu\Grid\Filters;

use PrestaShop\Module\CustomMenu\Grid\Definition\Factory\MenuLinkDefinitionFactory;
use PrestaShop\PrestaShop\Core\Search\Filters;


class LinkFilters extends Filters
{
    protected $filterId = MenuLinkDefinitionFactory::GRID_ID;

    /**
     * {@inheritdoc}
     */
    public static function getDefaults()
    {
        return [
            'limit' => 30,
            'offset' => 0,
            'orderBy' => 'id',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }

}