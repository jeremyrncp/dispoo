<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $categories = $categoryRepository->findBy(["owner" => $user]);

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/add', name: 'app_category_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $category = new Category();
        $category->setOwner($user);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash("message", "Catégorie bien ajoutée");

            return $this->redirectToRoute("app_category");
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}/edit', name: 'app_category_edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $category->getOwner()) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("message", "Catégorie bien modifiée");

            return $this->redirectToRoute("app_category");
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}/delete', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
            /** @var User $user */
            $user = $this->getUser();

            if ($user !== $category->getOwner()) {
                throw new AccessDeniedHttpException();
            }

            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash("message", "Catégorie bien supprimée");
        }

        return $this->redirectToRoute('app_category', [], Response::HTTP_SEE_OTHER);
    }
}
