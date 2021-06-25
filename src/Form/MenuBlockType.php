<?php


namespace PrestaShop\Module\CustomMenu\Form;


use PrestaShop\Module\CustomMenu\Entity\MenuBlock;
use PrestaShop\Module\CustomMenu\Entity\MenuLink;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MenuBlockType extends AbstractType
{

    private $blocks;

    public function __construct(array $blocks)
    {
        $this->blocks = $blocks;

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Libellé',
                    'constraints' => [
                    new Length([
                        'max' => 20,
                        'maxMessage' =>(
                        'This field cannot be longer than %limit% characters'
                        ),
                    ]),
                    new NotBlank(),
                ]
            ])
            ->add('parent', EntityType::class, [
                'label' => 'parent',
                'class'   => MenuBlock::class,
                'choices' => $this->blocks,
                'choice_label' => function(?MenuBlock $menuBlock) {
                    return $menuBlock ? $menuBlock->getName() : '';
                },
                'required' => false,
                'empty_data' => ''
            ])
            ->add('listLink', CollectionType::class, [
                'label' => 'Links',
                'entry_type' => MenuBlockLinkType::class,
                'entry_options' => [
                    'label' => false
                ],
                'by_reference' => false,
                // this allows the creation of new forms and the prototype too
                'allow_add' => true,
                // self explanatory, this one allows the form to be removed
                'allow_delete' => true,
                'required' => false,
                'empty_data' => ''
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrestaShop\Module\CustomMenu\Entity\MenuBlock'
        ));
    }


}