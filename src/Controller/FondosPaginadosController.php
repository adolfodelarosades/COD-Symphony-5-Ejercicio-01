<?php

namespace App\Controller;

use App\Repository\FondoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FondosPaginadosController extends AbstractController
{
    #[Route('/fondos/paginados/{orderBy}/{order}/{page}', 
            name: 'fondos_paginados'/*, 
            defaults: ['page'=>1, 'orderBy'=>'id']*/
            )]
    public function index($page, $orderBy, $order, FondoRepository $repo): Response
    {
        $itemsPorPagina = $this->getParameter('items_per_page', 10);
        $fondos = $repo->findAllWithAutoresAndEditorialesPaginado($page, $orderBy, $order, $itemsPorPagina);

        return $this->render('fondos_paginados/index.html.twig', [
            'fondos' => $fondos,
            'paginaActual' => $page,
            'orderBy' => $orderBy,
            'order' => $order,
            'numTotalPaginas' => intdiv(count($fondos),$itemsPorPagina)
        ]);
    }
}
