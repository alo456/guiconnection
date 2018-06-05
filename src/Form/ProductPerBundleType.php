<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\ProductBriefType;


class ProductPerBundleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
                ->add('products', CollectionType::class, [
                    'entry_type' => ProductBriefType::class,
                    'label' => "Lista de Productos",
                    'entry_options' => [
                        'attr' => [
                            'class' => 'product', // we want to use 'tr.item' as collection elements' selector
                        ]
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => [
                        'class' => 'table ingredient-collection',
                    ],
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Guardar',
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
        ]);
    }

}