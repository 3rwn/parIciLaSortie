<?php

namespace App\Controller;

use App\Entity\Outing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        return $this->render('outing/home.html.twig', [
            'controller_name' => 'OutingController',
        ]);
    }


    /**
     * @Route("/showouting/{id}", name="outing_detail")
     */
    public function detail(Outing $o): Response
    {
        return $this->render('outing/showouting.html.twig', [
            'outing' => $o,
        ]);
    }

    
}



