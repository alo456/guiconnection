<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExtraType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('extra', ChoiceType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'select2 form-control',
                        'style' => 'width: 100%',
                        'form' => 'ingredientsform'
                    )
                ))
                ->add('alias', TextType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Alias',
                        'form' => 'ingredientsform'
                    )
                ))
                ->add('quantity', NumberType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Cantidad',
                        'form' => 'ingredientsform'
                    )
                ))
                ->add('unit', TextType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Unidad',
                        'form' => 'ingredientsform'
                    )
                ))
                ->add('cost', NumberType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Costo',
                        'form' => 'ingredientsform'
                    )
                ));
    }

}
