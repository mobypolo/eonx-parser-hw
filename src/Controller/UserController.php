<?php

namespace App\Controller;

use App\Interfaces\IParsedUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/customers', name: 'customers.')]
class UserController extends AbstractController
{

    public function __construct(private IParsedUserRepository $user_repo)
    {
    }

    #[Route('', name: 'index')]
    public function index(): JsonResponse
    {
        return $this->json([
            'collection' => $this->user_repo->findAll(),
        ]);
    }

    #[Route('/{customerId}', name: 'detail')]
    public function detail(int $customerId): JsonResponse
    {
        $res = $this->user_repo->find($customerId);

        if ($res === null)
            throw new NotFoundHttpException(404);

        return $this->json([
            'collection' => $res,
        ]);
    }
}
