<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ItemInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('name', TextType::class, array(
                    'label' => 'Nombre del Producto',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                ))
                ->add('description', TextType::class, array(
                    'label' => 'Descripción',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('cost', IntegerType::class, array(
                    'label' => 'Costo',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('cookingtime', IntegerType::class, array(
                    'label' => 'Tiempo de cocción',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('menu', ChoiceType::class, array(
                    'label' => 'Menu',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'placeholder' => 'Selecciona un menú',
                    'choices' => $options['data']
                ))
                ->add('photo', FileType::class, array(
                    'label' => 'Fotografía',
                    'attr' => array(
                        'class' => 'form-control',
                        'accept' => '.png'
                    ),
                    'required' => false
                ));

        
    }
}

