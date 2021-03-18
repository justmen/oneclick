<?php
namespace Ligacom\Buyoneclick\Config;

use Bitrix\Main,
    Bitrix\Main\Config,
    Bitrix\Main\Localization\Loc;

/**
 * Class Options
 * @package Ligacom\Buyoneclick\Config
 */
class Options
{
    const TEMPLATE_BUTTON = 'button';
    const TEMPLATE_FORM = 'form';

    private $version;
    private $siteId;

    protected function __construct($siteId)
    {
        include __DIR__ . '/../install/version.php';

        $this->siteId = $siteId;
        /** @var array $arModuleVersion */
        $this->version = $arModuleVersion;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public static function getThemes()
    {
        return [
            'default' => 'По умолчанию',
        ];
    }

    /**
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->siteId;
    }
}