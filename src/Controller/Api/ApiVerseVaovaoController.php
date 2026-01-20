<?php

namespace App\Controller\Api;


use App\Service\CustomBibleVerseTestametaVaovaoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ApiVerseVaovaoController extends AbstractController
{
    #[Route('/api/versets/testameta_vaovao', name: 'api_versets_vaovao', methods: ['POST'])]
    public function getVersets(Request $request, CustomBibleVerseTestametaVaovaoService $bibleService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $book = $data['book'] ?? null;   
        $chapter = $data['chapter'] ?? null;
        $start = $data['start'] ?? null;
        $end = $data['end'] ?? null;

        if (!$book) {
            return $this->json([
                'statut' => 400,
                "message" => 'Livre manquant',
                'data' => null
                ], 400);
        }

        if (!$chapter) {
            return $this->json([
                'statut' => 400,
                "message" => 'Chapitre manquant',
                'data' => null
                ], 400);
        }

        if (!array_key_exists('start', $data)) {
            return $this->json([
                'statut' => 400,
                "message" => 'Le paramètre "start" est manquant dans le JSON',
                'data' => null
            ], 400);
        }

        $result = $bibleService->getVersesTestametaVaovao(
            $book,
            (int)$chapter,
            (int)$start,
            $end ? (int)$end : null
        );

        if (isset($result['error'])) {
            return $this->json([
                'statut' => 404,
                'message' => $result['error'],
                'data' => null
            ], 404);
        }

        return $this->json([
            'statut' => 200,
            "message" => 'Données récupérées avec succès',
            'data' => $result
        ]);
    }
}
