<?php
namespace App\Controller;

use App\Form\ScheduleType;
use App\Helper\ConnectionController as Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Unirest\Request as RequestAPI;
use Unirest\Request\Body;
class AdminController extends Controller
{
    public function login(Request $request) {
        $message = "";
        $form = $this->createFormBuilder([])
                ->add('username', TextType::class, array('label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre de usuario'
                    )
                ))
                ->add('password', PasswordType::class, array('label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Contraseña'
                    )
                ))
                ->add('submit', SubmitType::class, array('label' => 'Iniciar Sesión',
                    'attr' => array(
                        'class' => 'btn btn-primary px-4'
                    )
                ))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $headers = array('Accept' => 'application/json');
            $body = Body::form($data);

            $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin/login', $headers, $body);
            $body = $responseAPI->body;
            if ($body->status == 'OK') {
                $token = $responseAPI->headers['Set-Cookie'];
                $cookie = Cookie::fromString($token);
                $cookie = $cookie->getValue();
                setcookie("TOKEN", $cookie);
                $data = array();
                $cafeteria = $this->cookieCafeterias($request->cookies->get('CAFETERIAS'), $cookie);
                if (!$cafeteria) {
                    $message .= "Error de autenticación";
                } else {
                    $body = Body::form(array(
                                'action' => 'getCafeterias'
                    ));
                    $responseAPI = RequestAPI::post('http://localhost/taiuniversityapi/public/admin', $headers, $body);

                    $body = $responseAPI->body;

                    if ($body->status == 'OK') {
                        $cafeteria = is_object($body->payload) ? get_object_vars($body->payload) : $body->payload;
                        return $this->redirectToRoute('dashboard', array(
                                    'cafeteria' => strtolower(str_replace(" ", "_", $cafeteria[0]))
                        ));
                    }
                }
            } else {
                $message .= $body->payload;
            }
        }
        return $this->render("login.html.twig", array(
                    'form' => $form->createView(),
                    'message' => $message
        ));
