<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorPagetypes
 *
 * @ORM\Table(name="anchor_pagetypes")
 * @ORM\Entity
 */
class AnchorPagetypes
{
    /**
     * @var string $key
     *
     * @ORM\Column(name="key", type="string", length=32, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $key;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=32, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $value;


    /**
     * Set key
     *
     * @param string $key
     * @return AnchorPagetypes
     */
    public function setKey($key)
    {
        $this->key = $key;
    
        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return AnchorPagetypes
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}
