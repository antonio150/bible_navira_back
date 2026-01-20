<?php

namespace App\Service;

class CustomBibleVerseTestametaVaovaoService
{
    public function getVersesTestametaVaovao(string $book, ?int $chapter = null, ?int $start = null, ?int $end = null): array
    {
        $projectDir = dirname(__DIR__, 2);
        $path = $projectDir . '/src/baiboly-json/Testameta vaovao/' . $book . '.json';

        if (!file_exists($path)) {
            return ['error' => 'Livre introuvable'];
        }

        $data = json_decode(file_get_contents($path), true);

        // Si aucun chapitre n'est précisé, on renvoie tout le livre
        if ($chapter === null) {
            $verses = [];
            foreach ($data as $chapNum => $chapVerses) {
                foreach ($chapVerses as $verseNum => $verseText) {
                    $verses[] = ["$chapNum:$verseNum" => $verseText];
                }
            }

            return [
                'book' => $book,
                'text' => $verses,
            ];
        }

        // Vérifie que le chapitre existe
        if (!isset($data[$chapter])) {
            return ['error' => 'Chapitre introuvable'];
        }

        $chapterVerses = $data[$chapter];

        // Si start n'est pas défini, on prend depuis le premier verset
        $start = $start ?? min(array_keys($chapterVerses));
        // Si end n'est pas défini, on prend jusqu'au dernier verset
        $end = $end ?? max(array_keys($chapterVerses));

        $verses = [];
        for ($i = $start; $i <= $end; $i++) {
            if (isset($chapterVerses[$i])) {
                $verses[] = [$i => $chapterVerses[$i]];
            }
        }

        return [
            'book' => $book,
            'chapter' => $chapter,
            'start' => $start,
            'end' => $end,
            'text' => $verses,
        ];
    }


    public function getRandomVerseTestamentVaovao(): array
    {
        $books = [
            'matio.json',
            'marka.json',
            'lioka.json',
            'jaona.json',
            'asany-apostoly.json',
            'romanina.json',
            '1-korintianina.json',
            '2-korintianina.json',
            'galatianina.json',
            'efesianina.json',
            'filipianina.json',
            'kolosianina.json',
            '1-tesalonianina.json',
            '2-tesalonianina.json',
            '1-timoty.json',
            '2-timoty.json',
            'titosy.json',
            'filemona.json',
            'hebreo.json',
            'jakoba.json',
            '1-petera.json',
            '2-petera.json',
            '1-jaona.json',
            '2-jaona.json',
            '3-jaona.json',
            'joda.json',
            'apokalypsy.json'
        ];

        $projectDir = dirname(__DIR__, 2);
        $basePath = $projectDir . '/src/baiboly-json/Testameta vaovao/';

        // Livre aléatoire
        $bookFile = $books[array_rand($books)];
        $data = json_decode(file_get_contents($basePath . $bookFile), true);

        // Chapitre aléatoire
        $chapterNumber = array_rand($data);
        $chapter = $data[$chapterNumber];

        // Verset aléatoire
        $verseNumber = array_rand($chapter);
        $text = $chapter[$verseNumber];

        return [
            'testament' => 'vaovao',
            'book' => str_replace('.json', '', $bookFile),
            'chapter' => (int)$chapterNumber,
            'verse' => (int)$verseNumber,
            'text' => $verseNumber . ' ' . $text,
        ];
    }
}
