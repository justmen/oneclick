<?

namespace Ligacom\Buyoneclick\Config;

use Bitrix\Main\Config\Option;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Main\Page\Asset;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use CSite;

Loc::loadMessages(__FILE__);

/**
 * Class Form
 * @package Ligacom\Buyoneclick\Config
 */
class Form
{
    //todo не выводить зависимые настройки
    //todo протестировать с config_options отличным от false
    //todo проверить как это работает с мультиязычностью

    public $error = [];
    private $prefix = "ligacom_buyoneclick";
    private $moduleId = "ligacom.buyoneclick";
    private $fields;
    private $siteId;
    private $required = 'required';
    private $active = 'active';
    /**
     * @var array[]
     */
    private array $oneclickFormFields;

    function __construct()
    {
        $this->oneclickFormFields = array_filter(Config::getFormParams(), function ($field) {
            return ($field['field1Click']) ? true: false;
        });

        $this->fields = array_filter(Config::getFormParams(), function ($field) {
            return ($field['field1Click']) ? false: true;
        });
    }

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    public function getTab()
    {
        $table = "";

        $this->fields["personType"]['values'] = $this->getPersonTypes($this->siteId);

        foreach ($this->fields as $code => $field) {
            $field['value'] = $this->getFieldValue($this->siteId, $code);
            $table .= $this->renderField($code, $field);
        }

        $table .= $this->fields1ClickForm($this->oneclickFormFields);

        return $table;
    }

    private function renderField($code, $field)
    {
        switch ($field["type"]) {
            case 'list':
                return $this->getSelect($code, $field);
                break;
            case 'checkbox':
                return $this->getCheck($code, $field);
                break;
            case 'radio':
                return $this->getRadio($code, $field);
                break;
            case 'titleline':
                return $this->getTitleLine($field);
                break;
            case 'notes':
                return $this->notes($field);
                break;
            case 'range':
                return $this->getRange($code, $field);
                break;
            default:
                return $this->getText($code, $field);
        }
    }

