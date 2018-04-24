<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Unirest\Request as RequestAPI;
use Unirest\Request\Body;
class MenuController extends Controller
{
    public function category(Request $request) {

        return $this->render('Menu/category.html.twig');
        $data = array();
        $cafeterias = array();
        //Get all menus from cafeteria for add a subcategory
        $headers = array('Accept' => 'application/json');
        $data['cafeteria'] = $request->request->get('id_cafeteria');
        $data['action'] = 'getMenus';
        $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin/login', $headers, $body);
        $body = $responseAPI->body;
        if ($body->status == 'OK') {
            $cafeterias = $body->message;
        } else {
            echo($body->message);
        }
        $body = Body::form($data);

        $response = $this->render('Menu/category.html.twig', array(
            'cafeterias' => $cafeterias
        ));
        $response->headers->setCookie($cookie);
        return $response;
    }
    
    public function ingredient(Request $request){
        return $this->render('Menu/ingredient.html.twig');
    }

    public function product(Request $request){
        return $this->render('Menu/product.html.twig');
    }
}

