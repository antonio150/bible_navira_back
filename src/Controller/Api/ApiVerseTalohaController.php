<?php

namespace App\Controller\Api;

use App\Service\CustomBibleVerseTestametaTalohaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ApiVerseTalohaController extends AbstractController
{
    #[Route('/api/versets/testameta_taloha', name: 'api_versets_taloha', methods: ['GET'])]
    public function getVersets(Request $request, CustomBibleVerseTestametaTalohaService $bibleService): JsonResponse
    {
        $book = $request->query->get('book');    
        $chapter = $request->query->get('chapter'); 
        $start = $request->query->get('start');  
        $end = $request->query->get('end');     

        if (!$book || !$chapter || !$start) {
            return $this->json(['error' => 'Paramètres manquants'], 400);
        }

        $result = $bibleService->getVersesTestametaTaloha(
            $book,
            (int)$chapter,
            (int)$start,
            $end ? (int)$end : null
        );

        return $this->json($result);
    }
}
