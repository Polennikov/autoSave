<?php

namespace App\Service;

use App\Exception\ClientUnavailableException;
use App\Security\User;

class Client
{
    private $baseUri;

    public function __construct()
    {
        $this->baseUri = 'main.auto-save.local';
    }

    /**
     * @throws ClientUnavailableException
     */
    public function auth(string $request): array
    {
        // Формирование запроса в сервис Billing
        $curl = curl_init($this->baseUri.'/api/v1/auth');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: '.strlen($request),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте авторизоваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function register(string $request): array
    {

        // Формирование запроса в сервис Billing
        $curl = curl_init($this->baseUri.'/api/v1/register');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: '.strlen($request),
        ]);
        $response = curl_exec($curl);
        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @param   string  $refresh_token
     *
     * @return mixed
     *
     * @throws \App\Exception\ClientUnavailableException
     */
    public function refresh(string $refresh_token): array
    {
        // Формирование запроса в сервис Billing
        $curl = curl_init($this->baseUri.'/api/v1/token/refresh');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $refresh_token);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function getCurrentUser(User $user): array
    {
        // Формирование запроса в сервис Billing
        $curl = curl_init($this->baseUri.'/api/v1/current');
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
        ]);

        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен.
            Попробуйте авторизоваться позднее');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function newAuto(User $user, string $request): array
    {
        // Формирование запроса в сервис Billing
        $curl = curl_init($this->baseUri.'/api/v1/auto/new');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
            'Content-Length: '.strlen($request),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function getAuto(User $user, string $vin): array
    {
        // Запрос в сервис биллинг, получение данных
        $curl = curl_init($this->baseUri.'/api/v1/auto/'.$vin);
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function editAuto(User $user, string $vin, string $request): array
    {

        // Запрос в сервис биллинг, получение данных
        $curl = curl_init($this->baseUri.'/api/v1/auto/'.$vin.'/edit');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
            'Content-Length: '.strlen($request),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function delAuto(User $user, string $vin): array
    {

        // Запрос в сервис биллинг, получение данных
        $curl = curl_init($this->baseUri.'/api/v1/auto/'.$vin.'/delete');
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function newContract(User $user, string $request): array
    {
        var_dump($request);
        //exit();
        // Формирование запроса в сервис Billing
        $curl = curl_init($this->baseUri.'/api/v1/contract/new');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
            'Content-Length: '.strlen($request),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function editContract(User $user, string $id, string $request): array
    {
        var_dump($request);
        //exit();
        // Формирование запроса в сервис Billing
        $curl = curl_init($this->baseUri.'/api/v1/contract/'.$id.'/edit');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
            'Content-Length: '.strlen($request),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new BillingUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function getContract(User $user, string $id): array
    {
        // Запрос в сервис биллинг, получение данных
        $curl = curl_init($this->baseUri.'/api/v1/contract/'.$id);
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function getContractAll(User $user): array
    {
        // Запрос в сервис биллинг, получение данных
        $curl = curl_init($this->baseUri.'/api/v1/contract');
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function getContractAgent(User $user): array
    {
        // Запрос в сервис биллинг, получение данных
        $curl = curl_init($this->baseUri.'/api/v1/contract/agent');
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * @throws ClientUnavailableException
     */
    public function getUsersContract(User $user): array
    {
        // Запрос в сервис биллинг, получение данных
        $curl = curl_init($this->baseUri.'/api/v1/contract/agent');
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }



    /**
     * @throws ClientUnavailableException
     */
    public function getIndexKt(User $user): array
    {

        // Запрос в сервис биллинг, получение данных
        $curl = curl_init($this->baseUri.'/api/v1/books/kt');
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$user->getApiToken(),
        ]);
        $response = curl_exec($curl);

        // Ошибка биллинга
        if (!$response) {
            throw new ClientUnavailableException('Сервис временно недоступен. Попробуйте зарегистироваться позднее.');
        }

        curl_close($curl);

        // Ответ от сервиса
        $result = json_decode($response, true);

        return $result;
    }

}
