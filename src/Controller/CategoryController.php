<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Form\CategoryType;

class CategoryController extends AbstractController
{
    #[Route('/backend/categories', name: 'categories')]
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/backend/category/add', name: 'category_add')]
    public function new(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();
            $category->setCreatedAt( new \DateTime() );

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('categories', [],302);
        }

        return $this->renderForm('category/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/backend/category/edit/{id}', name: 'category_edit')]
    public function update(int $id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No categoría found for id '.$id
            );
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_show', ['id' => $category->getId()],302);
        }

        return $this->renderForm('category/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/backend/category/{id}', name: 'category_show')]
    public function show($id): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/backend/category/delete/{id}', name: 'category_del')]
    public function delete($id): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('categories', [],302);
    }
}
