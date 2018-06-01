<?php

namespace App\Form;

use App\Form\DayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ScheduleType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options)
        {

            $builder ->add('monday', DayType::class, array ('label' => false,
                                                                    'attr' => array(
                                                                        'class' =>'form-control',
                                                                        'required'=>true
                                                                    )
                                                                    
                            ))
                           ->add('tuesday', DayType::class, array ('label' => false,
                                                                    'attr' => array(
                                                                        'class' =>'form-control'
                                                                    )
                            ))
                            ->add('wednesday', DayType::class, array ('label' => false,
                                                                    'attr' => array(
                                                                        'class' =>'form-control'
                                                                    )
                            ))
                            ->add('thursday', DayType::class, array ('label' => false,
                                                                    'attr' => array(
                                                                        'class' =>'form-control'
                                                                    )
                            ))
                            ->add('friday', DayType::class, array ('label' => false,
                                                                    'attr' => array(
                                                                        'class' =>'form-control'
                                                                    )
                            ))
                            ->add('saturday', DayType::class, array ('label' => false,
                                                                    'attr' => array(
                                                                        'class' =>'form-control'
                                                                    )
                            ))
                            ->add('sunday', DayType::class, array ('label' => false,
                                                                    'attr' => array(
                                                                        'class' =>'form-control'
                                                                    )
                            ))
                            ->add('submit', SubmitType::class, array ('label' => 'Guardar',
                                                                    'attr' => array(
                                                                        'class' =>'btn btn-outline-primary col-auto'
                                                                    )
                            ))
                    ;     
        }
       
}

