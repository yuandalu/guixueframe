<?php

class QFrameContainer
{
    private $_objs = array();

    static public function getInstance()
    {
        static $container = null;
        if (is_null($container)) {
            $container = new QFrameContainer();
        }

        return $container;
    }

    static public function find($name)
    {
        $container = self::getInstance();

        return $container->get($name);
    }

    private function get($name)
    {
        if (!isset($this->_objs[$name])) {
            $this->set($name);
        }

        return $this->_objs[$name];
    }

    private function set($name)
    {
        $this->_objs[$name] = new $name;
    }

}

class QFrameBizResult
{
    static public function ensureNull($result, $msg)
    {
        if (!is_null($result)) {
            throw new QFrameRunException($msg);
        }
    }

    static public function ensureNotNull($result, $msg)
    {
        if (is_null($result)) {
            throw new QFrameRunException($msg);
        }
    }

    static public function ensureNotFalse($result, $msg)
    {
        if (false === $result) {
            throw new QFrameRunException($msg);
        }
    }

    static public function ensureFalse($result, $msg)
    {
        if (false !== $result) {
            throw new QFrameRunException($msg);
        }
    }

    static public function ensureEmpty($result, $msg)
    {
        if (true !== empty($result)) {
            throw new QFrameRunException($msg);
        }
    }

    static public function ensureNotEmpty($result, $msg)
    {
        if (true === empty($result)) {
            throw new QFrameRunException($msg);
        }
    }
}
