<?php

namespace App\Service;

class CustomBibleVerseTestametaTalohaService
{
    public function getVersesTestametaTaloha(string $book, ?int $chapter = null, ?int $start = null, ?int $end = null): array
    {
        $projectDir = dirname(__DIR__, 2);
        $path = $projectDir . '/src/baiboly-json/Testameta taloha/' . $book . '.json';

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


    public function getRandomVerseTestametTaloha(): array
    {
        $books = [
            'genesisy.json',
            'eksodosy.json',
            'levitikosy.json',
            'nomery.json',
            'deoteronomia.json',
            'josoa.json',
            'mpitsara.json',
            'rota.json',
            'samoela-voalohany.json',
            'samoela-faharoa.json',
            'mpanjaka-voalohany.json',
            'mpanjaka-faharoa.json',
            'tantara-voalohany.json',
            'tantara-faharoa.json',
            'ezra.json',
            'nehemia.json',
            'estera.json',
            'joba.json',
            'salamo.json',
            'ohabolana.json',
            'mpitoriteny.json',
            'tononkirani-solomona.json',
            'isaia.json',
            'jeremia.json',
            'fitomaniana.json',
            'ezekiela.json',
            'daniela.json',
            'hosea.json',
            'joela.json',
            'amosa.json',
            'obadia.json',
            'jona.json',
            'mika.json',
            'nahoma.json',
            'habakoka.json',
            'zefania.json',
            'hagay.json',
            'zakaria.json',
            'malakia.json'
        ];

        $projectDir = dirname(__DIR__, 2);
        $basePath = $projectDir . '/src/baiboly-json/Testameta taloha/';

        // livre aléatoire
        $bookFile = $books[array_rand($books)];
        $data = json_decode(file_get_contents($basePath . $bookFile), true);

        // chapitre aléatoire
        $chapterNumber = array_rand($data);
        $chapter = $data[$chapterNumber];

        // verset aléatoire
        $verseNumber = array_rand($chapter);
        $text = $chapter[$verseNumber];

        return [
            'book' => str_replace('.json', '', $bookFile),
            'chapter' => (int)$chapterNumber,
            'verse' => (int)$verseNumber,
            'text' => $verseNumber . ' ' . $text
        ];
    }
}
