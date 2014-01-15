<?php
/**
 * haf-rcp
 * copyright (c) 2014 abie
 *
 * @author abie
 * @date 1/13/14 2:59 PM
 */
namespace org\haf\rcp_test\object;

class SerializableObjectTest extends \PHPUnit_Framework_TestCase {
    public function testToArray1() {
        $obj1 = new \org\haf\rcp_test\helper\object\DummyObject(1, 'Obj1');

        $obj1Arr = $obj1->toArray();

        $this->assertArrayHasKey('__construct', $obj1Arr);
        $this->assertNull($obj1Arr['__construct']);

        $this->assertArrayHasKey('__prop', $obj1Arr);
        $this->assertArrayHasKey('id', $obj1Arr['__prop']);
        $this->assertSame(1, $obj1Arr['__prop']['id']);

        $this->assertArrayHasKey('nullVar', $obj1Arr['__prop']);
        $this->assertNull($obj1Arr['__prop']['nullVar']);

        $this->assertArrayNotHasKey('privateVar', $obj1Arr['__prop']);

        return $obj1Arr;
    }

    public function testToArray2() {
        $obj2 = new \org\haf\rcp_test\helper\object\Dummy2Object(2, 'Obj2');
        $obj2Arr = $obj2->toArray();

        $this->assertArrayHasKey('__construct', $obj2Arr);
        $this->assertArrayHasKey('id', $obj2Arr['__construct']);
        $this->assertArrayHasKey('name', $obj2Arr['__construct']);
        $this->assertSame(2, $obj2Arr['__construct']['id']);
        $this->assertSame('Obj2', $obj2Arr['__construct']['name']);

        $this->assertArrayHasKey('__prop', $obj2Arr);
        $this->assertArrayHasKey('otherVar', $obj2Arr['__prop']);
        $this->assertArrayHasKey('privateVar2', $obj2Arr['__prop']);
        $this->assertArrayNotHasKey('id', $obj2Arr['__prop']);

        //$this->logicalAnd()->setConstraints()

        return $obj2Arr;
    }


    /**
     * @param array $arr
     * @depends testToArray1
     * @depends org\haf\rcp_test\object\ObjectTest::testNameStatic
     */
    public function testFromArray1(array $arr)
    {
        /** @var \org\haf\rcp_test\helper\object\DummyObject $obj */
        $obj = \org\haf\rcp_test\helper\object\DummyObject::fromArray($arr);
        $this->assertInstanceOf(\org\haf\rcp_test\helper\object\DummyObject::_name(), $obj);
        $this->assertSame(1, $obj->getId());
        $this->assertSame('Obj1', $obj->getName());
    }

    /**
     * @param array $arr
     * @depends testToArray2
     * @depends org\haf\rcp_test\object\ObjectTest::testNameStatic
     */
    public function testFromArray2(array $arr)
    {
        /** @var \org\haf\rcp_test\helper\object\Dummy2Object $obj */
        $obj = \org\haf\rcp_test\helper\object\Dummy2Object::fromArray($arr);
        $this->assertInstanceOf(\org\haf\rcp_test\helper\object\Dummy2Object::_name(), $obj);
        $this->assertSame(2, $obj->getId());
        $this->assertSame('Obj2', $obj->getName());
    }
}
 