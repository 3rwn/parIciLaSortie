<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/villes", name="app_admin_villes")
     */
    public function updateCity(): Response
    {
        return $this->render('admin/AdminCity.html.twig');
    }

    /**
     * @Route("/admin/campus", name="app_admin_campus")
     */
    public function updateCampus(): Response
    {
        return $this->render('admin/AdminCampus.html.twig');
    }

    /**
     * @Route("/admin/outings", name="app_admin_outings")
     */
    public function updateOutings(): Response
    {
        return $this->render('admin/AdminOuting.html.twig');
    }

    /**
     * @Route("/admin/participants", name="app_admin_participants")
     */
    public function updateParticipants(): Response
    {
        return $this->render('admin/AdminParticipants.html.twig');
    }
}
