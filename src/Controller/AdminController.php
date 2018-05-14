<?php
namespace App\Controller;

use App\Helper\ConnectionController as Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Unirest\Request as RequestAPI;
use Unirest\Request\Body;
class AdminController extends Controller
{
    public function login(Request $request){
        $message = "";
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
            $cafeteria_name = "";
            $token = $responseAPI->headers['Set-Cookie'];
            $cookie = Cookie::fromString($token);
            $cookie = $cookie->getValue();
            setcookie("TOKEN", $cookie);
            $data = array();
            $body = $this->APICall($data, 'getCafeterias', $cookie);
            if ($body->status == 'OK') {
                $cafeterias = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                $cafeteria_name = strtolower(str_replace(" ", "_", $cafeterias[0]));
            } else {
                $message .= $body->message;
            }
            return $this->redirectToRoute('dashboard', array(
                                                'cafeteria'=> $cafeteria_name
            )); 
        }else{
            $message .= $body->message;
        }
    }
        return $response;
//        
    }
    
    public function dashboard(Request $request, $cafeteria){
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
//        var_dump($request->cookies->get('TOKEN'));
//         $cookie = Cookie::fromString($request->cookies->get('TOKEN'));
//         $response = $this->render("dashboard.html.twig");
//         $response->headers->setCookie($cookie);
        return $this->render('dashboard.html.twig', array('cafeteria'=>$cafeteria_name));
    }
    
    public function personal(Request $request){
        $data = array();
        $form = $this->createFormBuilder($data)
        ->add('firstname', TextType::class, array ('label' => 'Nombre',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Nombre'
                                                                )
            ))
       ->add('lastname', TextType::class, array ('label' => 'Apellido',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Apellido'
                                                                )
            ))        
        ->add('email', TextType::class, array ('label' => 'Email',
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
        $cookie = $request->cookies->get('TOKEN');
        $body  = $this->APICall($data,"updateInformation",$cookie);
       
        if(isset($body->status) && $body->status == 'OK'){
            echo("Cambios realizados");
        }else{
             var_dump($data);
        var_dump($body);
        die;
            echo($body->message);
        }
    }
    
    $response = $this->render('Administrator/personal.html.twig', array(
            'form' => $form->createView(),
            'cafeteria'=> 'cuckoo'
            ));
    
        return $response;
    }
    
    public function settings(Request $request, $cafeteria){
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        
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
        $cookie = $request->cookies->get('TOKEN');
        $body  = $this->APICall($data, "changePassword", $cookie);
        if(isset($body->status) && $body->status == 'OK'){
            echo("Cambios realizados");
            
        }else{
            if (isset($data['oldpassword'])) echo("Credenciales Inválidas");
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
         
         
         
          $scheduleData = array();
         $times = $this->createFormBuilder($scheduleData)
        ->add('fromHour', TimeType::class, array ('label' => 'Abre a las:',
                                                                'attr' => array(
                                                                    'class' =>'form-control'
                                                                )
            ))
       ->add('toHour', TimeType::class, array ('label' => 'Cierra a las:',
                                                                'attr' => array(
                                                                    'class' =>'form-control'
                                                                    )
            ))        
        ->add('submitSchedule', SubmitType::class, array('label' => 'Guardar',
                                                                'attr' => array(
                                                                    'class' =>'btn btn-outline-primary col-auto'
                                                                )
                ))
                 
        ->getForm();
         $times->handleRequest($request);
         
         if ($times->isSubmitted() && $times->isValid()) {
        $dataSchedule = $times->getData();
        if(isset($dataSchedule['fromHour'])){
            $dataSchedule['cafeteria'] = $cafeteria_name;
            $dataSchedule['fromHour'] = $dataSchedule['fromHour']->format('H:i');
            $dataSchedule['toHour'] = $dataSchedule['toHour']->format('H:i');
            $cookie = $request->cookies->get('TOKEN');
            $bodySchedule = $this->APICall($dataSchedule, "updateSchedule", $cookie);
        }
       
        if(isset($bodySchedule->status) && $bodySchedule->status == 'OK'){
            echo("Cambios realizados");
            
        }else{
              if(isset($dataSchedule['fromHour'])) echo("Credenciales Inválidas");
        }
    }
        
         
         
    
    $response = $this->render('Administrator/settings.html.twig', array(
            //'last_username' => $lastUsername,
            'form' => $form->createView(),
            'configuration' => $configuration->createView(),
            'schedule' => $times->createView(),
            'cafeteria' => $cafeteria_name
            ));
    
        return $response;
    }
    
    public function activity(Request $request){
        return $this->render('Administrator/activity.html.twig', array(
                                                    'cafeteria' => 'cuckoo'
                    
                                                            ));
    }
    
    public function balance(Request $request){
        return $this->render('Administrator/balance.html.twig');
    }
    
    public function account(Request $request){
        return $this->render('Administrator/account.html.twig');
    }
}
