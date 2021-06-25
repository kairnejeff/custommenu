<?php


namespace PrestaShop\Module\CustomMenu\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\CustomMenu\Repository\MenuBlockRepository")
 */
class MenuBlock
{
    /**
     * @var
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id ;


    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    private $name;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PrestaShop\Module\CustomMenu\Entity\MenuBlockLink", mappedBy="block", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $listLink;

    public function __construct()
    {
        $this->listLink=new ArrayCollection();
    }

    /**
     * One block has Many blocks.
     * @OneToMany(targetEntity="PrestaShop\Module\CustomMenu\Entity\MenuBlock", mappedBy="parent")
     */
    private $children;

    /**
     * @var MenuBlock
     * @ManyToOne(targetEntity="PrestaShop\Module\CustomMenu\Entity\MenuBlock", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

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
     * @return Collection
     */
    public function getListLink(): ?Collection
    {
        return $this->listLink;
    }

    /**
     * @param Collection $listLink
     */
    public function setListLink(Collection $listLink): void
    {
        $this->listLink = $listLink;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
    }

    /**
     * @return MenuBlock
     */
    public function getParent(): ?MenuBlock
    {
        return $this->parent;
    }

    /**
     * @param MenuBlock $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    public function addMenuLink(MenuBlockLink $menuBlockLink)
    {
        $this->listLink->add($menuBlockLink);
        return $this;
    }
    public function toArray()
    {
        $serializedLinks = [];
        /** @var MenuBlockLink $blockLink  **/
        foreach ($this->getListLink() as $blockLink) {
            $serializedLinks[] = $blockLink->toArray();
        }
        $serializedBlocks = [];
        if($this->getChildren()!=null){
            foreach ($this->getChildren() as $block) {
                $serializedBlocks[] = $block->toArray();
            }
        }
        return [
            'id_block' => $this->getId(),
            'name_block' => $this->getName(),
            'list_link' =>$serializedLinks,
            'children'=>$serializedBlocks
        ];
    }

}