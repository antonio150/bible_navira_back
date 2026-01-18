<?php

namespace App\Service;

use DateTime;
use IntlDateFormatter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Validator\Constraints\Date;

class MailDocumentService
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function send(
        ?string $toEmail,
        string $startVerse,
        string $endVerse,
        string $contenu,
        string $unsubscribe_url,
        string $book,
        string $chapter
    ): void {
        // S'il y a déjà un destinataire, on envoie à lui + CC antoniorollande
        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );

        $dateActuel = new DateTime();
        $email = (new TemplatedEmail())
            ->from($_ENV['SENDER_MAIL'])
            ->to($toEmail)
            ->subject('Verset du jour du '.$formatter->format($dateActuel))
            ->htmlTemplate('emails/verset.html.twig');

        $email->embedFromPath(
            __DIR__ . '/../../public/img_default/navira.png',
            'logo_navira'
        );

        $email->context([
            'image_url' => 'cid:logo_navira',
            'contenu' => $contenu,
            'startVerse' => $startVerse,
            'endVerse' => $endVerse,
            'book' => $book,
            'chapter' => $chapter,
            'unsubscribe_url' => $unsubscribe_url
        ]);


        $this->mailer->send($email);
    }
}
