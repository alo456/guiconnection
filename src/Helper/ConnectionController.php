<?php

namespace App\Helper;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Cookie;
use Unirest\Request\Body;
use Unirest\Request as RequestAPI;

class ConnectionController extends Controller {

    public function __construct(ContainerInterface $container,RequestStack $request) {
        $this->container = $container;//Fallback Container Asign
        $this->request=$request->getCurrentRequest();
    }
    
    public function APICall($data ,$action, $cookie){
        $url = "http://localhost/taiuniversityapi/public/admin";
        $headers = array('Accept' => 'application/json');
        $data['action'] = $action;
        $body = Body::form($data);
        RequestAPI::cookie("TOKEN=" . $cookie);
        $responseAPI = RequestAPI::post("$url", $headers, $body);
        return $responseAPI->body;
    }
    
    public function cookieCafeterias($cafeterias,$cookie){
        if(!$cafeterias){
            $body = $this->APICall([], 'getCafeterias', $cookie);
            if ($body->status == 'OK') {
                $cafeterias = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                setcookie("CAFETERIAS", json_encode($cafeterias,JSON_UNESCAPED_UNICODE));         
                return true;
            } 
            else{
                return false;
            }
        }
    }
    
    
}
