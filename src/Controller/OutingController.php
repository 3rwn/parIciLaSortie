<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use App\Form\CancelOutingType;
use App\Form\CreateOutingType;
use App\Form\FilterFormType;
use App\Form\LocationType;
use App\Form\ModifyOutingType;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use App\Repository\OutingRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class OutingController extends AbstractController
{

    /**
     * Méthode permettant de récupérer les informations d'une sortie avec son id
     * Methode servant dans la page ShowOuting.html.twig
     * @IsGranted("ROLE_USER")
     * @Route("/showouting/{id}", name="outing_detail")
     */
    public function detail(Outing $o): Response
    {
        return $this->render('outing/showouting.html.twig', [
            'outing' => $o,
        ]);
    }

    /**
     * Méthode permettant de récupérer les infos d'une sortie après modification et vérification pour update dans la BDD
     * Méthode servant dans la page ModifyOuting.html.twig
     * @IsGranted("ROLE_USER")
     * @Route("/modifyouting/{id}", name="outing_update")
     */
    public function updateOuting(Outing $o, StateRepository $stateRepository, Request $req, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        if ($user === $o->getOrganizer()){
        $form = $this->createForm(ModifyOutingType::class, $o);
        $form->setData($o);
        $form->handleRequest($req);
        $date = new \DateTime();
        if ($form->get('dateTimeStartOuting')->getData() > $date && $form->get('registrationDeadLine')->getData() < $form->get('dateTimeStartOuting')->getData() && $this->getUser() === $o->getOrganizer()) {
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->getClickedButton() && 'saveAndAdd' === $form->getClickedButton()->getName()) {
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
        return $this->render('outing/modifyouting.html.twig',
            ['formulaire' => $form->createView(), 'outing' => $o]);
        }
        $this->addFlash('warning', 'Vous ne pouvez pas modifier la sortie : ' . $o->getName());
        return $this->redirectToRoute('home');
    }

    /**
     * Méthode permettant de publier une sortie
     * @IsGranted("ROLE_USER")
     * @Route("/modifstateyouting/{id}", name="outing_state_update")
     */
    public function publishOuting(Outing $outing, StateRepository $stateRepository, Request $req, EntityManagerInterface $entityManager): Response
    {
        $state = $stateRepository->find(2);
        $outing->setState($state);
        $entityManager->persist($outing);
        $entityManager->flush();
        $this->addFlash('success', 'Vous avez publier votre sortie : ' . $outing->getName());
        return $this->redirectToRoute('home');
    }

    /**
     * Méthode permettant de supprimer une sortie
     * @IsGranted("ROLE_USER")
     * @Route("/deleteouting/{id}", name="outing_delete")
     */
    public function deleteOuting(Outing $o, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user === $o->getOrganizer()){
        $entityManager->remove($o);
        $entityManager->flush(); // SAVE execute la requete SQL
        $this->addFlash('success', 'Vous avez bien supprimer la sortie : ' . $o->getName());
        //dd($p->getId());
        // rediriger vers home
        return $this->redirectToRoute('home');
        }
        $this->addFlash('warning', 'Vous ne pouvez pas supprimer la sortie : ' . $o->getName());
        return $this->redirectToRoute('home');
    }

    /**
     * Méthode permettant de créer une sortie
     * @IsGranted("ROLE_USER")
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

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getClickedButton() && 'saveAndAdd' === $form->getClickedButton()->getName()) {
                $state = $stateRepository->find(2);
                $o->setState($state);
                $date = $date = new \DateTime();
                if ($form->get('dateTimeStartOuting')->getData() > $date && $form->get('registrationDeadLine')->getData() < $form->get('dateTimeStartOuting')->getData()) {
                    $entityManager->persist($o);
                    $entityManager->flush();
                    $this->addFlash('success', 'Vous avez créer une sortie.');
                    return $this->redirectToRoute('home');
                }
            }
            if ($form->getClickedButton() && 'enregistrer' === $form->getClickedButton()->getName()) {
                $state = $stateRepository->find(1);
                $o->setState($state);
                $date = $date = new \DateTime();
                if ($form->get('dateTimeStartOuting')->getData() > $date && $form->get('registrationDeadLine')->getData() < $form->get('dateTimeStartOuting')->getData()) {
                    $entityManager->persist($o);
                    $entityManager->flush();
                    $this->addFlash('success', 'Vous avez créer une sortie.');
                    return $this->redirectToRoute('home');
                }
            }
            $this->addFlash('warning', 'Vous avez créer une sortie.');
        }
        return $this->render('outing/createouting.html.twig',
            ['formulaire' => $form->createView(), 'outing' => $o]);
    }

    /**
     * Méthode permettant d'afficher les détails d'une sortie
     * @IsGranted("ROLE_USER")
     * @Route("/home/", name="home")
     */
    public function showOutings(OutingRepository $outingRepository, Request $req, EntityManagerInterface $entityManager, Security $security, StateRepository $stateRepository): Response
    {
        $outingRepository->updatestatebydatetime($entityManager, $outingRepository, $stateRepository);
        $form = $this->createForm(FilterFormType::class);
        $form->handleRequest($req);
        $user = $security->getUser();
        $outings = null;
      //  $actions = ['Afficher' => 'outing_detail', 'S\'inscrire' => 'outing_registration', 'Annuler' => 'outing_cancel', 'Se désister' => 'outing_withdrawn', 'Modifier' => 'outing_update'];
        if ($form->isSubmitted()) {
            $criteria = $form->getData();
            // dd($criteria->getCampus());
            $outings = $outingRepository->findByFilterOuting($criteria, $user);
            return $this->render('outing/home.html.twig', [
                'outings' => $outings,
                'user' => $user,
              //  'actions' => $actions,
                'formulaire' => $form->createView()
            ]);
        }
        return $this->render('outing/home.html.twig', [
            'outings' => $outings,
            'user' => $user,
         //   'actions' => $actions,
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * Méthode permettant de s'inscrire à une sortie
     * @IsGranted("ROLE_USER")
     * @Route("/registration/{id}", name="outing_registration")
     */
    public function outingRegistration(Outing $outing, EntityManagerInterface $entityManager): Response
    {
        $participant = $this->getUser();
        $datetimeNow = new \DateTime();
        if ($outing->getParticipants()->count() < $outing->getMaxRegistrations() && $outing->getRegistrationDeadLine() > $datetimeNow && $outing->getState()->getId() == 2) {
            $outing->addParticipant($participant);
            $participant->addOuting($outing);
            $entityManager->persist($outing);
            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Vous vous êtes inscrit à la sortie : ' . $outing->getName());
            return $this->redirectToRoute('home');
        } else {
            $this->addFlash('warning', 'Vous ne pouvez pas vous inscrire à la sortie : ' . $outing->getName());
            return $this->redirectToRoute('home');
        }
    }

    /**
     * Méthode permettant de se désister d'un sortie
     * @IsGranted("ROLE_USER")
     * @Route("/withdrawn/{id}", name="outing_withdrawn")
     */
    public function outingWthdrawn(Outing $outing, EntityManagerInterface $entityManager): Response
    {
        
        $participant = $this->getUser();
        $datetimeNow = new \DateTime();
        if ($outing->getDateTimeStartOuting() > $datetimeNow && $outing->getRegistrationDeadLine() > $datetimeNow && $outing->getState()->getId() == 2) {
            $outing->removeParticipant($participant);
            $participant->removeOuting($outing);
            $entityManager->persist($outing);
            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Vous vous êtes désisté de la sortie : ' . $outing->getName());
            return $this->redirectToRoute('home');
        } else {
            $this->addFlash('warning', 'Vous ne pouvez pas vous désister de la sortie : ' . $outing->getName());
            return $this->redirectToRoute('home');
        }
    }

    /**
     * Méthode permettant d'annuler un sortie
     * @IsGranted("ROLE_USER")
     * @Route("/cancelouting/{id}", name="outing_cancel")
     */
    public function cancelOuting(Outing $outing, EntityManagerInterface $entityManager, Request $request, StateRepository $stateRepository): Response
    {

        $user = $this->getUser();
        if ($user === $outing->getOrganizer()){
        $form = $this->createForm(CancelOutingType::class);
        $form->handleRequest($request);
        if ($outing->getOrganizer()->getId() == $user->getId()) {
            if ($form->isSubmitted()) {
                if ($outing->getState()->getId() < 4)
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
            $this->addFlash('warning', 'Vous ne pouvez pas annuler la sortie : ' . $outing->getName());
            return $this->redirectToRoute('home');
        }
        return $this->render('outing/CancelOuting.html.twig', ['outing' => $outing, 'form' => $form->createView()]);
    }
        $this->addFlash('warning', 'Vous ne pouvez pas annuler la sortie : ' . $outing->getName());
        return $this->redirectToRoute('home');
    }


    /**
     * Méthode permettant d'ajouter un lieu de sortie dans la BDD
     * Méthode servant dans la page CreateLocation.html.twig
     * @IsGranted("ROLE_USER")
     * @Route("/createlocation/", name="create_location")
     */
    public function createLocation(Request $req, EntityManagerInterface $entityManager): Response
    {
        $l = new Location();
        $form = $this->createForm(LocationType::class, $l);
        $form->setData($l);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($l);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez ajouter un lieu de sortie.');
            return $this->redirectToRoute('outing_create');
        }

        return $this->render('outing/CreateLocation.html.twig',
            ['formulaire' => $form->createView()]);
    }

}


