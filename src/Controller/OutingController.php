<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\CancelOutingType;
use App\Form\CreateOutingType;
use App\Form\FilterFormType;
use App\Form\ModifyOutingType;
use App\Repository\LocationRepository;
use App\Repository\OutingRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;



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
    public function updateOuting(Outing $o,StateRepository $stateRepository, Request $req, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModifyOutingType::class, $o);
        $form->setData($o);
        $form->handleRequest($req);
        $date = $date = new \DateTime();

        if ($form->get('dateTimeStartOuting')->getData() > $date && $form->get('registrationDeadLine')->getData() < $form->get('dateTimeStartOuting')->getData()) {

            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->getClickedButton() && 'save_and_add' === $form->getClickedButton()->getName()) {

                    $state = $stateRepository->find(2);
                    $o->setState($state);
                    $entityManager->persist($o);
                    $entityManager->flush();
                    $this->addFlash('success', 'Vous avez publier votre sortie.');
                    return $this->redirectToRoute('home');

                }
                if ($form->getClickedButton() && 'enregistrer' === $form->getClickedButton()->getName()) {
                    $state = $stateRepository->find(1);
                    $o->setState($state);
                    $entityManager->persist($o);
                    $entityManager->flush();
                    $this->addFlash('success', 'Vous avez modifier la sortie.');

                    return $this->redirectToRoute('home');
                }
            } else {

                return $this->render('outing/modifyouting.html.twig',
                    ['formulaire' => $form->createView(), 'outing' => $o]);
            }
        }
    }

    /**
     * @Route("/modifstateyouting/{id}", name="outing_state_update")
     */
    public function updateStateOuting(Outing $outing,StateRepository $stateRepository, Request $req, EntityManagerInterface $entityManager): Response
    {
        $state = $stateRepository->find(2);
        $outing->setState($state);
        $entityManager->persist($outing);
        $entityManager->flush();
        $this->addFlash('success', 'Vous avez publier votre sortie.');
        return $this->redirectToRoute('home');
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
           $this->addFlash('success', 'Vous avez bien supprimer la.');
           //dd($p->getId());
           // rediriger vers home
           return $this->redirectToRoute('home'); 
            
        }

    //Méthode permettant de créer une sortie et l'ajout dans la BDD
    //Méthode servant dans la page CreateOuting.html.twig

    /**
     * @Route("/createouting/", name="outing_create")
     */
    public function createOuting(Request $req, EntityManagerInterface $entityManager, StateRepository $stateRepository): Response
    {
        $o = new Outing(); 
        $form = $this->createForm(CreateOutingType::class, $o);
        $form->setData($o);
        $form->handleRequest($req);
        $o->setOrganizer($this->getUser());
        $o->addParticipant($this->getUser());
        $state = $stateRepository->find(1);
        $o->setState($state);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $date = new \DateTime();
            if($form->get('dateTimeStartOuting')->getData() > $date && $form->get('registrationDeadLine')->getData() < $form->get('dateTimeStartOuting')->getData()  ){

                $entityManager->persist($o);
                $entityManager->flush();
                $this->addFlash('success', 'Vous avez créer une sortie.');
            return $this->redirectToRoute('home');
            }
          }

          return $this->render('outing/createouting.html.twig',
           [ 'formulaire'=> $form->createView(), 'outing'=> $o]);
      }

//    /**
//     * @param Request $request
//     * @return JsonResponse
//     * @Route("/getLocation/", name="get_location", methods={"GET"})
//     */
//    public function listenLocationOfCity(Request $request, EntityManagerInterface $entityManager, LocationRepository $locationRepository)
//    {
//
//        $locations = $locationRepository->createQueryBuilder("q")
//            ->where("q.city = :cityId")
//            ->setParameter("cityId", $request->query->get("cityId"))
//            ->getQuery()
//            ->getResult();
//
//        // Serialize into an array the data that we need, in this case only name and id
//        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
//        $responseArray = array();
//        foreach($locations as $location){
//            $responseArray[] = array(
//                "id" => $location->getId(),
//                "name" => $location->getName()
//            );
//        }
//
//        return new JsonResponse($responseArray);
//
//        // e.g
//        // [{"id":"3","name":"Treasure Island"},{"id":"4","name":"Presidio of San Francisco"}]
//    }

      /******************************************************/














