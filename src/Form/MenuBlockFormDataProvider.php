<?php


namespace PrestaShop\Module\CustomMenu\Form;


use PrestaShop\Module\CustomMenu\Repository\MenuBlockRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class MenuBlockFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var MenuBlockRepository
     */
    private $repository;

    /**
     * MenuLinkDataProvider constructor.
     * @param MenuBlockRepository $repository
     */
    public function __construct(MenuBlockRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllData()
    {
        return $this->repository->findAll() ;
    }

    public function getAllParent()
    {
        return $this->repository->findBy(array("parent" => null)) ;
    }

    public function getData($id)
    {
        // TODO: Implement getData() method.
    }

    public function getDefaultData()
    {
        // TODO: Implement getDefaultData() method.
    }

}