<?php

namespace App\Form;

use App\Form\ExtraType;
use App\Form\IngredientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ItemType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        if(isset($options['data']['ingredients'])){
            //var_dump($options['data']['ingredients']);
            //die;
        }
        $builder->add('information', ItemInformationType::class, array(
                    'attr' => array(
                        'class' => 'select2 form-control',
                        'style' => 'width: 100%'
                    ),
                    'data' => $options['data']['menus']
                ))
                ->add('ingredients', CollectionType::class, [
                    'entry_type'   => IngredientType::class,
                    'label'=> "Lista de Ingredientes",
                    'entry_options' => [
                        'attr' => [
                            'class' => 'ingredient', // we want to use 'tr.item' as collection elements' selector
                        ],
                        'data' => isset($options['data']['ingredients']) ? $options['data']['ingredients'] : []
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
                 ->add('extras', CollectionType::class, [
                    'entry_type' => ExtraType::class,
                    'label' => "Lista de Extras",
                    'entry_options' => [
                        'attr' => [
                            'class' => 'extra', // we want to use 'tr.item' as collection elements' selector
                        ]
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
                ])
                ->add('save', SubmitType::class, [
                'label' => 'Guardar',
                'attr'=>array(
                             'class'=>'btn btn-primary float-right'
                            )
                ]);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            if(isset($data['id'])){
                $form
                        ->add('cancel', ResetType::class, array(
                            'label' => 'Cancelar',
                            'attr' => array(
                                'class' => 'btn btn-secondary',
                                'data-dismiss' => 'modal'
                            )
                ));
            }
            
        });
    }

}