//Test 














    /******************************************************/








     /******************************************************/


    // Methode permettant d'afficher les sorties avec un filtre
    //Methode servant dans la page home.html.twig
    /**
     * @Route("/home/", name="home")
     */
    public function showOutings(OutingRepository $outingRepository,Request $req, EntityManagerInterface $entityManager, Security $security, StateRepository $stateRepository): Response
    {
        $outingRepository->updatestatebydatetime($entityManager, $outingRepository, $stateRepository);
        $form = $this->createForm(FilterFormType::class);
        $form->handleRequest($req);
        $user = $security->getUser();
        //$criteria = ['campus' => '1', 'organizer' => true];
        //$organizer = $this->getUser();
        $outings = null;

        $actions = ['Afficher' => 'outing_detail', 'S\'inscrire' => 'outing_registration', 'Annuler' => 'outing_cancel', 'Se désister'=>'outing_withdrawn', 'Modifier'=>'outing_update'];

        if ($form->isSubmitted()) {
            $criteria=$form->getData();
         // dd($criteria->getCampus());
            $outings = $outingRepository->findByFilterOuting($criteria,$user);
            return $this->render('outing/home.html.twig', [
                'outings'=> $outings,
                'user' => $user,
                'actions' => $actions,
                'formulaire'=>$form->createView()
            ]);

        }
        return $this->render('outing/home.html.twig', [
            'outings'=> $outings,
            'user' => $user,
            'actions' => $actions,
            'formulaire'=>$form->createView()
        ]);

    }

    /**
     * @Route("/registration/{id}", name="outing_registration")
     */
    public function outingRegistration(Outing $outing, EntityManagerInterface $entityManager): Response
    {
        $participant = $this->getUser();
        $datetimeNow = new \DateTime();

        if($outing->getParticipants()->count() < $outing->getMaxRegistrations() && $outing->getRegistrationDeadLine() > $datetimeNow && $outing->getState()->getId() == 2){

            $outing->addParticipant($participant);
            $participant->addOuting($outing);

            $entityManager->persist($outing);
            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Vous vous êtes inscrit à la sortie : '. $outing->getName());
            return $this->redirectToRoute('home');

        }else{

            $this->addFlash('success', 'Vous ne pouvez pas vous inscrire à la sortie : '. $outing->getName());
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/withdrawn/{id}", name="outing_withdrawn")
     */
    public function outingWthdrawn(Outing $outing, EntityManagerInterface $entityManager): Response
    {
        $participant = $this->getUser();
        $datetimeNow = new \DateTime();

        if($outing->getDateTimeStartOuting() > $datetimeNow && $outing->getRegistrationDeadLine() > $datetimeNow && $outing->getState()->getId() == 2){

            $outing->removeParticipant($participant);
            $participant->removeOuting($outing);

            $entityManager->persist($outing);
            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Vous vous êtes désisté de la sortie : '. $outing->getName());
            return $this->redirectToRoute('home');

        }else{

            $this->addFlash('success', 'Vous ne pouvez pas vous désister de la sortie : '. $outing->getName());
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/cancelouting/{id}", name="outing_cancel")
     */
    public function cancelOuting(Outing $outing, EntityManagerInterface $entityManager, Request $request, StateRepository $stateRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(CancelOutingType::class);
        $form->handleRequest($request);

        if ($outing->getOrganizer()->getId() == $user->getId()) {
            if($form->isSubmitted()){
                if($outing->getState()->getId() < 4)
                $reason = $form->get('reason')->getData();
                $outing->setDescription($outing->getDescription() . "     Motif de l'annulation : " . $reason);
                $state = $stateRepository->find(6);
                $outing->setState($state);
                $entityManager->persist($outing);
                $entityManager->flush();
                $this->addFlash('success', 'Vous avez annulé la sortie : ' . $outing->getName());
                return $this->redirectToRoute('home');

            }
        } else {
            $this->addFlash('success', 'Vous ne pouvez pas annuler la sortie : ' . $outing->getName());
            return $this->redirectToRoute('home');
        }
        return $this->render('outing/CancelOuting.html.twig',['outing'=>$outing, 'form'=>$form->createView()]);
    }


//    public function updatestatebydatetime(EntityManagerInterface $entityManager, OutingRepository $outingRepository, StateRepository $stateRepository): Response
//    {
//
//        $now = new \DateTime('now');
//
//        $outings = $outingRepository->findAll();
//        foreach ($outings as $outing){
//            if( $outing->getRegistrationDeadLine() < $now && $outing->getDateTimeStartOuting() > $now){
//                $outing->setState($stateRepository->find(3));
//            }
//            if($outing->getDateTimeStartOuting() < $now && $outing->getDateTimeStartOuting()->add(new \DateInterval('P1D')) > $now){
//                $outing->setState($stateRepository->find(4));
//            }
//
//            if($outing->getDateTimeStartOuting() < $now &&  $outing->getState()->getId() == 4){
//                $outing->setState($stateRepository->find(5));
//            }
//
//            if($outing->getDateTimeStartOuting()->add(new \DateInterval('P1M')) < $now){
//            $outing->setState($stateRepository->find(7));
//            }
//            $entityManager->persist($outing);
//            $entityManager->flush();
//        }
//        return $this->render('admin/AdminOuting.html.twig',['outings'=>$outings]);
//    }



}/******************************************************/


