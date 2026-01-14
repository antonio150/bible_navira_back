<?php

namespace App\Controller;

use App\Service\BibleByTopicService;
use App\Service\BibleVerseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class BibleController extends AbstractController
{
    #[Route('/random-verse', name: 'random_verse')]
    public function randomVerse(BibleVerseService $reader): JsonResponse
    {
        $amosa = $reader->getVerseOfTheDay();

        dd($amosa);
    }
}
