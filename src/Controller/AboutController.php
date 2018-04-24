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
class AboutController extends Controller
{
    public function aboutUs(Request $request){
        return $this->render('About/aboutus.html.twig');
    }
    
    public function privacyNTerms(Request $request){
        return $this->render('About/privacynterms.html.twig');
    }
    
    public function help(Request $request){
        return $this->render('About/help.html.twig');
    }
    
    public function tutorial(Request $request){
        return $this->render('About/tutorial.html.twig');
    }
}
