<?php

namespace App\Form\Type;

use App\DTO\ManageUserPropertyDTO;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название',
            ])
            ->add('value', TextType::class, [
                'label' => 'Значение',
            ])
            ->add('submit', SubmitType::class)
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'login',
                'multiple' => false,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ManageUserPropertyDTO::class,
            'empty_data' => new ManageUserPropertyDTO(),
            'isNew' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'save__user_property';
    }
}