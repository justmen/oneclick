<?php

namespace Ligacom\Buyoneclick\Config;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserConsent\Agreement;

Loc::loadMessages(__FILE__);
Loader::includeSharewareModule("ligacom_buyoneclick");

class Config
{
    static function getFormParams()
    {
        return [
            "active" => [
                "type" => "checkbox",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ACTIVE"),
                "description" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ACTIVE_DESC"),
                "values" => ['Y', 'N'],
                "required" => false,
                "default" => "N",
                "system" => true,
            ],
            "formTitle" => [
                "type" => "text",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_FORMTITLE_NAME"),
                "description" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_FORMTITLE_NAME_DESC"),
                "values" => [],
                "required" => false,
                "default" => "",
            ],
            "buttonTitle" => [
                "type" => "text",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_BUTTONTITLE_NAME"),
                "description" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_BUTTONTITLE_NAME_DESC"),
                "values" => [],
                "required" => false,
                "default" => "",
            ],
            "personType" => [
                "type" => "list",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_PERSON_TYPE"),
                "description" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_PERSON_TYPE_DESC"),
                "values" => [],
                "required" => true,
                "default" => "",
            ],
            "titleRecaptcha" => [
                "type" => "titleline",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_RECAPTHA_TITLE"),
                "description" => "",
                "values" => [],
                "required" => false,
                "default" => "",
            ],
            'recaptcha_use' => [
                'type' => 'checkbox',
                'name' => 'Использовать recaptha',
                'description' => '',
                'values' => ['Y', 'N'],
                'required' => false,
                'default' => 'N'
            ],
            'recaptcha_include_script' => [
                'type' => 'checkbox',
                'name' => 'Подключить script google recaptcha v3',
                'description' => 'Настройка может понадобится, если у вас уже подключается recaptha из другого места.
                В таком случае следует отключить эту настройку. В остальных случаях, при использовании repatcha  этот
                флаг должен быть включен',
                'values' => ['Y', 'N'],
                'required' => false,
                'default' => 'Y',
            ],
            'recapcha_public_key' => [
                'type' => 'text',
                'name' => 'Публичный ключ',
                'description' => [],
                'required' => false,
                'default' => '',
            ],
            'recapcha_private_key' => [
                'type' => 'text',
                'name' => 'Приватный ключ',
                'description' => [],
                'required' => false,
                'default' => ''
            ],
            'recaptcha_scope' => [
                'type' => 'range',
                'name' => 'Индекс спам запроса (0.0 - 1.0)',
                'description' => 'Чем больше индекс, тем более вероятно, что это человек. В этом поле указывается от какого
                показателя, (по-умолчанию 0.5) считать, что форму заполнил человек, а не бот',
                'required' => false,
                'min' => 0,
                'max' => 1,
                'step' => 0.1,
                'default' => 0.5
            ],
            "titleСonsent" => [
                "type" => "titleline",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ORDER_TITLE_CONSENT"),
                "description" => "",
                "values" => [],
                "required" => false,
                "default" => "",
            ],
            "showConsent" => [
                "type" => "checkbox",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ACTIVE_CONSENT"),
                "description" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ACTIVE_CONSENT_DESC"),
                "values" => ['Y', 'N'],
                "required" => false,
                "default" => "N",
            ],
            "consentId" => [
                "type" => "list",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ID_CONSENT"),
                "description" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ID_CONSENT_DESC"),
                "values" => Agreement::getActiveList(),
                "required" => false,
                "default" => "",
            ],
            "consentIsChecked" => [
                "type" => "checkbox",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_IS_CHECKED_CONSENT"),
                "description" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_IS_CHECKED_CONSENT_DESC"),
                "values" => ['Y', 'N'],
                "required" => false,
                "default" => "N",
            ],
            "consenAutoSave" => [
                "type" => "checkbox",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_AUTO_SAVE_CONSENT"),
                "description" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_AUTO_SAVE_CONSEN_DESC"),
                "values" => ['Y', 'N'],
                "required" => false,
                "default" => "N",
            ],
            "titleOrder" => [
                "type" => "titleline",
                "name" => Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ORDER_TITLE"),
                "description" => "",
                "values" => [],
                "required" => false,
                "default" => "",
            ],
             'name' => [
                 'code' => 'name',
                 'name' => Loc::getMessage('LIGACOM_BUYONECLICK_SETTING_PERSON_FIELD_NAME'),
                 'field1Click' => true,
                 'type' => 'text',
             ],
             'phone' => [
                 'code' => 'phone',
                 'name' => Loc::getMessage('LIGACOM_BUYONECLICK_SETTING_PERSON_FIELD_PHONE'),
                 'type' => 'text',
                 'field1Click' => true,
             ],
             'mail' => [
                 'code' => 'mail',
                 'name' => Loc::getMessage('LIGACOM_BUYONECLICK_SETTING_PERSON_FIELD_MAIL'),
                 'type' => 'text',
                 'field1Click' => true,
             ],
             'comment' => [
                 'code' => 'comment',
                 'name' => Loc::getMessage('LIGACOM_BUYONECLICK_SETTING_PERSON_FIELD_COMMENT'),
                 'orderField' => 'USER_DESCRIPTION',
                 'type' => 'textarea',
                 'field1Click' => true,
             ],
        ];
    }
}
