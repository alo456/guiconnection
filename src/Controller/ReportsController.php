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
class ReportsController extends Controller
{
    public function session(Request $request){
        return $this->render('Reports/session.html.twig');
    }
    
    public function income(Request $request){
        return $this->render('Reports/income.html.twig');
    }
    
    public function product(Request $request){
        return $this->render('Reports/product.html.twig');
    }
    
    public function user(Request $request){
        return $this->render('Reports/user.html.twig');
    }
}

