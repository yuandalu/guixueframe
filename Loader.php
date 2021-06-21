<?php

class QFrameLoader
{
    public static function loadClass($classname)
    {
        $classpath = self::getClassPath();
        if (isset($classpath[$classname])) {
            include($classpath[$classname]);
        }
    }

    protected static function getClassPath()
    {
        static $classpath = array();
        if (!empty($classpath)) {
            return $classpath;
        }
        if (function_exists("apc_fetch")) {
            $classpath = apc_fetch("fw:autoload:application:3440");
            if ($classpath) {
                return $classpath;
            }

            $classpath = self::getClassMapDef();
            apc_store("fw:autoload:application:3440", $classpath);
        } else {
            if (function_exists("eaccelerator_get")) {
                $classpath = eaccelerator_get("fw:autoload:application:3440");
                if ($classpath) {
                    return $classpath;
                }

                $classpath = self::getClassMapDef();
                eaccelerator_put("fw:autoload:application:3440", $classpath);
            } else {
                $classpath = self::getClassMapDef();
            }
        }

        return $classpath;
    }

    protected static function getClassMapDef()
    {
        return array(
            "QFrame"                   => "QFrame.php",
            "QFrameBase"               => "QFramebase.php",
            "QFrameConfig"             => "base/QFrameconfig.php",
            "QFrameHttp"               => "base/QFramehttp.php",
            "QFrameStandRoute"         => "base/QFramerouteutils.php",
            "QFrameRouteRegex"         => "base/QFramerouteutils.php",
            "QFrameContainer"          => "base/QFrameutils.php",
            "QFrameBizResult"          => "base/QFrameutils.php",
            "QFrameRunException"       => "base/QFramexception.php",
            "QFrameException"          => "base/QFramexception.php",
            "QFrameDB"                 => "base/db/QFrameDB.php",
            "QFrameDBPDO"              => "base/db/QFrameDB.php",
            "QFrameDBStatment"         => "base/db/QFrameDB.php",
            "QFrameDBException"        => "base/db/QFrameDB.php",
            "QFrameDBExplainResult"    => "base/db/QFrameDBExplainResult.php",
            "QFrameLog"                => "logger/QFrameLog.php",
            "FirePHP"                  => "logger/writers/FirePHP.class.php",
            "QFrameLogWriterDisplay"   => "logger/writers/QFrameLogWriterDisplay.php",
            "QFrameLogWriterFile"      => "logger/writers/QFrameLogWriterFile.php",
            "QFrameLogWriterFirephp"   => "logger/writers/QFrameLogWriterFirephp.php",
            "QFramedbTest"             => "t/QFramedbTest.php",
            "AllTests"                 => "t/QFramedbTestSuite.php",
            "QFrameAction"             => "web/QFrameaction.php",
            "QFrameRouter"             => "web/QFramerouter.php",
            "QFrameRouterDefaultRoute" => "web/QFramerouter.php",
            "QFrameView"               => "web/QFrameview.php",
            "QFrameWeb"                => "web/QFrameweb.php",

        );
    }
}
