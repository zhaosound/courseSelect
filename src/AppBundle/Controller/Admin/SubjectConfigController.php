<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Subject;
use AppBundle\Form\StudentSubjectType;
use AppBundle\Form\StudentType;
use AppBundle\Form\SubjectType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubjectConfigController extends Controller
{
    /**
     * @Route("/admin/subject", name="admin_subject")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a from AppBundle:Subject a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('subject/configSubject.html.twig', [
            'pagination' => $pagination
        ]);

    }

    /**
     * @Route("/admin/edit/{subject}", name="edit_subject")
     */
    public function editSubjectAction(Request $request,$subject)
    {

        $subject2 = $this->getDoctrine()->getRepository(Subject::class)->findOneByName($subject);
        $editsubForm = $this->createForm(SubjectType::class,$subject2);
        $editsubForm->handleRequest($request);

        if ( $editsubForm->isSubmitted() && $editsubForm->isValid() )
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subject2);
            $em->flush();

            return $this->redirectToRoute('admin_subject');
        }

        return $this->render('subject/editSubject.html.twig', [
            'form' => $editsubForm->createView(),
        ]);
    }

    /**
     * @Route("admin/delete/{subject}", name="delete_subject")
     */
    public function deleteSubjectAction($subject)
    {
        $subject2 = $this->getDoctrine()->getRepository(Subject::class)->findOneByName($subject);
        $em = $this->getDoctrine()->getManager();
        $em->remove($subject2);
        $em->flush();

        return $this->redirectToRoute('admin_subject');
    }

    /**
     * @Route("admin/new", name="new_subject")
     */
    public function addSubjectAction(Request $request)
    {
        $subject = new Subject();
        $subjectForm = $this->createForm(SubjectType::class,$subject);
        $subjectForm->handleRequest($request);
        if ( $subjectForm->isSubmitted() && $subjectForm->isValid() )
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subject);
            $em->flush();

            return $this->redirectToRoute('admin_subject');
        }

        return $this->render('subject/addSubject.html.twig', [
            'form' => $subjectForm->createView(),
        ]);
    }
    
}