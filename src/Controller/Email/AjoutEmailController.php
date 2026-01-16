<?php 

namespace App\Controller\Email;

use App\Entity\Emails;
use App\Service\MailConfirmationService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class AjoutEmailController extends AbstractController
{
    public function __construct(private MailConfirmationService $confirmEmail)
    {
        
    }

    public function __invoke(  Request $request, EntityManagerInterface $entityManager):JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = trim($data['email'] ?? '');

        if(!isset($email))
        {
            return new JsonResponse([
                "code" => 303,
                "message" => "email non trouvé", 
            ]);
        }
       
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          
        } else {
            return new JsonResponse([
                "code" => 304,
                "message" => "email non valide", 
            ]);
        }

        $verifMailExiste = $entityManager->getRepository(Emails::class)->findBy(["email"=>$email, "confirmee"=>true]);
        if(!empty($verifMailExiste))
        {
           return new JsonResponse([
                "code" => 305,
                "message" => "email existe deja", 
            ]); 
        }

        $verifMailConfirmee = $entityManager->getRepository(Emails::class)->findBy(["email"=>$email]);
        if(empty($verifMailConfirmee))
        {
            $mail = new Emails();
            $mail->setEmail($email);
            $mail->setConfirmee(false);
            $entityManager->persist($mail);
            $entityManager->flush();

            $confirmationUrl = "https://back.baiboly.antonionavira.mg/api/email/confirmation/".$mail->getId();

            $this->confirmEmail->send(
                    toEmail: $mail->getEmail(),
                    confirmation_url: $confirmationUrl,
                );
        }else{
            $mail = $entityManager->getRepository(Emails::class)->findOneBy(["email"=>$email]);
            $confirmationUrl = "https://back.baiboly.antonionavira.mg/api/email/confirmation/".$mail->getId();

            $this->confirmEmail->send(
                toEmail: $email,
                confirmation_url: $confirmationUrl,
            ); 
        }
        return new JsonResponse([
            "code" => 200,
            "message" => "Une mail de confirmation a été envoyé à ".$email.". Veuillez accepter cet email."
        ]);
    }
}