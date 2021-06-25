<?php

namespace PrestaShop\Module\CustomMenu\Form;

use Doctrine\Common\Collections\ArrayCollection;
use PrestaShop\Module\CustomMenu\Entity\MenuBlock;
use PrestaShop\Module\CustomMenu\Entity\MenuItemBlock;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;
use \PrestaShop\Module\CustomMenu\Repository\MenuItemRepository;
use \PrestaShop\Module\CustomMenu\Entity\MenuItem;

class MenuItemFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var MenuItemRepository
     */
    private $repository;

    /**
     * @param MenuItemRepository $repository
     */
    public function __construct(MenuItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getData($itemId)
    {
        /** @var MenuItem $item */
        $item = $this->repository->findOneById($itemId);
        $listBlock = new ArrayCollection();
        //dump(sizeof($item->getListBlock()));die;
        if(!$item->isIsSingleLink()&&sizeof($item->getListBlock())!==0){
            /** @var MenuItemBlock $itemBlock */
            foreach ($item->getListBlock()  as $itemBlock) {
                $listBlock->add($itemBlock);
                //dump($itemBlock->getItem());die;
            }
        }

        //var_dump(sizeof($listBlock));die;
        $itemData = [
            'name' => $item->getName(),
            'is_single_link'=>$item->isIsSingleLink(),
            'link'=>$item->getLink(),
            'position'=>$item->getPosition(),
            'listBlock'=>$listBlock
        ];
        return $itemData;
    }

    public function getDefaultData()
    {
        return [
            'name' => '',
            'is_single_link'=>true,
            'link' => '',
            'position'=>0,
            'listBlock'=>array()
        ];
    }


}