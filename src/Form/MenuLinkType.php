<?php


namespace PrestaShop\Module\CustomMenu\Form;


use PrestaShop\Module\CustomMenu\Entity\MenuLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MenuLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('libelle', TextType::class, [
                'label' => 'Libéllé',
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
            ->add('link', TextType::class, [
                'label' => 'Link',
                'constraints' => [
                    new NotBlank(),
                ]
            ]);
    }
}