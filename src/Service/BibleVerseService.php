<?php

namespace App\Service;

class BibleVerseService
{
    public function getVerseOfTheDay(): array
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
            'malakia.json',
        ];

        $projectDir = dirname(__DIR__, 2);
        $basePath = $projectDir . '/src/baiboly-json/Testameta taloha/';

        // Date de départ (change si tu veux)
        $start = new \DateTime('2026-01-01');
        $today = new \DateTime('today');

        $days = $start->diff($today)->days;

        foreach ($books as $book) {

            $json = file_get_contents($basePath . $book);
            $data = json_decode($json, true);

            // compter tous les versets du livre
            $count = 0;
            foreach ($data as $chapter) {
                $count += count($chapter);
            }

            // encore trop de jours → passer au livre suivant
            if ($days >= $count) {
                $days -= $count;
                continue;
            }

            // verset trouvé dans ce livre
            foreach ($data as $chapterNumber => $chapter) {
                foreach ($chapter as $verseNumber => $text) {

                    if ($days === 0) {
                        return [
                            'book' => str_replace('.json', '', $book),
                            'chapter' => $chapterNumber,
                            'verse' => $verseNumber,
                            'text' => $text,
                        ];
                    }

                    $days--;
                }
            }
        }

        return [
            'book' => null,
            'chapter' => null,
            'verse' => null,
            'text' => 'Tonga any amin’ny faran’ny Baiboly 😊',
        ];
    }
}
