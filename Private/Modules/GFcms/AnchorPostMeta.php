<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorPostMeta
 *
 * @ORM\Table(name="anchor_post_meta")
 * @ORM\Entity
 */
class AnchorPostMeta
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
     * @var integer $post
     *
     * @ORM\Column(name="post", type="integer", nullable=false)
     */
    private $post;

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
     * Set post
     *
     * @param integer $post
     * @return AnchorPostMeta
     */
    public function setPost($post)
    {
        $this->post = $post;
    
        return $this;
    }

    /**
     * Get post
     *
     * @return integer 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set extend
     *
     * @param integer $extend
     * @return AnchorPostMeta
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
     * @return AnchorPostMeta
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
