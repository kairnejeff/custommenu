<?php


namespace PrestaShop\Module\CustomMenu\Controller\Admin;


use Doctrine\Common\Collections\ArrayCollection;
use PrestaShop\Module\CustomMenu\Entity\MenuBlock;
use PrestaShop\Module\CustomMenu\Form\MenuBlockType;
use PrestaShop\Module\CustomMenu\Grid\Filters\BlockFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;

class MenuBlockController extends FrameworkBundleAdminController
{
    public function indexAction(BlockFilters $blockFilters)
    {
        $blockGridFactory = $this->get('prestashop.module.kj_cutsommenu.grid.factory.blocks');
        $blockGrid = $blockGridFactory->getGrid($blockFilters);
        return $this->render(
            '@Modules/kj_custommenu/views/templates/admin/index.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Menu block', 'Modules.kj_cutsommenu.Admin'),
                'itemGrid' => $this->presentGrid($blockGrid),
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
            ]
        );
    }

    public function addAction(Request $request)
    {
        $bloc= new MenuBlock();
        $em = $this->get('doctrine.orm.default_entity_manager');
        $form = $this->createForm(MenuBlockType::class, $bloc)->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($bloc);
            $em->flush();
            $idBlock = $bloc->getId();
            return $this->redirectToRoute('kj_custommenu_block_edit', [
                'blockid' => $idBlock
            ]);
        }
        return $this->render('@Modules/kj_custommenu/views/templates/admin/block/create.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    public function editAction(Request $request, $blockid)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $bloc = $em->getRepository('PrestaShop\Module\CustomMenu\Entity\MenuBlock')->findOneBy(['id' => $blockid]);
        $links = new ArrayCollection();
        foreach ($bloc->getListLink()  as $link) {
            $links->add($link);
        }
        $form = $this->createForm(MenuBlockType::class, $bloc)->handleRequest($request);
        if ($form->isSubmitted()) {
            foreach ($links as $link) {
                if ($bloc->getListLink()->contains($link) === false) {
                    $em->remove($link);
                }
            }
            $em->persist($bloc);
            $em->flush();
            return $this->redirectToRoute('kj_custommenu_block_index');

        }
        return $this->render('@Modules/kj_custommenu/views/templates/admin/block/edit.html.twig', [
            'Form' => $form->createView(),
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
            'show_items' => [
                'desc' => $this->trans('Show items', 'Modules.kj_custommenu.Admin'),
                'icon' => 'arrow_drop_down',
                'href' => $this->generateUrl('kj_custommenu_item_index'),
            ]
        ];
    }

}