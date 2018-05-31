<?php
namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Unirest\Request as RequestAPI;
use Unirest\Request\Body;
use App\Helper\ConnectionController as Controller;
class ReportsController extends Controller
{

    public function data (Request $request){
      
            return $this->render('Reports/data.html.twig', array(
            'cafeteria' => 'cuckoo'

        ));
    }
    
}

