<?php

namespace App\Controller;

use App\Repository\FondoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataTableFondosController extends AbstractController
{
    #[Route('/fondos_json', name: 'fondos_json')]
    public function fondos_json(): Response
    {
        return $this->render('data_table_fondos/index.html.twig', [
            'controller_name' => 'DataTableFondosController',
        ]);
    }

    #[Route('/libros_json', name: 'libros_json')]
    public function libros_json(FondoRepository $fondoRepository): Response
    {
        $fondos = $fondoRepository->findAll();

        $fondosArray = [];

        foreach($fondos as $fondo){
            $fondoArray = [
                $fondo->getTitulo(),
                $fondo->getIsbn(),
                $fondo->getEdicion(),
                $fondo->getPublicacion()
            ];
            $fondosArray[] = $fondoArray;
        }

        $content = [
            'data' => $fondosArray
        ];

        return new JsonResponse($content);
    }
}
