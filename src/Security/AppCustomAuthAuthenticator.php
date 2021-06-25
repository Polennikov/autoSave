<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\ClientUnavailableException;
use App\Service\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Serializer\SerializerInterface;

class AppCustomAuthAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $serializer;
    private $Client;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        SerializerInterface $serializer,
        Client $Client
    )
    {$this->serializer = $serializer;
        $this->Client = $Client;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        // Данные для запроса к сервису
        $data = [
            'username' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        $request = $this->serializer->serialize($data, 'json');

        // Запрос к сервису оплаты для получения токена авторизации
        try {
            $result = $this->Client->auth($request);
        } catch (ClientUnavailableException $e) {
            throw new CustomUserMessageAuthenticationException($e->getMessage());
        }

        // Возврат ошибок из запроса
        if (isset($result['code']) && 401 === $result['code']) {
            throw new CustomUserMessageAuthenticationException($result['message']);
        }

        // Проверка ответа и формирование пользователя
        if ($result) {
            $response = explode('.', $result['token']);
            $payload = json_decode(base64_decode($response[1]), true);
            $user = new \App\Security\User();
            $user->setApiToken($result['token']);
            $user->setEmail($payload['username']);
            $user->setRoles($payload['roles']);
            $user->setRefreshToken($result['refresh_token']);
            try {
                $result = $this->Client->getCurrentUser($user);
            } catch (ClientUnavailableException $e) {
                throw new \Exception($e->getMessage());
            }
           // $user->setBalance($result['balance']);

            return $user;
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
            return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
