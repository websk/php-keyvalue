<?php

namespace WebSK\KeyValue;

use WebSK\Config\ConfWrapper;

/**
 * Class KeyValueConfig
 * @package WebSK\Auth
 */
class KeyValueConfig
{

    /**
     * @return string
     */
    public static function getMainLayout(): string
    {
        return ConfWrapper::value('keyvalue.layout_main', ConfWrapper::value('layout.main'));
    }

    /**
     * @return string
     */
    public static function getMainPageUrl(): string
    {
        return ConfWrapper::value('keyvalue.main_page_url', '/');
    }

    /**
     * @return string
     */
    public static function getSkifLayout(): string
    {
        return ConfWrapper::value('keyvalue.layout_skif', ConfWrapper::value('skif.layout'));
    }

    /**
     * @return string
     */
    public static function getSkifMainPageUrl(): string
    {
        return ConfWrapper::value('keyvalue.skif_main_page_url', '/admin');
    }
}
