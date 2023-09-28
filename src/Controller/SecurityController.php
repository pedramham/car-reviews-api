<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends AbstractController
{
    #[Route('auth', name: 'auth', methods: ['POST'])]
    public function login(Request $request, UserInterface $user): JsonResponse
    {
        // get the email and password from the request body
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // check if the email and password match the user entity
        if ($email === $user->getUserIdentifier() && password_verify($password, $user->getPassword())) {
            // generate a JWT token using the LexikJWTAuthenticationBundle service
            $token = $this->container->get('lexik_jwt_authentication.jwt_manager')->create($user);

            // return a JSON response with the token
            return new JsonResponse(['token' => $token]);
        }

        // return a JSON response with an error message
        return new JsonResponse(['message' => 'Invalid credentials.'], 401);
    }
}
