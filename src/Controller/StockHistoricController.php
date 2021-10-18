<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\StockHistoric;
//use App\Form\StockHistoricType;

class StockHistoricController extends AbstractController
{
    #[Route('/backend/stock-historics', name: 'stock_historics')]
    public function index(): Response
    {
        $stockHistorics = $this->getDoctrine()
            ->getRepository(StockHistoric::class)
            ->findAll();

        return $this->render('stock-historic/index.html.twig', [
            'stockHistorics' => $stockHistorics,
        ]);
    }
}