<?php

namespace App\Controller;

use App\Helper\ConnectionController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller{
    
    public function search(Request $request){
        $data = array();
        $data['q'] = $request->request->get('q');
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data, 'searchIngredient', $cookie);
        if ($body->status == 'OK') {
            $result = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $result = $body->message;
        }
        return new Response(json_encode($result));
    }
}



