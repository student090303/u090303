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
$arParams["USER_ID"] = intval($arParams["USER_ID"]);
if(!$arParams["USER_ID"]) {
	ShowError(GetMessage("EMPTY_USER"));
	@define("ERROR_404", "Y");
	if($arParams["SET_STATUS_404"]==="Y") {
		CHTTP::SetStatus("404 Not Found");
	}
	return array();
}
if(!$_REQUEST['UF_PERIOD']) {
	$_REQUEST['UF_PERIOD'] = '01.' . date('m') . '.' . date('Y');
}
$arParams["DATE_FORMAT"] = trim($arParams["DATE_FORMAT"]);
if(strlen($arParams["DATE_FORMAT"]) <= 0) {
	$arParams["DATE_FORMAT"] = $DB->DateFormatToPHP(CSite::GetDateFormat("SHORT"));
}
###

###Получение данных из БД###
if($this->StartResultCache(false, array(($arParams["CACHE_GROUPS"] === "N" ? false: $USER->GetGroups())))) {
	if ($arUser = CUser::GetList($by = 'id', $order = 'asc', ['ID' => $arParams["USER_ID"]], ['FIELDS' => ['ID'], 'SELECT' => ['UF_DEPARTMENT']])->Fetch()) {
		$arResult['USER_DEPARTMENT'] = $arUser['UF_DEPARTMENT'];
	}
	###Формирование списка отчетных периодов (месяц, год)###
	//Получение текущего месяца и года
	for($i = 1; $i <= 12; $i++) {
		if(strlen($i) == 1) {
			$i = '0' . $i;
		}
		$arResult['PERIOD_ITEMS'][] = array(
			'VALUE' => '01.' . $i . '.' . date('Y')
		);
	}
	###
	###Получение списка показателей KPI для сотрудника###
	$arResult['ITEMS'] = KPI\KPIManager::GetKPIEmployee($arParams["USER_ID"]);

	###Кэширование значения элементов массива $arResult###
	###EndResultCache помещает в кеш весь $arResult так, что SetResultCacheKeys не сработает. Удалим его.
	$this->EndResultCache();
###
}
if (count($arResult['ITEMS']) > 0) {
	###Получение значений показателей вынесли из кеша. Иначе после обновления всё равно будем видеть устаревшие данные.
	$arKpiIds = array_column($arResult['ITEMS'], 'ID');
	$arKPI = KPI\KPIManager::GetKPIEmployeeValue($arKpiIds, $arParams['USER_ID'], $_REQUEST['UF_PERIOD']);
	$arResult['KPI'] = array_combine(array_column($arKPI, 'UF_KPI'), $arKPI);
}

###Сохранение значений KPI###
if($_REQUEST['saveKPI']) {
	if(KPI\KPIManager::SetKPIEmployee($arParams["USER_ID"], $_REQUEST['UF_PERIOD'], $_REQUEST['KPI'], $arResult['KPI'])) {
		// Установим KPI для подразделений.
		if ($arResult['USER_DEPARTMENT']) {
			foreach ($_REQUEST['KPI'] as $KPI => $KPIValue) {
				KPI\KPIManager::SetKPIDepartment($KPI, $arResult['USER_DEPARTMENT'], $_REQUEST['UF_PERIOD']);
			}
		}
		$arResult['OK'] = 'Изменения успешно сохранены';
	} else {
		$arResult['ERROR'] = 'Ошибка при сохранении';
	}
	### Чтоб после сохранения мы увидели уже новые данные.
	unset($arResult['KPI']);
}
###

###Подключение шаблона компонента###
$this->IncludeComponentTemplate();
###

###Вывод элементов из кэша###
if(isset($arResult["ITEM"])) {
	$this->SetTemplateCachedData();
	return $arResult["ITEM"];
}
###