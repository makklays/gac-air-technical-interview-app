<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\StockHistoric;
use App\Form\ProductType;
use App\Form\CambiaStockType;

class ProductController extends AbstractController
{
    #[Route('/backend/products', name: 'products')]
    public function index(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/backend/product/add', name: 'product_add')]
    public function new(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            $product->setCreatedAt( new \DateTime() );

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('products', [],302);
        }

        return $this->renderForm('product/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/backend/product/edit/{id}', name: 'product_edit')]
    public function update(int $id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', ['id' => $product->getId()],302);
        }

        return $this->renderForm('product/add.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/backend/product/{id}", name="product_show")
     */
    public function show(int $id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/backend/product/delete/{id}', name: 'product_del')]
    public function delete($id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('products', [],302);
    }

    #[Route('/backend/cambia-stock/{product_id}', name: 'cambia_stock')]
    public function cambia_stock(int $product_id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($product_id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$product_id
            );
        }

        $stockHistoric = new StockHistoric();

        $form = $this->createForm(CambiaStockType::class, $stockHistoric);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // current user - necesito añadir sign-in
            //$user = $this->security->getUser();
            $user = $em->getRepository(User::class)->find(1);

            $stockHistoric = $form->getData();
            $stockHistoric->setUser($user);
            $stockHistoric->setProduct($product); // by product_id
            $stockHistoric->setCreatedAt( new \DateTime() );

            $em = $this->getDoctrine()->getManager();
            $em->persist($stockHistoric);
            $em->flush();

            return $this->redirectToRoute('stock_historics', [],302);
        }

        return $this->renderForm('product/cambia-stock.html.twig', [
            'form' => $form,
        ]);
    }
}
