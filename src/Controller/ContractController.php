<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Exception\ClientUnavailableException;
use App\Form\ContractType;
use App\Form\PayType;
use App\Form\AmountType;
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

        $form->remove('amount');

        $form->handleRequest($request);

        $vin = $request->get('vin');
        //var_dump($vin);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('date_start_two')->getNormData() == null) {
                $dateTwoStart = "null";
                $dateTwoEnd   = "null";
            } else {
                $dateTwoStart = $form->get('date_start_two')->getNormData();
                $dateTwoEnd   = $form->get('date_end_two')->getNormData();
            }

            if ($form->get('date_start_three')->getNormData() == null) {
                $dateThreeStart = "null";
                $dateThreeEnd   = "null";
            } else {
                $dateThreeStart = $form->get('date_start_three')->getNormData();
                $dateThreeEnd   = $form->get('date_end_three')->getNormData();
            }


            // ???????????? ?? ???????????? billing ?????? ???????????????? ??????????
            $dataRequest = [
                'date_start' => $form->get('date_start')->getNormData(),
                'date_end'   => $form->get('date_end')->getNormData(),

                'purpose'         => $form->get('purpose')->getNormData(),
                'amount'          => " ",
                'diagnostic_card' => $form->get('diagnostic_card')->getNormData(),
                'non_limited'     => $form->get('non_limited')->getNormData(),
                'status'          => "1",
                'trailer'         => $form->get('trailer')->getNormData(),
                'auto_vin'        => $vin,
                'agent_id'        => " ",
                'marks'=>" ",

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

            //var_dump($serializer->serialize($dataRequest, 'json'));
            // exit();
            $contract = $Client->newContract($this->getUser(), $serializer->serialize($dataRequest, 'json'));

            //var_dump($contract['id']);

//return $this->redirectToRoute('contract_show',['id'=>$contract['id']]);
            if ($this->getUser()->getRoles() == ['ROLE_USER']) {
                return $this->redirectToRoute('amount_pay', ['id' => $contract['id']]);
            } else {
                return $this->redirectToRoute('home');
            }
        }
        $pos = strpos($request->getRequestUri(), 'edit');
        //var_dump($request->getRequestUri());
        if ($pos != false) {
            $pos = true;
        } else {
            $pos = false;
        }

        return $this->render('contract/new.html.twig', [
            'pos'  => $pos,
            //'$dataRequest' => $dataRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/amount/{id}", name="amount_pay", methods={"GET","POST"})
     */
    public function amountContract(Request $request, Client $Client, SerializerInterface $serializer): Response
    {
        $contract = new Contract();
        $form     = $this->createForm(AmountType::class, $contract);
        $form->handleRequest($request);

        $id       = $request->get('id');
        $contract = $Client->getContract($this->getUser(), $id);

        $amount = $Client->getAmount($this->getUser(), $id);
        $amount=$amount['amount'];

        if ($form->isSubmitted() && $form->isValid()) {

            $contract['amount'] = $amount;

            $contract = $Client->editContract($this->getUser(), $id, $serializer->serialize($contract, 'json'));

            //var_dump($contract['id']);
//return $this->redirectToRoute('contract_show',['id'=>$contract['id']]);
            return $this->redirectToRoute('profile');

        }


        return $this->render('contract/amount.html.twig', [
            'amount' => $amount,
            'form'     => $form->createView(),
        ]);


    }

    /**
     * @Route("/pay/{id}", name="contract_pay", methods={"GET","POST"})
     */
    public function contractPay(Request $request, Client $Client, SerializerInterface $serializer): Response
    {

        try {

            $contract = new Contract();
            $form     = $this->createForm(AmountType::class, $contract);
            $form->handleRequest($request);

            $id       = $request->get('id');
            $contract = $Client->getContract($this->getUser(), $id);



            $contract['status'] = '3';

            $contract = $Client->editContract($this->getUser(), $id, $serializer->serialize($contract, 'json'));
            // flash message
            $this->addFlash('message', '?????????????? ?????????????? ??????????????');

        } catch (ClientUnavailableException $e) {
            // flash message
            $this->addFlash('message', '???????????????? ????????????!');

        }
        return $this->redirectToRoute('profile');




    }

    /**
     * @Route("/{id}", name="contract_show", methods={"GET"})
     */
    public function show(Request $request, Client $Client): Response
    {
        $id       = $request->get('id');
        $contract = $Client->getContract($this->getUser(), $id);

        if (isset($auto['code']) && $auto['code'] == 404) {
            return $this->render('contract/show.html.twig', [
                'contract' => null,
            ]);
        }

        return $this->render('contract/show.html.twig', [
            'contract' => $contract,
        ]);

    }

    /**
     * @Route("/{id}/edit", name="contract_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $Client, SerializerInterface $serializer): Response
    {
        $id       = $request->get('id');
        $contract = $Client->getContract($this->getUser(), $id);
        $form     = $this->createForm(ContractType::class, $contract);
        $form->get('date_start')->setData(new \DateTime($contract['date_start']));
        $form->get('date_end')->setData(new \DateTime($contract['date_end']));
        $form->get('date_start_one')->setData(new \DateTime($contract['date_start_one']));
        $form->get('date_end_one')->setData(new \DateTime($contract['date_end_one']));
        if (isset($contract['date_start_two']) || $contract['date_start_two']!='null') {
            $form->get('date_start_two')->setData(new \DateTime($contract['date_start_two']));
            $form->get('date_end_two')->setData(new \DateTime($contract['date_end_two']));
        }
        if (isset($contract['date_start_three'])) {
            $form->get('date_start_three')->setData(new \DateTime($contract['date_start_three']));
            $form->get('date_end_three')->setData(new \DateTime($contract['date_end_three']));
        }
        $form->remove('driver_one');
        $form->remove('driver_two');
        $form->remove('driver_three');
        $form->remove('driver_four');
        $form->handleRequest($request);
        //print_r($request->getRequestUri());
        $pos = strpos($request->getRequestUri(), 'edit');
        if (isset($pos)) {
            $pos = true;
        } else {
            $pos = false;
        }

        $auto      = $Client->getAuto($this->getUser(), $contract['auto_vin']);
        $contracts = $auto['contracts'][0]['auto']['users'];
        $date1     = new \DateTime();
        $date2     = new \DateTime($contracts['date_driver']);
        $age       = $date1->diff($date2);
        $age       = $age->format("%Y");

        if ($contracts['gender_driver'] == true) {
            $gender = 1;
        } else {
            $gender = 0;
        }
        if ($contracts['exp_driver'] == true) {
            $exp = 1;
        } else {
            $exp = 0;
        }

        $dataRequest = [
            'age'    => $age,
            'gender' => $gender,
            'exp'    => $exp,
            'marka'  => $auto['marka'],
            'year'   => $auto['year'],
            'engine' => $auto['power'],
            'kbm'    => $contracts['_kbm'],

        ];

        $predict = $Client->predictKNN($this->getUser(), $serializer->serialize($dataRequest, 'json'));
        if($predict['prediction']==1){
            $marks='???????????? ?????????????????? ?????????????? ?????????????????? ?? ???????????? ?????????? ??????????????????????';
        }else{
            $marks='???????????? ?????????????????? ?????????????? ?????????????????? ?????? ?????????? ?????????? ??????????????????????';
        }
        //var_dump($predict);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('date_start_two')->getNormData() == null) {
                $dateTwoStart = "null";
                $dateTwoEnd   = "null";
            } else {
                $dateTwoStart = $form->get('date_start_two')->getNormData();
                $dateTwoEnd   = $form->get('date_end_two')->getNormData();
            }

            if ($form->get('date_start_three')->getNormData() == null) {
                $dateThreeStart = "null";
                $dateThreeEnd   = "null";
            } else {
                $dateThreeStart = $form->get('date_start_three')->getNormData();
                $dateThreeEnd   = $form->get('date_end_three')->getNormData();
            }
            // ???????????? ?? ???????????? billing ?????? ???????????????? ??????????
            $dataRequest = [
                'date_start' => $form->get('date_start')->getNormData(),
                'date_end'   => $form->get('date_end')->getNormData(),

                'purpose'         => $form->get('purpose')->getNormData(),
                'amount'          => $form->get('amount')->getNormData(),
                'diagnostic_card' => $form->get('diagnostic_card')->getNormData(),
                'non_limited'     => $form->get('non_limited')->getNormData(),
                'status'          => "2",
                'auto_vin'        => $contract['auto_vin'],
                'agent_id'        => $this->getUser()->getUsername(),

                'drivers' => $contract['drivers'],
                /*'driver_two'   => $contract['driver_two'],
                'driver_three' => $contract['driver_three'],
                'driver_four'  => $contract['driver_four'],*/
                'marks'=>$marks,
                'date_start_one' => $form->get('date_start_one')->getNormData(),
                'date_end_one'   => $form->get('date_end_one')->getNormData(),

                'date_start_two' => $dateTwoStart,
                'date_end_two'   => $dateTwoEnd,

                'date_start_three' => $dateThreeStart,
                'date_end_three'   => $dateThreeEnd,
            ];

            $contract = $Client->editContract($this->getUser(), $contract['id'],
                $serializer->serialize($dataRequest, 'json'));


            return $this->redirectToRoute('home');
        }

        return $this->render('contract/edit.html.twig', [
            'pos'      => $pos,
            'predict'  => $predict,
            'contract' => $contract,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contract_delete", methods={"POST"})
     */
    public function delete(Request $request,  Client $Client): Response
    {
        $id = $request->get('id');
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            try {
                 $Client->delContract($this->getUser(), $id);
            } catch (ClientUnavailableException $e) {
                // flash message
                $this->addFlash('message', '???????????????? ????????????!');


                return $this->redirectToRoute('profile');
            }
            return $this->redirectToRoute('profile');


        }
    }

    /**
     * @Route("/blanc/{id}", name="blanc", methods={"GET","POST"})
     */
    public function blanc(Request $request, Client $Client)
    {
        $id       = $request->get('id');
        $contract = $Client->getContract($this->getUser(), $id);
        $user     = $Client->getCurrentUser($this->getUser());
        $auto     = $Client->getAuto($this->getUser(), $contract['auto_vin']);
        $users    = $Client->getUsersContract($contract['id'], $this->getUser());

        $users2 = null;
        $users3 = null;
        $users4 = null;
        $tmp    = 1;
        foreach ($users as $User) {
            if ($tmp == 1) {
                $users1 = $tmp.'    '.$User['surname'].'  '.$User['name'].'  '.$User['midName'].'                                                                    '.$User['number'];
            }
            if ($tmp = 2) {
                $users2 = $tmp.'    '.$User['surname'].'  '.$User['name'].'  '.$User['midName'].'                                                                    '.$User['number'];
            }
            if ($tmp == 3) {
                $users3 = $tmp.'    '.$User['surname'].'  '.$User['name'].'  '.$User['midName'].'                                                                    '.$User['number'];
            }
            if ($tmp == 4) {
                $users4 = $tmp.'    '.$User['surname'].'  '.$User['name'].'  '.$User['midName'].'                                                                    '.$User['number'];
            }
            $tmp++;
        }
        //var_dump($users2);
        if (isset($auto['code']) && $auto['code'] == 404) {
            return $this->render('contract/blanc.html.twig', [
                'contract' => null,
            ]);
        }

        return $this->render('contract/blanc.html.twig', [
            'user'     => $user,
            'users1'   => $users1,
            'users2'   => $users2,
            'users3'   => $users3,
            'users4'   => $users4,
            'auto'     => $auto,
            'contract' => $contract,
        ]);

    }
}
