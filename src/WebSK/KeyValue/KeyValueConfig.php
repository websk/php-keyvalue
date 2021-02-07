<?php

namespace WebSK\KeyValue;

use WebSK\Config\ConfWrapper;

/**
 * Class KeyValueConfig
 * @package WebSK\KeyValue
 */
class KeyValueConfig
{

    /**
     * @return string
     */
    public static function getLayout(): string
    {
        return ConfWrapper::value('keyvalue.layout',  ConfWrapper::value('layout.admin'));
    }

    /**
     * @return string
     */
    public static function getMainPageUrl(): string
    {
        return ConfWrapper::value('keyvalue.main_page_url',  '');
    }
}
