<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function adminLoginAction()
    {
        return $this->render("security/adminLogin.html.twig");
    }

    /**
     * @Route("admin/logout", name="admin_logout")
     */
    public function logoutAction()
    {
        throw new \Exception("this should never be reached!");
    }
}
