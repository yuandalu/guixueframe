<?php

class QFrameAction
{
    public function __construct()
    {
        $this->init();
    }

    /**
     * Dispatch the requested action
     *
     * @param string $action : Method name of action
     * @return void
     */

    public function dispatch($action)
    {
        $this->preDispatch();
        $data = $this->$action();
        if (is_array($data) || is_object($data)) {
            $this->setNoViewRender(true);
            header("content-type:application/json;charset=utf-8");
            $data = json_encode($data);
            $etag = md5($data);
            if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
                header("HTTP/1.1 304 Not Modified");
            } else {
                header("Etag: " . $etag);
                echo $data;
            }
        } else if (is_string($data) || is_numeric($data)) {
            $this->setNoViewRender(true);
            echo $data;
        }
        $this->postDispatch();
    }

    public function init()
    {
    }

    public function preDispatch()
    {
    }

    public function postDispatch()
    {
    }

    public static function htmlspecialcharsRecursive($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
        if (is_string($value)) {
            return htmlspecialchars($value);
        }
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = self::htmlspecialcharsRecursive($v);
            }

            return $value;
        }
        if (is_object($value)) {

            foreach ($value as $k => $v) {
                $value->$k = self::htmlspecialcharsRecursive($v);
            }

            return $value;
        }

        return $value;
    }

    /**
     * Assigns variables to the view script
     *
     * assign('name',$value) assigns a variable called 'name' with the corresponding $value.
     *
     * assign($array) assigns the array keys as variable names (with the corresponding array values)
     *
     * @param string|array
     * @param if assigning a named variable , use this as the value
     * @see __set()
     */

    public function assign($spec, $value = null, $dohtmlspecialchars = true)
    {
        if (is_string($spec)) {
            QFrameBizResult::ensureFalse('_' == substr($spec, 0, 1), "Setting private or protected class members is not allowed");
            if ($dohtmlspecialchars) {
                $value = self::htmlspecialcharsRecursive($value);
            }
            QFrameContainer::find('QFrameView')->$spec = $value;
        } elseif (is_array($spec)) {
            //TODO if(is_array($val))
            foreach ($spec as $key => $val) {
                QFrameBizResult::ensureFalse('_' == substr($key, 0, 1), "Setting private or protected class members is not allowed");
                if (is_string($val)) {
                    QFrameContainer::find('QFrameView')->$key = $dohtmlspecialchars ? htmlspecialchars($val) : $val;
                } else {
                    if ($dohtmlspecialchars) {
                        $val = self::htmlspecialcharsRecursive($val);
                    }
                    QFrameContainer::find('QFrameView')->$key = $val;
                }
            }
        }
    }

    /**
     * Set the noRender flag , if true, will not autorender views
     * @param boolean $flag
     */

    public function setNoViewRender($flag)
    {
        return QFrameContainer::find('QFrameView')->setNoRender($flag);
    }

    /**
     * Get cur Controller Name
     * @return string
     */

    public function getControllerName()
    {
        return QFrameWeb::$curController;
    }

    /**
     * Gets cur Action Name
     * @return string
     */

    public function getActionName()
    {
        return QFrameWeb::$curAction;
    }

    /**
     * Gets a parameter from {@link $_request Request object}. If the
     * parameter does not exist , NULL will be returned .
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */

    public function getParam($key, $default = null)
    {
        $value = QFrameContainer::find('QFrameHttp')->get($key);

        return (null == $value && null !== $default) ? $default : $value;
    }

    public function getRequest($key = null, $default = null)
    {
        $value = QFrameContainer::find('QFrameHttp')->getRequest($key, $default);

        return $value;
    }

    public function getPost($key = null, $default = null)
    {
        $value = QFrameContainer::find('QFrameHttp')->getPost($key, $default);

        return $value;
    }

    /**
     * Processes a view script
     *
     * @param string $name : The script script name to process
     *
     */

    public function render($name = null, $noController = false)
    {
        if (is_null($name)) {
            return;
        }
        QFrameContainer::find('QFrameView')->setControllerRender(true);

        return QFrameContainer::find('QFrameView')->render($name, $noController);
    }

    /**
     * Return a view script
     *
     * @param string $name : The script script name to process
     *
     */

    public function fetch($name = null, $noController = false)
    {
        if (is_null($name)) {
            return;
        }
        QFrameContainer::find('QFrameView')->setControllerRender(true);

        return QFrameContainer::find('QFrameView')->fetch($name, $noController);
    }
    /**
     * modify the default suffix of view script
     *
     * @param string $suffix : The script suffix name
     *
     */

    public function setViewSuffix($suffix)
    {
        if (empty($suffix)) {
            return false;
        }
        QFrameContainer::find('QFrameView')->setViewSuffix($suffix);
    }

    public function _forward($action, $controller = null)
    {
        if (null !== $controller) {
            QFrameContainer::find('QFrameWeb')->setControllerName($controller);
        }
        QFrameContainer::find('QFrameWeb')->setActionName($action);
        QFrameContainer::find('QFrameWeb')->setDispatched(false);
        QFrameContainer::find('QFrameWeb')->dispatch();
    }

    public function initView()
    {
        $this->view = QFrameContainer::find('QFrameView');
    }
}
