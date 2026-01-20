<?php

namespace App\Controller\Api;

use App\Service\CustomBibleVerseTestametaTalohaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiRandomVerseTestametaTalohaController extends AbstractController
{
    #[Route('/api/testameta-taloha/verset-random', name: 'api_random_verse_taloha', methods: ['GET'])]
    public function random(CustomBibleVerseTestametaTalohaService $service): JsonResponse
    {
        return $this->json($service->getRandomVerseTestametTaloha());
    }
}
