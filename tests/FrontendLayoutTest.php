<?php

namespace WebSK\KeyValue\Tests;

use PHPUnit\Framework\TestCase;
use WebSK\Config\ConfWrapper;
use WebSK\Views\BreadcrumbItemDTO;
use WebSK\Views\LayoutDTO;
use WebSK\Views\NavTabItemDTO;

final class FrontendLayoutTest extends TestCase
{
    protected function tearDown(): void
    {
        ConfWrapper::setConfig([]);
        unset($_SERVER['REQUEST_URI']);
        $_COOKIE = [];
    }

    public function testLayoutUsesLocalBootstrapFiveAssetsAndMarkup(): void
    {
        ConfWrapper::setConfig([
            'site_name' => 'KeyValue',
            'site_title' => 'KeyValue Admin',
        ]);
        $_SERVER['REQUEST_URI'] = '/admin/keyvalue/12?tab=edit';
        $_COOKIE = [];

        $layout_dto = new LayoutDTO();
        $layout_dto->setTitle('Edit setting');
        $layout_dto->setContentHtml('<form class="row"><input class="form-control"></form>');
        $layout_dto->setBreadcrumbsDtoArr([
            new BreadcrumbItemDTO('Admin', '/admin'),
            new BreadcrumbItemDTO('Settings'),
        ]);
        $layout_dto->setNavTabsDtoArr([
            new NavTabItemDTO('Edit', '/admin/keyvalue/12'),
            new NavTabItemDTO('Log', '/admin/logger/object/12', '_blank'),
        ]);

        ob_start();
        require __DIR__ . '/../views/layouts/layout.main.tpl.php';
        $html = ob_get_clean();

        self::assertIsString($html);
        self::assertStringContainsString('/assets/libraries/bootstrap/css/bootstrap.min.css', $html);
        self::assertStringContainsString('/assets/libraries/bootstrap/js/bootstrap.bundle.min.js', $html);
        self::assertStringContainsString('/assets/libraries/crud/crud.css', $html);
        self::assertStringContainsString('/assets/libraries/logger/logger.css', $html);
        self::assertStringContainsString('class="breadcrumb-item active" aria-current="page"', $html);
        self::assertStringContainsString('class="nav-link active"', $html);
        self::assertStringContainsString('target="_blank"', $html);
        self::assertStringContainsString('class="form-control"', $html);
        self::assertStringNotContainsString('bootstrap/3.', $html);
        self::assertStringNotContainsString('https://', $html);
    }
}
