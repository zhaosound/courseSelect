<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Student;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class RegistrationManager
{
    protected $encoderFactory;
    protected $em;
    protected $student;
    protected $userPasswordEncoder;
    protected $fileUploaderManager;

    public function __construct(EncoderFactoryInterface $encoderFactory,
                                UserPasswordEncoder $userPasswordEncoder,
                                EntityManager $em,
                                $entity, FileUploaderManager $fileUploaderManager)
    {
        $this->encoderFactory = $encoderFactory;
        $this->em = $em;
        $this->student = $entity;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->fileUploaderManager = $fileUploaderManager;
    }

    public function register(Student $student)
    {
        $password = $this->userPasswordEncoder->encodePassword($student, $student->getPassword());
        $student->setPassword($password);

        $regtime = new \DateTime();
        $token_exptime = (new \DateTime())->add(new \DateInterval('P1D'));
        $token = uniqid();

        $student->setToken($token);
        $student->setRegtime($regtime);
        $student->setTokenExptime($token_exptime);
        $student->setStatus(0);

        $this->em->persist($student);
        $this->em->flush();
    }
}