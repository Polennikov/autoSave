<?php

namespace App\Controller;

use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Client $Client): Response
    {
        //var_dump($this->getUser()->getRoles());
        if($this->getUser() && $this->getUser()->getRoles()[0]=='ROLE_AGENT'){

            $contracts=$Client->getContractAgent($this->getUser());

            return $this->render('home/index.html.twig', [
                'contracts'=> $contracts,
                'controller_name' => 'HomeController',
            ]);
        }


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
