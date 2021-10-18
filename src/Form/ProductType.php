<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'label' => 'Nombre',
            ])
            //->add('created_at')
            ->add('stock', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Stock',
            ])
            ->add('category', EntityType::class,  array(
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'class' => Category::class,
                'choice_label' => function ($category) {
                    return $category->getName();
                },
                'placeholder' => 'Elige categoría',
                'label' => 'Categoría',
            ))
            /*->add('category', ChoiceType::class, array(
                'choices'  => array(
                    1 => 1,
                    'Categoria 2' => '2',
                    'Categoria 3' => '3',
                    'Categoria 4' => '4',
                ),
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'placeholder' => 'Elige categoría',
                'label' => 'Categoría',
            ))*/
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mr-2'],
                'label' => 'Añadir categoría'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
