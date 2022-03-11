<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\ModifyOutingType;
use App\Repository\OutingRepository;
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
    //Méthode servant dans la page ModifyOuting.html.twig

    /**
     * @Route("/modifyouting/{id}", name="outing_update")
     */
    public function updateOuting(Outing $o, Request $req, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModifyOutingType::class, $o);
        $form->setData($o);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
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
    //Méthode servant dans la page ModifyOuting.html.twig

    /**
     * @Route("/deleteouting/{id}", name="outing_delete")
     */
    public function deleteOuting(Outing $o, EntityManagerInterface $entityManager): Response
    {    
          
           $entityManager->remove($o);
           $entityManager->flush(); // SAVE execute la requete SQL
    
           //dd($p->getId());
           // rediriger vers home
           return $this->redirectToRoute('home'); 
            
        }

      /******************************************************/














//Test 














    /******************************************************/








    /******************************************************/


    // Methode permettant d'afficher les sorties avec un filtre
    //Methode servant dans la page home.html.twig
    /**
     * @Route("/home/", name="home")
     */
    public function showOutings(OutingRepository $outingRepository,Request $req, EntityManagerInterface $entityManager): Response
    {
        $organizer = $this->getUser();
        $form = null;
        $outings = $outingRepository->findAll();
        $actions = ['Afficher', 'S\'inscrire'];

        return $this->render('outing/home.html.twig', [
           // 'formulaire'=> $form->createView(),
            'outings'=> $outings,
            'organizer' => $organizer,
            'actions' => $actions
        ]);
    }







}/******************************************************/



