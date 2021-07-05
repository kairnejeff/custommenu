<?php


namespace PrestaShop\Module\CustomMenu\Grid\Definition\Factory;


use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MenuLinkDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'custommenulink';

    protected function getId(){
        return self::GRID_ID;
    }

    protected function getName()
    {
        return $this->trans('Links', [], 'Modules.kj_custommenu.Admin');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add(
                (new DataColumn('linkId'))
                    ->setName('ID')
                    ->setOptions([
                        'field' => 'id',
                    ])
            )
            ->add(
                (new DataColumn('libelle'))
                    ->setName('Libelle')
                    ->setOptions([
                        'field' => 'libelle',
                    ])
            )
            ->add(
                (new DataColumn('url'))
                    ->setName('url')
                    ->setOptions([
                        'field' => 'url',
                    ])
            )->add((new ActionColumn('actions'))
                ->setName($this->trans('Actions', [], 'Admin.Global'))
                ->setOptions([
                    'actions' => (new RowActionCollection())
                        ->add((new LinkRowAction('edit'))
                            ->setName($this->trans('Edit', [], 'Admin.Actions'))
                            ->setIcon('edit')
                            ->setOptions([
                                'route' => 'kj_custommenu_link_edit',
                                'route_param_name' => 'linkid',
                                'route_param_field' => 'id',
                                'clickable_row' => true,
                            ])
                        )
                        ->add((new LinkRowAction('delete'))
                            ->setName($this->trans('Delete', [], 'Admin.Actions'))
                            ->setIcon('delete')
                            ->setOptions([
                                'route' => 'kj_custommenu_link_delete',
                                'route_param_name' => 'linkid',
                                'route_param_field' => 'id',
                                'clickable_row' => true,
                            ])
                        )
                ])
            );
    }

    protected function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter('id', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('ID', [], 'Admin.Global'),
                    ],
                ])
                ->setAssociatedColumn('linkId')
            )
            ->add((new Filter('libelle', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('libelle', [], 'Modules.Demodoctrine.Admin'),
                    ],
                ])
                ->setAssociatedColumn('libelle')
            )
            ->add((new Filter('url', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('url', [], 'Modules.Demodoctrine.Admin'),
                    ],
                ])
                ->setAssociatedColumn('url')
            )
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions([
                    'reset_route' => 'admin_common_reset_search_by_filter_id',
                    'reset_route_params' => [
                        'filterId' => self::GRID_ID,
                    ],
                    'redirect_route' => 'kj_custommenu_link_index',
                ])
                ->setAssociatedColumn('actions')
            )
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add((new SimpleGridAction('common_refresh_list'))
                ->setName($this->trans('Refresh list', [], 'Admin.Advparameters.Feature'))
                ->setIcon('refresh')
            )
            ->add((new SimpleGridAction('common_show_query'))
                ->setName($this->trans('Show SQL query', [], 'Admin.Actions'))
                ->setIcon('code')
            )
            ->add((new SimpleGridAction('common_export_sql_manager'))
                ->setName($this->trans('Export to SQL Manager', [], 'Admin.Actions'))
                ->setIcon('storage')
            )
            ;
    }


}