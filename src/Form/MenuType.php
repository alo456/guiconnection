<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', TextType::class, array(
                    'label' => 'Nombre',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre de la categoria'
                    )
                ))
                ->add('description', TextType::class, array(
                    'label' => 'Descripcion',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Description'
                    )
                ))
                ->add('menu', ChoiceType::class, array(
                    'label' => 'Categoria Padre',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'choices' => $options['data'],
                    'choice_label'=>function($key){
                        return $key?$key->name:'';
                    },
                    'choice_value' => function($key){
                        return $key?$key->id:'';                        
                    },
                    'placeholder' => 'Seleciona una categoria'
                ))
                ->add('background', FileType::class, array(
                    'label' => 'Imagen de Fondo',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'accept' => '.png'
                    )
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'Guardar',
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
        ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            if (isset($data['id'])) {
                $form
                        ->add('cancel', ResetType::class, array(
                            'label' => 'Cancelar',
                            'attr' => array(
                                'class' => 'btn btn-secondary',
                                'data-dismiss' => 'modal'
                            )
                ));
            }
        });
    }

}

