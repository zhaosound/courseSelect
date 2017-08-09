<?php
/**
 * this file is just a try, no sense by now.
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Teacher;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAdminData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $teacher = new Teacher();
        $teacher->setUsername('admin');
        $teacher->setPassword('admin');
        $teacher->setIsValid(1);
        $manager->persist($teacher);
        $manager->flush();
    }
}