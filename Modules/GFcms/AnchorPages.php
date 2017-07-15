<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorPages
 *
 * @ORM\Table(name="anchor_pages")
 * @ORM\Entity
 */
class AnchorPages
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
     * @var integer $parent
     *
     * @ORM\Column(name="parent", type="integer", nullable=false)
     */
    private $parent;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=150, nullable=false)
     */
    private $slug;

    /**
     * @var string $pagetype
     *
     * @ORM\Column(name="pagetype", type="string", length=140, nullable=false)
     */
    private $pagetype;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=150, nullable=false)
     */
    private $title;

    /**
     * @var string $markdown
     *
     * @ORM\Column(name="markdown", type="text", nullable=true)
     */
    private $markdown;

    /**
     * @var string $html
     *
     * @ORM\Column(name="html", type="text", nullable=false)
     */
    private $html;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status;

    /**
     * @var string $redirect
     *
     * @ORM\Column(name="redirect", type="text", nullable=false)
     */
    private $redirect;

    /**
     * @var boolean $showInMenu
     *
     * @ORM\Column(name="show_in_menu", type="boolean", nullable=false)
     */
    private $showInMenu;

    /**
     * @var integer $menuOrder
     *
     * @ORM\Column(name="menu_order", type="integer", nullable=false)
     */
    private $menuOrder;


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
     * Set parent
     *
     * @param integer $parent
     * @return AnchorPages
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return integer 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return AnchorPages
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set pagetype
     *
     * @param string $pagetype
     * @return AnchorPages
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
     * Set name
     *
     * @param string $name
     * @return AnchorPages
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return AnchorPages
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set markdown
     *
     * @param string $markdown
     * @return AnchorPages
     */
    public function setMarkdown($markdown)
    {
        $this->markdown = $markdown;
    
        return $this;
    }

    /**
     * Get markdown
     *
     * @return string 
     */
    public function getMarkdown()
    {
        return $this->markdown;
    }

    /**
     * Set html
     *
     * @param string $html
     * @return AnchorPages
     */
    public function setHtml($html)
    {
        $this->html = $html;
    
        return $this;
    }

    /**
     * Get html
     *
     * @return string 
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return AnchorPages
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set redirect
     *
     * @param string $redirect
     * @return AnchorPages
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    
        return $this;
    }

    /**
     * Get redirect
     *
     * @return string 
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * Set showInMenu
     *
     * @param boolean $showInMenu
     * @return AnchorPages
     */
    public function setShowInMenu($showInMenu)
    {
        $this->showInMenu = $showInMenu;
    
        return $this;
    }

    /**
     * Get showInMenu
     *
     * @return boolean 
     */
    public function getShowInMenu()
    {
        return $this->showInMenu;
    }

    /**
     * Set menuOrder
     *
     * @param integer $menuOrder
     * @return AnchorPages
     */
    public function setMenuOrder($menuOrder)
    {
        $this->menuOrder = $menuOrder;
    
        return $this;
    }

    /**
     * Get menuOrder
     *
     * @return integer 
     */
    public function getMenuOrder()
    {
        return $this->menuOrder;
    }
}
