<?php

namespace App\Controller;

use App\Exception\ClientUnavailableException;
use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile" )
     */
    public function index(Client $Client): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_USER',
            $this->getUser(),
            'У вас нет доступа к этой странице'
        );

        try {
            $result = $Client->getCurrentUser($this->getUser());
             //var_dump($result);
        } catch (ClientUnavailableException $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'autos'=>$result['autos'],
            'user' => $result
        ]);
    }
}
