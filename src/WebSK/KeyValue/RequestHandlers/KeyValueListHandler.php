<?php

namespace WebSK\KeyValue\RequestHandlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WebSK\KeyValue\KeyValueConfig;
use WebSK\Slim\RequestHandlers\BaseHandler;
use WebSK\Views\LayoutDTO;
use WebSK\Views\BreadcrumbItemDTO;
use WebSK\CRUD\CRUDServiceProvider;
use WebSK\CRUD\Form\CRUDFormRow;
use WebSK\CRUD\Form\Widgets\CRUDFormWidgetInput;
use WebSK\CRUD\Form\Widgets\CRUDFormWidgetTextarea;
use WebSK\CRUD\Table\CRUDTableColumn;
use WebSK\CRUD\Table\Widgets\CRUDTableWidgetDelete;
use WebSK\CRUD\Table\Widgets\CRUDTableWidgetText;
use WebSK\CRUD\Table\Widgets\CRUDTableWidgetTextWithLink;
use WebSK\KeyValue\KeyValue;
use WebSK\Views\PhpRender;

/**
 * Class KeyValueListHandler
 * @package WebSK\KeyValue\RequestHandlers
 */
class KeyValueListHandler extends BaseHandler
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $crud_table_obj = CRUDServiceProvider::getCrud($this->container)->createTable(
            KeyValue::class,
            CRUDServiceProvider::getCrud($this->container)->createForm(
                'keyvalue_create_rand8476256485',
                new KeyValue(),
                [
                    new CRUDFormRow('Название', new CRUDFormWidgetInput(KeyValue::_NAME, false, true)),
                    new CRUDFormRow('Описание', new CRUDFormWidgetInput(KeyValue::_DESCRIPTION)),
                    new CRUDFormRow('Значение', new CRUDFormWidgetTextarea(KeyValue::_VALUE))
                ]
            ),
            [
                new CRUDTableColumn(
                    'Название',
                    new CRUDTableWidgetTextWithLink(
                        KeyValue::_NAME,
                        function (KeyValue $key_value) {
                            return $this->pathFor(KeyValueEditHandler::class, ['keyvalue_id' => $key_value->getId()]);
                        }
                    )
                ),
                new CRUDTableColumn(
                    'Описание',
                    new CRUDTableWidgetText(
                        KeyValue::_DESCRIPTION
                    )
                ),
                new CRUDTableColumn('', new CRUDTableWidgetDelete())
            ]
        );

        $crud_form_response = $crud_table_obj->processRequest($request, $response);
        if ($crud_form_response instanceof ResponseInterface) {
            return $crud_form_response;
        }

        $layout_dto = new LayoutDTO();
        $layout_dto->setTitle('Параметры');
        $layout_dto->setContentHtml($crud_table_obj->html($request));

        $breadcrumbs_arr = [
            new BreadcrumbItemDTO('Главная', KeyValueConfig::getAdminMainPageUrl()),

        ];
        $layout_dto->setBreadcrumbsDtoArr($breadcrumbs_arr);

        return PhpRender::renderLayout($response, KeyValueConfig::getAdminLayout(), $layout_dto);
    }
}
