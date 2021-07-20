<?php
use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\CustomMenu\Entity;
use PrestaShop\Module\CustomMenu\Repository\MenuItemRepository;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

class Kj_CustomMenu extends Module
{
    private $db;
    const MENU_JSON_CACHE_KEY = 'KJ_CUSTOM_MENU_JSON';
    public function __construct() {
        $this->name = 'kj_custommenu';
        $this->author = 'Jing';
        $this->version = '1.0.0';
        $this->need_instance= 0;
        $this->bootstrap = true;
        $this->tab = 'others';
        parent::__construct();
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => _PS_VERSION_
        );
        $this->displayName = $this->l('custom menu');
        $this->description = $this->l('Ajouter des blocks de liens');
        $this->db= Db::getInstance();
    }

    public function install(){
        if (parent::install() && $this->registerHook('displayTop')
        ) {
            return $this->installDatabaseTables();
        }
        return false;
    }

    public function uninstall()
    {
        /* Deletes Module */
        if (parent::uninstall()) {
            return $this->uninstallDatabaseTables();
        }
        return false;
    }

    public function hookDisplayTop()
    {
        $serializedItems = [];
       /* @var MenuItemRepository $repository */

        try {
            $repository = $this->get('prestashop.module.kj_cutsommenu.repository.menu_item_repository');
        } catch (Exception $e) {
            // Catch exception in case container is not available, or service is not available
            $repository = null;
        }

        if (!$repository) {
            $serializedItems =$this->getConfigFieldsValues();
            //var_dump($serializedItems);die;
        }else{
            $items = $repository->findBy([],array('position' => 'ASC'));
            /* @var Entity\MenuItem $item */
            foreach ($items as $item) {
                $serializedItems[] = $item->toArray();
            }
        }
        $this->smarty->assign(['items' => $this->getCurrentPageIdentifier($serializedItems)]);

        return $this->fetch('module:kj_custommenu/views/templates/front/menu.tpl');
    }

    private function getCurrentPageIdentifier($serializedItems)
    {
        $current= "index";
        $controllerName = Dispatcher::getInstance()->getController();
        if ($controllerName === 'cms' && ($id = Tools::getValue('id_cms'))) {
            $current= $this->context->link->getCMSLink(new CMS($id,$this->context->language->id));
        } else if ($controllerName === 'category' && ($id = Tools::getValue('id_category'))) {
            $current= $this->context->link->getCategoryLink($id);
        } else if ($controllerName === 'cms' && ($id = Tools::getValue('id_cms_category'))) {
            $current= $this->context->link->getCMSCategoryLink(new CMSCategory($id));
        }  else if ($controllerName === 'product' && ($id = Tools::getValue('id_product'))) {
            $current= $this->context->link->getProductLink(new Product($id));
        } else if($controllerName === 'search' && ($tag = Tools::getValue('tag'))){
            $current =$this->context->link->getPageLink($controllerName,null,$this->context->language->id,array("tag"=>$tag) ) ;
        }
        foreach ($serializedItems as $index =>  $item){
            if($item['is_single']){
                if($item['link']['url']==$current){
                    $serializedItems[$index]['link']['current']=true;
                }
            }else{
                foreach ($item['list_block']  as $indexListBlock => $block) {

                    if (sizeof($block['block']['children'])!=0){
                        foreach ($block['block']['children'] as $indexBlockChild => $block){
                            foreach ($block['list_link']  as $indexLink => $Childlink){
                                if(strcmp(urlencode($Childlink['link']['url']),$current)==0) {
                                    $serializedItems[$index]['list_block'][$indexListBlock]['block']['children'][$indexBlockChild]['list_link'][$indexLink]['link']['current'] = true;
                                }
                            }
                        }
                    }else{
                        foreach ($block['block']['list_link'] as $indexLink => $link){
                            if(strcmp(urlencode($link['link']['url']),$current)==0){
                                $serializedItems[$index]['list_block'][$indexListBlock]['block']['list_link'][$indexLink]['link']['current']=true;
                            }
                        }
                    }
                }
            }
        }
        return $serializedItems;
    }

    protected function getConfigFieldsValues(){
        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;

        $key = self::MENU_JSON_CACHE_KEY . '_' . $id_lang . '_' . $id_shop . '.json';
        $cacheDir = _PS_CACHE_DIR_ . 'kj_custommenu';
        $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $key;
        $menu = json_decode(@file_get_contents($cacheFile), true);
        if (!is_array($menu) || json_last_error() !== JSON_ERROR_NONE) {
            $menu = $this->sqlGetvaribales();
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir);
            }
            file_put_contents($cacheFile, json_encode($menu));
        }
        return $menu;
    }
    protected function sqlGetvaribales(){
        $sqlSelectItem = "select * from ". _DB_PREFIX_ ."menu_item order by position";
        $items = Db::getInstance()->executeS($sqlSelectItem);
        foreach ($items as $index => $item){
            $items[$index]['id_item']=$item['id'];
            $items[$index]['name_item']=$item['name'];
            $items[$index]['is_single']=$item['is_single_link'];
            if(!$item['is_single_link']){
                $items[$index]['list_block']=$this->sqlGetBlocks($item['id']);
            }else{
                $sqlSelectLink = $sqlSelectLinks = "select l.* from ". _DB_PREFIX_ ."menu_item as i, ". _DB_PREFIX_ ."menu_link as l 
                           where i.link_id= l.id and i.link_id=".$item['link_id'];
                $link=Db::getInstance()->executeS($sqlSelectLink);
                $items[$index]['link']['id_link'] =$link[0]['id'];
                $items[$index]['link']['libelle_link']=$link[0]['libelle'];
                $items[$index]['link']['url']=$link[0]['url'];
                $items[$index]['link']['type']=$link[0]['type'];
            }
        }
        return $items;
    }

    protected function sqlGetBlocks($idItem){
        $sqlSelectBlcok = "select b.* from ". _DB_PREFIX_ ."menu_item_block as i, ". _DB_PREFIX_ ."menu_block as b 
                            where i.block_id=b.id
                            and i.item_id = ".$idItem ." order by position";
        $resultat = Db::getInstance()->executeS($sqlSelectBlcok);
        foreach ($resultat as  $index => $row){
            $block=$this->sqlGetInfoBlock($row['id']);
            $resultat[$index]['block']['id_block']=  $row['name'];
            $resultat[$index]['block']['name_block']= $row['name'];
            $resultat[$index]['block']['children']=$block['children'];
            $resultat[$index]['block']['list_link']=$block['list_link'];
        }
        return $resultat;
    }
    protected function sqlGetInfoBlock($idBlock){
        $sqlSelectBlcok = "select * from ". _DB_PREFIX_ ."menu_block where id = ".$idBlock;
        $resultat = Db::getInstance()->executeS($sqlSelectBlcok);
        $block=[];
        $block['children']=[];
        $block['list_link']=[];
        $sqlSelectChildren = "select * from ". _DB_PREFIX_ ."menu_block where parent_id = ".$idBlock;
        $childrens = Db::getInstance()->executeS($sqlSelectChildren);
        if(!empty($childrens)){
            foreach ($childrens as $index =>$blockChild){
                $block['children'][$index]['list_link']= $this->sqlGetLinks($blockChild['id']);
                $block['children'][$index]['name_block']= $blockChild['name'];
            }
        }else{
            $block['list_link'] = $this->sqlGetLinks($resultat[0]['id']);
        }

        return $block;
    }
    protected function sqlGetLinks($idBlock){
        $sqlSelectLinks = "select l.* from ". _DB_PREFIX_ ."menu_block_link as b, ". _DB_PREFIX_ ."menu_link as l 
                           where b.link_id= l.id
                           and block_id = ".$idBlock.
                           " order by b.position";
        $resultat =Db::getInstance()->executeS($sqlSelectLinks);
        $links =[];
        foreach ($resultat as $index =>$link){
            $links[$index]['link']['id_link']=$link['id'];
            $links[$index]['link']['libelle_link']=$link['libelle'];
            $links[$index]['link']['url']=$link['url'];
            $links[$index]['link']['type']=$link['type'];
        }
        return $links;
    }

    public function getContent()
    {
        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminCustomMenu')
        );
    }

    /**
     * Installs database tables
     *
     * @return bool
     */
    public function installDatabaseTables()
    {
        $dbInstallFile = (dirname(__FILE__).DIRECTORY_SEPARATOR."/sql/install.sql");

        if (!file_exists($dbInstallFile)) {
            return false;
        }

        $sql = Tools::file_get_contents($dbInstallFile);

        if (empty($sql) || !is_string($sql)) {
            return false;
        }

        $sql = str_replace(['PREFIX_', 'ENGINE_TYPE'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql);
        $sql = preg_split("/;\s*[\r\n]+/", trim($sql));

        if (!empty($sql)) {
            foreach ($sql as $query) {
                if (!$this->db->execute($query)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function uninstallDatabaseTables()
    {
        $dbUninstallFile = (dirname(__FILE__).DIRECTORY_SEPARATOR."/sql/uninstall.sql");

        if (!file_exists($dbUninstallFile)) {
            return false;
        }

        $sql = Tools::file_get_contents($dbUninstallFile);

        if (empty($sql) || !is_string($sql)) {
            return false;
        }

        $sql = str_replace(['PREFIX_'], [_DB_PREFIX_], $sql);
        $sql = preg_split("/;\s*[\r\n]+/", trim($sql));

        if (!empty($sql)) {
            foreach ($sql as $query) {
                if (!$this->db->execute($query)) {
                    return false;
                }
            }
        }

        return true;
    }
}