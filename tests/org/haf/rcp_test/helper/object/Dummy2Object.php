<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/13/14 3:08 PM
 */

namespace org\haf\rcp_test\helper\object;


class Dummy2Object extends DummyObject {
    protected $otherVar;
    protected $hiddenVar;
    private $privateVar2;

    protected function _getPropertiesName() {
        return array('otherVar', 'privateVar2');
    }

    protected function _getConstructParam() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
        );
    }


    /**
     * @param mixed $privateVar2
     */
    public function setPrivateVar2($privateVar2)
    {
        $this->privateVar2 = $privateVar2;
    }

    /**
     * @return mixed
     */
    public function getPrivateVar2()
    {
        return $this->privateVar2;
    }

} 