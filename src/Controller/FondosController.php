<?php

namespace App\Controller;

use App\Repository\FondoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FondosController extends AbstractController
{
    /**
     * @Route("/fondos", name="fondos")
     */
    public function index(FondoRepository $fondoRepository): Response
    {
        $fondos = $fondoRepository->findAll();

        return $this->render('fondos/index.html.twig', [
            'fondos' => $fondos
        ]);
    }
}
