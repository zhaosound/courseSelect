<?php

namespace AppBundle\Controller;

use AppBundle\Form\AddImageType;
use AppBundle\Form\ModifyAddressType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class: SecurityController
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("security/login.html.twig", [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \Exception("this should never be reached!");
    }

    /**
     * @Route("/active/{token}", name="active")
     */
    public function activeAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $nowtime = new \DateTime();
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->findOneByToken($token);

        if (! $student) {
            return $this->render('@Twig/Exception/error.html.twig');
        }

        if ($student->getTokenExptime() < $nowtime) {
            //time out
            $em->remove($student);
            $em->flush();

            return $this->render('user/timeout.html.twig');
        }

        $student->setStatus(1);
        $em->persist($student);
        $em->flush();

        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/addimage", name="add_image")
     */
    public function addImageAction(Request $request)
    {
        $student = $this->getUser();
        $modifyForm = $this->createForm(AddImageType::class,$student);
        $modifyForm->handleRequest($request);

        if ($modifyForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/addImage.html.twig', [
            'form' => $modifyForm->createView()
        ]);
    }

    /**
     * @Route("/modifyaddress", name="modify_address")
     */
    public function modifyAddressAction(Request $request)
    {
        $student = $this->getUser();
        $addressForm = $this->createForm(ModifyAddressType::class, $student);
        $addressForm->handleRequest($request);
        if ($addressForm->isValid()) {
            dump($student);

            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute('modify_address');
        }

        return $this->render('security/modifyAddress.html.twig', [
            'form'   => $addressForm->createView()
        ]);
    }
}
