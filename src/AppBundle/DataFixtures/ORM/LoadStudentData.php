<?php
/**
 * this file is just a try, no sense by now.
 */
namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Student;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadStudentData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $student = new Student();
        $student->setUsername('fixturetest');
        $student->setEmail('fixturetest@test.com');
        $student->setPassword(sha1('test'));
        $student->setRegtime(new \DateTime());
        $student->setStatus(1);
        $student->setToken(uniqid());
        $student->setTokenExptime((new \DateTime())->add(new \DateInterval('P1D')));

        $manager->persist($student);
        $manager->flush();
    }
}