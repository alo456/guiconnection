<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

class TimeIntervalType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('start', TimeType::class, array('label' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Inicio'
                ))
                ->add('end', TimeType::class, array('label' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Fin'
                ));
    }

}

