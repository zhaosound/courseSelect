<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 * @UniqueEntity(fields={"email"})
 * @UniqueEntity(fields={"username"})
 * @Vich\Uploadable()
 */
class Student implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Length(min="6", minMessage="student.password.too_short")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var datetime
     *
     * @ORM\Column(name="token_exptime", type="datetime", length=255)
     */
    private $tokenExptime;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var Datetime
     *
     * @ORM\Column(name="regtime", type="datetime", length=255)
     */
    private $regtime;

    /**
     * @var string
     * @ORM\Column(name="pic_name", type="string", length=255, nullable= true)
     */
    private $picName;

    /**
     * @var Image
     * @Vich\UploadableField(mapping="student_pic", fileNameProperty="picName")
     */
    private $picFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ManyToMany(targetEntity="Subject",inversedBy="students")
     * @JoinTable(name="students_subjects")
     */
    private $subjects;

    /**
     * @ManyToMany(targetEntity="AppBundle\Entity\Address", inversedBy="students", cascade={"persist"})
     * @JoinTable(name="students_addresses")
     *
     */
    private $addresses;


    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Student
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Student
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Student
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set tokenExptime
     *
     * @param datetime $tokenExptime
     *
     * @return Student
     */
    public function setTokenExptime($tokenExptime)
    {
        $this->tokenExptime = $tokenExptime;

        return $this;
    }

    /**
     * Get tokenExptime
     *
     * @return datetime
     */
    public function getTokenExptime()
    {
        return $this->tokenExptime;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Student
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set regtime
     *
     * @param string $regtime
     *
     * @return Student
     */
    public function setRegtime($regtime)
    {
        $this->regtime = $regtime;

        return $this;
    }

    /**
     * Get regtime
     *
     * @return datetime
     */
    public function getRegtime()
    {
        return $this->regtime;
    }


    /**
     * @param mixed $subjects
     * @return Student
     */
    public function setSubjects($subjects)
    {
        $this->subjects = $subjects;
        return $this;
    }

    public function getSubjects()
    {
        return $this->subjects;
    }

    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
        return $this->getStatus();
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        return ['ROLE_USER'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return mixed
     */
    public function getPicName()
    {
        return $this->picName;
    }

    /**
     * @param string $picName
     * @return Student
     */
    public function setPicName($picName)
    {
        $this->picName = $picName;
        return $this;
    }

    /**
     * @return File
     */
    public function getPicFile()
    {
        return $this->picFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     * return Student
     */
    public function setPicFile(File $image = null)
    {
        $this->picFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
        ]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
        list (
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            ) = unserialize($serialized);
    }

    /**
     * @return mixed
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param mixed $addresses
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
    }

//    public function addAddress(Address $address)
//    {
//        $address->addStudent($this);
//        dump($address);
//
//        if (!($this->addresses->contains($address))) {
//            $this->addresses->add($address);
//        }
//    }
//
//    public function removeAddress(Address $address)
//    {
//        if (!($this->addresses->contains($address))) {
//            $this->addresses->removeElement($address);
//        }
//    }
}

