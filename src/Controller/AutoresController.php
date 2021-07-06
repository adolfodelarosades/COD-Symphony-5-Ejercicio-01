<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Repository\AutorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /* EL ORDEN EN QUE SE PONEN LAS ACCIONES IMPORTA */
    /**
     * @Route("/autores/nuevo/", name="autor_nuevo")
     */
    public function nuevo(AutorRepository $autorRepository, EntityManagerInterface $em, Request $request): Response
    {
        return $this->render('autores/nueva.html.twig', [
            
        ]);
    }

    /**
     * @Route("/autores/create", name="autor_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    { 
        dump('EN CREATE AUTOR');
        // 1) recibir datos del formulario
        $nombre = $request->request->get('nombre');
        $tipo = $request->request->get('tipo');
        
        // 2) dar de alta en bbdd 
        $autor = new Autor();
        $autor->setNombre($nombre);
        $autor->setNombre($tipo);

        $em->persist($autor);
        
        try {
            $em->flush();
            $autor->getId();
        } catch(\Exception $ex) {
            $ex->getMessage();
            $ex->getCode();
            $ex->getTraceAsString();
        }

        // 3) redirigir al formulario
        return $this->redirectToRoute("autores");
    }

    /**
     * @Route("/autores/modificar/{id}", name="autor_modificar")
     */
    public function modificar($id, AutorRepository $autorRepository): Response
    {
        dump($id);
        $autor = $autorRepository->find($id);

        if(!$autor) {
            return $this->render('comunes/recurso-no-encontrado.html.twig', [
                'mensaje' => 'Este Autor No Existe.'
            ]);
        }
        
        return $this->render('autores/modificar.html.twig', [
            'autor' => $autor
        ]);
    }

    /**
     * @Route("/autores/update/{id}", name="autor_update")
     */
    public function update($id, AutorRepository $autorRepository, Request $request, EntityManagerInterface $em): Response
    { 
        // 1) recibir datos del formulario
        $nombre = $request->request->get('nombre');
        $tipo = $request->request->get('tipo');
        
        // 2) Recuperar la Entidad de la BD
        $autor = $autorRepository->find($id);

        // 3) Actualizar los valores de los campos recuperados
        $autor->setNombre($nombre);
        $autor->setTipo($tipo);
        
        // 4) Persistir los cambios
        $em->persist($autor);
        
        try {
            $em->flush();
            $autor->getId();
        } catch(\Exception $ex) {
            $ex->getMessage();
            $ex->getCode();
            $ex->getTraceAsString();
        }

        // 5) redirigir al formulario
        return $this->redirectToRoute("autores");
    }

    /**
     * @Route("/autores/{id}", name="autor_detalle")
     */
    public function detalle($id, AutorRepository $autorRepository): Response
    {
        $autor = $autorRepository->find($id);
        
        if(!$autor) {
            return $this->render('comunes/recurso-no-encontrado.html.twig', [
                'mensaje' => 'Este Autor No Existe.'
            ]);
        }

        return $this->render('autores/detalle.html.twig', [
            'autor' => $autor
        ]);
    }

    /**
     * @Route("/autores/{id}/borrar", name="autor_delete")
     */
    public function delete($id, AutorRepository $autorRepository, EntityManagerInterface $em): Response
    {
        $autor = $autorRepository->find($id);
        
        if(!$autor) {
            return $this->render('comunes/recurso-no-encontrado.html.twig', [
                'mensaje' => 'Este Autor No Existe.'
            ]);
        }else{
            $em->remove($autor);
            $em->flush();
        }
        
        return $this->redirectToRoute('autores');
    }
}
