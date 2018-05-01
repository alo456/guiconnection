<?php
namespace App\Controller;

use App\Form\ProductType;
use Doctrine\Common\Collections\ArrayCollection;
use App\Helper\ConnectionController as Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Unirest\Request as RequestAPI;
use Unirest\Request\Body;
class MenuController extends Controller
{
    public function category(Request $request,$cafeteria) {
        $message = "";
        $menus = [];
        //Get menus from cafeteria
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $data = array();
        $headers = array('Accept' => 'application/json');
        $data['cafeteria'] = $cafeteria_name;
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data,'getMenus',$cookie);
        var_dump($body);
        die;
        
        $data['action'] = 'getMenus';
        $body = Body::form($data);
        RequestAPI::cookie("TOKEN=" . $cookie);
        $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin', $headers, $body);
        $body = $responseAPI->body;
        if ($body->status == 'OK') {
            $menus  =  is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message = $body->message;
        }
        
        //------------------
        
        //Form Builder 
        $form = $this->createFormBuilder($data)
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
                    'required'=>false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'choices' => $menus,
                    'placeholder' => 'Seleciona una categoria'
                    
                ))
                ->add('background', FileType::class, array(
                    'label' => 'Imagen de Fondo',
                    'required'=>false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('submit', SubmitType::class, array('label' => 'Guardar',
                    'attr' => array(
                        'class' => 'btn btn-primary px-4'
                    )
                ))
                ->getForm();
        //------------------
        
        //Form Request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $headers = array('Accept' => 'application/json');
            $data['action'] = 'createMenu';
            $data['cafeteria'] = $cafeteria_name;
            $body = Body::form($data);
            RequestAPI::cookie("TOKEN=" . $cookie);
//            var_dump($body);
//            die;
            $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin', $headers, $body);
            $body = $responseAPI->body;
            if ($body->status == 'OK') {
                $message = "Menu creado";
            } else {
                $message = $body->message;
            }
        }
        //-----------------
        $response = $this->render('Menu/category.html.twig',array(
                                                            'cafeteria'=>$cafeteria_name,
                                                            'form'=>$form->createView(),
                                                            'message' =>$message
                                                            ));
        return $response;
    }
    
    public function ingredient(Request $request, $cafeteria){
        $message = "";
        $menus = [];
        $ingredients = [];
        //Get menus from cafeteria
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        return $this->render('Menu/ingredient.html.twig' , array(
                                                'cafeteria' => $cafeteria_name,
                                                'message' => $message
        ));
    }

    public function product(Request $request, $cafeteria){
        $message = "";
        $menus = [];
        $ingredients = [];
        //Get menus from cafeteria
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $data = array();
        $headers = array('Accept' => 'application/json');
        $data['cafeteria'] = $cafeteria_name;
        $data['action'] = 'getMenus';
        $body = Body::form($data);
        $cookie = $request->cookies->get('TOKEN');
        RequestAPI::cookie("TOKEN=" . $cookie);
        $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin', $headers, $body);
        $body = $responseAPI->body;
        if ($body->status == 'OK') {
            $menus  =  is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message .= $body->message;
            
        }
        //------------------
        
        
        //Get ingredients from cafeteria
        $data = array();
        $headers = array('Accept' => 'application/json');
        $data['cafeteria'] = $cafeteria_name;
        $data['action'] = 'getIngredients';
        $body = Body::form($data);
        $cookie = $request->cookies->get('TOKEN');
        RequestAPI::cookie("TOKEN=" . $cookie);
        $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin', $headers, $body);
        $body = $responseAPI->body;
        if ($body->status == 'OK') {
            $ingredients  =  is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message .= $body->message;
            
        }
        //------------------
        
        //FORM JQUERY 
      
        
        $data = ['values' => ['a', 'b', 'c']];

       $arr = new ArrayCollection();
       $arr->add('a');
       $arr->add('b');
       $arr->add('c');
        
       $data = array();
/*
        $formj = $this
           ->createFormBuilder($data)
           ->add('values', CollectionType::class, [
               'entry_type'    => IngredientType::class,
               'entry_options' => [
                   'label' => 'Value',
                   'attr'=>[
                       'class' => 'withPlugin',
                   ],
               ],
               'label'        => 'Add, move, remove values and press Submit.',
               'allow_add'    => true,
               'allow_delete' => true,
               'prototype'    => true,
               'required'     => false,
               'attr'         => [
                   'class' => 'table discount-collection',
               ],
           ])
           ->add('submit', SubmitType::class)
           ->getForm()
        ;
        */
       $data = array();
       
       $form = $this->get('form.factory');
       $formj = $form->createNamedBuilder("Producto", ProductType::class,$data)->getForm();
       

        //----------------------
        //Form Builder 
        $form = $this->createFormBuilder($data)
                ->add('name', TextType::class, array(
                    'label' => 'Nombre',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre del producto'
                    )
                ))
                ->add('description', TextType::class, array(
                    'label' => 'Description',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Descripcion'
                    )
                ))
                ->add('menu', ChoiceType::class, array(
                    'label' => 'Categoria Padre',
                    'required'=>false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'choices' => $menus,
                    'placeholder' => 'Seleciona una categoria'
                    
                ))
                ->add('ingredients', ChoiceType::class, array(
                    'label' => 'Ingredientes de tu producto',
                    'attr' => array(
                        'class' => 'form-control select2-multiple', 
                        'id'=>'select2-2'
                   ),
                    'multiple'=>true,
                    'choices' => $ingredients,
                ))
                ->add('extras', ChoiceType::class, array(
                    'label' => 'Extras de tu producto',
                    'attr' => array(
                        'class' => 'form-control select2-multiple', 
                        'id'=>'select2-2'
                   ),
                    'multiple'=>true,
                    'choices' => $ingredients,
                ))
                ->add('photo', FileType::class, array(
                    'label' => 'Foto',
                    'required'=>false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('submit', SubmitType::class, array('label' => 'Guardar',
                    'attr' => array(
                        'class' => 'btn btn-primary px-4'
                    )
                ))
                ->getForm();
        //------------------
        
        //Form Request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $headers = array('Accept' => 'application/json');
            $data['action'] = 'createItem';
            $data['cafeteria'] = $cafeteria_name;
            var_dump($data);
            $body = Body::form($data);
            RequestAPI::cookie("TOKEN=" . $cookie);
            var_dump($body);
            die;
            $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin', $headers, $body);
            $body = $responseAPI->body;
            if ($body->status == 'OK') {
                $message = "Menu creado";
            } else {
                $message = $body->message;
            }
        }
        //-----------------
         $data = ['values' => ['a', 'b', 'c']];
        $response = $this->render('Menu/product.html.twig',array(
                                                            'cafeteria'=>$cafeteria_name,
                                                            'form'=>$form->createView(),
                                                            'message' =>$message,
                                                            'formj' => $formj->createView(),
                                                            'data' => $data
                                                            ));
        return $response;
    }
}

