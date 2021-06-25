<?php
declare(strict_types=1);

namespace PrestaShop\Module\CustomMenu\Controller\Admin;


use PrestaShop\Module\CustomMenu\Grid\Filters\ItemFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MenuItemController extends FrameworkBundleAdminController
{
    public function indexAction(ItemFilters $filters)
    {
        $itemGridFactory = $this->get('prestashop.module.kj_cutsommenu.grid.factory.items');
        $itemGrid = $itemGridFactory->getGrid($filters);
        return $this->render(
            '@Modules/kj_custommenu/views/templates/admin/index.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Custom menu', 'Modules.kj_cutsommenu.Admin'),
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
                'itemGrid' => $this->presentGrid($itemGrid),
            ]
        );
    }


    /**
     * Edit quote
     *
     * @param Request $request
     * @param int $itemId
     *
     * @return Response
     */

    public function editAction(Request $request, $itemId)
    {
        $FormBuilder = $this->get('prestashop.module.kj_cutsommenu.form.identifiable_object.builder.menu_item_form_builder');
        $Form = $FormBuilder->getFormFor((int) $itemId);
        $Form->handleRequest($request);

        $FormHandler = $this->get('prestashop.module.kj_cutsommenu.form.identifiable_object.handler.menu_item_form_handler');
        $result = $FormHandler->handleFor((int) $itemId, $Form);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
            return $this->redirectToRoute('kj_custommenu_item_index');
        }
        return $this->render('@Modules/kj_custommenu/views/templates/admin/edit.html.twig', [
            'layoutHeaderToolbarBtn' => $this->getToolbarAddLinkButtons(),
            'Form' => $Form->createView(),
        ]);

    }

    public function deleteAction(Request $request, $itemId)
    {
        $handler= $this->get('prestashop.module.kj_cutsommenu.form.identifiable_object.data_handler.menu_item_form_data_handler');
        $result = $handler->delete($itemId);
        if ($result) {
            $this->addFlash('success', $this->trans('Successful delete.', 'Admin.Notifications.Success'));
            return $this->redirectToRoute('kj_custommenu_item_index');
        }
    }

    public function addSingleAction(Request $request)
    {
        $FormBuilder = $this->get('prestashop.module.kj_cutsommenu.form.identifiable_object.builder.menu_item_form_builder');
        $Form = $FormBuilder->getForm()->handleRequest($request);
        //$em = $this->get('doctrine.orm.default_entity_manager');
        //dump($request);die;
        //$Form = $this->createForm(MenuItemType::class)->handleRequest($request);

        $FormHandler = $this->get('prestashop.module.kj_cutsommenu.form.identifiable_object.handler.menu_item_form_handler');
        $result = $FormHandler->handle($Form);
        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('kj_custommenu_item_index');
        }

        return $this->render('@Modules/kj_custommenu/views/templates/admin/create.html.twig', [
            'layoutHeaderToolbarBtn' => $this->getToolbarAddLinkButtons(),
            'Form' => $Form->createView(),
        ]);

    }


    private function getToolbarButtons()
    {
        return [
            'add_link' => [
                'desc' => $this->trans('Add new item', 'Modules.kj_custommenu.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('kj_custommenu_item_add_single'),
            ],
            'add_block' => [
                'desc' => $this->trans('Add new block', 'Modules.kj_custommenu.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('kj_custommenu_block_add'),
            ],
            'show_block' => [
                'desc' => $this->trans('Show blocks', 'Modules.kj_custommenu.Admin'),
                'icon' => 'arrow_drop_down',
                'href' => $this->generateUrl('kj_custommenu_block_index'),
            ],
            'generate' => [
                'desc' => $this->trans('Indexer pages', 'Modules.kj_custommenu.Admin'),
                'icon' => 'system_update_alt',
                'href' => $this->generateUrl('kj_custommenu_link_generate'),
            ],
        ];
    }

    private function getToolbarAddLinkButtons()
    {
        return [
            'add' => [
                'desc' => $this->trans('Add New Link', 'Modules.Demodoctrine.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('kj_custommenu_link_add'),
            ]
        ];
    }
}