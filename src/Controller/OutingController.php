<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Entity\Participant;
use App\Form\ModifyOutingType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    // Methode permettant de récupérer les informations d'une sortie avec son id
    //Methode servant dans la page ShowOuting.html.twig
    /**
     * @Route("/showouting/{id}", name="outing_detail")
     */
    public function detail(Outing $o): Response
    {


        return $this->render('outing/showouting.html.twig', [
            'outing' => $o,
        ]);
    }

        /**
     * @Route("/modifyouting/{id}", name="outing_update")
     */
    public function updateOuting(Outing $o, Request $req, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifyOutingType::class, $o);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
  
                $em->flush();
              return $this->redirectToRoute('home');
              
          }
  
          return $this->render('outing/modifyouting.html.twig',
           [ 'formulaire'=> $form->createView()]);
      }
  
    

    
}



