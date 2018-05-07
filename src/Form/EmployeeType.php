<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('cancel', ResetType::class, array(
                    'label' => 'Cancelar',
                    'attr' => array (
                        'class' => 'btn btn-secondary',
                        'data-dismiss' => 'modal'
                    )
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'Añadir',
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                ));
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $employee = $event->getData();
            $form = $event->getForm();
            if (!isset($employee['employee'])) {
                $form
                    ->add('firstname', TextType::class, array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre'
                        )
                    ))
                    ->add('lastname', TextType::class, array(
                        'label' => false,
                        'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Apellidos'
                        )
                    ))
                    ->add('username', TextType::class, array(
                        'label' => false,
                        'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre de Usuario'
                    )
                ))
                    ->add('password', PasswordType::class, array(
                        'label' => false,
                        'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Contraseña'
                    )
                    ));
            } else {
                $form
                    ->add('firstname', TextType::class, array(
                        'label' => false,
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Nombre'
                        ),
                        'required' => false
                    ))
                    ->add('lastname', TextType::class, array(
                        'label' => false,
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Apellidos'
                        ),
                        'required' => false
                    ))
                    ->add('username', TextType::class, array(
                        'label' => false,
                        'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre de Usuario'
                        ),
                        'disabled' => true,
                        'required' => false
                    ))
                    ->add('password', PasswordType::class, array(
                        'label' => false,
                        'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Contraseña'
                        ),
                        'required' => false
                    ));
            }
        });
        
    }
}

