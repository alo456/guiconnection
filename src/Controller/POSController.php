<?php
namespace App\Controller;

use App\Helper\ConnectionController as Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EmployeeType;
class POSController extends Controller
{
    public function access(Request $request,$cafeteria){
        $form = $this->get('form.factory');
        $message = "";
        $employees = [];
        //Get menus from cafeteria
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $data = array();
        $data['cafeteria'] = $cafeteria_name;
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data, 'getEmployees', $cookie);
        if ($body->status == 'OK') {
            $employees  =  is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message = $body->message;
        }
        
        //------------------
        
        //Form Builder  ADD EMPLOYEE
        $formCreateEmp = $form->createNamedBuilder("CreateEmployee", EmployeeType::class,$data)->getForm();
        //------------------
        
        //Form Request
        $formCreateEmp ->handleRequest($request);
        if ($formCreateEmp ->isSubmitted() && $formCreateEmp ->isValid()) {
            $data = $formCreateEmp ->getData();
            $data['cafeteria'] = $cafeteria_name;
             $cookie = $request->cookies->get('TOKEN');
            $body = $this->APICall($data, 'createEmployee', $cookie);
            if ($body->status == 'OK') {
                $message = "Empleado creado";
            } else {
                $message = $body->message;
            }
        }
        //-----------------
        $response = $this->render('pos/access.html.twig',array(
                                                            'cafeteria'=>$cafeteria_name,
                                                            'formCreateEmp'=>$formCreateEmp ->createView(),
                                                            'message' =>$message,
                                                            'employees' => $employees
                                                            ));
        return $response;
    }
    public function helper(){
        
    }
}
