<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductBriefType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('product', ChoiceType::class, array(
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
    }

}
