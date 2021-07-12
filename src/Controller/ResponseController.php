<?php

namespace App\Controller;

use App\Repository\FondoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResponseController extends AbstractController
{
    /**
     * @Route("/response", name="response")
     */
    public function response(): Response
    {
        $response = new Response(
            '<h1>Contenido de la respuesta</h1>',
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );

        return $response;
    }

    /**
     * @Route("/response_json", name="response_json")
     */
    public function responseJson(): Response
    {
        $response = new Response(
            '{"name": "Adolfo", "apellido": "De la Rosa"}',
            Response::HTTP_OK,
            array('content-type' => 'application/json')
        );

        return $response;
    }

    /**
     * @Route("/response_json2", name="response_json2")
     */
    public function responseJson2(): Response
    {
        $response = new Response();
        $response->setContent('{"name": "Adolfo", "apellido": "De la Rosa"}');
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/json_encode", name="json_encode")
     */
    public function json_encode(): Response
    {
        $personas = [
          [
            'name' => 'Carlos',
            'age' => 21
          ],
          [
            'name' => 'Carmen',
            'age' => 16
          ],
          [
            'name' => 'Carla',
            'age' => 32
          ],
          [
            'name' => 'Carlota',
            'age' => 17
          ]
        ];

        $personasEncodedToJson = json_encode($personas);
        $response = new Response(
            $personasEncodedToJson,
            Response::HTTP_OK,
            array('content-type' => 'application/json')
        );
        
        return $response;
    }

    /**
     * @Route("/json_bd", name="json_bd")
     */
    public function json_bd(FondoRepository $fondoRepository): Response
    {
        $fondos = $fondoRepository->findAll();

        //json_encode Trabaja bien con Arrays pero no con Objetos
        //Muestra el JSON con objetos vacios
        //Hay que serializar el Objeto, existen 2 formas:
        
        //$fondosEncodedToJson = json_encode($fondos);

        //1er Forma
        $fondosArray = [];
        foreach($fondos as $fondo){
            $fondoArray = [
                'titulo' => $fondo->getTitulo(),
                'isbn' => $fondo->getIsbn()
                //Añadir todos los campos
            ];
            $fondosArray[] = $fondoArray; //Push en PHP
        }

        //2da Formas
        //foreach($fondos as $fondo){
        //    $fondosArray[] = $fondo->toArray(); 
            //Declarar método toArray() en la Entidad Fondo
            //Que retorne el Objeto convertido a Array
        //}

        $fondosEncodedToJson = json_encode($fondosArray);

        $response = new Response(
            $fondosEncodedToJson,
            Response::HTTP_OK,
            array('content-type' => 'application/json')
        );
        
        return $response;
    }

    /**
     * @Route("/json_response", name="json_response")
     */
    public function json_response(FondoRepository $fondoRepository): Response
    {
        $fondos = $fondoRepository->findAll();

        $fondosArray = [];
        foreach($fondos as $fondo){
            $fondoArray = [
                'titulo' => $fondo->getTitulo(),
                'isbn' => $fondo->getIsbn()
                //Añadir todos los campos
            ];
            $fondosArray[] = $fondoArray; //Push en PHP
        }

        /*
        $fondosEncodedToJson = json_encode($fondosArray);

        $response = new Response(
            $fondosEncodedToJson,
            Response::HTTP_OK,
            array('content-type' => 'application/json')
        );
        */

        //Usamos JsonResponse
        //Pasamos el array directamente sin códificar a JSON, el array de arrays
        //JsonResponse se encarga de pasarlo por la función json_encode
        // y mete la cabecera 'application/json'
        //Nos ahorramos codificar y pasar cabecera
        //Además se ve más claro y escribimos menos
        $response = new JsonResponse($fondosArray);

        return $response;    
    }

    /**
     * @Route("/metodo_json", name="metodo_json")
     */
    public function metodo_json(FondoRepository $fondoRepository): Response
    {
        $fondos = $fondoRepository->findAll();

        $fondosArray = [];
        foreach($fondos as $fondo){
            $fondoArray = [
                'titulo' => $fondo->getTitulo(),
                'isbn' => $fondo->getIsbn()
                //Añadir todos los campos
            ];
            $fondosArray[] = $fondoArray; //Push en PHP
        }

        //Una alternativa a la opción de abajo es:
        //$response = new JsonResponse($fondosArray);
        $response = $this->json($fondosArray);

        return $response;    
    }

}