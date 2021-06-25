<?php


namespace PrestaShop\Module\CustomMenu\Form;


use PrestaShop\Module\CustomMenu\Entity\MenuLink;
use PrestaShop\Module\CustomMenu\Repository\MenuLinkRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class MenuLinkFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var MenuLinkRepository
     */
    private $repository;

    /**
     * MenuLinkDataProvider constructor.
     * @param MenuLinkRepository $repository
     */
    public function __construct(MenuLinkRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllData()
    {
        $cmsPages =  $this->repository-> findBy(array('type'=> 'cms'));
        $categoryPages =$this->repository-> findBy(array('type'=> 'category'));
        $customPages =$this->repository-> findBy(array('type'=> 'custom'));
        return [
            'cmsPages'=>$cmsPages,
            'categoryPages'=>$categoryPages,
            'customPages'=>$customPages
        ];
    }

    public function getData($linkid)
    {
        /** @var MenuLink $link */
        $link = $this->repository->findOneById($linkid);

        $itemData = [
            'libelle' => $link->getLibelle(),
            'link'=>$link->getLink(),
        ];
        return $itemData;
    }

    public function getDefaultData()
    {
        // TODO: Implement getDefaultData() method.
    }


}