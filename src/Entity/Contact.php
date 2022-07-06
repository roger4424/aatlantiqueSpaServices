<?php

namespace App\Entity;


use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{

    public function __construct()
    {
        /* construc auto date heure de creation */
        $this->createdAt = new \DateTimeImmutable();
        $this->devis = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;



    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank()]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank()]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank()]
    private $adresse;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank()]
    private $ville;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank()]
    private $phone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Email]
    #[NotBlank()]
    private $email;

    #[ORM\Column(type: 'text', nullable: true)]
    private $complement;

    #[ORM\Column(type: 'text')]
    #[NotBlank()]
    private $message;

    #[ORM\Column(type: 'string', length: 255)]
    private $zipcode;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private $inTrash = false;

    #[ORM\ManyToMany(targetEntity: Devi::class, inversedBy: 'contacts')]
    private $devis;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContact(): ?string
    {
        return $this->Contact;
    }

    public function setContact(string $Contact): self
    {
        $this->Contact = $Contact;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getInTrash(): ?bool
    {
        return $this->inTrash;
    }

    public function setInTrash(bool $inTrash): self
    {
        $this->inTrash = $inTrash;
        return $this;
    }

    /**
     * @return Collection|Devi[]
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devi $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis[] = $devi;
        }

        return $this;
    }

    public function removeDevi(Devi $devi): self
    {
        $this->devis->removeElement($devi);

        return $this;
    }
}
