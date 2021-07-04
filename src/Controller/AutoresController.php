<?php

namespace App\Controller;

use App\Repository\AutorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutoresController extends AbstractController
{
    /**
     * @Route("/autores", name="autores")
     */
    public function index(AutorRepository $autorRepository): Response
    {
        $autores = $autorRepository->findAll();

        return $this->render('autores/index.html.twig', [
            'autores' => $autores
        ]);
    }
}
