<?php


namespace PrestaShop\Module\CustomMenu\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class MenuBlockLink
{
    /**
     * @var MenuLink
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShop\Module\CustomMenu\Entity\MenuLink",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $link;

    /**
     * @var MenuBlock
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShop\Module\CustomMenu\Entity\MenuBlock", inversedBy="listLink",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $block;

    /**
     * @var int
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

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
            'position'=>$this->getPosition(),
            'link'=>$this->getLink()->toArray()
        ];
    }
}