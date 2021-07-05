<?php

namespace App\Controller;

use App\Repository\EditorialRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("/editoriales/{id}", name="editorial_detalle")
     */
    public function detalle($id, EditorialRepository $editorialRepository): Response
    {
        $editorial = $editorialRepository->find($id);
        
        if(!$editorial) {
            return $this->render('comunes/recurso-no-encontrado.html.twig', [
                'mensaje' => 'Esta Editorial No Existe.'
            ]);
        }

        return $this->render('editoriales/detalle.html.twig', [
            'editorial' => $editorial
        ]);
    }

    /**
     * @Route("/editoriales/{id}/borrar", name="editorial_delete")
     */
    public function delete($id, EditorialRepository $editorialRepository, EntityManagerInterface $em): Response
    {
        $editorial = $editorialRepository->find($id);
        
        if(!$editorial) {
            return $this->render('comunes/recurso-no-encontrado.html.twig', [
                'mensaje' => 'Esta Editorial No Existe.'
            ]);
        }else{
            $em->remove($editorial);
            $em->flush();
        }
        
        /* En lugar de repetir lo que ya hace una acción REDIRIGIMOS

        $editoriales = $editorialRepository->findAll();

        return $this->render('editoriales/index.html.twig', [
            'editoriales' => $editoriales
        ]);
        
        */

        return $this->redirectToRoute('editoriales');
    }
}
