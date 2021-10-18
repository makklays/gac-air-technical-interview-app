<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'label' => 'Nombre',
            ])
            ->add('password',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'label' => 'Contraseña',
            ])
            ->add('active', CheckboxType::class, [
                'attr' => ['class' => ''],
                'required' => true,
                'label' => 'Active',
            ])
            //->add('created_at')
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mr-2'],
                'label' => 'Añadir usuario'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
