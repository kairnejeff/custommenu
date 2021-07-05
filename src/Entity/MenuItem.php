<?php


namespace PrestaShop\Module\CustomMenu\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\CustomMenu\Repository\MenuItemRepository")
 */
class MenuItem
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    private $name;


    /**
     * @var boolean
     * @ORM\Column(name="is_single_link", type="boolean")
     */
    private $is_single_link;

    /**
     * @var int
     * @ORM\Column(name="position", type="integer")
     */
    private $position;


    /**
     * @var MenuLink
     * @ORM\ManyToOne(targetEntity="PrestaShop\Module\CustomMenu\Entity\MenuLink")
     * @ORM\JoinColumn(name="link_id", referencedColumnName="id", nullable=true)
     */
    private $link;


    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="PrestaShop\Module\CustomMenu\Entity\MenuItemBlock", mappedBy="item", cascade={"remove","persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $listItemBlock;


    public function __construct()
    {
        $this->listitemBlock = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isIsSingleLink(): ?bool
    {
        return $this->is_single_link;
    }

    /**
     * @param bool $is_single_link
     */
    public function setIsSingleLink(bool $is_single_link): void
    {
        $this->is_single_link = $is_single_link;
    }

    /**
     * @return Collection
     */
    public function getListBlock(): ?Collection
    {
        return $this->listItemBlock;
    }

    /**
     * @param Collection $listItemBlock
     */
    public function setListBlock(Collection $listItemBlock): void
    {
        $this->listItemBlock = $listItemBlock;
    }


    /**
     * @return MenuLink
     */
    public function getLink(): ?MenuLink
    {
        return $this->link;
    }

    /**
     * @param MenuLink $link
     */
    public function setLink(MenuLink $link): void
    {
        $this->link = $link;
    }

    /**
     * @return int
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function addItemBlock(MenuItemBlock $menuItemBlock){
        if(!$this->listItemBlock->contains($menuItemBlock))
            $this->listItemBlock->add($menuItemBlock);
    }

    public function removeAllBlock(){
        $this->listItemBlock->clear();
    }

    public function toArray()
    {
        $serializedBlocks = [];
        $link="";
        if($this->isIsSingleLink()){
            $link=$this->getLink()->toArray();
        }else{
            /** @var MenuItemBlock $listBlock  **/
            foreach ($this->getListBlock() as $listBlock) {
                $serializedBlocks[] = $listBlock->toArray();
            }
        }
        return [
            'id_item' => $this->getId(),
            'name_item' => $this->getName(),
            'is_single'=>$this->isIsSingleLink(),
            'link'=>$link,
            'list_block' =>$serializedBlocks

        ];
    }
}