<?php 
namespace App\Controller\Email;

use App\Repository\EmailsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UnsubscribeEmailController extends AbstractController
{
    public function __invoke(EntityManagerInterface $em, EmailsRepository $emailsrepo, string $id):Response
    {
       
        if (!$id) {
            return new Response('Lien invalide.', 400);
        }

        $email = $emailsrepo->findOneBy(['id' => $id]);


        $em->remove($email);
        $em->flush();

        return $this->render('unsubscribe/confirmed.html.twig', [
            'email' => $email->getEmail()
        ]);
    }
}