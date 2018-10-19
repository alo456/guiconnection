<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Form\ItemType;
use App\Form\MenuType;
use App\Helper\ConnectionController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RDL extends Controller {

    public function update(Request $request, $context, $id) {
        $form = $this->get('form.factory');
        $data = array();
        $cookie = $request->cookies->get('TOKEN');
        switch ($context) {
            case 'employee':
                /*
                $data['employee'] = $id;
                $body = $this->APICall($data, 'getEmployee', $cookie);
                if ($body->status == 'OK') {
                    $employee = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                    $cafeteria_name = $cafeteria;
                    $formUpdateEmp = $form->createNamedBuilder("UpdateEmployee", EmployeeType::class, $data)->getForm();
                    $formUpdateEmp->handleRequest($request);
                    if ($formUpdateEmp->isSubmitted()) {
                        $data = $formUpdateEmp->getData();
                        $data['employee'] = $id;
                        $cookie = $request->cookies->get('TOKEN');
                        $body = $this->APICall($data, 'updateEmployee', $cookie);
                        if ($body->status == 'OK') {
                            $message = "Empleado creado";
                            return $this->redirectToRoute('POSAccess', array(
                                        'cafeteria' => $cafeteria_name
                            ));
                        } else {
                            $message = $body->message;
                        }
                    }

                    return $this->render('edit_employee.html.twig', [
                                'form' => $formUpdateEmp->createView(),
                                'employee' => $employee
                    ]);
                } else {
                    $message = $body->message;
                }
                 * 
                 */
                break;
            case 'item':
                /*
                $data['item'] = $id;
                $body = $this->APICall($data, 'getItem', $cookie);
                var_dump($body);
                echo $body;
                die;
                if ($body->status == 'OK') {
                    $item = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                    $data['cafeteria'] = $item['cafeteria'];
                    $body = $this->APICall($data, 'getMenus', $cookie);
                    $data = $item;
                    foreach ($data['ingredients'] as $key => $value) {
                        $data['ingredients'][$key] = get_object_vars($value);
                    }
                    foreach ($data['extras'] as $key => $value) {
                        $data['extras'][$key] = get_object_vars($value);
                    }
                    $menus = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                    $data['menus'] = $menus;
                    $formUpdateItem = $form->createNamedBuilder("UpdateItem", ItemType::class, $data)->getForm();
                    $formUpdateItem->handleRequest($request);
                    if ($formUpdateItem->isSubmitted() && $formUpdateItem->isValid()) {
                        
                    }
                    return $this->render('edit_item.html.twig', [
                                'form' => $formUpdateItem->createView(),
                                'item' => $item
                    ]);
                } else {
                    $message = $body->message;
                }
                 * 
                 */
                break;
            case 'bundle':
                break;
            case 'menu':
                $data = "{
                    menu(id :" .'"' . $id .'"' . "){
                        name
                        menu{
        			name
        		}
                	background
                        description
                    }
                }";
                $body = $this->GraphCall($data, $cookie);
                $response = $body->payload->query->data->menu;
                $menu = is_object($response[0]) ? get_object_vars($response[0]) : $response;
                
                
                var_dump($response);
                if ($body->status == 'OK') {
                    $formUpdateMenu = $form->createNamedBuilder("UpdateMenu", MenuType::class, $data)->getForm();
                    $formUpdateMenu->handleRequest($request);
                    if ($formUpdateMenu->isSubmitted()) {
                        $data = $formUpdateMenu->getData();
                        $data['menu'] = $id;
                        $cookie = $request->cookies->get('TOKEN');
                        $body = $this->APICall($data, 'updateMenu', $cookie);
                        if ($body->status == 'OK') {
                            $message = "Empleado creado";
                            return $this->redirectToRoute('category', array(
                                        'cafeteria' => $cafeteria_name
                            ));
                        } else {
                            $message = $body->message;
                        }
                    }

                    return $this->render('edit_menu.html.twig', [
                                'form' => $formUpdateMenu->createView(),
                                'menu' => $menu
                    ]);
                } else {
                    $message = $body->message;
                }
                
                break;
        }
    }

    public function getResource(Request $request, $cafeteria, $resource) {
        $data = array();
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $data['cafeteria'] = $cafeteria_name;
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall($data, "get$resource", $cookie);
        if ($body->status == 'OK') {
            $resource = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
        }
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($resource);
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        $response->headers->set('Cache-control', 'max-age=31536000');
        return $response;
    }

    public function getGQLResource(Request $request, $cafeteria, $resource) {
        $cookie = $request->cookies->get('TOKEN');
        $data = "";
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        switch ($resource) {
            case 'menus':
                $element ['id'] = '';
                $element ['name'] = '';
                $element ['description'] = '';
                $element ['parent_id'] = '';
                $element ['level'] = '';
                $element ['isLeaf'] = '';
                $element ['loaded'] = false;
                $element ['expanded'] = false;

                if ($request->request->get('nodeid') != null) {
                    $data = "{menusPerCafeteria(cafeteria:" . '"' . $cafeteria_name . '"' . ", id:" . '"' . $request->request->get('nodeid') . '"' . " ){
                                    submenus{
                                        id
                                        name
                                        description
                                        submenus{
                                            id
                                        }
                                    }
                                  }}";
                } else {
                    $data = "{menusPerCafeteria(cafeteria:" . '"' . $cafeteria_name . '"' . ", id:" . '"' . "null" . '"' . " ){
                                    id
                                    name
                                    description
                                    submenus{
                                        id
                                    }
                                  }}";
                }
                // var_dump($data);
                $response = $this->GraphCall($data, $cookie);
                $response = $response->payload->query->data->menusPerCafeteria;
                $menus = is_object($response) ? get_object_vars($response) : $response;
                $elements = [];
                $parent = $request->request->get('nodeid',null);
                $level = $request->request->get('n_level',null);
                foreach ($menus as $menu) {
                    $element['id'] = isset($menu->id) ? $menu->id : $menu->submenus[0]->id;
                    $element['name'] = isset($menu->name) ? $menu->name : $menu->submenus[0]->name;
                    $element['description'] = isset($menu->description) ? $menu->description : $menu->submenus[0]->description;
                    $element['parent_id'] = $parent != null ? $parent : null;
                    $element['level'] = $level != null ? $level + 1 : 0 ;
                    $element['isLeaf'] = isset($menu->submenus[0]->submenus) && !empty($menu->submenus[0]->submenus) ? false : isset($menu->submenus[0]->submenus) && empty($menu->submenus[0]->submenus) ? true : empty($menu->submenus)? true: false;
                    $elements[] = $element;
                }
                $response = $elements;
                break;
        }