    private function getSelect($code, $field)
    {
        $html = '<tr><td width="50%" class="adm-detail-content-cell-l">';

        if ($field["description"]) {
            $html .= '<span id="hint_' . $code . '"></span>
					<script type="text/javascript">	BX.hint_replace(BX(\'hint_' . $code . '\'), \'' . $field["description"] . '\');</script>&nbsp;';
        }

        $html .= '<label for="' . $this->prefix . '[' . $this->siteId . '][' . $code . ']">' . $field["name"] . '' . ($field["required"] ? '
                        <span class="required-red">*</span>' : '') . ':
                  </label>
			</td>
			<td width="50%" class="adm-detail-content-cell-r">
				<select name="' . $this->prefix . '[' . $this->siteId . '][' . $code . ']" class="' . $this->prefix . '_' . $code . '">';
        if (empty($field["required"]))
            $html .= '<option value="">' . Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ORDER_FIELD_SELECT") . '</option>';
        foreach ($field["values"] as $k => $v)
            $html .= '<option value="' . $k . '"' . ($field["value"] == $k ? ' selected="selected"' : '') . '>' . $v .
                '</option>';
        $html .= '</select>
			</td>
		</tr>';

        return $html;
    }

    private function getCheck($code, $field)
    {
        $table = '<tr id="' . $this->prefix . '_' . $this->siteId . '_field_' . $code . '">
			<td width="50%" class="adm-detail-content-cell-l">';
        if ($field["description"]):
            $table .= '<span id="hint_' . $code . '"></span>
					<script type="text/javascript">	BX.hint_replace(BX(\'hint_' . $code . '\'), \'' . $field["description"] . '\');</script>&nbsp;';
        endif;
        $table .= '<label for="' . $this->prefix . '[' . $this->siteId . '][' . $code . ']">' . $field["name"] . '' . ($field["required"] ? '<span class="required-red">*</span>' : '') . ':</label>
			</td>
			<td width="50%" class="adm-detail-content-cell-r">
				<input id="' . $this->siteId . '_' . $code . '" 
				type="checkbox"
				name="' . $this->prefix . '[' . $this->siteId . '][' . $code . ']" 
				class="' . $this->prefix . '_' . $code . '" 
				value="Y" '.($field["value"] == 'Y' ? ' checked="checked"' : '') . '>
			</td>
		</tr>';
        return $table;
    }

    private function getRange($code, $field)
    {
        $table = '<tr id="' . $this->prefix . '_' . $this->siteId . '_field_' . $code . '"><td width="50%" class="adm-detail-content-cell-l">';
        if ($field["description"]) {
            $table .= '<span id="hint_' . $code . '"></span>
                    <script type="text/javascript">	BX.hint_replace(BX(\'hint_' . $code . '\'), \'' . $field["description"] . '\');</script>&nbsp;';
        }

        $table .= '<label>' . $field["name"] . '' . ($field["required"] ? '<span class="required-red">*</span>' : '') . ':</label></td><td width="50%" class="adm-detail-content-cell-r">';


        $table .= '<input type="range"
                          name="' . $this->prefix . '[' . $this->siteId . '][' . $code . ']" 
                          min="'.$field['min'].'"
                          max="'.$field['max'].'"
                          step="'.$field['step'].'"
                          class="'.$this->prefix.'_'.$code. '" 
                          value="' . $field["value"] . '"/>';

        $table .= '</td></tr>';

        return $table;
    }
    private function getRadio($code, $field)
    {
        $table = '<tr id="' . $this->prefix . '_' . $this->siteId . '_field_' . $code . '">
			<td width="50%" class="adm-detail-content-cell-l">';
        if ($field["description"]):
            $table .= '<span id="hint_' . $code . '"></span>
					<script type="text/javascript">	BX.hint_replace(BX(\'hint_' . $code . '\'), \'' . $field["description"] . '\');</script>&nbsp;';
        endif;
        $table .= '<label>' . $field["name"] . '' . ($field["required"] ? '<span class="required-red">*</span>' : '') . ':</label>
			</td>
			<td width="50%" class="adm-detail-content-cell-r">';

        foreach ($field as $k => $v)
            $table .= '<label><input type="radio" name="' . $this->prefix . '[' . $this->siteId . '][' . $code . ']" class="' . $this->prefix . '_' . $code . '" value="' . $k . '" ' . (
                ($field[$code] == $k || empty($field["result"])) ? ' checked="checked"' : '') . '>' . $v . '</label>';
        $table .= '</td></tr>';

        return $table;
    }

    private function getText($code, $field)
    {
        $table = '<tr id="' . $this->prefix . '_' . $this->siteId . '_field_' . $code . '"><td width="50%" class="adm-detail-content-cell-l">';
        if ($field["description"]):
            $table .= '<span id="hint_' . $code . '"></span>
                    <script type="text/javascript">	BX.hint_replace(BX(\'hint_' . $code . '\'), \'' . $field["description"] . '\');</script>&nbsp;';
        endif;
        $table .= '<label>' . $field["name"] . '' . ($field["required"] ? '<span class="required-red">*</span>' : '') . ':</label></td><td width="50%" class="adm-detail-content-cell-r">';

        if (empty($field["row"]) || intval($field["row"]) == 1):
            $table .= '<input type="text" name="' . $this->prefix . '[' . $this->siteId . '][' . $code . ']" class="'
                . $this->prefix . '_' . $code . '" value="' . $field["value"] . '"/>';
        else:
            $table .= '<textarea name="' . $this->prefix . '[' . $this->siteId . '][' . $code . ']" class="' .
                $this->prefix . '_' . $code . '">' . $field["value"] . '</textarea>';
        endif;
        $table .= '</td></tr>';

        return $table;
    }

    private function getTitleLine($field)
    {
        $table = '<tr class="heading"><td colspan="2">' . $field["name"] . '</td></tr>';

        return $table;
    }

    private function notes($field)
    {
        $table = '<tr><td colspan="2" align="center"><div class="adm-info-message-wrap"><div class="adm-info-message">' . $field["name"] . '</div></div></td></tr>';

        return $table;
    }

    /**
     * @param $formFields
     * @return string
     */
    private function fields1ClickForm($formFields)
    {
        $code = 'fields';

        $table = '<tr><td colspan="2" align="center">
                    <table>
                    <tr>
                        <th>' . Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ORDER_FIELD_TD_TITLE") . '</th>
                        <th>' . Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ORDER_FIELD_TD_ACTIVE") . '</th>
                        <th>' . Loc::getMessage("LIGACOM_BUYONECLICK_SETTING_ORDER_FIELD_TD_REQUIRED") . '</th>
                    </tr>';

        foreach ($formFields as $field) {
            $field[$this->active] = $this->getFieldValue( $this->siteId, $field['code'].'_'.$this->active);
            $field[$this->required] = $this->getFieldValue($this->siteId, $field['code'].'_'.$this->required);
            $table .=
                '<tr>
                    <td>' . $field["name"] . '</td>
                    <td align="center">
                        <input type="checkbox" 
                               name="'.$this->prefix.'['.$this->siteId.']['.$code.']['.$field["code"].'][active]" 
                               class="'.$this->prefix.'_'.$code.'"
                               value="Y" '.($field[$this->active] == "Y" ? ' checked="checked"' : '').'>
                     </td>
                    <td align="center">
                        <input type="checkbox" 
                               name="' . $this->prefix . '['. $this->siteId . '][' . $code . '][' . $field["code"] . '][required]" 
                               class="' . $this->prefix . '_' . $code . '" 
                               value="Y" ' . ($field[$this->required] == "Y" ? ' checked="checked"' : '') . '>
                   </td>
                </tr>';
        }

        $table .= '</table></td></tr>';

        return $table;
    }

    /**
     * @param $siteId
     *
     * @return array
     */
    private function getPersonTypes($siteId)
    {
        Loader::includeModule('sale');

        $personType = [];
        $dbPersonType = \CSalePersonType::GetList(["SORT" => "ASC"], ["LID" => $siteId]);
        while ($ptype = $dbPersonType->Fetch()) {
            $personType[$ptype["ID"]] = "[" . $ptype["ID"] . "] " . $ptype["NAME"];
        }
        return $personType;
    }

    private function getFieldValue($siteId, $code)
    {
        return Option::get($this->moduleId, $code,"", $siteId);
    }

    private function getSiteList()
    {
        $result = [];
        $sites = CSite::GetList($by = "sort", $order = "desc");

        while ($site = $sites->fetch()) {
           $result[] = $site;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getTabs()
    {
        $tabs = [];

        $sites = $this->getSiteList();

        foreach ($sites as $site) {
            $tabs[] = [
                "DIV" => "edit" . $site["LID"],
                "LID" => $site["LID"],
                "TAB" => "[" . $site["LID"] . "] " . $site["NAME"],
                "TITLE" => Loc::getMessage($this->moduleId . "_TAB_TITLE"),
            ];
        }

        return $tabs;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function saveOptions()
    {
        $form = HttpApplication::getInstance()->getContext()->getRequest()->get($this->getPrefix());

        foreach ($this->getSiteList() as $site) {
            $siteId = $site['LID'];
           // $siteOptions = Option::getForModule($this->moduleId, $siteId);
            foreach ($this->fields as $fieldCode => $field) {
                  if (array_key_exists($fieldCode, $form[$siteId])) {
                     Option::set($this->moduleId, $fieldCode, $form[$siteId][$fieldCode], $siteId);
                  } else {
                     Option::delete($this->moduleId, ["name"=>$siteId,"site_id" => $siteId]);
                     Option::set($this->moduleId, $fieldCode, '', $siteId);
                  }
            }

            if(count($form[$siteId]['fields']) > 0) {
                $this->save1ClickFields($form[$siteId]['fields'], $siteId);
            }
        }
    }

    /**
     * @param $fields
     * @param $siteId
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    private function save1ClickFields($fields, $siteId)
    {
        foreach ($this->oneclickFormFields as $fieldCode => $field) {
            if ($fields[$fieldCode]) {
                if ($fields[$fieldCode][$this->active] === 'Y') {
                    Option::set($this->moduleId,
                                $fieldCode.'_'.$this->active,
                                $fields[$fieldCode][$this->active],
                                $siteId);
                } else {
                    Option::set($this->moduleId,
                                $fieldCode.'_'.$this->active,
                                'N',
                                $siteId);
                }
                if ($fields[$fieldCode][$this->active] === 'Y' &&
                    $fields[$fieldCode][$this->required] === 'Y') {
                    Option::set($this->moduleId,
                                $fieldCode.'_'.$this->required,
                                $fields[$fieldCode][$this->required],
                                $siteId);
                } else {
                    Option::set($this->moduleId,
                                $fieldCode.'_'.$this->required,
                                'N',
                                $siteId);
                }
            } else {
                Option::set($this->moduleId,
                            $fieldCode.'_'.$this->active,
                            'N',
                            $siteId);
                Option::set($this->moduleId,
                            $fieldCode.'_'.$this->required,
                            'N',
                            $siteId);
            }
        }

    }
}