<?php

namespace AppBundle\Command;

use AppBundle\Entity\Teacher;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AddAdminCommand extends ContainerAwareCommand
{
    const MAX_ATTEMPTS = 5;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
       $this
           ->setName('app:add-admin')
           ->setDescription('Create admin and store them in the database')
           ->setHelp($this->getCommandHelp())
           ->addArgument('username',InputArgument::REQUIRED,'name of the admin')
           ->addArgument('password',InputArgument::REQUIRED,'password of the admin')
           ->addArgument('isValid',InputArgument::REQUIRED,'Is the account valid now?(if true then input 1 else input 0)');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }


    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('username') && null !== $input->getArgument('password') && null !== $input->getArgument('isValid')) {
            return;
        }

        // multi-line messages can be displayed this way...
        $output->writeln('');
        $output->writeln('Add Admin Command Interactive Wizard');
        $output->writeln('-----------------------------------');

        // ...but you can also pass an array of strings to the writeln() method
        $output->writeln([
            '',
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:add-admin username password isValid',
            '',
        ]);

        $output->writeln([
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
            '',
        ]);

        // See http://symfony.com/doc/current/components/console/helpers/questionhelper.html
        $console = $this->getHelper('question');

        // Ask for the username if it's not defined
        $username = $input->getArgument('username');
        if (null === $username) {
            $question = new Question(' > <info>Name</info>: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('The username cannot be empty');
                }

                return $answer;
            });
            $question->setMaxAttempts(self::MAX_ATTEMPTS);

            $username = $console->ask($input, $output, $question);
            $input->setArgument('username', $username);
        } else {
            $output->writeln(' > <info>username</info>: '.$username);
        }

        // Ask for the password if it's not defined
        $password = $input->getArgument('password');
        if (null === $password) {
            $question = new Question(' > <info>Password</info> (your type will be hidden): ');
            $question->setValidator([$this, 'passwordValidator']);
            $question->setHidden(true);
            $question->setMaxAttempts(self::MAX_ATTEMPTS);

            $password = $console->ask($input, $output, $question);
            $input->setArgument('password', $password);
        } else {
            $output->writeln(' > <info>Password</info>: '.str_repeat('*', strlen($password)));
        }

        // Ask for the isValid if it's not defined
        $isValid = $input->getArgument('isValid');
        if (null === $isValid) {
            $question = new Question(' > <info>isValid</info>: ');
            $question->setValidator([$this, 'isValidValidator']);
            $question->setMaxAttempts(self::MAX_ATTEMPTS);

            $isValid = $console->ask($input, $output, $question);
            $input->setArgument('isValid', $isValid);
        } else {
            $output->writeln(' > <info>isValid</info>: '.$isValid);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $username = $input->getArgument('username');
        $plainPassword = $input->getArgument('password');
        $isValid
            = $input->getArgument('isValid');

        // first check if a user with the same username already exists
        $existingUser = $this->entityManager->getRepository(Teacher::class)->findOneBy(['username' => $username]);

        if (null !== $existingUser) {
            throw new \RuntimeException(sprintf('There is already a user registered with the "%s" username.', $username));
        }

        // create the user and encode its password
        $teacher = new Teacher();
        $teacher->setUsername($username);
        $teacher->setPassword($plainPassword);
        $teacher->setIsValid($isValid);

        // See http://symfony.com/doc/current/book/security.html#security-encoding-password
        $this->entityManager->persist($teacher);
        $this->entityManager->flush();

        $output->writeln('');
        $output->writeln(sprintf('[OK] successfully created'));

        if ($output->isVerbose()) {
            $finishTime = microtime(true);
            $elapsedTime = $finishTime - $startTime;

            $output->writeln(sprintf('[INFO] New user database id: %d / Elapsed time: %.2f ms', $teacher->getId(), $elapsedTime*1000));
        }
    }

    public function passwordValidator($plainPassword)
    {
        if (empty($plainPassword)) {
            throw new \Exception('The password can not be empty');
        }

        if (strlen(trim($plainPassword)) < 6) {
            throw new \Exception('The password must be at least 6 characters long');
        }

        return $plainPassword;
    }

    public function isValidValidator($isValid)
    {
        if (empty($isValid)) {
            throw new \Exception('The isValid can not be empty');
        }

        if (($isValid <> 0 ) && ($isValid <>1)) {
            throw new \Exception('The isValid must be 0 or 1 ');
        }

        return $isValid;
    }


    private function getCommandHelp()
    {
        return <<<HELP
        something show be put here after.

HELP;
    }

}