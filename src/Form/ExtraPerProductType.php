<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class IngredientPerProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ingredient', ChoiceType::class , array(
                                                    'label'=>false,
                                                    'attr'=>array(
                                                                'class'=>'form-control',
                                                                'placeholder'=>'Ingrediente'
                                                        
                                                            ),
                                                    'choices' => $options
                                                ))
                ->add('quantity', NumberType::class, array(
                                                    'label'=>false,
                                                    'attr'=>array(
                                                                'class'=>'form-control',
                                                                'placeholder'=>'Cantidad'
                                                        
                                                            )
                                                ))
                ->add('unit', TextType::class, array(
                                                    'label'=>false,
                                                    'attr'=>array(
                                                                'class'=>'form-control',
                                                                'placeholder'=>'Unidad'
                                                        
                                                            )
                                                ));
    }
}
