<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

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
                                                    'label'=>false,
                                                    'attr'=>array(
                                                                'class'=>'form-control'
                                                            )
                                                
                ))
                ->add('cost', NumberType::class, array(
                                                    'label'=>false,
                                                    'attr'=>array(
                                                                'class'=>'form-control'
                                                            )
                                                
                ))
                
                ;
    }
}

