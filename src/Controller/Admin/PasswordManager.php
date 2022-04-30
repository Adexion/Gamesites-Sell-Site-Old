<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

trait PasswordManager
{
    /**
     * @Route("/admin/passwd", name="admin_password_manager")
     */
    public function passwd(): Response
    {

    }
}