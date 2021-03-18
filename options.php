<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\HtmlFilter;
use Ligacom\Buyoneclick\Config\Form;
use Ligacom\Buyoneclick\Config\Options;

/**
 * @var string $mid
 * @var string $reset
 * @var string $restore
 * @var string $save
 */

global $APPLICATION;
global $USER;

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'ligacom.buyoneclick');

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

Loader::includeSharewareModule(ADMIN_MODULE_NAME);

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

Loc::loadMessages($context->getServer()->getDocumentRoot() . "/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);

$settings = [];
$tabs = [];
$form = new Form();
$tabs = $form->getTabs();

$tabControl = new CAdminTabControl("tabControl", $tabs);

if ((!empty($save) || !empty($restore)) && $request->isPost() && check_bitrix_sessid()) {
    if (!empty($restore)) { //восстановить настройки по умолчанию

    }
    if (!empty($save)) {
        $form->saveOptions();
    }
}
//Loc::getMessage("REFERENCES_OPTIONS_RESTORED")
//CAdminMessage::showMessage(Loc::getMessage("REFERENCES_INVALID_VALUE"));

?>

<form id="form_<?=$form->getPrefix()?>"
      method="post"
      action='<?=$APPLICATION->GetCurPageParam("lang=".$request["lang"]."&mid=".$request["mid"], ["lang", "mid", "okSave"]) ?>'
      name="<?=$form->getPrefix()?>_settings">
    <?php
    $tabControl->begin();
    foreach ($tabs as $tab) {
        $tabControl->BeginNextTab();
        $form->setSiteId($tab["LID"]);
        echo $form->getTab();
    }
    echo bitrix_sessid_post();

    $tabControl->buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?= Loc::getMessage("MAIN_SAVE") ?>"
           title="<?= Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
    />
    <input type="submit"
           name="restore"
           title="<?= Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
           onclick="return confirm('<?= AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING")) ?>')"
           value="<?= Loc::getMessage("MAIN_RESTORE_DEFAULTS") ?>"
    />
    <input type="reset"
           name="reset"
           value="<?=Loc::getMessage("LIGACOM_BUYONECLICK_OPTION_BUTTON_CANCEL_NAME"); ?>" />

    <?php
    $tabControl->end();
    ?>
</form>
