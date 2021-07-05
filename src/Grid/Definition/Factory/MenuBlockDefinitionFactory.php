<?php
namespace PrestaShop\Module\CustomMenu\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;


class MenuBlockDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'block';

    protected function getId(){
        return self::GRID_ID;
    }

    protected function getName()
    {
        return $this->trans('Blocks', [], 'Modules.kj_custommenu.Admin');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add(
                (new BulkActionColumn('delete_block'))
                    ->setOptions([
                        'bulk_field' => 'id',
                    ])
            )
            ->add(
                (new DataColumn('blockId'))
                    ->setName('ID')
                    ->setOptions([
                        'field' => 'id',
                    ])
            )
            ->add(
                (new DataColumn('name'))
                    ->setName('Nom')
                    ->setOptions([
                        'field' => 'name',
                    ])
            )
            ->add(
                (new DataColumn('parent'))
                    ->setName('Parent')
                    ->setOptions([
                        'field' => 'parent_id',
                    ])
            )->add((new ActionColumn('actions'))
                ->setName($this->trans('Actions', [], 'Admin.Global'))
                ->setOptions([
                    'actions' => (new RowActionCollection())
                        ->add((new LinkRowAction('edit'))
                            ->setName($this->trans('Edit', [], 'Admin.Actions'))
                            ->setIcon('edit')
                            ->setOptions([
                                'route' => 'kj_custommenu_block_edit',
                                'route_param_name' => 'blockid',
                                'route_param_field' => 'id',
                                'clickable_row' => true,
                            ])
                        )
                        ->add((new LinkRowAction('delete'))
                            ->setName($this->trans('Delete', [], 'Admin.Actions'))
                            ->setIcon('delete')
                            ->setOptions([
                                'route' => 'kj_custommenu_block_delete',
                                'route_param_name' => 'blockid',
                                'route_param_field' => 'id',
                                'clickable_row' => true,
                            ])
                        )
                ])
            );
    }

}