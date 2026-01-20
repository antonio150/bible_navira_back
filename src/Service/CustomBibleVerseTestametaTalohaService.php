<?php

namespace App\Service;

class CustomBibleVerseTestametaTalohaService
{
    public function getVersesTestametaTaloha(string $book, int $chapter, int $start, ?int $end = null): array
    {
        $projectDir = dirname(__DIR__, 2);
        $path = $projectDir . '/src/baiboly-json/Testameta taloha/' . $book . '.json';

        if (!file_exists($path)) {
            return ['error' => 'Livre introuvable'];
        }

        $data = json_decode(file_get_contents($path), true);

        if (!isset($data[$chapter])) {
            return ['error' => 'Chapitre introuvable'];
        }

        $verses = [];
        $end = $end ?? $start;

        for ($i = $start; $i <= $end; $i++) {
            if (isset($data[$chapter][$i])) {
                $verses[] = $i . ' ' . $data[$chapter][$i];
            }
        }

        return [
            'book' => $book,
            'chapter' => $chapter,
            'start' => $start,
            'end' => $end,
            'text' => implode(' ', $verses),
        ];
    }

    public function getRandomVerseTestametTaloha(): array
    {
        $books = [
            'genesisy.json','eksodosy.json','levitikosy.json','nomery.json','deoteronomia.json',
            'josoa.json','mpitsara.json','rota.json','samoela-voalohany.json','samoela-faharoa.json',
            'mpanjaka-voalohany.json','mpanjaka-faharoa.json','tantara-voalohany.json','tantara-faharoa.json',
            'ezra.json','nehemia.json','estera.json','joba.json','salamo.json','ohabolana.json',
            'mpitoriteny.json','tononkirani-solomona.json','isaia.json','jeremia.json','fitomaniana.json',
            'ezekiela.json','daniela.json','hosea.json','joela.json','amosa.json','obadia.json',
            'jona.json','mika.json','nahoma.json','habakoka.json','zefania.json','hagay.json',
            'zakaria.json','malakia.json'
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