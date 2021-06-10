<?php

namespace App\Controller;

use App\Entity\Auto;
use App\Exception\ClientUnavailableException;
use App\Form\DtpType;
use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/dtp")
 */
class DtpController extends AbstractController
{
    /**
     * @Route("/", name="dtp")
     */
    public function index(): Response
    {
        return $this->render('dtp/index.html.twig', [
        ]);
    }

    /**
     * @Route("/new", name="dtp_new", methods={"GET","POST"})
     */
    public function new(Request $request, Client $Client, SerializerInterface $serializer): Response
    {

        $form = $this->createForm(DtpType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Запрос в сервис billing для создания курса
                $dataRequest = [
                    'date_dtp'      => $form->get('date_dtp')->getNormData(),
                    'description'    => $form->get('description')->getNormData(),
                    'adress_dtp'    => $form->get('adress_dtp')->getNormData(),
                    'degree'    => $form->get('degree')->getNormData(),
                    'initiator'    => $form->get('initiator')->getNormData(),
                    'users'   => $form->get('users')->getNormData(),
                    'autos'   => $form->get('autos')->getNormData(),

                ];
                $Client->newDtp($this->getUser(), $serializer->serialize($dataRequest, 'json'));
            } catch (ClientUnavailableException $e) {
                // flash message
                $this->addFlash('message', 'Возникла ошибка!');

                return $this->render('dtp/new.html.twig', [
                    'form'   => $form->createView(),
                ]);
            }

            return $this->redirectToRoute('home');
        }

        return $this->render('dtp/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/file", name="dtp_file_create", methods={"GET","POST"})
     */
    public function dtpFileCreate(Request $request, Client $Client, SerializerInterface $serializer): Response
    {

            try {

                $Client->createFileDtp($this->getUser());
                // flash message
                $this->addFlash('message', 'Файл Успешно создан');

            } catch (ClientUnavailableException $e) {
                // flash message
                $this->addFlash('message', 'Возникла ошибка!');

            }
        return $this->redirectToRoute('dtp');

    }
}
