<?php

namespace App\Controller;

use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgentController extends AbstractController
{
    /**
     * @Route("/agent", name="agent")
     */
    public function index(Request $request, Client $Client): Response
    {
        $id       = $request->get('id');
        $contract = $Client->getContract($this->getUser(), $id);
        //var_dump($id);
        return $this->render('agent/index.html.twig', [
            'contract' => $contract,
        ]);
    }
}
