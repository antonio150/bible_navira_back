<?php

namespace App\Command;

use App\Repository\EmailsRepository;
use App\Repository\UtilisateurRepository;
use App\Service\MailDocumentService;
use App\Service\BibleVerseService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:send-daily-verse',
    description: 'Envoie le verset du jour aux utilisateurs abonnés',
)]
class SendDailyVerseCommand extends Command
{
    public function __construct(
        private EmailsRepository $emails,
        private MailDocumentService $mailDocumentService,
        private BibleVerseService $bibleVerseService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 1️⃣ Récupérer les utilisateurs abonnés
        $emails = $this->emails->findBy(["confirmee" => true]);

       
        // 2️⃣ Récupérer le verset du jour
        $verse = $this->bibleVerseService->getVerseOfTheDay();

        $book = $verse['book'];
        $chapter = $verse['chapter'];
        $veset = $verse['verse'];
        $contenu = $verse['text'];

     
        foreach ($emails as $email) {
            // 👉 adapter URL selon ton appli
            $unsubscribeUrl = 'https://back.baiboly.antonionavira.mg/api/email/unsubscribe/' . $email->getId();
            try{
            $this->mailDocumentService->send(
                toEmail: $email->getEmail(),
                veset: $veset,
                contenu: $contenu,
                unsubscribe_url: $unsubscribeUrl,
                book: $book,
                chapter: $chapter
            );}
            catch(Exception $e){
                $output->writeln($e);
            }

            $output->writeln("📧 Email envoyé à : " . $email->getEmail(),);
     
        }
        $output->writeln('🎉 Terminé');
        return Command::SUCCESS;
    }
}
