<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\Email\AjoutEmailController;
use App\Controller\Email\ConfirmationEmailController;
use App\Controller\Email\UnsubscribeEmailController;
use App\Repository\EmailsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EmailsRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
       
       new Post(
            uriTemplate: '/email/add',
            controller: AjoutEmailController::class
       ),
       new Get(
            uriTemplate: '/email/unsubscribe/{id}',
            controller: UnsubscribeEmailController::class
       ),
       new Get(
            uriTemplate: '/email/confirmation/{id}',
            controller: ConfirmationEmailController::class
       )
 
    ]
)]
class Emails
{
    #[ORM\Id]
    #[ORM\Column( unique: true, type: 'string', length: 36, nullable: false)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?bool $confirmee = null;

    public function __construct()
    {
        $this->id = Uuid::v4()->toRfc4122();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isConfirmee(): ?bool
    {
        return $this->confirmee;
    }

    public function setConfirmee(?bool $confirmee): static
    {
        $this->confirmee = $confirmee;

        return $this;
    }
}
