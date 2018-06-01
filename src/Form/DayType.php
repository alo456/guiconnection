<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

class DayType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options)
        {

            $builder ->add('fromHour', TimeType::class, array ('label' => false,
                                                                    'attr' => array(
                                                                        'class' =>'form-control'
                                                                    )
                            ))
                            ->add('toHour', TimeType::class, array ('label' => false,
                                                                    'attr' => array(
                                                                        'class' =>'form-control'
                                                                        )
                            ))  ;     
        }
       
}

