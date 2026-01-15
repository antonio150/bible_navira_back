<?php

namespace App\Service;

use DateTime;
use IntlDateFormatter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Validator\Constraints\Date;

class MailConfirmationService
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function send(
        ?string $toEmail,
        string $confirmation_url,
    ): void {
        // S'il y a déjà un destinataire, on envoie à lui + CC antoniorollande
        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::LONG,
            IntlDateFormatter::SHORT
        );

        $dateActuel = new DateTime();
        $email = (new TemplatedEmail())
            ->from($_ENV['SENDER_MAIL'])
            ->to($toEmail)
            ->subject('Confirmation email du '.$formatter->format($dateActuel))
            ->htmlTemplate('emails/welcome.html.twig');

        $email->embedFromPath(
            __DIR__ . '/../../public/img_default/navira.png',
            'logo_navira'
        );

        $email->context([
            'image_url' => 'cid:logo_navira',
            'toEmail' => $toEmail,
            'confirmation_url' => $confirmation_url
        ]);


        $this->mailer->send($email);
    }
}
