<?php 

namespace App\Controller\Email;

use App\Entity\Emails;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ConfirmationEmailController extends AbstractController
{

    public function __invoke(Request $request, EntityManagerInterface $entityManager, string $id):Response
    {
        $data = json_decode($request->getContent(), true);

        $mail = $entityManager->getRepository(Emails::class)->find($id);

        if ($mail === null) {
            return new JsonResponse([
                "code" => 404,
                "message" => "Cet id n'existe pas"
            ], 404);
        }

        $email = $mail->getEmail();

        $mail->setConfirmee(true);
        $entityManager->persist($mail);
        $entityManager->flush();

        return $this->render('emails/confirmation.html.twig', [
            'email' => $email
        ]);
    }
}