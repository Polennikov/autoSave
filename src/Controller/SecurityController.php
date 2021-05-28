<?php

namespace App\Controller;

use App\Exception\ClientUnavailableException;
use App\Form\RegisterType;
use App\Security\User;
use App\Security\AppCustomAuthAuthenticator;
use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
             return $this->redirectToRoute('target_path');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="register")
     *
     * @param   Request                                                      $request
     * @param   SerializerInterface                                          $serializer
     * @param   Client                                                $Client
     * @param   \Symfony\Component\Security\Guard\GuardAuthenticatorHandler  $guardAuthenticatorHandler
     * @param   \App\Security\AppCustomAuthAuthenticator                              $Authenticator
     *
     * @return Response
     */
    public function register(
        Request $request,
        SerializerInterface $serializer,
        Client $Client,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        AppCustomAuthAuthenticator $Authenticator
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('profile');
        }

        $error = null;
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Формируем данные для запроса
                $data = $serializer->serialize($form->getData(), 'json');
                // Запрос к сервису для регистрации пользователя

                $response = $Client->register($data);

                if (isset($response['token'])) {
                    // Создаем пользователя
                    $user = new User();
                    $user->setEmail($form->getData()['email']);
                    //$user->setPassword($form->getData()['password']);
                    $user->setApiToken($response['token']);
                    $user->setRefreshToken($response['refresh_token']);
                    // Авторизация пользователя
                    $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                        $user,
                        $request,
                        $Authenticator,
                        'main'
                    );

                    // Переход на страницу курсов
                    return $this->redirectToRoute('home');
                } else {
                    $error = $response['message'];
                }
            } catch (ClientUnavailableException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}
