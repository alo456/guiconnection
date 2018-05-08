<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\IngredientType;

class IngredientPerProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre del producto',
            ])
           ->add('ingredients', CollectionType::class, [
                'entry_type'   => IngredientType::class,
                'label'=>'Ingredientes',
                'entry_options' => [
                    'attr' => [
                        'class' => 'ingredient', // we want to use 'tr.item' as collection elements' selector
                    ],
                ],
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
                'by_reference' => true,
                'delete_empty' => true,
                'attr' => [
                    'class' => 'table ingredient-collection',
                ],

            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar',
            ])
        ;
    }
}