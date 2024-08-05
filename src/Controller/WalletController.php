<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\TransactionRequest;
use App\DTO\WalletRequest;
use App\Entity\User;
use App\Entity\Wallet;
use App\Service\Transaction\TransactionService;
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
        #[MapRequestPayload(acceptFormat: 'json',)] WalletRequest $walletRequest,
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

        $wallet = $walletService->createWallet($walletRequest, $user);

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
    public function getBalance(Wallet $wallet): JsonResponse
    {
        return new JsonResponse(
            [
                'status' => 'success',
                'balance' => $wallet->getFormattedBalance()
            ],
            Response::HTTP_OK
        );
    }

    #[Route(
        '/api/wallet/{id}/update-balance',
        requirements: ['id' => '\d+'],
        methods: ['POST']
    )]
    public function updateBalance(
        #[MapRequestPayload(acceptFormat: 'json')] TransactionRequest $transactionRequest,
        Wallet $wallet,
        TransactionService $transactionService,
        WalletService $walletService
    ): JsonResponse {
        $transaction = $transactionService->create($transactionRequest, $wallet);
        $walletService->updateBalance($transaction, $wallet);

        return new JsonResponse(
            [
                'status' => 'success',
                'balance' => $wallet->getFormattedBalance()
            ],
            Response::HTTP_OK
        );
    }
}
