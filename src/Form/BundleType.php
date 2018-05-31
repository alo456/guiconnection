<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class BundleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$options['data'] = null;
       
        $builder->add('name', TextType::class , array(
                                                    'label'=>'Nombre del Paquete',
                                                    'attr'=>array(
                                                                'class'=>'form-control',
                                                                'form' => 'ingredientsform'
                                                            ),
                                                    
                ))
                ->add('description', TextType::class, array(
                                                    'label'=>'Descripción',
                                                    'attr'=>array(
                                                                'class'=>'form-control',
                                                                'form' => 'ingredientsform'
                                                            )
                ))
                ->add('cost', NumberType::class, array(
                                                    'label'=>'Costo',
                                                    'attr'=>array(
                                                                'class'=>'form-control',
                                                                'form' => 'ingredientsform'
                                                            )
                                                
                ))
                ->add('cookingtime', NumberType::class, array(
                                                    'label'=>'Tiempo de preparación',
                                                    'attr'=>array(
                                                                'class'=>'form-control',
                                                                'form' => 'ingredientsform'
                                                            )
                ))
                ->add('product', ChoiceType::class, array(
                                                     'label' => false,
                                                    'attr' => array(
                                                                'class' => 'select2 form-control',
                                                                'style' => 'width: 100%'
                                                    ),
                                                    'choices' => array()
                ))
                ->add('quantity', NumberType::class, array(
                                                    'label' => false,
                                                    'attr' => array(
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Cantidad'
                                                    )
                ));
        
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $event->setData(null);
            });
    }
}

