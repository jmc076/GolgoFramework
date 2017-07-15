<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorCategoryMeta
 *
 * @ORM\Table(name="anchor_category_meta")
 * @ORM\Entity
 */
class AnchorCategoryMeta
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
     * @var integer $category
     *
     * @ORM\Column(name="category", type="integer", nullable=false)
     */
    private $category;

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
     * Set category
     *
     * @param integer $category
     * @return AnchorCategoryMeta
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set extend
     *
     * @param integer $extend
     * @return AnchorCategoryMeta
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
     * @return AnchorCategoryMeta
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
