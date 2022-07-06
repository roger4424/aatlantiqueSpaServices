<?php

namespace App\Entity;

use App\Repository\DeviRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
// use App\Component\Validator\Constraints as DeviAssert;

#[ORM\Entity(repositoryClass: DeviRepository::class)]
/**
 * @Vich\Uploadable
 */
class Devi
{
    public function __construct()
    {
        //au moment ou l'objet est construit creation de la date
        $this->createdAt = new \DateTimeImmutable();
//        $this->interventionAt = new \DateTime();
        $this->contacts = new ArrayCollection();
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

    private $phone;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank()]
    #[Email()]
    private $email;

    #[ORM\Column(type: 'datetime_immutable')]
    #[NotBlank()]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank()]
    private $type;

    #[ORM\Column(type: 'datetime_immutable')]
    #[NotBlank()]
    private $interventionAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $status;

    #[ORM\Column(type: 'text', length: 255)]
    private $adresse;

    /**
     * @Vich\UploadableField(mapping="devis_file", fileNameProperty="devisFileName")
     */
    #[Assert\File(maxSize: "5M", mimeTypes: ["image/jpeg", "image/png", "image/webp"], maxSizeMessage: "Le fichier est trop volumineux. Taille max 5Mo")]
    private ?File $devisFile = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $devisFileName = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $inTrash = false;

    #[ORM\ManyToMany(targetEntity: Contact::class, mappedBy: 'devis')]
    private $contacts;

    #[ORM\OneToOne(inversedBy: 'devi', targetEntity: Evenement::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $evenement;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isInterventionValidated;


    /**  #[ORM\Column(type: 'text', nullable: true)]
    private $marque;*/

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

     public function getInterventionAt(): ?\DateTimeImmutable
    {
        return $this->interventionAt;
    }

    public function setInterventionAt(\DateTimeImmutable $interventionAt): self
    {
        $this->interventionAt = $interventionAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

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

    public function setDevisFileName(?string $devisFileName):void
    {
        $this->devisFileName = $devisFileName;
    }

    public function getDevisFileName(): ?string
    {
        return $this->devisFileName;
    }

    public function setDevisFile(?File $devisFile= null)
    {
        $this->devisFile = $devisFile;
        if (null !== $devisFile){
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getDevisFile(): ?File
    {
        return $this->devisFile;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->addDevi($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            $contact->removeDevi($this);
        }

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getIsInterventionValidated(): ?bool
    {
        return $this->isInterventionValidated;
    }

    public function setIsInterventionValidated(?bool $isInterventionValidated): self
    {
        $this->isInterventionValidated = $isInterventionValidated;

        return $this;
    }

    public function getFullName(): string{
        return $this->prenom.' '.$this->nom;
    }


}
