<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantUpdateType;
use App\Security\AppParticipantAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
     * @IsGranted("ROLE_USER")
     * @Route("/participant", name="app_participant")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * Fonction permettant d'afficher le profil du User connecté et permettant de modifier le profil
     * @Route("/MyProfile",  name="my_profile")
     */
    public function showMyProfile(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppParticipantAuthenticator $authenticator, SluggerInterface $slugger): Response
    {

        $participant = $this->getUser();
        $form = $this->createForm(ParticipantUpdateType::class, $participant);
        $form->setData($participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $profilePictureFile = $form->get('profilePictureFileName')->getData();
            $password = $form->get('password')->getData();

            if ($password == null && $profilePictureFile == null) {
                $entityManager->persist($participant);
                $entityManager->flush();
                $this->addFlash('success', 'Votre profil a été mis à jour.');
                return $this->redirectToRoute('home');
            }

            if ($password && $profilePictureFile == '') {
                $participant->setPassword(
                    $userPasswordHasher->hashPassword(
                        $participant,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($participant);
                $entityManager->flush();
                $this->addFlash('success', 'Votre profil a été mis à jour.');
                return $userAuthenticator->authenticateUser($participant, $authenticator, $request);
                $this->redirectToRoute('home');
            }

            if ($password == '' && $profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();
                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $participant->setProfilePictureFileName($newFilename);

                $entityManager->persist($participant);
                $entityManager->flush();
                $this->addFlash('success', 'Votre profil a été mis à jour.');
                return $this->redirectToRoute('home');
            }
            if ($password && $profilePictureFile) {
                $participant->setPassword(
                    $userPasswordHasher->hashPassword(
                        $participant,
                        $form->get('password')->getData()
                    )
                );
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();
                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $participant->setProfilePictureFileName($newFilename);
                $entityManager->persist($participant);
                $entityManager->flush();
                $this->addFlash('success', 'Votre profil a été mis à jour.');
                return $this->redirectToRoute('home');

            }

        }

        return $this->render('participant/MyProfile.html.twig', [
            'participant' => $participant,
            'formulaire' => $form->createView(),]);
    }

    /**
     * @IsGranted("ROLE_USER")
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
