<?php

namespace PrestaShop\Module\CustomMenu\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use PrestaShop\Module\CustomMenu\Entity\MenuItem;
use PrestaShop\Module\CustomMenu\Entity\MenuItemBlock;
use PrestaShop\Module\CustomMenu\Repository\MenuItemRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;

class MenuItemFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var MenuItemRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MenuItemFormDataHandler constructor.
     * @param MenuItemRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(MenuItemRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }


    public function create(array $data)
    {
        /** @var MenuItem $item */
        $item = new MenuItem();
        $item->setName($data['name']);
        $item->setPosition($data['position']);
        $item->setIsSingleLink($data['is_single_link']);
        if($data['is_single_link']){
            $menuLink= $this->entityManager->getRepository('PrestaShop\Module\CustomMenu\Entity\MenuLink')->find($data['link']);
            //$position = $this->repository->getCountItem();
            $item->setLink($menuLink);
            $this->entityManager->persist($item);
            //$item->setPosition($position+1);
        }else{
            /** @var MenuItemBlock $itemBlock */
           $this->entityManager->persist($item);
           foreach($data['listBlock'] as $itemBlock){
               $itemBlock->setItem($item);
               $this->entityManager->persist($itemBlock);
           }
        }
        $this->entityManager->flush();
        return $item->getId();
    }

    public function update($id, array $data)
    {
        /** @var MenuItem $item */
        $item = $this->repository->findOneById($id);
        $item->setName($data['name']);
        $item->setPosition($data['position']);
        if($data['is_single_link']){
            $menuLink= $this->entityManager->getRepository('PrestaShop\Module\CustomMenu\Entity\MenuLink')->find($data['link']);
            //$position = $this->repository->getCountItem();
            $item->setLink($menuLink);
        }else{
            $item->removeAllBlock();
            $this->entityManager->persist($item);
            $this->entityManager->flush();
            foreach($data['listBlock'] as $block){
                $item->addItemBlock($block);
            }
        }
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    public function delete($id)
    {
        try {
            $item=$this->repository->findOneById($id);
        } catch (EntityNotFoundException $e) {
            return false;
        }
        $this->entityManager->remove($item);
        $this->entityManager->flush();
        return true;

    }

}