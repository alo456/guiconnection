<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Helper\ConnectionController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Helper extends Controller{
    
    public function delete(Request $request,$context){
        $data = array();
        $action = $request->request->get('action');
        switch($context){
            case 'employee':
                $data['id_employee'] = $request->request->get('id_employee');
                $cookie = $request->cookies->get('TOKEN');
                $body = $this->APICall($data, $action, $cookie);
                return new Response(json_encode($body));
                break;
            case 'item':
                $data['id_item'] = $request->request->get('id_item');
                $cookie = $request->cookies->get('TOKEN');
                $body = $this->APICall($data, $action, $cookie);
                return new Response(json_encode($body));
                break;
            case 'bundle':
                break;
            case 'menu':
                $data['id_menu'] = $request->request->get('id_menu');
                $cookie = $request->cookies->get('TOKEN');
                $body = $this->APICall($data, $action, $cookie);
                return new Response(json_encode($body));
                break;
        }
    }
    

}



