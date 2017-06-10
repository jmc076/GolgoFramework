<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorMeta
 *
 * @ORM\Table(name="anchor_meta")
 * @ORM\Entity
 */
class AnchorMeta
{
    /**
     * @var string $key
     *
     * @ORM\Column(name="key", type="string", length=140, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $key;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="text", nullable=false)
     */
    private $value;


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
     * @return AnchorMeta
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
