<?php

namespace PrestaShop\Module\CustomMenu\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\CustomMenu\Repository\MenuLinkRepository")
 */
class MenuLink
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
     * @ORM\Column(name="libelle", type="string")
     */
    private $libelle;

    /**
     * @var string
     * @ORM\Column(name="url", type="string")
     */
    private $link;


    /**
     * @var string
     * @ORM\Column(name="type", type="string")
     */
    private $type;


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
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }


    public function toArray()
    {
        return [
            'id_link' => $this->getId(),
            'libelle_link' => $this->getLibelle(),
            'url' =>$this->getLink(),
            'type'=>$this->getType()
        ];
    }
}