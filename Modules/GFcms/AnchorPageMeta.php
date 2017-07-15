<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorPageMeta
 *
 * @ORM\Table(name="anchor_page_meta")
 * @ORM\Entity
 */
class AnchorPageMeta
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $page
     *
     * @ORM\Column(name="page", type="integer", nullable=false)
     */
    private $page;

    /**
     * @var integer $extend
     *
     * @ORM\Column(name="extend", type="integer", nullable=false)
     */
    private $extend;

    /**
     * @var string $data
     *
     * @ORM\Column(name="data", type="text", nullable=false)
     */
    private $data;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set page
     *
     * @param integer $page
     * @return AnchorPageMeta
     */
    public function setPage($page)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return integer 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set extend
     *
     * @param integer $extend
     * @return AnchorPageMeta
     */
    public function setExtend($extend)
    {
        $this->extend = $extend;
    
        return $this;
    }

    /**
     * Get extend
     *
     * @return integer 
     */
    public function getExtend()
    {
        return $this->extend;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return AnchorPageMeta
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }
}
