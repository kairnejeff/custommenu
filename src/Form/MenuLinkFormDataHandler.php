<?php


namespace PrestaShop\Module\CustomMenu\Form;


use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\CustomMenu\Entity\MenuLink;
use PrestaShop\Module\CustomMenu\Repository\MenuLinkRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;

class MenuLinkFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var MenuLinkRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MenuLinkFormDataHandler constructor.
     * @param MenuLinkRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(MenuLinkRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function create(array $data)
    {
       $link = new MenuLink();
       $link->setLibelle($data['libelle']);
       $link->setLink($data['link']);
       $link->setType('custom');
       $this->entityManager->persist($link);
       $this->entityManager->flush();

       return $link->getId();
    }

    public function update($id, array $data)
    {
        /**@var MenuLink $link**/
        $link = $this->repository->findOneById($id);
        $link->setLink($data['link']);
        $link->setLibelle($data['libelle']);
        $this->entityManager->persist($link);
        $this->entityManager->flush();
    }


}