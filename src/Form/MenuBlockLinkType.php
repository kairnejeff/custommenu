<?php


namespace PrestaShop\Module\CustomMenu\Form;


use PrestaShop\Module\CustomMenu\Entity\MenuBlock;
use PrestaShop\Module\CustomMenu\Entity\MenuLink;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuBlockLinkType extends AbstractType
{
    /**
     * @var array
     */
    private $pageChoices;

    /**
     * MenuItemType constructor.
     * @param array $pageChoices
     */
    public function __construct(array $pageChoices)
    {
        $this->pageChoices = $pageChoices;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position',IntegerType::class)
            // this is the embeded form, the most important things are highlighted at the bottom
            ->add('block', EntityType::class,[
                'class'   => MenuBlock::class,
                'choice_label' => 'name',
            ])
            ->add('link', EntityType::class, [
                'label' => 'Link',
                'class'   => MenuLink::class,
                'choices' => [
                    'CMS pages' => $this->pageChoices['cmsPages'],
                    'Category pages' => $this->pageChoices['categoryPages'],
                    'Custom pages' => $this->pageChoices['customPages'],
                ],
                'choice_label' => function(?MenuLink $menuLink) {
                    return $menuLink ? $menuLink->getLibelle() : '';
                },
            ])
        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PrestaShop\Module\CustomMenu\Entity\MenuBlockLink',
            'block'=>null
        ]);
    }

}