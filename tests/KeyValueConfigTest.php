<?php

namespace WebSK\KeyValue\Tests;

use PHPUnit\Framework\TestCase;
use WebSK\Config\ConfWrapper;
use WebSK\KeyValue\KeyValueConfig;

final class KeyValueConfigTest extends TestCase
{
    protected function tearDown(): void
    {
        ConfWrapper::setConfig([]);
    }

    public function testReadsConfiguredStringValues(): void
    {
        ConfWrapper::setConfig([
            'keyvalue' => [
                'layout_admin' => '/views/admin.php',
                'admin_main_page_url' => '/control',
            ],
            'site_name' => 'Example',
            'site_title' => 'Example title',
        ]);

        self::assertSame('/views/admin.php', KeyValueConfig::getAdminLayout());
        self::assertSame('/control', KeyValueConfig::getAdminMainPageUrl());
        self::assertSame('Example', KeyValueConfig::getSiteName());
        self::assertSame('Example title', KeyValueConfig::getSiteTitle());
    }

    public function testUsesDefaults(): void
    {
        ConfWrapper::setConfig([]);

        self::assertSame('', KeyValueConfig::getAdminLayout());
        self::assertSame('/admin', KeyValueConfig::getAdminMainPageUrl());
        self::assertSame('', KeyValueConfig::getSiteName());
        self::assertSame('', KeyValueConfig::getSiteTitle());
    }

    public function testRejectsNonStringValue(): void
    {
        ConfWrapper::setConfig(['site_name' => ['invalid']]);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('site_name');

        KeyValueConfig::getSiteName();
    }
}
