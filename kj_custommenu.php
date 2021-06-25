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
       /* @var MenuItemRepository $repository */
        $repository = $this->get('prestashop.module.kj_cutsommenu.repository.menu_item_repository');
        $items = $repository->findBy([],array('position' => 'ASC'));
        $serializedItems = [];
        /* @var Entity\MenuItem $item */
        foreach ($items as $item) {
            $serializedItems[] = $item->toArray();
        }
        $this->smarty->assign(['items' => $serializedItems]);

        return $this->fetch('module:kj_custommenu/views/templates/front/menu.tpl');
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