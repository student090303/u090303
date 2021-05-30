<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
###Инициализация глобальных переменных Битрикс###
global $DB;
/** @global CUser $USER */
global $USER;
###
\Bitrix\Main\Loader::includeModule('miet.kpi');
use MIET\KPI;
###Проверка входных параметров на корректность и приведение их к нужному виду###
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
if(!$arParams["IBLOCK_ID"]) {
	ShowError(GetMessage("EMPTY_IBLOCK"));
	return array();
}

$arParams["DEPARTMENT_ID"] = intval($arParams["DEPARTMENT_ID"]);
if(!$arParams["DEPARTMENT_ID"]) {
	ShowError(GetMessage("EMPTY_USER"));
	@define("ERROR_404", "Y");
	if($arParams["SET_STATUS_404"]==="Y") {
		CHTTP::SetStatus("404 Not Found");
	}
	return array();
}
###
###Получение данных из БД###
if($this->StartResultCache(false, array(($arParams["CACHE_GROUPS"] === "N" ? false: $USER->GetGroups()), date('Y')))) {
	if (!\Bitrix\Main\Loader::includeModule('iblock')) {
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	if ($arDepartment = CIBlockSection::GetList(['ID'=>'ASC'], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arParams['DEPARTMENT_ID']], false, ['ID', 'NAME'])->Fetch()) {
		// Получаем имя пользователя
		$arResult['DEPARTMENT'] = $arDepartment;
		###Формирование списка отчетных периодов (месяц, год)###
		//Получение текущего месяца и года
		for ($i = 1; $i <= 12; $i++) {
			if (strlen($i) == 1) {
				$i = '0' . $i;
			}
			$arResult['PERIOD_ITEMS'][] = array('VALUE' => '01.' . $i . '.' . date('Y'));
		}
		###
		###Получение списка показателей KPI для сотрудника###
		$arResult['ITEMS'] = KPI\KPIManager::GetKPI(
			array('NAME' => 'asc'),
			array('PROPERTY_DEPARTMENT' => $arParams['DEPARTMENT_ID']),
			false,
			false,
			array('ID', 'NAME', 'PROPERTY_INDICATOR_TYPE', 'PROPERTY_WEIGHT', 'PROPERTY_REGULATIONS')
		);
		###Кэширование значения элементов массива $arResult###
		$this->EndResultCache();
	} else {
		$this->AbortResultCache();
		ShowError(GetMessage("DEPARTMENT_NOT_FOUND"));
		return;
	}
###
}

if(!$_REQUEST['UF_PERIOD']) {
	$_REQUEST['UF_PERIOD'] = '01.' . date('m') . '.' . date('Y');
}

$arKpiIds = array_column($arResult['ITEMS'], 'ID');
$arKPI = KPI\KPIManager::GetKPIDepartment($arKpiIds, $arParams['DEPARTMENT_ID'], $_REQUEST['UF_PERIOD']);

$arResult['KPI'] = array_combine(array_column($arKPI, 'UF_KPI'), $arKPI);

###Подключение шаблона компонента###
$this->IncludeComponentTemplate();
###
if(count($arResult["KPI"])) {
	return $arResult["KPI"];
}
###