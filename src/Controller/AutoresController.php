<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutoresController extends AbstractController
{
    /**
     * @Route("/autores", name="autores")
     */
    public function index(): Response
    {
        return $this->render('autores/index.html.twig', [
            'controller_name' => 'AutoresController',
        ]);
    }
}
