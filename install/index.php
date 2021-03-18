<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use \Bitrix\Main\UserGroupTable;
use \Bitrix\Main\GroupTable;
use Bitrix\Main\UserTable;

Loc::loadMessages(__FILE__);

class ligacom_buyoneclick extends CModule
{
    private string $userLogin = 'LIGACOM_BUYONECLICK';

    public function __construct()
    {
        $arModuleVersion = [];
        
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        
        $this->MODULE_ID = 'ligacom.buyoneclick';
        $this->MODULE_NAME = Loc::getMessage('LIGACOM_BUYONECLICK_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('LIGACOM_BUYONECLICK_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('LIGACOM_BUYONECLICK_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'http://liga-com';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDb();
    }
    function installDb() :void
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            $newGroup = GroupTable::add([
                'NAME' => 'Служебная: для купить в 1 клик',
                'ACTIVE' => 'Y',
                'IS_SYSTEM' => 'Y',
                'C_SORT' => 400,
                'STRING_ID' => $this->userLogin
            ]);

            if (!$newGroup->isSuccess()) return;

            $user = new CUser;
            $userPass = "18lSgUWBgPmr9Nny9vou";
            $arFields = [
                "NAME" => "Служебная: купить в 1 клик",
                "EMAIL" => "liga-com@mail.ru",
                "LOGIN" => $this->userLogin,
                "LID" => "ru",
                "ACTIVE" => "Y",
                "GROUP_ID" => [$newGroup->getId()],
                "PASSWORD" => $userPass,
                "CONFIRM_PASSWORD" => $userPass,
            ];

            $userId = $user->Add($arFields);

            if (intval($userId) == 0) return;

            UserGroupTable::add([
                'USER_ID' => $userId,
                'GROUP_ID' => $newGroup->getId(),
            ]);
        }
    }

    public function doUninstall() :void
    {
        $this->uninstallDb();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    function uninstallDb() :void
    {
        if (!Loader::includeModule($this->MODULE_ID)) return;


        $groupId = GroupTable::getList([
            'select' => ['ID'],
            'filter' => ['STRING_ID' => $this->userLogin],
            'limit' => 1
        ])->fetchRaw()['ID'];

        if (!$groupId) return;

        $userId = UserTable::getList([
            'select' => ['ID'],
            'filter' => ['LOGIN' => $this->userLogin],
            'limit' => 1
        ])->fetchRaw()['ID'];

        if (!$userId) return;

       UserGroupTable::delete(
            [
                'USER_ID' => $userId,
                'GROUP_ID' => $groupId,
            ]
        );

        CUser::Delete($userId);
        GroupTable::delete($groupId);
    }

}