//        echo($response);
//        die;
        return new Response(json_encode($response));
    }

    public function create(Request $request, $cafeteria , $context) {
        // var_dump($request->request);
        $form = $this->get('form.factory');
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $cookie = $request->cookies->get('TOKEN');
        $menus = json_decode($request->cookies->get('MENUS'), true);
        //var_dump($menus); die;
        switch ($context) {
            case 'menu':
                $formCreateMenu = $form->createNamedBuilder("Menu", MenuType::class, $menus)->getForm();
                //Form Request
                $formCreateMenu->handleRequest($request);
                if ($formCreateMenu->isSubmitted() && $formCreateMenu->isValid()) {
                    var_dump("hola alo" );
                    $data = $request->request->get('Menu');
                    $data['cafeteria'] = $cafeteria_name;
                    $data['background'] = base64_encode($formCreateMenu->getData()['background']);
                    $body = $this->APICall($data, 'createMenu', $cookie);
                    if ($body->status == 'OK') {
                        $message = "Menu creado";
                        return $this->redirectToRoute('menuCategory', array(
                                    'cafeteria' => str_replace(" ", "_", $cafeteria)
                        ));
                    } else {
                        $message = $body->message;
                    }
                }
                //die;
                return $this->render('create_menu.html.twig', [
                            'form' => $formCreateMenu->createView(),
                            'context' => $context,
                            'cafeteria' => $cafeteria
                ]);
        }
        
    }
    
    public function delete(Request $request, $context , $id){
        $cookie = $request->cookies->get('TOKEN');
        switch($context){
            case 'menu':
                $body = $this->APICall(['id_menu' => $id],'deleteMenu',$cookie);
                if ($body->status == 'OK') {
                    $message = "OK";
                    $response = new Response($message);
                    return $response->setStatusCode(200);
                } else {
                    $message = $body->message;
                    $response = new Response($message);
                    return $response->setStatusCode(200);
                }
                break;
            default:
                break;
        }
        
        
    }

}
