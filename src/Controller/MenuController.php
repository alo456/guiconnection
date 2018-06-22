<?php
namespace App\Controller;

use App\Form\BundleType;
use App\Form\ItemType;
use App\Form\MenuType;
use App\Helper\ConnectionController as Controller;
use Symfony\Component\HttpFoundation\Request;
class MenuController extends Controller
{
    public function category(Request $request, $cafeteria) {
        $message = "";
        $menus = [];
        //Get menus from cafeteria
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $data= "{cafeteria(name:".'"'.$cafeteria_name.'"'."){
                menus{
                    id
                    name
                }
        }}";
//        var_dump($data);
//        die;
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->GraphCall($data, $cookie);
        
//        var_dump($body->payload->query->data->cafeteria[0]->menus);
//        die;
        if ($body->status == 'OK') {
        $body = $body->payload->query->data->cafeteria[0]->menus;
        $menus = is_object($body) ? get_object_vars($body) : $body;
        //var_dump($menus);
//        die;
        } else {
            $message = $body->message;
        }

        //------------------
        //Form Builder 
        $form = $this->get('form.factory');
        $formCreateMenu = $form->createNamedBuilder("Menu", MenuType::class, $menus)->getForm();
       
//        //------------------
//        //Form Request
//        $formCreateMenu->handleRequest($request);
//        if ($formCreateMenu->isSubmitted() && $formCreateMenu->isValid()) {
//            $data = $formCreateMenu->getData();
//            $data['background'] = base64_encode($data['background']);
//            $body = $this->GraphCallCall($data, 'createMenu', $cookie);
//            if ($body->status == 'OK') {
//                $message = "Menu creado";
//            } else {
//                $message = $body->message;
//            }
//        }
//        //-----------------
        if (!$this->cookieCafeterias($request->cookies->get('CAFETERIAS'), $cookie)) {
            $message .= "Error del sistema";
        }
        $response = $this->render('Menu/category.html.twig', array(
            'cafeteria' => $cafeteria_name,
            'formCreateMenu' => $formCreateMenu->createView(),
            'message' => $message,
            'menus' => $menus
        ));
        return $response;
    }

    public function bundle(Request $request, $cafeteria) {
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $cookie = $request->cookies->get('TOKEN');
        $data['cafeteria'] = $cafeteria_name;

        //FORM JQUERY 
        $form = $this->get('form.factory');
        $formBundles = $form->createNamedBuilder("Paquetes", BundleType::class, [])->getForm();
        $formBundles->setData([]);
        //----------------------


        return $this->render('Menu/bundle.html.twig', array(
                    'cafeteria' => $cafeteria_name,
                    'formBundle' => $formBundles->createView()
        ));
    }

    public function product(Request $request, $cafeteria) {
        $message = "";
        $menus = [];
        $ingredients = [];
        $items = [];
        $alias = [];
        $timestamp = "";
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));

        //Get Timestamp
        $data = array();
        $data['cafeteria'] = $cafeteria_name;
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data, 'getTime', $cookie);
        if ($body->status == 'OK') {
            $timestamp = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message .= $body->message;
        }
        //--------------------------------
        //Get menus from cafeteria
        $data = array();
        $data['cafeteria'] = $cafeteria_name;
        $body = $this->APICall($data, 'getMenus', $cookie);
        if ($body->status == 'OK') {
            $menus = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message .= $body->message;
        }
        //------------------
        //Get items from cafeteria
        $data['cafeteria'] = $cafeteria_name;
        $body = $this->APICall($data, 'getItems', $cookie);
        if ($body->status == 'OK') {
            $items = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        } else {
            $message .= $body->message;
        }
        //------------------
        //FORM JQUERY 
        $data['menus'] = $menus;
        $form = $this->get('form.factory');
        $formItem = $form->createNamedBuilder("Item", ItemType::class, $data)->getForm();
        //----------------------
        //Form Item
        $formItem->handleRequest($request);
        if ($formItem->isSubmitted() && $formItem->isValid()) {
            $item = $request->request->get('Item');
            $data = $item['information'];
            $data['background'] = base64_encode($formItem->getData()['information']['background']);
            $data['ingredients'] = isset($item['ingredients']) ? json_encode($item['ingredients']) : json_encode([]);
            $data['extras'] = isset($item['extras']) ? json_encode($item['extras']) : json_encode([]);
            var_dump($item);
            var_dump($data);
            die;
            $body = $this->APICall($data, 'createItem', $cookie);
            
            if ($body->status == 'OK') {
                $message .= $body->payload;
            } else {
                $message .= $body->message;
            }
        }
        //-----------------
        if (!$this->cookieCafeterias($request->cookies->get('CAFETERIAS'), $cookie)) {
            $message .= "Error del sistema";
        }
        $response = $this->render('Menu/product.html.twig', array(
            'cafeteria' => $cafeteria_name,
            'formItem' => $formItem->createView(),
            'items' => $items,
            'time' => $timestamp,
            'message' => $message
        ));
        return $response;
    }

}

