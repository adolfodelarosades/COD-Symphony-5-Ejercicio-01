<?php
namespace App\Servicios;

use App\Entity\Editorial;
use Doctrine\ORM\EntityManagerInterface;

class EditorialManager {

    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function createEditorial(string $nombre){
        $editorial = new Editorial ();
        $editorial->setNombre($nombre);
    
        $this->em->persist($editorial);
        
        try {
            $this->em->flush();
            $editorial->getId();
        } catch(\Exception $ex) {
            $ex->getMessage();
            $ex->getCode();
            $ex->getTraceAsString();
        }
        
        return $editorial;
    }
}