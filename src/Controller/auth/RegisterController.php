<?php

namespace App\Controller\auth;

use App\Dto\auth\RegisterDTO;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_auth')]
class RegisterController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    ){}

    #[Route('/auth', name: '', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload]RegisterDTO $dto)
    {
        try {

            $user = $this->userService->createFromRequest($dto);

            return $this->json([
                'success' => true,
                'message'=>"user created",
            ], Response::HTTP_CREATED);

        } catch(\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
