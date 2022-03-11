<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\ModifyOutingType;
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


    //Méthode permettant de récupérer les infos d'une sortie après modification et vérification pour update dans la BDD

    /**
     * @Route("/modifyouting/{id}", name="outing_update")
     */
    public function updateOuting(Outing $o, Request $req, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModifyOutingType::class, $o);
        $form->setData($o);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $date = new \DateTime();
            if($form->get('dateTimeStartOuting')->getData() > $date && $form->get('registrationDeadLine')->getData() < $form->get('dateTimeStartOuting')->getData()  ){
                $entityManager->persist($o);
                $entityManager->flush();
            return $this->redirectToRoute('home');
            }
          }
  
          return $this->render('outing/modifyouting.html.twig',
           [ 'formulaire'=> $form->createView(), 'outing'=> $o]);
      }
  
    //Méthode permettant de supprimer une sortie par son id et de la supprimer dans la BDD

    /**
     * @Route("/deleteouting/{id}", name="outing_delete")
     */
    public function deleteOuting(Outing $o, Request $req, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModifyOutingType::class, $o);
        $form->setData($o);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $date = new \DateTime();
            if($form->get('dateTimeStartOuting')->getData() > $date && $form->get('registrationDeadLine')->getData() < $form->get('dateTimeStartOuting')->getData()  ){
                $entityManager->persist($o);
                $entityManager->flush();
            return $this->redirectToRoute('home');
            }
          }
  
          return $this->render('outing/modifyouting.html.twig',
           [ 'formulaire'=> $form->createView(), 'outing'=> $o]);
      }
    

    
}



