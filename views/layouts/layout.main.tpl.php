<?php
/**
 * @var LayoutDTO $layout_dto
 */

use WebSK\Utils\Messages;
use WebSK\KeyValue\KeyValueConfig;
use WebSK\Utils\Sanitize;
use WebSK\Views\LayoutDTO;
use WebSK\Utils\Url;
?>
<!DOCTYPE html>
<html lang="ru">
<head xmlns:og="http://ogp.me/ns#">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title><?= Sanitize::sanitizeTagContent($layout_dto->getTitle()) ?></title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>

    <meta name="keywords" content="<?php echo $layout_dto->getKeywords() ?>"/>
    <meta name="description" content="<?php echo $layout_dto->getDescription() ?>"/>
    <style>
        body {margin:0; padding:0; font-family: Arial; background-color: #fff;}
        #html {width: 90%; margin: auto; font-size: 12px; line-height: 18px; margin-bottom: 30px;}
        #content {width: 98%; margin-bottom: 30px; font-size: 14px;}
        #header {margin: 20px 0;}
        #footer {margin-top: 20px; background-color: #C0C0C0; color: #ffffff; padding: 10px}
    </style>
</head>
<body>

<div id="html">
    <div id="header" class="row">
        <div style="font-size: 22px;"><?= Sanitize::sanitizeTagContent(KeyValueConfig::getSiteTitle()) ?></div>
    </div>

    <div>
        <div class="row">
            <div id="content">
                <ol class="breadcrumb">
                    <?php
                    foreach ($layout_dto->getBreadcrumbsDtoArr() as $breadcrumb_item_dto) {
                        if (!$breadcrumb_item_dto->getUrl()) {
                            echo '<li class="active">'
                                . Sanitize::sanitizeTagContent($breadcrumb_item_dto->getName())
                                . '</li>';
                            continue;
                        }

                        echo '<li><a href="' . Sanitize::sanitizeUrl($breadcrumb_item_dto->getUrl()) . '">' . Sanitize::sanitizeTagContent($breadcrumb_item_dto->getName()) . '</a></li>';
                    }
                    ?>
                </ol>
                <?php

                $current_url_no_query = Url::getUriNoQueryString();

                if ($current_url_no_query != '/') {
                    ?>
                    <h1><?= Sanitize::sanitizeTagContent($layout_dto->getTitle()) ?></h1>
                    <hr class="hidden-xs hidden-sm">
                    <?php
                }
                ?>

                <?php
                echo Messages::renderMessages();
                ?>

                <div>
                    <ul class="nav nav-tabs">
                        <?php
                        foreach ($layout_dto->getNavTabsDtoArr() as $nav_tab_item_dto) {
                            ?>
                            <li role="presentation" <?php echo (strpos($current_url_no_query, $nav_tab_item_dto->getUrl()) !== false ? ' class="active"' : '') ?>>
                                <a href="<?= Sanitize::sanitizeUrl($nav_tab_item_dto->getUrl()) ?>"><?= Sanitize::sanitizeTagContent($nav_tab_item_dto->getName()) ?></a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>


                <?php echo $layout_dto->getContentHtml(); ?>
            </div>
        </div>
    </div>

    <div id="footer" class="row">
        <div>
            &copy; <?= Sanitize::sanitizeTagContent(KeyValueConfig::getSiteName()) ?>, <?= date('Y') ?>
        </div>
    </div>
</div>

</body>
</html>
