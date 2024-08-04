<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\WalletCreateRequest;
use App\Entity\User;
use App\Entity\Wallet;
use App\Service\Wallet\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class WalletController extends AbstractController
{
    #[Route('/api/wallet/create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: Response::HTTP_BAD_REQUEST
        )] WalletCreateRequest $walletCreateRequest,
        WalletService $walletService,
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getWallet()) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'A wallet already exists for this user.'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $wallet = $walletService->createWallet($walletCreateRequest, $user);

        return new JsonResponse(
            [
                'status' => 'success',
                'wallet_id' => $wallet->getId()
            ],
            Response::HTTP_OK
        );
    }

    #[Route(
        '/api/wallet/{id}/balance',
        requirements: ['id' => '\d+'],
        methods: ['GET']
    )]
    public function getBalance(Wallet $wallet): Response
    {
        return new JsonResponse(
            [
                'status' => 'success',
                'balance' => $wallet->getFormattedBalance()
            ],
            Response::HTTP_OK
        );
    }
}
