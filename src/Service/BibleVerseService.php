<?php
namespace App\Service;

class BibleVerseService
{
    public function getVersesOfTheDay(int $perDay = 5): array
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

        $start = new \DateTime('2026-01-01');
        $today = new \DateTime('today');
        $days = $start->diff($today)->days;

        foreach ($books as $bookFile) {

            $json = file_get_contents($basePath . $bookFile);
            $data = json_decode($json, true);

            // Total versets dans le livre
            $totalVerses = 0;
            foreach ($data as $chapter) {
                $totalVerses += count($chapter);
            }

            $blocks = (int) ceil($totalVerses / $perDay);

            if ($days >= $blocks) {
                $days -= $blocks;
                continue;
            }

            // On est dans le bon livre
            $startIndex = $days * $perDay;
            $currentIndex = 0;

            $versesText = [];
            $chapterResult = null;
            $startVerse = null;
            $endVerse = null;

            foreach ($data as $chapterNumber => $chapter) {
                foreach ($chapter as $verseNumber => $text) {

                    if ($currentIndex >= $startIndex && count($versesText) < $perDay) {

                        if ($chapterResult === null) {
                            $chapterResult = $chapterNumber;
                            $startVerse = $verseNumber;
                        }

                        $versesText[] = "{$verseNumber} {$text}";
                        $endVerse = $verseNumber;
                    }

                    $currentIndex++;

                    if (count($versesText) >= $perDay) {
                        break 2;
                    }
                }
            }

            return [
                'book'       => str_replace('.json', '', $bookFile),
                'chapter'    => $chapterResult,
                'startVerse' => $startVerse,
                'endVerse'   => $endVerse,
                'text'       => implode(' ', $versesText),
            ];
        }

        return [
            'book'       => null,
            'chapter'    => null,
            'startVerse' => null,
            'endVerse'   => null,
            'text'       => 'Tonga any amin’ny faran’ny Baiboly 😊',
        ];
    }
}
