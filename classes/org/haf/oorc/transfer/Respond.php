<?php
/**
 * HafSoft Object Oriented Remote Call - PHP Implementation
 * Copyright (c) 2014 Abi Hafshin <abiehafshin@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace org\haf\oorc\transfer;

use org\haf\oorc\base\Exception;
use org\haf\oorc\object\SerializableObject;
use org\haf\oorc\object\TObject;
use org\haf\oorc\serializer\IArraiable;
use org\haf\oorc\serializer\ISerializable;
use org\haf\oorc\util\Dict;

class Respond extends SerializableObject implements ISerializable, IArraiable
{

    const STATUS_OK  = 'OK';
    const STATUS_ERR = 'ERR';

    /** @var  string */
    protected $status;

    /** @var  mixed */
    protected $data = null;

    /** @var  Exception */
    protected $error = null;

    /** @var  Dict */
    protected $extraData = null;


    public function __construct($status = self::STATUS_OK)
    {
        $this->status = $status;
    }

    public function isError()
    {
        return $this->status != self::STATUS_OK;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->status = self::STATUS_ERR;
        $this->error  = $error;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }


    public function &getExtraData()
    {
        if ($this->extraData == null) {
            $this->extraData = new Dict();
        }
        return $this->extraData;
    }

    public function setExtraData($extraData)
    {
        $this->extraData = $extraData;
    }

}