<?php

namespace App\Helper;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Unirest\Request as RequestAPI;
use Unirest\Request\Body;

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
}
