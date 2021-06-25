<?php


namespace PrestaShop\Module\CustomMenu\Form;


use PrestaShop\Module\CustomMenu\Entity\MenuLink;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class MenuItemType extends AbstractType
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

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('position',IntegerType::class,[
                'label' => 'Position',
            ])
            ->add('name', TextType::class, [
                'label' => 'name',
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
            ->add('is_single_link', ChoiceType::class, [
                'label' => 'Simple link',
                'choices'  => [
                    'Yes' => true,
                    'No' => false,
                ],
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
                'required' => false,
                'empty_data' => ''
            ])
            ->add('listBlock', CollectionType::class, [
                'label' => 'Blocks',
                'entry_type' => MenuItemBlockType::class,
                'entry_options' => [
                    'label' => false
                ],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'empty_data' => ''
            ]);
    }


}