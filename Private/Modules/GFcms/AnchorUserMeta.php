<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorUserMeta
 *
 * @ORM\Table(name="anchor_user_meta")
 * @ORM\Entity
 */
class AnchorUserMeta
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
     * @var integer $user
     *
     * @ORM\Column(name="user", type="integer", nullable=false)
     */
    private $user;

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
     * Set user
     *
     * @param integer $user
     * @return AnchorUserMeta
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set extend
     *
     * @param integer $extend
     * @return AnchorUserMeta
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
     * @return AnchorUserMeta
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
