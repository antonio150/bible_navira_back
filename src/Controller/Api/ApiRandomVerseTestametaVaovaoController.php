<?php

namespace App\Controller\Api;

use App\Service\CustomBibleVerseTestametaVaovaoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiRandomVerseTestametaVaovaoController extends AbstractController
{
    #[Route('/api/testameta-vaovao/verset-random', name: 'api_random_verse_vaovao', methods: ['GET'])]
    public function random(CustomBibleVerseTestametaVaovaoService $service): JsonResponse
    {
        return $this->json($service->getRandomVerseTestamentVaovao());
    }
}
