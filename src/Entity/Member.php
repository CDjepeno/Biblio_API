<?php

namespace App\Entity;

use App\Entity\Role;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MemberRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 * @ApiResource()
 * @UniqueEntity("mail", message="Il existe déja ce mail {{ value }} veuillez saisir un autre mail")
 * 
 */
class Member implements UserInterface
{
    
    /**
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $communeCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=BookRent::class, mappedBy="member")
     */
    private $bookRents;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="members")
     */
    private $roles;   

    public function __construct()
    {
        $this->bookRents = new ArrayCollection();
        $this->roles     = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCommuneCode(): ?string
    {
        return $this->communeCode;
    }

    public function setCommuneCode(?string $communeCode): self
    {
        $this->communeCode = $communeCode;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|BookRent[]
     */
    public function getBookRents(): Collection
    {
        return $this->bookRents;
    }

    public function addBookRent(BookRent $bookRent): self
    {
        if (!$this->bookRents->contains($bookRent)) {
            $this->bookRents[] = $bookRent;
            $bookRent->setMember($this);
        }

        return $this;
    }

    public function removeBookRent(BookRent $bookRent): self
    {
        if ($this->bookRents->removeElement($bookRent)) {
            // set the owning side to null (unless already changed)
            if ($bookRent->getMember() === $this) {
                $bookRent->setMember(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles->map(function($role){
            return $role->getTitle();
        })->toArray();
        $roles[] = "ROLE_USER";
        // dd($roles);
        return $roles;
    }
   
    public function getSalt(){}

    public function getUsername()
    {
        return $this->mail;
    }

    public function eraseCredentials(){}

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->getMembers($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->removeElement($role)) {
            // set the owning side to null (unless already changed)
            if ($role->getMembers() === $this) {
                $role->getMembers(null);
            }
        }

        return $this;
    }

}