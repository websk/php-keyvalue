<?php

namespace WebSK\KeyValue\RequestHandlers;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WebSK\CRUD\CRUD;
use WebSK\KeyValue\KeyValueConfig;
use WebSK\KeyValue\KeyValueService;
use WebSK\Views\LayoutDTO;
use WebSK\Slim\RequestHandlers\BaseHandler;
use WebSK\Views\BreadcrumbItemDTO;
use WebSK\Views\NavTabItemDTO;
use WebSK\CRUD\Form\CRUDFormRow;
use WebSK\CRUD\Form\Widgets\CRUDFormWidgetInput;
use WebSK\CRUD\Form\Widgets\CRUDFormWidgetTextarea;
use WebSK\KeyValue\KeyValue;
use WebSK\Logger\LoggerRender;
use WebSK\Views\PhpRender;

/**
 * Class KeyValueEditHandler
 * @package VitrinaTV\KeyValue\RequestHandlers
 */
class KeyValueEditHandler extends BaseHandler
{
    /** @Inject */
    protected KeyValueService $key_value_service;

    /** @Inject */
    protected CRUD $crud_service;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $keyvalue_id
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, int $keyvalue_id)
    {
        $keyvalue_obj = $this->key_value_service->getById($keyvalue_id, false);
        if (!$keyvalue_obj) {
            return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        $crud_table_obj = $this->crud_service->createForm(
            'keyvalue_edit_form',
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
            new NavTabItemDTO('Редактирование', $this->urlFor(KeyValueEditHandler::class, ['keyvalue_id' => $keyvalue_id])),
            new NavTabItemDTO('Журнал', LoggerRender::getLoggerLinkForEntityObj($keyvalue_obj), '_blank'),
        ]);

        $layout_dto->setBreadcrumbsDtoArr([
            new BreadcrumbItemDTO('Главная', KeyValueConfig::getAdminMainPageUrl()),
            new BreadcrumbItemDTO('Параметры', $this->urlFor(KeyValueListHandler::class))
        ]);

        return PhpRender::renderLayout($response, KeyValueConfig::getAdminLayout(), $layout_dto);
    }
}
