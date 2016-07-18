<?php

namespace Adadgio\GraphBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Graph
{
    /**
     * @var array Properties keys and values.
     */
    private $properties;

    /**
     * @var array Entity field property name
     */
    private $fieldName;

    /**
     * Catch annotations from the entity.
     *
     * @param array Annotations options
     * @return void
     */
    public function __construct($options)
    {
        $this->properties = array();

        foreach($options as $key => $value) {
            $this->properties[$key] = $value;
        }
    }
    
    /**
     * Get entity field name on which the annotation is.
     *
     * @param  string
     * @return \Graph
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Get entity field name on which the annotation is.
     *
     * @param  string
     * @return \Graph
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Get annotation properties.
     *
     * @return array Annotation properties and values
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Get annotation property by key access.
     *
     * @return mixed Property value
     */
    public function getProperty()
    {
        return isset($this->properties[$key]) ? $this->properties[$key] : null;
    }

    /**
     * Get annotation property by key access.
     *
     * @param  string Property key (name)
     * @param  mixed  Property value
     * @return object \Graph
     */
    public function setProperty($key, $value)
    {
        $this->properties[$key] = $value;

        return $this;
    }

    /**
     * Has annotation property
     *
     * @return boolean
     */
    public function hasProperty($key)
    {
        return isset($this->properties[$key]);
    }
}
