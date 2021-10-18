<?php

namespace App\Form;

use App\Entity\StockHistoric;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CambiaStockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stock', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Stock',
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mr-2'],
                'label' => 'Cambia Stock'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StockHistoric::class,
        ]);
    }
}
