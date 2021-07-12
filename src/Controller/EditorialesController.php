<?php

namespace App\Controller;

use App\Entity\Editorial;
use App\Repository\EditorialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /* EL ORDEN EN QUE SE PONEN LAS ACCIONES IMPORTA */
    /**
     * @Route("/editoriales/nueva/", name="editorial_nueva")
     */
    public function nueva(EditorialRepository $editorialRepository, EntityManagerInterface $em, Request $request): Response
    {
        return $this->render('editoriales/nueva.html.twig', [
            
        ]);
    }

    /**
     * @Route("/editoriales/create", name="editorial_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    { 
        // 1) recibir datos del formulario
        $nombre = $request->request->get('nombre');
        
        // 2) dar de alta en bbdd 
        $editorial = new Editorial ();
        $editorial->setNombre($nombre);
        

        $em->persist($editorial);
        
        try {
            $em->flush();
            $this->addFlash(
                'success',
                'Editorial ' . $editorial->getId() . ' creada correctamente!'
            );
        } catch(\Exception $ex) {
            $ex->getMessage();
            $ex->getCode();
            $ex->getTraceAsString();
            $this->addFlash(
                'danger',
                $ex->getMessage()
            );
        }

        // 3) redirigir al formulario
        return $this->redirectToRoute("editoriales");
    }

    /**
     * @Route("/editoriales/modificar/{id}", name="editorial_modificar")
     */
    public function modificar($id, EditorialRepository $editorialRepository): Response
    {
        dump($id);
        $editorial = $editorialRepository->find($id);

        if(!$editorial) {
            return $this->render('comunes/recurso-no-encontrado.html.twig', [
                'mensaje' => 'Esta Editorial No Existe.'
            ]);
        }
        
        return $this->render('editoriales/modificar.html.twig', [
            'editorial' => $editorial
        ]);
    }

    /**
     * @Route("/editoriales/update/{id}", name="editorial_update")
     */
    public function update($id, EditorialRepository $editorialRepository, Request $request, EntityManagerInterface $em): Response
    { 
        // 1) recibir datos del formulario
        $nombre = $request->request->get('nombre');
        
        // 2) Recuperar la Entidad de la BD
        $editorial = $editorialRepository->find($id);

        // 3) Actualizar los valores de los campos recuperados
        $editorial->setNombre($nombre);
        
        // 4) Persistir los cambios
        $em->persist($editorial);
        
        try {
            $em->flush();
            $this->addFlash(
                'success',
                'Editorial ' . $editorial->getId() . ' actualizada correctamente!'
            );
        } catch(\Exception $ex) {
            $ex->getMessage();
            $ex->getCode();
            $ex->getTraceAsString();
            $this->addFlash(
                'danger',
                $ex->getMessage()
            );
        }

        // 5) redirigir al formulario
        return $this->redirectToRoute("editoriales");
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
            
            try {
                $em->flush();
                $this->addFlash(
                    'success',
                    'Editorial ' . $id . ' eliminada correctamente!'
                );
            } catch(\Exception $ex) {
                $ex->getMessage();
                $ex->getCode();
                $ex->getTraceAsString();
                $this->addFlash(
                    'danger',
                    $ex->getMessage()
                );
            }
        }

        return $this->redirectToRoute('editoriales');
    }
}
