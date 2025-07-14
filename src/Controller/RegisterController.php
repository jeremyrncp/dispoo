<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, Security $security, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $errors = [];

        if ($request->request->get("firstname") === "") {
            $errors[] = "Le prénom est requis";
        }

        if ($request->request->get("lastname") === "") {
            $errors[] = "Le nom est requis";
        }

        if ($request->request->get("address") === "") {
            $errors[] = "L'adresse est requise";
        }

        if ($request->request->get("email") === "") {
            $errors[] = "L'adresse email est requise";
        }

        if ($request->request->get("password") === "") {
            $errors[] = "Le mot de passe est requis";
        }


        if (count($errors) === 0 && $request->request->get("email") !== null) {
            $uerFinded = $userRepository->findOneBy(["email" => $request->request->get("email")]);


            if ($uerFinded instanceof User) {
                $this->addFlash("error", "Cet email est déjà pris,  <a href='/login'>connectez-vous</a>");
            } else {
                $user = new User();
                $user->setFirstname($request->request->get("firstname"));
                $user->setName($request->request->get("lastname"));
                $user->setAddress($request->request->get("address"));
                $user->setEmail($request->request->get("email"));
                $user->setPassword($userPasswordHasher->hashPassword($user, $request->request->get("password")));
                $user->setRoles(["ROLE_USER"]);
                $user->setCreatedAt(new \DateTimeImmutable());

                $entityManager->persist($user);
                $entityManager->flush();

                return $security->login($user, 'form_login', 'app');
            }
        } else {
            foreach ($errors as $item) {
                $this->addFlash("error", $item);
            }
        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
}
