<?php


namespace Modules\GFStarterKit\Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * Files
 *
 * @ORM\Table(name="gf_files")
 * @ORM\Entity
 */
class FilesModel extends BasicModel
{

    /**
     * @var integer $ruta
     *
     * @ORM\Column(name="ruta", type="string", nullable=false)
     */
    protected $ruta;



    /**
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     */
    protected $titulo;


    /**
     * @var \DateTime $fechaCreacion
     *
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */
    protected $fechaCreacion;


    /**
     * @var float $size
     *
     * @ORM\Column(name="size", type="float", nullable=true)
     */
    protected $size;

    /**
     * @var string $fileName
     *
     * @ORM\Column(name="file_name", type="string", length=255, nullable=false)
     */
    protected $fileName;

    /**
     * @var string $fileName
     *
     * @ORM\Column(name="isMain", type="integer")
     */
    protected $isMain;



    public function setIsMain($main)
    {
    	$this->isMain = $main;

    }
    public function getIsMain()
    {
    	return $this->isMain;
    }

    /**
     * Set ruta
     *
     * @param integer $ruta
     * @return Files
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return integer
     */
    public function getRuta()
    {
        return $this->ruta;
    }


    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Files
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }


    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Files
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }



    /**
     * Set size
     *
     * @param float $size
     * @return Files
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return Files
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
}
