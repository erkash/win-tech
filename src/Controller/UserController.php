<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\UserRequest;
use App\Exception\UserExistException;
use App\Service\AccessToken\AccessTokenService;
use App\Service\User\ApiUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserController extends AbstractController
{
    #[Route('/api/user/register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: Response::HTTP_BAD_REQUEST
        )] UserRequest $registerRequest,
        ApiUserService $apiUserService,
        AccessTokenService $accessTokenService
    ): JsonResponse {
        try {
            $user = $apiUserService->create($registerRequest);
            $accessToken = $accessTokenService->create($user);
            return new JsonResponse(
                [
                    'status' => 'success',
                    'access_token' => $accessToken->getToken()
                ],
                Response::HTTP_OK
            );
        } catch (UserExistException $e) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/api/user/login', methods: ['POST'])]
    public function login(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: Response::HTTP_BAD_REQUEST
        )] UserRequest $loginRequest,
        ApiUserService $apiUserService
    ): Response {
        $username = $loginRequest->username;
        $password = $loginRequest->password;

        try {
            $user = $apiUserService->getUserByCredentials($username, $password);
        } catch (UserNotFoundException|InvalidPasswordException $e) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $accessToken = $user->getAccessToken();

        return new JsonResponse(
            [
                'status' => 'success',
                'access_token' => $accessToken->getToken()
            ],
            Response::HTTP_OK
        );
    }


}
