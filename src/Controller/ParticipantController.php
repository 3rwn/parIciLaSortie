<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantUpdateType;
use App\Security\AppParticipantAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant", name="app_participant")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * Fonction permettant d'afficher le profil du User connecté et permettant de modifier le profil
     * @Route("/MyProfile",  name="my_profile")
     */
    public function showMyProfile(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppParticipantAuthenticator $authenticator): Response
    {
        $participant = $this->getUser();
        $form = $this->createForm(ParticipantUpdateType::class, $participant);
        $form->setData($participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData() == '') {
                $entityManager->persist($participant);
                $entityManager->flush();
                return $this->redirectToRoute('home');
            }
            $participant->setPassword(
                $userPasswordHasher->hashPassword(
                    $participant,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($participant);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser($participant, $authenticator, $request);
            $this->redirectToRoute('home');
        }

        return $this->render('participant/MyProfile.html.twig', [
            'participant' => $participant,
            'formulaire' => $form->createView(),
        ]);
    }

    /**
     * Fonction permettant d'afficher n'importe quel participant grâce à son id
     * @Route("/ShowParticipant/{id}", requirements={"id"="\d+"}, name="show_participant")
     */
    public function showParticipant(Participant $participant): Response
    {
        return $this->render('participant/ShowParticipant.html.twig', [
            'participant' => $participant,
        ]);
    }
}
