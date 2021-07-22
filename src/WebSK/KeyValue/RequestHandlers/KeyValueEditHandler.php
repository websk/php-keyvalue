<?php

namespace WebSK\KeyValue\RequestHandlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WebSK\KeyValue\KeyValueConfig;
use WebSK\Views\LayoutDTO;
use WebSK\Slim\RequestHandlers\BaseHandler;
use WebSK\Utils\HTTP;
use WebSK\Views\BreadcrumbItemDTO;
use WebSK\Views\NavTabItemDTO;
use WebSK\CRUD\CRUDServiceProvider;
use WebSK\CRUD\Form\CRUDFormRow;
use WebSK\CRUD\Form\Widgets\CRUDFormWidgetInput;
use WebSK\CRUD\Form\Widgets\CRUDFormWidgetTextarea;
use WebSK\KeyValue\KeyValue;
use WebSK\KeyValue\KeyValueServiceProvider;
use WebSK\Logger\LoggerRender;
use WebSK\Views\PhpRender;

/**
 * Class KeyValueEditHandler
 * @package VitrinaTV\KeyValue\RequestHandlers
 */
class KeyValueEditHandler extends BaseHandler
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $keyvalue_id
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, int $keyvalue_id)
    {
        $keyvalue_obj = KeyValueServiceProvider::getKeyValueService($this->container)->getById($keyvalue_id, false);
        if (!$keyvalue_obj) {
            return $response->withStatus(HTTP::STATUS_NOT_FOUND);
        }

        $crud_table_obj = CRUDServiceProvider::getCrud($this->container)->createForm(
            'keyvalue_edit_form_884772948',
            $keyvalue_obj,
            [
                new CRUDFormRow('Название', new CRUDFormWidgetInput(KeyValue::_NAME, false, true)),
                new CRUDFormRow('Описание', new CRUDFormWidgetInput(KeyValue::_DESCRIPTION)),
                new CRUDFormRow('Значение', new CRUDFormWidgetTextarea(KeyValue::_VALUE))
            ]
        );

        $crud_form_response = $crud_table_obj->processRequest($request, $response);
        if ($crud_form_response instanceof ResponseInterface) {
            return $crud_form_response;
        }

        $layout_dto = new LayoutDTO();
        $layout_dto->setTitle($keyvalue_obj->getName());
        $layout_dto->setContentHtml($crud_table_obj->html());

        $layout_dto->setNavTabsDtoArr([
            new NavTabItemDTO('Редактирование', $this->pathFor(KeyValueEditHandler::class, ['keyvalue_id' => $keyvalue_id])),
            new NavTabItemDTO('Журнал', LoggerRender::getLoggerLinkForEntityObj($keyvalue_obj), '_blank'),
        ]);

        $layout_dto->setBreadcrumbsDtoArr([
            new BreadcrumbItemDTO('Главная', KeyValueConfig::getAdminMainPageUrl()),
            new BreadcrumbItemDTO('Параметры', $this->pathFor(KeyValueListHandler::class))
        ]);

        return PhpRender::renderLayout($response, KeyValueConfig::getAdminLayout(), $layout_dto);
    }
}
