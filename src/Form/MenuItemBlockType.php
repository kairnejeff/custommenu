<?php


namespace PrestaShop\Module\CustomMenu\Form;


use PrestaShop\Module\CustomMenu\Entity\MenuBlock;
use PrestaShop\Module\CustomMenu\Entity\MenuItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuItemBlockType extends AbstractType
{
    /**
     * @var array
     */
    private $blockChoices;

    /**
     * MenuItemType constructor.
     * @param array $blockChoices
     */
    public function __construct(array $blockChoices)
    {
        $this->blockChoices = $blockChoices;

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position',IntegerType::class)
            ->add('item', EntityType::class,[
                'class'   => MenuItem::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('block', EntityType::class, [
                'label' => 'Block',
                'class'   => MenuBlock::class,
                'choices' => $this->blockChoices,
                'choice_label' => function(?MenuBlock $menuBlock) {
                    return $menuBlock ? $menuBlock->getName() : '';
                },
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrestaShop\Module\CustomMenu\Entity\MenuItemBlock',
            'block'=>null,
            'item'=>null
        ));
    }

}