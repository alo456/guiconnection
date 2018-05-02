<?php
namespace App\Controller;

use Helper\ConnectionController as Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Unirest\Request as RequestAPI;
use Unirest\Request\Body;
class POSController extends Controller
{
    public function access(Request $request,$cafeteria){
        $message = "";
        $employees = [];
        //Get menus from cafeteria
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $data = array();
        $data['cafeteria'] = $cafeteria_name;
        $action = 'getEmployees';
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data, $action, $cookie);
 
        if ($body->status == 'OK') {
            $employees  = get_object_vars($body->payload);
        } else {
            $message = $body->message;
        }
        
        //------------------
        
        //Form Builder  ADD EMPLOYEE
        $formAddEmp = $this->createFormBuilder($data)
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
                        'placeholder' => 'password'
                    )
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'Añadir',
                    'attr' => array(
                        'class' => 'btn btn-outline-primary'
                    )
                ))
                ->getForm();
        //------------------
        
        //Form Request
        $formAddEmp->handleRequest($request);
        if ($formAddEmp->isSubmitted() && $formAddEmp->isValid()) {
            $data = $formAddEmp->getData();
            $data['cafeteria'] = $cafeteria_name;
            $action= 'createEmployee'; 
            $cookie = $request->cookies->get('TOKEN');
            $body = $this->APICall($data, $action, $cookie);
            if ($body->status == 'OK') {
                $message = "Empleado creado";
            } else {
                $message = $body->message;
            }
        }
        //-----------------
        $response = $this->render('POS/access.html.twig',array(
                                                            'cafeteria'=>$cafeteria_name,
                                                            'formAddEmp'=>$formAddEmp->createView(),
                                                            'message' =>$message,
                                                            'employees' => $employees
                                                            ));
        return $response;
    }
}