//        
    }

    public function dashboard(Request $request, $cafeteria) {
        $name = "";
        $message = "";
        $cookie = $request->cookies->get('TOKEN');
        $body = $this->APICall([], "getAdministratorName", $cookie);
        if ($body->status == 'OK') {
            $name = $body->payload;
        } else {
            $message .= $body->message;
        }
        if (!$this->cookieCafeterias($request->cookies->get('CAFETERIAS'), $cookie)) {
            $message .= "Error del sistema";
        }
        return $this->render('dashboard.html.twig', array(
                    'cafeteria' => $cafeteria,
                    'name' => $name,
                    'message' => $message
        ));
    }

    public function personal(Request $request, $cafeteria) {
        $message = "";
        $form = $this->createFormBuilder([])
                ->add('firstname', TextType::class, array('label' => 'Nombre',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre'
                    ),
                    'required' => false
                ))
                ->add('lastname', TextType::class, array('label' => 'Apellido',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Apellido'
                    ),
                    'required' => false
                ))
                ->add('email', TextType::class, array('label' => 'Email',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Email'
                    ),
                    'required' => false
                ))
                ->add('password', PasswordType::class, array('label' => 'Contraseña',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Contraseña'
                    )
                ))
                ->add('submit', SubmitType::class, array('label' => 'Guardar',
                    'attr' => array(
                        'class' => 'btn btn-primary px-4'
                    )
                ))
                ->getForm();
        $form->handleRequest($request);

        $cookie = $request->cookies->get('TOKEN');
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $body = $this->APICall($data, "updateInformation", $cookie);
            if ($body->status == 'OK') {
                $message .= "Cambios realizados";
                header("Refresh:0");
            } else {
                $message .= $body->message;
            }
        }
        if (!$this->cookieCafeterias($request->cookies->get('CAFETERIAS'), $cookie)) {
            $message .= "Error del sistema";
        }


        $response = $this->render('Administrator/personal.html.twig', array(
            'form' => $form->createView(),
            'cafeteria' => $cafeteria,
            'message' => $message
        ));

        return $response;
    }

    public function settings(Request $request, $cafeteria) {
        $message = "";
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $cookie = $request->cookies->get('TOKEN');
        $formPassword = $this->createFormBuilder([])
                ->add('oldpassword', PasswordType::class, array('label' => 'Contraseña Actual',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Contraseña Actual'
                    )
                ))
                ->add('newpassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Nueva Contraseña', 'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Nueva Contraseña'
                        )),
                    'second_options' => array('label' => 'Confirmación de Contraseña', 'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Confirmación de Contraseña'
                        ))
                ))
                ->add('submit', SubmitType::class, array('label' => 'Guardar',
                    'attr' => array(
                        'class' => 'btn btn-primary px-4'
                    )
                ))
                ->getForm();
        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $data = $formPassword->getData();
            $body = $this->APICall($data, "changePassword", $cookie);
            if ($body->status == 'OK') {
                $message .= "Cambios realizados";
            } else {
                $message .= $body->message;
            }
        }
        $formConfiguration = $this->createFormBuilder([])
                ->add('fromCafe', TextType::class, array('label' => 'Copiar configuración de:',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Cafeteria'
                    )
                ))
                ->add('toCafe', TextType::class, array('label' => 'a:',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Cafeteria'
                    )
                ))
                ->add('submit', SubmitType::class, array('label' => 'Copiar configuración',
                    'attr' => array(
                        'class' => 'btn btn-outline-primary col-auto'
                    )
                ))
                ->getForm();

        $formConfiguration->handleRequest($request);
        $form = $this->get('form.factory');
        $formSchedule = $form->createNamedBuilder("Schedule", ScheduleType::class, [])->getForm();

        $formSchedule->handleRequest($request);

        if ($formSchedule->isSubmitted() && $formSchedule->isValid()) {
            $dataSchedule = $formSchedule->getData();
            $result = array();
            foreach ($dataSchedule as $day => $interval) {
                $data = array();
                foreach ($interval as $time) {
                    $data[] = array(
                        $time['start']->format('H:i'),
                        $time['end']->format('H:i')
                    );
                }
                $result['schedule'][$day] = $data;
            }

            $result['cafeteria'] = $cafeteria_name;

            $bodySchedule = $this->APICall($result, "updateSchedule", $cookie);
            if ($bodySchedule->status == 'OK') {
                $message .= "Cambios realizados";
            } else {
                $message .= $bodySchedule->message;
            }
        }
        if (!$this->cookieCafeterias($request->cookies->get('CAFETERIAS'), $cookie)) {
            $message .= "Error del sistema";
        }

        $response = $this->render('Administrator/settings.html.twig', array(
            'form' => $formPassword->createView(),
            'configuration' => $formConfiguration->createView(),
            'schedule' => $formSchedule->createView(),
            'cafeteria' => $cafeteria,
            'message' => $message
        ));

        return $response;
    }

    public function activity(Request $request, $cafeteria) {
        $cafeteria_name = strtolower(str_replace("_", " ", $cafeteria));
        $cookie = $request->cookies->get('TOKEN');
        $message = "";
        $data['cafeteria'] = $cafeteria_name;
        $activity = "";
        $body = $this->APICall($data, "getLastTransactions", $cookie);
        if ($body->status == 'OK') {
            $activity = $body->payload;
        } else {
            $message .= $body->message;
        }

        if (!$this->cookieCafeterias($request->cookies->get('CAFETERIAS'), $cookie)) {
            $message .= "Error del sistema";
        }
        return $this->render('Administrator/activity.html.twig', array(
                    'cafeteria' => $cafeteria,
                    'activity' => $activity,
                    'message' => $message
        ));
    }

    public function balance(Request $request) {
        return $this->render('Administrator/balance.html.twig', array(
                    'cafeteria' => 'Cuckoo',
        ));
    }

    public function account(Request $request) {
        return $this->render('Administrator/account.html.twig');
    }

}
