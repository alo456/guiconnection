<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class IngredientType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('ingredient', ChoiceType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'select2 form-control ',
                        'style' => 'width: 100%',
                        'form' => 'itemform'
                    ),
                    'required' => true
                ))
                ->add('alias', TextType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Alias',
                        'form' => 'itemform'
                    )
                ))
                ->add('quantity', IntegerType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Cantidad',
                        'form' => 'itemform'
                    ),
                    'required' => true
                ))
                ->add('unit', TextType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Unidad',
                        'form' => 'itemform',
                        'readonly' => true
                    )
        ));
    }

}
