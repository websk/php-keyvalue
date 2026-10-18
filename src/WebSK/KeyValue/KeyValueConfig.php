<?php

namespace WebSK\KeyValue;

use WebSK\Config\ConfWrapper;

/**
 * Class KeyValueConfig
 * @package WebSK\KeyValue
 */
class KeyValueConfig
{
    public static function getAdminLayout(): string
    {
        return self::stringValue(
            'keyvalue.layout_admin',
            self::stringValue('layout.admin', '')
        );
    }

    public static function getAdminMainPageUrl(): string
    {
        return self::stringValue('keyvalue.admin_main_page_url', '/admin');
    }

    public static function getSiteName(): string
    {
        return self::stringValue('site_name', '');
    }

    public static function getSiteTitle(): string
    {
        return self::stringValue('site_title', '');
    }

    private static function stringValue(string $path, string $default): string
    {
        $value = ConfWrapper::value($path, $default);
        if (!is_string($value)) {
            throw new \UnexpectedValueException('Configuration value "' . $path . '" must be a string.');
        }

        return $value;
    }
}
