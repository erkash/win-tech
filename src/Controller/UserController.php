<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\User\ApiUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{


    #[Route(
        '/api/user/login',
        name: 'api_user_login',
        methods: ['POST']
    )]
    public function login(Request $request, ApiUserService $apiUserService): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $apiUserService->getUserByCredentials($email, $password);


    }
}
