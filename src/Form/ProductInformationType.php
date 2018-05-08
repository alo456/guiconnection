<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class , array(
                                                    'label'=>'Nombre del Producto',
                                                    'attr'=>array(
                                                                'class'=>'form-control'
                                                        
                                                            ),
                                                    
                ))
                ->add('description', TextType::class, array(
                                                    'label'=>'Descripción',
                                                    'attr'=>array(
                                                                'class'=>'form-control'
                                                            )
                ))
                ->add('cost', NumberType::class, array(
                                                    'label'=>'Costo',
                                                    'attr'=>array(
                                                                'class'=>'form-control'
                                                            )
                                                
                ))
                ->add('cookingtime', NumberType::class, array(
                                                    'label'=>'Tiempo de cocción',
                                                    'attr'=>array(
                                                                'class'=>'form-control'
                                                            )
                                                
                ))
                ->add('submit', SubmitType::class, array(
                                                    'label'=>'Guardar',
                                                    'attr'=>array(
                                                                'class'=>'btn btn-primary'
                                                            )
                                                
                ))
                ;
        
        
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $menus = $event->getData();
            $form = $event->getForm();
            $form
                ->add('password', ChoiceType::class, array(
                        'label' => false,
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Contraseña'
                        ),
                        'choices' => $menus
                ));
            
        });
    }
}

