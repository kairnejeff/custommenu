<?php


namespace PrestaShop\Module\CustomMenu\Controller\Admin;


use PrestaShop\Module\CustomMenu\Grid\Definition\Factory\MenuLinkDefinitionFactory;
use PrestaShop\Module\CustomMenu\Grid\Filters\LinkFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuLinkController extends FrameworkBundleAdminController
{
    public function indexAction(Request $request,LinkFilters $filters)
    {
        $linkGridFactory = $this->get('prestashop.module.kj_cutsommenu.grid.factory.links');
        $linkGrid = $linkGridFactory->getGrid($filters);
        return $this->render(
            '@Modules/kj_custommenu/views/templates/admin/index.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Custom menu', 'Modules.kj_cutsommenu.Admin'),
                'layoutHeaderToolbarBtn' => $this->getToolbarAddLinkButtons(),
                'itemGrid' => $this->presentGrid($linkGrid),
            ]
        );
    }

    public function searchAction(Request $request)
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('prestashop.module.kj_cutsommenu.grid.definition.factory.links'),
            $request,
            MenuLinkDefinitionFactory::GRID_ID,
            'kj_custommenu_link_index'
        );
    }

    /**
     * générer tous les links de cms pages et catégories
     *
     * @param Request $request
     *
     * @return Response
     */
    public function generateAction(Request $request)
    {
        $menuLinkIndexer = $this->get('prestashop.module.kj_cutsommenu.links.generator');
        $menuLinkIndexer->insertMenuLinks();
        $this->addFlash('success', $this->trans('links were successfully generated.', 'Modules.Demodoctrine.Admin'));
        return $this->redirectToRoute('kj_custommenu_item_index');
    }

    /**
     * générer tous les links de cms pages et catégories
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $FormBuilder = $this->get('prestashop.module.kj_cutsommenu.form.identifiable_object.builder.menu_link_form_builder');
        $Form = $FormBuilder->getForm();
        $Form->handleRequest($request);

        $FormHandler = $this->get('prestashop.module.kj_cutsommenu.form.identifiable_object.handler.menu_link_form_handler');
        $result = $FormHandler->handle($Form);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );
            return $this->redirectToRoute('kj_custommenu_link_add');
        }

        return $this->render('@Modules/kj_custommenu/views/templates/admin/create.html.twig', [
            'Form' => $Form->createView(),
            'layoutHeaderToolbarBtn' => $this->getToolbarAddLinkButtons(),
            'name'=>'Link',
            'icon'=>'insert_link'
        ]);
    }

    private function getToolbarAddLinkButtons()
    {
        return [
            'add' => [
                'desc' => $this->trans('Add New Item', 'Modules.Demodoctrine.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('kj_custommenu_item_add_single'),
            ]
        ];
    }

}