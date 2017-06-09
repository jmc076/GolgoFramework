<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorSessions
 *
 * @ORM\Table(name="anchor_sessions")
 * @ORM\Entity
 */
class AnchorSessions
{
    /**
     * @var string $id
     *
     * @ORM\Column(name="id", type="string", length=32, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $expire
     *
     * @ORM\Column(name="expire", type="integer", nullable=false)
     */
    private $expire;

    /**
     * @var string $data
     *
     * @ORM\Column(name="data", type="text", nullable=false)
     */
    private $data;


    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set expire
     *
     * @param integer $expire
     * @return AnchorSessions
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;
    
        return $this;
    }

    /**
     * Get expire
     *
     * @return integer 
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return AnchorSessions
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
