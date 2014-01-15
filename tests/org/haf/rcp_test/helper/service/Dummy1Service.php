<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/12/14 9:09 PM
 */


namespace org\haf\rcp_test\helper\service;

use org\haf\oorc\object\Object;
use org\haf\rcp_test\helper\object\DummyObject;

class Dummy1Service extends Object implements \org\haf\oorc\service\IService {

    /**
     * @param string $methodName
     * @return bool
     */
    public function isRemoteAllowed($methodName)
    {
        if ($methodName == 'restrictedMethod') {
            return false;
        }
        else {
            return true;
        }
    }

    public function getDummy($id = 123, $name = 'Dummy') {
        return new DummyObject($id, $name);
    }

    public function restrictedMethod() {
        return true;
    }
}