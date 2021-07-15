<?php

namespace PrestaShop\Module\CustomMenu\Database;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\CustomMenu\Repository\MenuLinkRepository;
use PrestaShop\Module\CustomMenu\Entity\MenuLink;


class MenuLinkIndexer
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $dbPrefix;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $linkMaker ;

    /**
     * @var int
     */
    private $idLang;

    /**
     * @var array
     */
    private $shopIds;


    /**
     * MenuLinkIndexer constructor.
     * @param Connection $connection
     * @param string $dbPrefix
     * @param EntityManagerInterface $entityManager
     * @param $linkMaker
     * @param $idLang
     * @param array|null $shopIds
     */
    public function __construct(Connection $connection, string $dbPrefix, EntityManagerInterface $entityManager, $linkMaker, $idLang, array $shopIds = null)
    {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
        $this->entityManager = $entityManager;
        $this->linkMaker = $linkMaker;
        $this->idLang = $idLang;
        $this->shopIds = $shopIds;
    }

    public function insertMenuLinks()
    {
        //$this->removeAllPagesNoCustom();
        //indexder categories pages
        $categories= $this->getCategories();
        foreach ($categories as $category) {
            if($memuLink = $this->entityManager->getRepository('PrestaShop\Module\CustomMenu\Entity\MenuLink')->findOneBy(array('libelle'=>$category['name']))){
            }else{
                $memuLink= new MenuLink();
            }
            $memuLink->setLibelle($category['name']);
            $memuLink->setLink($this->linkMaker->getCategoryLink((int)$category['id_category']));
            $memuLink->setType('category');
            $this->entityManager->persist($memuLink);
        }

        //indexder CMS pages
        $pages = $this ->getCmsPages();
        foreach ($pages as $page) {
            if($memuLink = $this->entityManager->getRepository('PrestaShop\Module\CustomMenu\Entity\MenuLink')->findOneBy(array('libelle'=>$page['meta_title']))){
            }else{
                $memuLink= new MenuLink();
            }
            $memuLink->setLibelle($page['meta_title']);
            $memuLink->setLink($this->linkMaker->getCategoryLink((int)$page['id_cms']));
            $memuLink->setType('cms');
            $this->entityManager->persist($memuLink);
        }

        $this->entityManager->flush();
    }

    public function removeAllPagesNoCustom()
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->neq('type', 'custom'));

        $links=$this->entityManager->getRepository('PrestaShop\Module\CustomMenu\Entity\MenuLink')->matching($criteria);
        foreach ($links as $link) {
            $this->entityManager->remove($link);
        }
    }


    private function getCategories()
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('cc.id_category, ccl.name')
            ->from($this->dbPrefix . 'category', 'cc')
            ->innerJoin('cc', $this->dbPrefix . 'category_lang', 'ccl', 'cc.id_category = ccl.id_category')
            ->innerJoin('cc', $this->dbPrefix . 'category_shop', 'ccs', 'cc.id_category = ccs.id_category')
            ->andWhere('cc.active = 1')
            ->andWhere('ccl.id_lang = :idLang')
            ->andWhere('ccs.id_shop IN (:shopIds)')
            ->setParameter('idLang', $this->idLang)
            ->setParameter('shopIds', implode(',', $this->shopIds))
            ->orderBy('ccl.name')
        ;

        return $qb->execute()->fetchAll();
    }

    private function getCmsCategories()
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('cc.id_cms_category')
            ->from($this->dbPrefix . 'cms_category', 'cc')
            ->innerJoin('cc', $this->dbPrefix . 'cms_category_lang', 'ccl', 'cc.id_cms_category = ccl.id_cms_category')
            ->innerJoin('cc', $this->dbPrefix . 'cms_category_shop', 'ccs', 'cc.id_cms_category = ccs.id_cms_category')
            ->andWhere('cc.active = 1')
            ->andWhere('ccl.id_lang = :idLang')
            ->andWhere('ccs.id_shop IN (:shopIds)')
            ->setParameter('idLang', $this->idLang)
            ->setParameter('shopIds', implode(',', $this->shopIds))
            ->orderBy('ccl.name')
        ;

        return $qb->execute()->fetchAll();
    }

    private function getCmsPages()
    {
        $cmsCategories=$this->getCmsCategories();
        $pages=[];
        foreach ($cmsCategories as $cmsCategory) {
            $qb = $this->connection->createQueryBuilder();
            $qb
                ->select('c.id_cms, cl.meta_title')
                ->from($this->dbPrefix . 'cms', 'c')
                ->innerJoin('c', $this->dbPrefix . 'cms_lang', 'cl', 'c.id_cms = cl.id_cms')
                ->innerJoin('c', $this->dbPrefix . 'cms_shop', 'cs', 'c.id_cms = cs.id_cms')
                ->andWhere('c.active = 1')
                ->andWhere('cl.id_lang = :idLang')
                ->andWhere('cs.id_shop IN (:shopIds)')
                ->andWhere('c.id_cms_category = :idCmsCategory')
                ->setParameter('idCmsCategory', $cmsCategory['id_cms_category'])
                ->setParameter('idLang', $this->idLang)
                ->setParameter('shopIds', implode(',', $this->shopIds))
                ->orderBy('c.position')
            ;
            $pages = array_merge($pages, $qb->execute()->fetchAll());
        }
        return $pages;
    }
}