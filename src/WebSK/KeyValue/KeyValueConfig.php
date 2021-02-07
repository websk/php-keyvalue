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
    public static function getAdminLayout(): string
    {
        return ConfWrapper::value('keyvalue.layout_admin',  ConfWrapper::value('layout.admin'));
    }

    /**
     * @return string
     */
    public static function getAdminMainPageUrl(): string
    {
        return ConfWrapper::value('keyvalue.admin_main_page_url',  '/admin');
    }
}
