<?php
namespace PrestaShop\Module\CustomMenu\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class MenuItemBlock
{
    /**
     * @var MenuItem
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShop\Module\CustomMenu\Entity\MenuItem",inversedBy="listBlock",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @var MenuBlock
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShop\Module\CustomMenu\Entity\MenuBlock",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $block;

    /**
     * @var int
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @return MenuItem
     */
    public function getItem(): ?MenuItem
    {
        return $this->item;
    }

    /**
     * @param MenuItem $item
     */
    public function setItem(MenuItem $item): void
    {
        $this->item = $item;
    }

    /**
     * @return MenuBlock
     */
    public function getBlock(): ?MenuBlock
    {
        return $this->block;
    }

    /**
     * @param MenuBlock $block
     */
    public function setBlock(MenuBlock $block): void
    {
        $this->block = $block;
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

    public function toArray(){
        return [
            "block"=>$this->getBlock()->toArray(),
            "position"=>$this->getPosition()
        ];
    }
}