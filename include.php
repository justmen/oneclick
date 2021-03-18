<?php
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Config\Option;

$moduleId = "ligacom.buyoneclick";

Asset::getInstance()->addCss('/bitrix/css/ligacom/buyoneclick.css');
Asset::getInstance()->addJs('/bitrix/js/ligacom/buyoneclick.js');

$rechapchaUse = Option::get($moduleId, 'recaptcha_use', '', SITE_ID);
$rechapchaPublic = Option::get($moduleId, 'recapcha_public_key', '', SITE_ID);
$rechapchaPrivate = Option::get($moduleId, 'recapcha_private_key', '', SITE_ID);
if ($rechapchaUse === 'Y' && $rechapchaPublic !== '' && $rechapchaPrivate !== '') {
    Asset::getInstance()->addJs('https://www.google.com/recaptcha/api.js?render='.$rechapchaPublic);
}

