<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ScheduleType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options){

            $builder
                ->add('MO', CollectionType::class, [
                    'entry_type' => TimeIntervalType::class,
                    'label' => 'false',
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => [
                        'class' => 'form-control schedule-collection',
                    ],
                ])
                ->add('TU', CollectionType::class, [
                    'entry_type' => TimeIntervalType::class,
                    'label' => 'false',
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => [
                        'class' => 'form-control schedule-collection',
                    ],
                ])
                ->add('WE', CollectionType::class, [
                    'entry_type' => TimeIntervalType::class,
                    'label' => 'false',
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => [
                        'class' => 'form-control schedule-collection',
                    ],
                ])
                ->add('TH', CollectionType::class, [
                    'entry_type' => TimeIntervalType::class,
                    'label' => 'false',
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => [
                        'class' => 'form-control schedule-collection',
                    ],
                ])
                ->add('FR', CollectionType::class, [
                    'entry_type' => TimeIntervalType::class,
                    'label' => 'false',
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => [
                        'class' => 'form-control schedule-collection',
                    ],
                ])
                ->add('SA', CollectionType::class, [
                    'entry_type' => TimeIntervalType::class,
                    'label' => 'false',
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => [
                        'class' => 'form-control schedule-collection',
                    ],
                ])
                ->add('SU', CollectionType::class, [
                    'entry_type' => TimeIntervalType::class,
                    'label' => 'false',
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'required' => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => [
                        'class' => 'form-control schedule-collection',
                    ],
                ])
                ->add('submit', SubmitType::class, array('label' => 'Guardar',
                    'attr' => array(
                        'class' => 'btn btn-outline-primary col-auto'
                    )
                ));
    }

}

