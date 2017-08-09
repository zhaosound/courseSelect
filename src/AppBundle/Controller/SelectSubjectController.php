<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subject;
use AppBundle\Form\SelectSubjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SelectSubjectController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $student = $this->getUser();
        $selectForm = $this->createForm(SelectSubjectType::class,$student);
        $selectForm->handleRequest($request);

        if( $selectForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute("homepage");
        }

        return $this->render('subject/selectSubject.html.twig', [
            'form'     => $selectForm->createView(),
            'student'  => $student,
        ]);
    }
}