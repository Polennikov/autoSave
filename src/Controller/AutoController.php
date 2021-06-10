<?php

namespace App\Controller;

use App\Entity\Auto;
use App\Exception\ClientUnavailableException;
use App\Form\AutoType;
use App\Repository\AutoRepository;
use App\Security\User;
use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/auto")
 */
class AutoController extends AbstractController
{
    /**
     * @Route("/", name="auto_index", methods={"GET"})
     */
    public function index(AutoRepository $autoRepository): Response
    {

        return $this->render('auto/index.html.twig', [
            'autos' => $autoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="auto_new", methods={"GET","POST"})
     */
    public function new(Request $request, Client $Client, SerializerInterface $serializer): Response
    {
        $auto = new Auto();
        $form = $this->createForm(AutoType::class, $auto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Запрос в сервис billing для создания курса
                $dataRequest = [
                    'vin'      => $form->get('vin')->getNormData(),
                    'marka'    => $form->get('marka')->getNormData(),
                    'model'    => $form->get('model')->getNormData(),
                    'mileage'    => $form->get('mileage')->getNormData(),
                    'number'   => $form->get('number')->getNormData(),
                    'number_sts'   => $form->get('number_sts')->getNormData(),
                    'color'    => $form->get('color')->getNormData(),
                    'year'     => $form->get('year')->getNormData(),
                    'power'    => $form->get('power')->getNormData(),
                    'category' => $form->get('category')->getNormData(),
                ];
                $Client->newAuto($this->getUser(), $serializer->serialize($dataRequest, 'json'));
            } catch (ClientUnavailableException $e) {
                // flash message
                $this->addFlash('message', 'Возникла ошибка!');

                return $this->render('auto/new.html.twig', [
                    'course' => $auto,
                    'form'   => $form->createView(),
                ]);
            }

            return $this->redirectToRoute('profile');
        }

        return $this->render('auto/new.html.twig', [
            'auto' => $auto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{vin}", name="auto_show", methods={"GET"})
     */
    public function show(Request $request, Client $Client): Response
    {
        $vin  = $request->get('vin');
        $auto = $Client->getAuto($this->getUser(), $vin);


        $contractVal=true;

        if (isset($auto['code']) && $auto['code'] == 404) {
            return $this->render('auto/show.html.twig', [
                'auto' => null,
            ]);
        } else {
            if (isset($auto['contracts'])) {
                foreach ($auto['contracts'] as $contract) {

                    if(new DateTime($contract['date_end']) > new DateTime(date('Y-m-d H:i:s')))
                    {
                        $contractVal=false;
                    }
                }
            }

            return $this->render('auto/show.html.twig', [
                'auto' => $auto,
                'contract' => $contractVal,
            ]);
        }
    }

    /**
     * @Route("/{vin}/edit", name="auto_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $Client, SerializerInterface $serializer): Response
    {

        $vin  = $request->get('vin');
        $auto = $Client->getAuto($this->getUser(), $vin);

        $form = $this->createForm(AutoType::class);
        $form->get('vin')->setData($auto['vin']);
        $form->get('model')->setData($auto['model']);
        $form->get('marka')->setData($auto['marka']);
        $form->get('year')->setData($auto['year']);
        $form->get('number')->setData($auto['number']);
        $form->get('color')->setData($auto['color']);
        $form->get('power')->setData($auto['power']);
        $form->get('mileage')->setData($auto['mileage']);
        $form->get('category')->setData($auto['category']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Запрос в сервис billing для создания курса
                $dataRequest = [
                    'vin'      => $form->get('vin')->getNormData(),
                    'marka'    => $form->get('marka')->getNormData(),
                    'model'    => $form->get('model')->getNormData(),
                    'number'   => $form->get('number')->getNormData(),
                    'color'    => $form->get('color')->getNormData(),
                    'year'     => $form->get('year')->getNormData(),
                    'power'    => $form->get('power')->getNormData(),
                    'mileage'  => $form->get('mileage')->getNormData(),
                    'category' => $form->get('category')->getNormData(),
                ];
                $Client->editAuto($this->getUser(), $dataRequest['vin'], $serializer->serialize($dataRequest, 'json'));
            } catch (ClientUnavailableException $e) {
                // flash message
                $this->addFlash('message', 'Возникла ошибка!');

                return $this->render('auto/new.html.twig', [
                    'course' => $auto,
                    'form'   => $form->createView(),
                ]);
            }

            return $this->redirectToRoute('home');
        }

        return $this->render('auto/edit.html.twig', [
            'auto' => $auto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{vin}", name="auto_delete", methods={"POST"})
     */
    public function delete(Request $request, Client $Client): Response
    {
        $vin = $request->get('vin');
        if ($this->isCsrfTokenValid('delete'.$vin, $request->request->get('_token'))) {
            try {
                $autos = $Client->delAuto($this->getUser(), $vin);
            } catch (ClientUnavailableException $e) {
                // flash message
                $this->addFlash('message', 'Возникла ошибка!');

                $autos = $Client->getAuto($this->getUser(), $vin);

                return $this->render('auto/show.html.twig', [
                    'auto' => $autos,
                ]);
            }
            //$autos = $Client->getAuto($this->getUser(), $vin);
            return $this->redirectToRoute('home');
            ///return $this->render('home');

        }
    }
}
