<?php
namespace App\Controller;

use App\Form\ScheduleType;
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
                $token = $responseAPI->headers['Set-Cookie'];
                $cookie = Cookie::fromString($token);
                $cookie = $cookie->getValue();
                setcookie("TOKEN", $cookie);
                $data = array();
                $cafeteria = $this->cookieCafeterias($request->cookies->get('CAFETERIAS'),$cookie); 
                if(!$cafeteria){    
                    $message .= "Error de autenticación";
                }else{
                        $body = Body::form(array(
                            'action' => 'getCafeterias'                        
                        ));
                        $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin',$headers,$body);

                         $body  = $responseAPI->body;
                         
                        if($body->status =='OK'){
//                            var_dump($body);
//                         die;
                            $cafeteria = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                            return $this->redirectToRoute('dashboard', array(
                                                                'cafeteria'=> $cafeteria[0]
                        ));

                        }  
                    }

            }else{
                    $message.=$body->payload;
                }
        }
        return $response;
//        
    }
    
    public function dashboard(Request $request, $cafeteria){
        $name='';
        $message='';
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $availableCafeterias = $request->cookies->get('CAFETERIAS');
        $availableCafeterias = json_decode($availableCafeterias);
        
        $cookie = $request->cookies->get('TOKEN');
        $body  = $this->APICall([],"getAdministratorName",$cookie);
        
        if($body->status =='OK'){
            $name = $body->payload;
        }
        else{
            $message.=$body->message;
        }
        
        return $this->render('dashboard.html.twig', array(
            
                                            'cafeteria'=>$cafeteria_name,
                                            'name' => $name
                                                
                ));
    }
    
    public function personal(Request $request){
        $data = array();
        $form = $this->createFormBuilder($data)
        ->add('firstname', TextType::class, array ('label' => 'Nombre',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Nombre'
                                                                ),
                                                                'required' => false
            ))
       ->add('lastname', TextType::class, array ('label' => 'Apellido',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Apellido'
                                                                ),
                                                                'required' => false
            ))        
        ->add('email', TextType::class, array ('label' => 'Email',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Email'
                                                                ),
                                                                'required' => false
            ))
          ->add('password', PasswordType::class, array('label' => 'Contraseña',
                                                                'attr' => array(
                                                                    'class' =>'form-control'
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
         $formPassword = $this->createFormBuilder($data)
        ->add('oldpassword', PasswordType::class, array ('label' => 'Contraseña Actual',
                                                                'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Contraseña Actual'
                                                                )
            ))
       ->add('newpassword', RepeatedType::class, array (
                                                                'type' => PasswordType::class,
                                                                'first_options'  => array('label' => 'Nueva Contraseña',  'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Nueva Contraseña'
                                                                )),
                                                                'second_options' => array('label' => 'Confirmación de Contraseña', 'attr' => array(
                                                                    'class' =>'form-control',
                                                                    'placeholder' => 'Confirmación de Contraseña'
                                                                ))        
            ))        
        ->add('submit', SubmitType::class, array('label' => 'Guardar',
                                                                'attr' => array(
                                                                    'class' =>'btn btn-primary px-4'
                                                                )
                ))
        ->getForm();
         $formPassword->handleRequest($request);
     
    if ($formPassword->isSubmitted() && $form->isValid()) {
        $data = $formPassword->getData();
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
         
         
         $form = $this->get('form.factory');
         $formSchedule= $form->createNamedBuilder("Schedule", ScheduleType::class,[])->getForm();
          
         $formSchedule->handleRequest($request);
         
         if ($formSchedule->isSubmitted() && $formSchedule->isValid()) {
            $dataSchedule = $formSchedule->getData();
            $result = array();
            foreach($dataSchedule as $day => $hour){
                $result['schedule'][$day] = array(
                  'fromHour' => $hour['fromHour']->format('H:i'),
                  'toHour' => $hour['toHour']->format('H:i')
                );
            }
            $result['cafeteria']=$cafeteria_name;
            $cookie = $request->cookies->get('TOKEN');
            $bodySchedule = $this->APICall($result, "updateSchedule", $cookie);
        if(isset($bodySchedule->status) && $bodySchedule->status == 'OK'){
            echo("Cambios realizados");
            
        }else{
              if(isset($dataSchedule['fromHour'])) echo("Credenciales Inválidas");
        }
    }
        
         
         
    
    $response = $this->render('Administrator/settings.html.twig', array(
            //'last_username' => $lastUsername,
            'form' => $formPassword->createView(),
            'configuration' => $configuration->createView(),
            'schedule' => $formSchedule->createView(),
            'cafeteria' => $cafeteria_name
            ));
    
        return $response;
    }
    
    public function activity(Request $request, $cafeteria){
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $cookie = $request->cookies->get('TOKEN');
        $data['cafeteria'] = $cafeteria_name;
        $activity = "";
        $body  = $this->APICall($data, "getLastTransactions", $cookie);
        if($body->status == 'OK'){
            $activity = $body->payload;
        }
       

        return $this->render('Administrator/activity.html.twig', array(
                                                    'cafeteria' => $cafeteria_name,
                                                    'activity' =>$activity                                               
                                                            ));
    }
    
    
    public function balance(Request $request){
        return $this->render('Administrator/balance.html.twig', array(
                                                        'cafeteria' => 'Cuckoo',
        ));
    }
    
    public function account(Request $request){
        return $this->render('Administrator/account.html.twig');
    }
}
