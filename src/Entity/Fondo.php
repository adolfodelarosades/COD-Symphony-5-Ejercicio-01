<?php

namespace App\Entity;

use App\Repository\FondoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FondoRepository::class)
 */
class Fondo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $isbn;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $edicion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $publicacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categoria;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getEdicion(): ?int
    {
        return $this->edicion;
    }

    public function setEdicion(?int $edicion): self
    {
        $this->edicion = $edicion;

        return $this;
    }

    public function getPublicacion(): ?int
    {
        return $this->publicacion;
    }

    public function setPublicacion(?int $publicacion): self
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }
}
