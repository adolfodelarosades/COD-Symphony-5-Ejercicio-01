<?php

namespace App\Controller;

use App\Repository\EditorialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditorialesController extends AbstractController
{
    /**
     * @Route("/editoriales", name="editoriales")
     */
    public function index(EditorialRepository $editorialRepository): Response
    {
        $editoriales = $editorialRepository->findAll();

        return $this->render('editoriales/index.html.twig', [
            'editoriales' => $editoriales
        ]);
    }
}
