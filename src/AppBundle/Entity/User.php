<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="Looks like you already have an account")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isScientist = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $universityName = null;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    private $avatarUri = null;

    /**
     * @Assert\NotBlank(groups={"Registration"})
     */
    private $plainPassword;

    /**
     * @ORM\ManyToMany(targetEntity="Genus", mappedBy="genusScientists")
     * @ORM\OrderBy({"name"="ASC"})
     */
    private $studiedGenuses;

    public function __construct()
    {
        $this->studiedGenuses = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        if(!in_array('ROLE_USER', $roles))
        {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getIsScientiest()
    {
        return $this->isScientiest;
    }

    /**
     * @param mixed $isScientiest
     */
    public function setIsScientiest($isScientiest)
    {
        $this->isScientiest = $isScientiest;
    }

    /**
     * @return mixed
     */
    public function getIsScientist()
    {
        return $this->isScientist;
    }

    /**
     * @param mixed $isScientist
     */
    public function setIsScientist($isScientist)
    {
        $this->isScientist = $isScientist;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    public function getFullName()
    {
        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getUniversityName()
    {
        return $this->universityName;
    }

    /**
     * @param mixed $universityName
     */
    public function setUniversityName($universityName)
    {
        $this->universityName = $universityName;
    }

    /**
     * @return mixed
     */
    public function getAvatarUri()
    {
        return $this->avatarUri;
    }

    /**
     * @param mixed $avatarUri
     */
    public function setAvatarUri($avatarUri)
    {
        $this->avatarUri = $avatarUri;
    }

    /**
     * @return ArrayCollection|Genus[]
     */
    public function getStudiedGenuses()
    {
        return $this->studiedGenuses;
    }

    public function addStudiedGenus(Genus $genus)
    {
        if ($this->studiedGenuses->contains($genus)) {
            return;
        }

        $this->studiedGenuses[] = $genus;
        $genus->addGenusScientist($this);
    }

    public function removeStudiedGenus(Genus $genus)
    {
        if(!$this->studiedGenuses->contains($genus)) {
            return;
        }

        $this->studiedGenuses->removeElement($genus);
        $genus->removeGenusScientist($this);
    }

}