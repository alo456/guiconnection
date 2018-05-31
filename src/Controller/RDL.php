<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Form\ItemType;
use App\Helper\ConnectionController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RDL extends Controller{
    
    public function update(Request $request,$context, $id){
        $form = $this->get('form.factory');
        $data = array();
        $cookie = $request->cookies->get('TOKEN');
        switch($context){
            case 'employee':
                $data['employee']=$id;
                $body = $this->APICall($data, 'getEmployee', $cookie);
                if ($body->status == 'OK') {
                    $employee = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                    $cafeteria_name = strtolower(str_replace("_", " ", $employee['cafeteria']));
                    $formUpdateEmp = $form->createNamedBuilder("UpdateEmployee", EmployeeType::class,$data)->getForm();
                    $formUpdateEmp ->handleRequest($request);
                    if ($formUpdateEmp->isSubmitted()) {
                        $data = $formUpdateEmp->getData();
                        $data['employee']=$id;
                        $cookie = $request->cookies->get('TOKEN');
                        $body = $this->APICall($data, 'updateEmployee', $cookie);
                        if ($body->status == 'OK') {
                            $message = "Empleado creado";
                             return $this->redirectToRoute('POSAccess', array(
                                                            'cafeteria'=>$cafeteria_name
                             ));
                        } else {
                            $message = $body->message;
                        }
                    }
                
                    return $this->render('edit_employee.html.twig', [
                                'form' => $formUpdateEmp->createView(),
                                'employee' => $employee
                    ]);
                } else {
                    $message = $body->message;
                }
                break;
            case 'item':
                $data['item'] = $id;
                $body = $this->APICall($data, 'getItem', $cookie);
                if ($body->status == 'OK') { 
                    $item = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                    $data['cafeteria'] = $item['cafeteria'];
                    $body = $this->APICall($data, 'getMenus', $cookie);
                    $data = $item;
                    foreach ($data['ingredients'] as $key => $value){
                        $data['ingredients'][$key] = get_object_vars($value);
                    }
                    foreach ($data['extras'] as $key => $value){
                        $data['extras'][$key] = get_object_vars($value);
                    }
                    $menus  =  is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                    $data['menus'] = $menus;
                    $formUpdateItem = $form->createNamedBuilder("UpdateItem", ItemType::class, $data)->getForm();
                    $formUpdateItem->handleRequest($request);
                    if ($formUpdateItem->isSubmitted() && $formUpdateItem->isValid()) {
                        
                    }
                    return $this->render('edit_item.html.twig', [
                                'form' => $formUpdateItem->createView(),
                                'item' => $item
                    ]);
                } else {
                    $message = $body->message;
                }
                break;
            case 'bundle':
                break;
        }
    }
    
    public function getResource(Request $request, $cafeteria , $resource){
        $data = array();
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $data['cafeteria'] = $cafeteria_name;
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data, "get$resource" , $cookie);
        if ($body->status == 'OK') {
            $resource = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        }
        $response= new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($resource);
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        $response->headers->set('Cache-control', 'max-age=31536000');
        return $response;
    }
}


