<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorExtend
 *
 * @ORM\Table(name="anchor_extend")
 * @ORM\Entity
 */
class AnchorExtend
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
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var string $pagetype
     *
     * @ORM\Column(name="pagetype", type="string", length=140, nullable=false)
     */
    private $pagetype;

    /**
     * @var string $field
     *
     * @ORM\Column(name="field", type="string", nullable=false)
     */
    private $field;

    /**
     * @var string $key
     *
     * @ORM\Column(name="key", type="string", length=160, nullable=false)
     */
    private $key;

    /**
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=160, nullable=false)
     */
    private $label;

    /**
     * @var string $attributes
     *
     * @ORM\Column(name="attributes", type="text", nullable=false)
     */
    private $attributes;


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
     * Set type
     *
     * @param string $type
     * @return AnchorExtend
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set pagetype
     *
     * @param string $pagetype
     * @return AnchorExtend
     */
    public function setPagetype($pagetype)
    {
        $this->pagetype = $pagetype;
    
        return $this;
    }

    /**
     * Get pagetype
     *
     * @return string 
     */
    public function getPagetype()
    {
        return $this->pagetype;
    }

    /**
     * Set field
     *
     * @param string $field
     * @return AnchorExtend
     */
    public function setField($field)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set key
     *
     * @param string $key
     * @return AnchorExtend
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
     * Set label
     *
     * @param string $label
     * @return AnchorExtend
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return AnchorExtend
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    
        return $this;
    }

    /**
     * Get attributes
     *
     * @return string 
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
