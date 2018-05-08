<?php
namespace App\Controller;

use App\Form\IngredientPerProductType;
use App\Form\ProductInformationType;
use App\Helper\ConnectionController as Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
class MenuController extends Controller
{
    public function category(Request $request,$cafeteria) {
        $message = "";
        $menus = [];
        //Get menus from cafeteria
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $data = array();
        $data['cafeteria'] = $cafeteria_name;
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data,'getMenus',$cookie);
        if ($body->status == 'OK') {
            $menus  =  is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message = $body->message;
        }
        
        //------------------
        
        //Form Builder 
        $formCreateMenu = $this->createFormBuilder($data)
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
        $formCreateMenu->handleRequest($request);
        if ($formCreateMenu->isSubmitted() && $formCreateMenu->isValid()) {
            $data = $formCreateMenu->getData();
            $body = $this->APICall($data, 'createMenu', $cookie);
            if ($body->status == 'OK') {
                $message = "Menu creado";
            } else {
                $message = $body->message;
            }
        }
        //-----------------
        $response = $this->render('Menu/category.html.twig',array(
                                                            'cafeteria'=>$cafeteria_name,
                                                            'formCreateMenu'=>$formCreateMenu->createView(),
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
        $items = [];
        
        //Get menus from cafeteria
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $data = array();
        $data['cafeteria'] = $cafeteria_name;
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data, 'getMenus', $cookie);
        if ($body->status == 'OK') {
            $menus  =  is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message .= $body->message;
            
        }
        //------------------
        
        
        //Get ingredients from cafeteria
        $data = array();
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data, 'getIngredients', $cookie);
        
        if ($body->status == 'OK') {
            $ingredients  =  is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message .= $body->message;
            
        }
        //------------------
        //Get items from cafeteria
        $data = array();
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data, 'getItems', $cookie);
        
        if ($body->status == 'OK') {
            $items  =  is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message .= $body->message;
            
        }
        //------------------
        
        
        
        //FORM JQUERY 
       $data = array();
       $form = $this->get('form.factory');
       $formIngredients = $form->createNamedBuilder("Ingredientes", IngredientPerProductType::class,$data)->getForm();
       $formProductInformation = $form->createNamedBuilder("Producto", ProductInformationType::class,$menus)->getForm();
       
        //----------------------
        
       
        //Form Ingredients
        $formIngredients->handleRequest($request);
        if ($formIngredients->isSubmitted() && $formIngredients->isValid()) {
            $data = $formIngredients->getData();
            $data['cafeteria'] = $cafeteria_name;
            $body = $this->APICall($data, 'createItem', $cookie);
            if ($body->status == 'OK') {
                $message = "Menu creado";
            } else {
                $message = $body->message;
            }
        }
        //-----------------
         //Form Info
        $formProductInformation->handleRequest($request);
        if ($formProductInformation->isSubmitted() && $formProductInformation->isValid()) {
            $data = $formProductInformation->getData();
            $data['cafeteria'] = $cafeteria_name;
            $body = $this->APICall($data, 'createItem', $cookie);
            if ($body->status == 'OK') {
                $message = "Menu creado";
            } else {
                $message = $body->message;
            }
        }
        //-----------------
        
        
        
        
        $response = $this->render('Menu/product.html.twig',array(
                                                            'cafeteria'=>$cafeteria_name,
                                                            'formIngredients'=>$formIngredients->createView(),
                                                            'formProduct' => $formProductInformation->createView(),
                                                            'items' => $items,
                                                            'message' =>$message
                                                            ));
        return $response;
    }
}

