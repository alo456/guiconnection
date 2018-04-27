<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Unirest\Request as RequestAPI;
use Unirest\Request\Body;
class AdminController extends Controller
{
    public function login(Request $request){
        $data = array();
         $form = $this->createFormBuilder($data)
        ->add('username', TextType::class, array ('label' => false,
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Nombre de usuario'
                                                                )
            ))
        ->add('password', PasswordType::class, array ('label' => false,
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Contraseña'
                                                                )
            ))
        ->add('submit', SubmitType::class, array('label' => 'Iniciar Sesión',
                                                                'attr' => array(
                                                                    'class' =>'btn btn-primary px-4'
                                                                )
                ))
        ->getForm();
     
    $form->handleRequest($request);
    $response = $this->render("login.html.twig", array(
            //'last_username' => $lastUsername,
            'form' => $form->createView()
            ));
    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $headers = array('Accept' => 'application/json');
        $body = Body::form($data);
     
        $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin/login',$headers,$body);
        $body  = $responseAPI->body;
        if($body->status == 'OK'){ 
            $token = $responseAPI->headers['Set-Cookie'];
            $cookie = Cookie::fromString($token);
            //RequestAPI::cookie($cookie);
            $response->headers->setCookie($cookie);
            $response->send();
            //var_dump($cookie);
            return $this->redirectToRoute('dashboard', array(
                                                'TOKEN' => $cookie
            )); 
        }else{
            echo($body->message);
        }
    }

    if($request->cookies->has('TOKEN')){
            var_dump($request->cookies->get('TOKEN'));
            //return null;
           return $this->redirectToRoute('dashboard');
    }
        return $response;
//        
    }
    public function dashboard(Request $request){
//        var_dump($request->cookies->get('TOKEN'));
//         $cookie = Cookie::fromString($request->cookies->get('TOKEN'));
//         $response = $this->render("dashboard.html.twig");
//         $response->headers->setCookie($cookie);
         return $this->render('dashboard.html.twig');
    }
    
    public function personal(Request $request){
        $data = array();
         $form = $this->createFormBuilder($data)
        ->add('clientName', TextType::class, array ('label' => 'Nombre',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Nombre'
                                                                )
            ))
       ->add('clientSecondName', TextType::class, array ('label' => 'Apellido',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Apellido'
                                                                )
            ))        
        ->add('clientEmail', TextType::class, array ('label' => 'Email',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Email'
                                                                )
            ))
        ->add('submit', SubmitType::class, array('label' => 'Guardar',
                                                                'attr' => array(
                                                                    'class' =>'btn btn-primary px-4'
                                                                )
                ))
        ->getForm();
         
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $headers = array('Accept' => 'application/json');
        $body = Body::form($data);
     
        $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin/login',$headers,$body);
        $body  = $responseAPI->body;
        if($body->status == 'OK'){ 
            $token = $responseAPI->headers['Set-Cookie'];
            $cookie = Cookie::fromString($token);
            //RequestAPI::cookie($cookie);
            $response->headers->setCookie($cookie);
            $response->send();
            //var_dump($cookie);
            return $this->redirectToRoute('dashboard', array(
                                                'TOKEN' => $cookie
            )); 
        }else{
            echo($body->message);
        }
    }
    
    $personal = array();
    
    $personalData = $this->createFormBuilder($personal)
        ->add('cellphone', TextType::class, array ('label' => 'Celular',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Celular'
                                                                )
            ))
       ->add('birthday', DateType::class, array ('label' => 'Fecha de Nacimiento',
                                                                'attr' => array(
                                                                    'class' =>'form-control'
                                                                )
            ))        
        ->add('address', TextType::class, array ('label' => 'Calle y número',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Calle #'
                                                                )
            ))
            
        ->add('colony', TextType::class, array ('label' => 'Colonia y municipio',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Colonia '
                                                                )
            ))
        ->add('state', TextType::class, array ('label' => 'Estado',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Estado'
                                                                )
            ))
        ->add('submit', SubmitType::class, array('label' => 'Guardar',
                                                                'attr' => array(
                                                                    'class' =>'btn btn-primary px-4'
                                                                )
                ))
        ->getForm();
     
    $personalData->handleRequest($request);
    
    
    
    
    $response = $this->render('Administrator/personal.html.twig', array(
            'form' => $form->createView(),
            'personalData' => $personalData->createView()
            ));
    
        return $response;
    }
    
    public function settings(Request $request){
        $data = array();
         $form = $this->createFormBuilder($data)
        ->add('oldpassword', PasswordType::class, array ('label' => 'Contraseña Actual',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Password'
                                                                )
            ))
       ->add('newpassword', RepeatedType::class, array (
                                                                'type' => PasswordType::class,
                                                                'first_options'  => array('label' => 'New Password',  'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'New Password'
                                                                )),
                                                                'second_options' => array('label' => 'Repeat Password', 'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => ' Confirm Password'
                                                                ))        
            ))        
        ->add('submit', SubmitType::class, array('label' => 'Guardar',
                                                                'attr' => array(
                                                                    'class' =>'btn btn-primary px-4'
                                                                )
                ))
        ->getForm();
         $form->handleRequest($request);
     
    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $headers = array(
                                'Accept' => 'application/json'
            );
        $data['action'] = 'changePassword';
        $body = Body::form($data);
        requestAPI::cookie("TOKEN=" . $request->cookies->get('TOKEN'));
        $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin',$headers,$body);
        $body  = $responseAPI->body;
//        var_dump($body);
//        var_dump($body->status);
        if(isset($body->status) && $body->status == 'OK'){
//            $token = $responseAPI->headers['Set-Cookie'];
//            $cookie = Cookie::fromString($token);
//            //RequestAPI::cookie($cookie);
//            $response->headers->setCookie($cookie);
//            $response->send();
            
        }else{
            echo("Credenciales Inválidas");
        }
    }
        
    
    $cafeteria = array();
         $configuration = $this->createFormBuilder($cafeteria)
        ->add('fromCafe', TextType::class, array ('label' => 'Copiar configuración de:',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Cafeteria'
                                                                )
            ))
       ->add('toCafe', TextType::class, array ('label' => 'a:',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Cafeteria'
                                                                    )
            ))        
        ->add('submit', SubmitType::class, array('label' => 'Copiar configuración',
                                                                'attr' => array(
                                                                    'class' =>'btn btn-outline-primary col-auto'
                                                                )
                ))
                 
        ->getForm();
         $configuration->handleRequest($request);
    
    $response = $this->render('Administrator/settings.html.twig', array(
            //'last_username' => $lastUsername,
            'form' => $form->createView(),
            'configuration' => $configuration->createView()
            ));
    
        return $response;
    }
    
    public function activity(Request $request){
        return $this->render('Administrator/activity.html.twig');
    }
    
    public function balance(Request $request){
        return $this->render('Administrator/balance.html.twig');
    }
    
    public function account(Request $request){
        return $this->render('Administrator/account.html.twig');
    }
}
