<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\IngredientType;

class ExtraPerProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
                ->add('extras', CollectionType::class, [
                    'entry_type' => ExtraType::class,
                    'label' => "Lista de Extras",
                    'entry_options' => [
                        'attr' => [
                            'class' => 'extra', // we want to use 'tr.item' as collection elements' selector
                        ],
                        'data' => $options['data']
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => [
                        'class' => 'table extra-collection',
                    ],
        ]);
    }
}