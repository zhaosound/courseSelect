<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StudentConfigController extends Controller
{

    /**
     * @Route("/admin/show", name="show")
     * @Route("/admin", name="admin")
     */
    public function studentShowAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a from AppBundle:Student a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            10
        );

        return $this->render(':subject:configStudent.html.twig', [
            'students' => $pagination,
        ]);
    }

    /**
     * @Route("/admin/deletestudent/{studid}", name="delete_student")
     */
    public  function deleteStudent($studid)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->findOneByUserame($studid);
        $em = $this->getDoctrine()->getManager();
        $em->remove($student);
        $em->flush();

        return $this->redirectToRoute('admin_subject');
    }
}