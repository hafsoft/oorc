<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/13/14 2:17 PM
 */

namespace org\haf\rcp_test\object;

class ObjectTest extends \PHPUnit_Framework_TestCase {

    public function testNameStatic() {
        $this->assertSame('org\haf\rcp_test\helper\object\DummyObject', \org\haf\rcp_test\helper\object\DummyObject::_name());
        $this->assertSame('org.haf.rcp_test.helper.object.DummyObject', \org\haf\rcp_test\helper\object\DummyObject::_name(true));
    }

    public function testGetName() {
        $obj1 = new \org\haf\rcp_test\helper\object\DummyObject();
        $this->assertSame('org\haf\rcp_test\helper\object\DummyObject', $obj1->className());
        $this->assertSame('org.haf.rcp_test.helper.object.DummyObject', $obj1->className(true));
    }

    /**
     * @depends org\haf\rcp_test\object\ObjectTest::testNameStatic
     */
    public function testMagicGet() {
        $obj1 = new \org\haf\rcp_test\helper\object\DummyObject(1, "Obj1");

        $this->assertSame(1, $obj1->id);
        $this->assertSame("Obj1", $obj1->name);

        $this->setExpectedException(\org\haf\oorc\object\PropertyNotFoundException::_name());
        $email = $obj1->email;
    }

    /**
     * @depends org\haf\rcp_test\object\ObjectTest::testNameStatic
     */
    public function testMagicSet() {
        $obj1 = new \org\haf\rcp_test\helper\object\DummyObject(1, "Obj1");

        $obj1->name = 'NewName';
        $this->assertSame('NewName', $obj1->getName());

        $this->setExpectedException(\org\haf\oorc\object\PropertyIsReadOnlyException::_name());
        $obj1->id = 2;

        $this->setExpectedException(\org\haf\oorc\object\PropertyNotFoundException::_name());
        $obj1->email = 'foo@bar.com';
    }

}
 