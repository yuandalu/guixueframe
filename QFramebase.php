<?php

class QFrameBase
{
    public function __construct()
    {
    }

    public static function getVersion()
    {
        return '0.1.0';
    }

    public static function createWebApp()
    {
        return QFrameContainer::find('QFrameWeb');
    }

}
