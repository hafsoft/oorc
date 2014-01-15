<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/13/14 2:18 PM
 */

namespace org\haf\rcp_test\helper\object;


use org\haf\oorc\object\Object;
use org\haf\oorc\object\SerializableObject;

/**
 * Class DummyObject
 *
 * @package object
 * @property $id int
 * @property $name string
 */
class DummyObject extends SerializableObject {

    protected $id;
    protected $name;
    protected $nullVar;
    private $privateVar = true;

    function __construct($id = 123, $name = 'Dummy')
    {
        $this->id   = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

} 