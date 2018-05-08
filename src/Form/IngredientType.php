<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ingredient', ChoiceType::class , array(
                                                    'label'=>false,
                                                    'attr'=>array(
                                                                'class'=>'form-control'
                                                        
                                                            ),
                                                    'choices' => $options['data'],
                                                    'choice_label' => function ($value, $key, $index) {
                                                                    return ucwords($value->name);
                                                    },
                                                    'choice_value' => function ($value) {
                                                                    return $value ? $value->id : "";
                                                    },
                                                    'placeholder'=>'Selecciona un Ingrediente'
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