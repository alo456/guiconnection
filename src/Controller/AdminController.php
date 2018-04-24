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
class AdminController extends Controller
{
    public function login(Request $request){
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
            //RequestAPI::cookie($cookie);
            $response->headers->setCookie($cookie);
            $response->send();
            //var_dump($cookie);
            return $this->redirectToRoute('dashboard', array(
                                                'TOKEN' => $cookie
            )); 
        }else{
            echo($body->message);
        }
    }

    if($request->cookies->has('TOKEN')){
            var_dump($request->cookies->get('TOKEN'));
            //return null;
           return $this->redirectToRoute('dashboard');
    }
        return $response;
//        
    }
    public function index(Request $request){
//        var_dump($request->cookies->get('TOKEN'));
//         $cookie = Cookie::fromString($request->cookies->get('TOKEN'));
//         $response = $this->render("index.html");
//         $response->headers->setCookie($cookie);
         return $this->render('index.html.twig');
    }
    
    public function menuCategory(Request $request){
        
        return $this->render('Menu/category.html.twig');
        $data = array();
        $cafeterias = array();
        //Get all menus from cafeteria for add a subcategory
        $headers = array('Accept' => 'application/json');
        $data['cafeteria'] = $request->request->get('id_cafeteria');
        $data['action'] = 'getMenus';
        $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin/login',$headers,$body);
        $body  = $responseAPI->body;
        if($body->status == 'OK'){ 
            $cafeterias = $body->message;
        }else{
            echo($body->message);
        }
        $body = Body::form($data);
        
        $response = $this->render('Menu/category.html.twig', array(
                                        'cafeterias' => $cafeterias
                                  ));
        $response->headers->setCookie($cookie);
        return $response;
    }
    
    public function menuIngredient(){
        return $this->render('Menu/ingredient.html.twig');
    }
    
    public function menuProduct(){
        return $this->render('Menu/product.html.twig');
    }
    
    public function POSAccess(){
        return $this->render('POS/access.html.twig');
    }
    
    public function POSrecord(){
        return $this->render('POS/record.html.twig');
    }
    
}
