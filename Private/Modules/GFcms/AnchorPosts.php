<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AnchorPosts
 *
 * @ORM\Table(name="anchor_posts")
 * @ORM\Entity
 */
class AnchorPosts
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=150, nullable=false)
     */
    private $title;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=150, nullable=false)
     */
    private $slug;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string $markdown
     *
     * @ORM\Column(name="markdown", type="text", nullable=false)
     */
    private $markdown;

    /**
     * @var string $html
     *
     * @ORM\Column(name="html", type="text", nullable=false)
     */
    private $html;

    /**
     * @var string $css
     *
     * @ORM\Column(name="css", type="text", nullable=false)
     */
    private $css;

    /**
     * @var string $js
     *
     * @ORM\Column(name="js", type="text", nullable=false)
     */
    private $js;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var integer $author
     *
     * @ORM\Column(name="author", type="integer", nullable=false)
     */
    private $author;

    /**
     * @var integer $category
     *
     * @ORM\Column(name="category", type="integer", nullable=false)
     */
    private $category;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status;

    /**
     * @var boolean $comments
     *
     * @ORM\Column(name="comments", type="boolean", nullable=true)
     */
    private $comments;


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
     * Set title
     *
     * @param string $title
     * @return AnchorPosts
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
     * Set slug
     *
     * @param string $slug
     * @return AnchorPosts
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
     * Set description
     *
     * @param string $description
     * @return AnchorPosts
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set markdown
     *
     * @param string $markdown
     * @return AnchorPosts
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
     * @return AnchorPosts
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
     * Set css
     *
     * @param string $css
     * @return AnchorPosts
     */
    public function setCss($css)
    {
        $this->css = $css;
    
        return $this;
    }

    /**
     * Get css
     *
     * @return string 
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * Set js
     *
     * @param string $js
     * @return AnchorPosts
     */
    public function setJs($js)
    {
        $this->js = $js;
    
        return $this;
    }

    /**
     * Get js
     *
     * @return string 
     */
    public function getJs()
    {
        return $this->js;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return AnchorPosts
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set author
     *
     * @param integer $author
     * @return AnchorPosts
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return integer 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return AnchorPosts
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
     * Set status
     *
     * @param string $status
     * @return AnchorPosts
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
     * Set comments
     *
     * @param boolean $comments
     * @return AnchorPosts
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    
        return $this;
    }

    /**
     * Get comments
     *
     * @return boolean 
     */
    public function getComments()
    {
        return $this->comments;
    }
}
