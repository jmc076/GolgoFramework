<?php

namespace Proxies\__CG__\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class WpPosts extends \Entities\WpPosts implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getfeaturedImage()
    {
        $this->__load();
        return parent::getfeaturedImage();
    }

    public function setPostAuthor($postAuthor)
    {
        $this->__load();
        return parent::setPostAuthor($postAuthor);
    }

    public function getPostAuthor()
    {
        $this->__load();
        return parent::getPostAuthor();
    }

    public function setPostDate($postDate)
    {
        $this->__load();
        return parent::setPostDate($postDate);
    }

    public function getPostDate()
    {
        $this->__load();
        return parent::getPostDate();
    }

    public function setPostDateGmt($postDateGmt)
    {
        $this->__load();
        return parent::setPostDateGmt($postDateGmt);
    }

    public function getPostDateGmt()
    {
        $this->__load();
        return parent::getPostDateGmt();
    }

    public function setPostContent($postContent)
    {
        $this->__load();
        return parent::setPostContent($postContent);
    }

    public function getPostContent()
    {
        $this->__load();
        return parent::getPostContent();
    }

    public function setPostTitle($postTitle)
    {
        $this->__load();
        return parent::setPostTitle($postTitle);
    }

    public function getPostTitle()
    {
        $this->__load();
        return parent::getPostTitle();
    }

    public function setPostExcerpt($postExcerpt)
    {
        $this->__load();
        return parent::setPostExcerpt($postExcerpt);
    }

    public function getPostExcerpt()
    {
        $this->__load();
        return parent::getPostExcerpt();
    }

    public function setPostStatus($postStatus)
    {
        $this->__load();
        return parent::setPostStatus($postStatus);
    }

    public function getPostStatus()
    {
        $this->__load();
        return parent::getPostStatus();
    }

    public function setCommentStatus($commentStatus)
    {
        $this->__load();
        return parent::setCommentStatus($commentStatus);
    }

    public function getCommentStatus()
    {
        $this->__load();
        return parent::getCommentStatus();
    }

    public function setPingStatus($pingStatus)
    {
        $this->__load();
        return parent::setPingStatus($pingStatus);
    }

    public function getPingStatus()
    {
        $this->__load();
        return parent::getPingStatus();
    }

    public function setPostPassword($postPassword)
    {
        $this->__load();
        return parent::setPostPassword($postPassword);
    }

    public function getPostPassword()
    {
        $this->__load();
        return parent::getPostPassword();
    }

    public function setPostName($postName)
    {
        $this->__load();
        return parent::setPostName($postName);
    }

    public function getPostName()
    {
        $this->__load();
        return parent::getPostName();
    }

    public function setToPing($toPing)
    {
        $this->__load();
        return parent::setToPing($toPing);
    }

    public function getToPing()
    {
        $this->__load();
        return parent::getToPing();
    }

    public function setPinged($pinged)
    {
        $this->__load();
        return parent::setPinged($pinged);
    }

    public function getPinged()
    {
        $this->__load();
        return parent::getPinged();
    }

    public function setPostModified($postModified)
    {
        $this->__load();
        return parent::setPostModified($postModified);
    }

    public function getPostModified()
    {
        $this->__load();
        return parent::getPostModified();
    }

    public function setPostModifiedGmt($postModifiedGmt)
    {
        $this->__load();
        return parent::setPostModifiedGmt($postModifiedGmt);
    }

    public function getPostModifiedGmt()
    {
        $this->__load();
        return parent::getPostModifiedGmt();
    }

    public function setPostContentFiltered($postContentFiltered)
    {
        $this->__load();
        return parent::setPostContentFiltered($postContentFiltered);
    }

    public function getPostContentFiltered()
    {
        $this->__load();
        return parent::getPostContentFiltered();
    }

    public function setPostParent($postParent)
    {
        $this->__load();
        return parent::setPostParent($postParent);
    }

    public function getPostParent()
    {
        $this->__load();
        return parent::getPostParent();
    }

    public function setGuid($guid)
    {
        $this->__load();
        return parent::setGuid($guid);
    }

    public function getGuid()
    {
        $this->__load();
        return parent::getGuid();
    }

    public function setMenuOrder($menuOrder)
    {
        $this->__load();
        return parent::setMenuOrder($menuOrder);
    }

    public function getMenuOrder()
    {
        $this->__load();
        return parent::getMenuOrder();
    }

    public function setPostType($postType)
    {
        $this->__load();
        return parent::setPostType($postType);
    }

    public function getPostType()
    {
        $this->__load();
        return parent::getPostType();
    }

    public function setPostMimeType($postMimeType)
    {
        $this->__load();
        return parent::setPostMimeType($postMimeType);
    }

    public function getPostMimeType()
    {
        $this->__load();
        return parent::getPostMimeType();
    }

    public function setCommentCount($commentCount)
    {
        $this->__load();
        return parent::setCommentCount($commentCount);
    }

    public function getCommentCount()
    {
        $this->__load();
        return parent::getCommentCount();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function getModelName($obj)
    {
        $this->__load();
        return parent::getModelName($obj);
    }

    public function getEntityWithNamespace($obj)
    {
        $this->__load();
        return parent::getEntityWithNamespace($obj);
    }

    public function loadById($em, $id, $hydrated = false)
    {
        $this->__load();
        return parent::loadById($em, $id, $hydrated);
    }

    public function loadAll($em, $dataArray, $hydrated = false)
    {
        $this->__load();
        return parent::loadAll($em, $dataArray, $hydrated);
    }

    public function getMaxId($em)
    {
        $this->__load();
        return parent::getMaxId($em);
    }

    public function loadCount($em)
    {
        $this->__load();
        return parent::loadCount($em);
    }

    public function callAssociatedEntity($entity)
    {
        $this->__load();
        return parent::callAssociatedEntity($entity);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'postAuthor', 'postDate', 'postDateGmt', 'postContent', 'postTitle', 'postExcerpt', 'postStatus', 'commentStatus', 'pingStatus', 'postPassword', 'postName', 'toPing', 'pinged', 'postModified', 'postModifiedGmt', 'postContentFiltered', 'postParent', 'guid', 'menuOrder', 'postType', 'postMimeType', 'commentCount', 'id', 'featuredImage', 'images');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}