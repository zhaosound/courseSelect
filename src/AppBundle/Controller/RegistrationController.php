<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use AppBundle\Form\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class: RegistrationController
 *
 * @see http://symfony.com/doc/current/book/controller.html
 * @see Controller
 */
class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="student_register")
     */
    public function indexAction(Request $request)
    {
        $student = new Student();
        $studentForm = $this->createForm(RegistrationType::class, $student);
        $studentForm->handleRequest($request);

        if (! $request->get('back') && $studentForm->isValid()) {
            if ($request->get('confirm')) {
                return $this->render("registration/confirm.html.twig", [
                    'form' => $studentForm->createView(),
                ]);
            } else {
                $this->get('registration_manager')->register($student);

                $body = $this->renderView('user/mail.html.twig', [
                    'student' => $student
                ]);
                $this->get('mail_manager')->sendMail($student,$body);

                return $this->redirectToRoute('student_register_finish');
            }
        }

        return $this->render("registration/index.html.twig", [
            'form' => $studentForm->createView(),
        ]);
    }

    /**
     * @Route("/register/finish", name="student_register_finish")
     */
    public function finishAction(Request $request)
    {
        return $this->render("registration/finish.html.twig");
    }
}
