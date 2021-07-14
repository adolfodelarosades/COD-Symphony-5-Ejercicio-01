<?php

namespace App\Controller;

use App\Entity\Fondo;
use App\Repository\EditorialRepository;
use App\Repository\AutorRepository;
use App\Repository\FondoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FondosController extends AbstractController
{
    /**
     * @Route("/fondos", name="fondos")
     */
    public function index(FondoRepository $fondoRepository): Response
    {
        //$fondos = $fondoRepository->findAll();
        //$fondos = $fondoRepository->findAllWithAutoresAndEditoriales();
        $fondos = $fondoRepository->findAllWithAutoresAndEditorialesBuilder();
        return $this->render('fondos/index.html.twig', [
            'fondos' => $fondos
        ]);
    }

    /* EL ORDEN EN QUE SE PONEN LAS ACCIONES IMPORTA */
    /**
     * @Route("/fondos/nuevo/", name="fondo_nuevo")
     */
    public function nuevo(
        EditorialRepository $editorialRepository,
        AutorRepository $autorRepository
    ): Response
    {
        return $this->render('fondos/nueva.html.twig', [
            'editoriales' => $editorialRepository->findAll(),
            'autores' => $autorRepository->findAll(), 
        ]);
    }

    /**
     * @Route("/fondos/create", name="fondo_create")
     */
    public function create(
        Request $request, 
        EditorialRepository $editorialRepository,
        AutorRepository $autorRepository,
        EntityManagerInterface $em
    ): Response
    { 
        // 1) recibir datos del formulario
        $datosForm = $request->request->get('fondo');

        // 2) dar de alta en bbdd 
        $fondo = new Fondo();
        $fondo->setTitulo($datosForm['titulo']);
        $fondo->setIsbn($datosForm['isbn']);
        $fondo->setEdicion($datosForm['edicion']);
        $fondo->setPublicacion($datosForm['publicacion']);
        $fondo->setCategoria($datosForm['categoria']);
        
        $editorial = $editorialRepository->find($datosForm['editorialId']);
        $fondo->setEditorial($editorial);

        $autoresIds = (array)$datosForm['autoresIds'];
        foreach($autoresIds as $autorId) {
            $autor = $autorRepository->find($autorId);
            $fondo->addAutor($autor);
        }

        $em->persist($fondo);
        try {
            $em->flush();
            $this->addFlash(
                'success',
                'Fondo ' . $fondo->getId() . ' creado correctamente!'
            );
        } catch(\Exception $ex) {
            dump($ex->getMessage());
            $ex->getCode();
            $ex->getTraceAsString();
            $this->addFlash(
                'danger',
                $ex->getMessage()
            );
        }

        // 3) redirigir al formulario
        return $this->redirectToRoute('fondos');
    }

    /**
     * @Route("/fondos/modificar/{id}", name="fondo_modificar")
     */
    public function modificar(
        $id,
        FondoRepository $fondoRepository, 
        EditorialRepository $editorialRepository,
        AutorRepository $autorRepository
    ): Response
    {
        dump($id);
        $fondo = $fondoRepository->find($id);

        if(!$fondo) {
            return $this->render('comunes/recurso-no-encontrado.html.twig', [
                'mensaje' => 'Este Fondo No Existe.'
            ]);
        }
        
        return $this->render('fondos/modificar.html.twig', [
            'fondo' => $fondo,
            'editoriales' => $editorialRepository->findAll(),
            'autores' => $autorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/fondos/update/{id}", name="fondo_update")
     */
    public function update($id,
        Request $request, 
        FondoRepository $fondoRepository, 
        EditorialRepository $editorialRepository,
        AutorRepository $autorRepository,
        EntityManagerInterface $em
    ): Response{ 
        // 1) recibir datos del formulario
        $datosForm = $request->request->get('fondo');
        
        
        // 2) Recuperar la Entidad de la BD
        $fondo = $fondoRepository->find($id);

        // 3) Actualizar los valores de los campos recuperados
        $fondo->setTitulo($datosForm['titulo']);
        $fondo->setIsbn($datosForm['isbn']);
        $fondo->setEdicion($datosForm['edicion']);
        $fondo->setPublicacion($datosForm['publicacion']);
        $fondo->setCategoria($datosForm['categoria']);
        
        $editorial = $editorialRepository->find($datosForm['editorialId']);
        $fondo->setEditorial($editorial);

        $autoresIds = (array)$datosForm['autoresIds'];//El Cast se pone para que no chille en el foreach "Aun que funciona sin el cast"
        $fondo->removeAllAutores();
        foreach($autoresIds as $autorId) {
            $autor = $autorRepository->find($autorId);
            $fondo->addAutor($autor);
        }

        // 4) Persistir los cambios
        $em->persist($fondo);

        try {
            $em->flush();
            $this->addFlash(
                'success',
                'Fondo ' . $fondo->getId() . ' actualizado correctamente!'
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
        return $this->redirectToRoute("fondos");

        /*
        return $this->render('fondo/edit.html.twig', [
            'fondo' => $fondo,
            'editoriales' => $editorialRepository->findAll(),
            'autores' => $autorRepository->findAll(),
        ]);*/
    }

    /**
     * @Route("/fondos/{id}", name="fondo_detalle")
     */
    public function detalle($id, FondoRepository $fondoRepository): Response
    {    
        $fondo = $fondoRepository->find($id);
        
        if(!$fondo) {
            return $this->render('comunes/recurso-no-encontrado.html.twig', [
                'mensaje' => 'Este Fondo No Existe.'
            ]);
        }

        return $this->render('fondos/detalle.html.twig', [
            'fondo' => $fondo
        ]);
    }

    /**
     * @Route("/fondos/{id}/borrar", name="fondo_delete")
     */
    public function delete($id, FondoRepository $fondoRepository, EntityManagerInterface $em): Response
    {
        $fondo = $fondoRepository->find($id);
        
        if(!$fondo) {
            return $this->render('comunes/recurso-no-encontrado.html.twig', [
                'mensaje' => 'Este Fondo No Existe.'
            ]);
        }else{
            $em->remove($fondo);
            try {
                $em->flush();
                $this->addFlash(
                    'success',
                    'Fondo ' . $id . ' eliminado correctamente!'
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
        
        return $this->redirectToRoute('fondos');
    }
}
