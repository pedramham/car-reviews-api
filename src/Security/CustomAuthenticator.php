<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CustomAuthenticator extends JWTAuthenticator
{
    public function supports(Request $request): ?bool
    {
        // TODO: implement logic to decide if the authenticator should be used for the request
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // TODO: implement logic to execute on successful authentication
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // TODO: implement logic to execute on failed authentication
    }
}
