<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Exception\ClientUnavailableException;
use App\Form\ContractType;
use App\Form\PayType;
use App\Repository\ContractRepository;
use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/contract")
 */
class ContractController extends AbstractController
{
    /**
     * @Route("/", name="contract_index", methods={"GET"})
     */
    public function index(ContractRepository $contractRepository): Response
    {
        return $this->render('contract/index.html.twig', [
            'contracts' => $contractRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="contract_new", methods={"GET","POST"})
     */
    public function new(Request $request, Client $Client, SerializerInterface $serializer): Response
    {
        $contract = new Contract();
        $form     = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        $vin = $request->get('vin');
        //var_dump($vin);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('date_start_two')->getNormData() == null) {
                $dateTwoStart = "null";
                $dateTwoEnd   = "null";
            } else {
                $dateTwoStart    = $form->get('date_start_two')->getNormData();
                $dateTwoEnd = $form->get('date_end_two')->getNormData();
            }

            if ($form->get('date_start_three')->getNormData() == null) {
                $dateThreeStart = "null";
                $dateThreeEnd   = "null";
            } else {
                $dateThreeStart    = $form->get('date_start_three')->getNormData();
                $dateThreeEnd = $form->get('date_end_three')->getNormData();
            }


            // Запрос в сервис billing для создания курса
            $dataRequest = [
                'date_start' => $form->get('date_start')->getNormData(),
                'date_end'   => $form->get('date_end')->getNormData(),

                'purpose'         => $form->get('purpose')->getNormData(),
                'amount'          => " ",
                'diagnostic_card' => $form->get('diagnostic_card')->getNormData(),
                'non_limited'     => $form->get('non_limited')->getNormData(),
                'status'          => "2",
                'auto_vin'        => $vin,
                'agent_id'        => " ",

                'driver_one'   => $form->get('driver_one')->getNormData(),
                'driver_two'   => $form->get('driver_two')->getNormData(),
                'driver_three' => $form->get('driver_three')->getNormData(),
                'driver_four'  => $form->get('driver_four')->getNormData(),

                'date_start_one' => $form->get('date_start_one')->getNormData(),
                'date_end_one'   => $form->get('date_end_one')->getNormData(),

                'date_start_two' => $dateTwoStart,
                'date_end_two'   => $dateTwoEnd,

                'date_start_three' => $dateThreeStart,
                'date_end_three'   => $dateThreeEnd,
            ];

            var_dump($serializer->serialize($dataRequest, 'json'));
           // exit();
            $contract = $Client->newContract($this->getUser(), $serializer->serialize($dataRequest, 'json'));

            var_dump($contract['id']);

//return $this->redirectToRoute('contract_show',['id'=>$contract['id']]);
            return $this->redirectToRoute('contract_pay', ['id' => $contract['id']]);

        }

        return $this->render('contract/new.html.twig', [
            //'$dataRequest' => $dataRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pay/{id}", name="contract_pay", methods={"GET","POST"})
     */
    public function pay(Request $request, Client $Client, SerializerInterface $serializer): Response
    {
        $contract = new Contract();
        $form     = $this->createForm(PayType::class, $contract);
        $form->handleRequest($request);

        $id       = $request->get('id');
        $contract = $Client->getContract($this->getUser(), $id);

        if ($form->isSubmitted() && $form->isValid()) {

            $contract['amount'] = '32000000';

            $contract = $Client->editContract($this->getUser(), $id, $serializer->serialize($contract, 'json'));

            //var_dump($contract['id']);
//return $this->redirectToRoute('contract_show',['id'=>$contract['id']]);
            return $this->redirectToRoute('home');

        }


        return $this->render('contract/pay.html.twig', [
            'contract' => null,
            'form'     => $form->createView(),
        ]);


    }

    /**
     * @Route("/{id}", name="contract_show", methods={"GET"})
     */
    public function show(Request $request, Client $Client): Response
    {
        $id       = $request->get('id');
        $contract = $Client->getContract($this->getUser(), $id);

        $contractVal = true;

        if (isset($auto['code']) && $auto['code'] == 404) {
            return $this->render('contract/show.html.twig', [
                'contract' => null,
            ]);
        } /*else {
            if (isset($auto['contracts'])) {
                foreach ($auto['contracts'] as $contract) {

                    if(new DateTime($contract['date_end']) > new DateTime(date('Y-m-d H:i:s')))
                    {
                        $contractVal=false;
                    }
                }
            }*/

//var_dump($contract['drivers']);
        return $this->render('contract/show.html.twig', [
            'contract' => $contract,
            /*'contract' => $contractVal,*/
        ]);
        // }
    }

    /**
     * @Route("/{id}/edit", name="contract_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contract $contract): Response
    {
        $form = $this->createForm(Contract1Type::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contract_index');
        }

        return $this->render('contract/edit.html.twig', [
            'contract' => $contract,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contract_delete", methods={"POST"})
     */
    public function delete(Request $request, Contract $contract): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contract->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contract);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contract_index');
    }
}
