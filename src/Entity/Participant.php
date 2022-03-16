<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "Votre mail n'est pas valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     *     message = "Votre prénome est obligatoire."
     * )
     * @Assert\Length(
     * min = 2,
     * max = 50,
     * minMessage = "Votre nom doit contenir au minimum  2 caractéres",
     * maxMessage = "Votre nom doit contenir au maximum 50 caractéres"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     *     message = "Votre prénom est obligatoire."
     * )
     * @Assert\Length(
     * min = 2,
     * max = 50,
     * minMessage = "Votre prénom doit contenir au minimum  2 caractéres"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(
     *     message = "Votre pseudo est obligatoire."
     * )
     * @Assert\Length(
     * min = 2,
     * max = 20,
     * minMessage = "Votre pseudo doit contenir au minimum  2 caractéres"
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Regex(
     *      pattern="/^[0-9]*$/",
     *      message="Votre numéro ne doit contenir que des chiffres."
     * )
     * @Assert\Length(
     * min = 2,
     * max = 20,
     * minMessage = "Votre numéro doit contenir au minimum 2 caractéres"
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Outing::class, inversedBy="participants")
     */
    private $outings;

    /**
     * @ORM\OneToMany(targetEntity=Outing::class, mappedBy="organizer")
     */
    private $organizer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ProfilePictureFileName;

    public function __construct()
    {
        $this->outings = new ArrayCollection();
        $this->organizer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getOutings(): Collection
    {
        return $this->outings;
    }

    public function addOuting(Outing $outing): self
    {
        if (!$this->outings->contains($outing)) {
            $this->outings[] = $outing;
        }

        return $this;
    }

    public function removeOuting(Outing $outing): self
    {
        $this->outings->removeElement($outing);

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getOrganizer(): Collection
    {
        return $this->organizer;
    }

    public function addOrganizer(Outing $organizer): self
    {
        if (!$this->organizer->contains($organizer)) {
            $this->organizer[] = $organizer;
            $organizer->setOrganizer($this);
        }

        return $this;
    }

    public function removeOrganizer(Outing $organizer): self
    {
        if ($this->organizer->removeElement($organizer)) {
            // set the owning side to null (unless already changed)
            if ($organizer->getOrganizer() === $this) {
                $organizer->setOrganizer(null);
            }
        }

        return $this;
    }

    public function getProfilePictureFileName(): ?string
    {
        return $this->ProfilePictureFileName;
    }

    public function setProfilePictureFileName(string $ProfilePictureFileName): self
    {
        $this->ProfilePictureFileName = $ProfilePictureFileName;

        return $this;
    }
}
