<?php
namespace MIET\KPI;
use Bitrix\Main\Application;
use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\UserTable;
Loc::loadMessages(__FILE__);
class KPIManager {
	const IBLOCK_CODE_KPI = 'kpi';
	const IBLOCK_CODE_DEPARTMENTS = 'departments';
	public static function GetKPI(
		$arOrder = array('SORT' => 'ASC'),
		$arFilter = array(),
		$arGroupBy = false,
		$arNavStartParams = false,
		$arSelectFields = array('ID', 'NAME')
	) {
		$elements = array();
		//Получаем ID инфоблока KPI по его символьному коду
		$rsIblock = \CIBlock::GetList(
			array(),
			array('CODE' => self::IBLOCK_CODE_KPI, 'SITE_ID' => SITE_ID)
		);
		$arIblock = $rsIblock->GetNext();
		$arFilter['IBLOCK_ID'] = $arIblock['ID'];
		$rsElements = \CIBlockElement::GetList(
			$arOrder, //массив полей сортировки элементов и её направления31
			$arFilter, //массив полей фильтра элементов и их значений
			$arGroupBy, //массив полей для группировки элементов
			$arNavStartParams, //параметры для постраничной навигации и ограничения количества выводимых элементов
			$arSelectFields //массив возвращаемых полей элементов
		);
		while($arElements = $rsElements->Fetch()) {
			//Получение информации о файле с регламентом расчета показателя: ссылка на файл на сервере, название файла и т.д.
			foreach($arElements['PROPERTY_REGULATIONS_VALUE'] as $key => $idFileRegulation) {
				$arElements['PROPERTY_REGULATIONS_VALUE'][$key] = \CFile::GetFileArray($idFileRegulation);
			}
			$elements[] = $arElements;
		}
		return $elements;
	}
	public static function GetKPIEmployee($idEmployee) {
		if(!$idEmployee) {
			return array();
		}
		//Получаем список всех подразделений сотрудника
		$arDepartmentsUser = UserTable::getList(array(
			'select' => array(
				'UF_DEPARTMENT'
			),
			'filter' => array(
				'ID' => $idEmployee
			)
		))->fetch();
		//Получаем список всех KPI данных подразделений
		return self::GetKPI(
			array('NAME' => 'asc'),
			array('PROPERTY_DEPARTMENT' => $arDepartmentsUser['UF_DEPARTMENT']),
			false,
			false,
			array('ID', 'NAME', 'PROPERTY_INDICATOR_TYPE', 'PROPERTY_WEIGHT', 'PROPERTY_REGULATIONS')
		);
	}
	public static function SetKPIEmployee($idEmployee, $period, $arKPIValues, $existValues = array()) {
		if(!$idEmployee || !is_array($arKPIValues) || !count($arKPIValues) || !is_array($existValues)) {
			return array();
		}
		global $USER;
		//Получаем объект подключения к БД
		$db = Application::getConnection();
		//Начинаем транзакцию
		$db->startTransaction();
		foreach($arKPIValues as $KPI => $KPIValue) {
			if (isset($existValues[$KPI])) {
				$arValue = array(
					'UF_VALUE' => $KPIValue,
					'UF_CHANGED_BY' => $USER->GetID(),
					'UF_CHANGED' => new \Bitrix\Main\Type\DateTime(date('d.m.Y') . ' 00:00:00'),

				);
				$result = KPIEmployeeTable::update($existValues[$KPI]['ID'], $arValue);
			} else {
				$arValue = array(
					'UF_VALUE' => $KPIValue,
					'UF_KPI' => $KPI,
					'UF_EMPLOYEE' => $idEmployee,
					'UF_CREATED_BY' => $USER->GetID(),
					'UF_CREATED' => new \Bitrix\Main\Type\DateTime(date('d.m.Y') . ' 00:00:00'),
					'UF_PERIOD' => new \Bitrix\Main\Type\DateTime($period . ' 00:00:00'));
				$result = KPIEmployeeTable::add($arValue);
			}
			if (!$result->isSuccess()) {
				$db->rollbackTransaction();
				return false;
			} else {

			}
		}
		if ($result->isSuccess()) {
			$db->commitTransaction();
			return true;
		}
	}

	public static function GetKPIEmployeeValue($idKPI, $idEmployee, $period){
		if(!$idKPI || !$idEmployee || !$period) {
			return array();
		}
		if (!($period = \Bitrix\Main\Type\DateTime::createFromUserTime($period))) {
			return array();
		}
		$filter = array('UF_KPI' => $idKPI, 'UF_EMPLOYEE' => $idEmployee, 'UF_PERIOD' => $period);
		$select = array('ID', 'UF_KPI', 'UF_VALUE');
		return KPIEmployeeTable::getList(['filter' => $filter, 'select' => $select])->fetchAll();
	}

	public static function SetKPIDepartment($idKPI, $idDepartment, $period){
		if(!$idKPI || !$idDepartment || !$period) {
			return false;
		}
		if (!($period = \Bitrix\Main\Type\DateTime::createFromUserTime($period))) {
			return false;
		}

		// Получим всех сотрудников подразделения
		$arDepartmentsUser = UserTable::getList(array(
			'select' => array(
				'ID'
			),
			'filter' => array(
				'UF_DEPARTMENT' => $idDepartment
			)
		))->fetchAll();

		$arDepartmentsUser = array_column($arDepartmentsUser, 'ID');
		$employeeCount = count($arDepartmentsUser);

		if ($employeeCount > 0) {
			global $USER;
			// Получим KPI для всех сотрудников подраздаления за отчётный период
			$employeeKPI = static::GetKPIEmployeeValue($idKPI, $arDepartmentsUser, $period);
			$employeeKPISumm = 0;

			array_walk($employeeKPI, function ($item) use (&$employeeKPISumm) {$employeeKPISumm += $item['UF_VALUE'];});

			$newDepartmentKPI = round($employeeKPISumm / $employeeCount, 2);

			$currentDepartmentKPI = static::GetKPIDepartment($idKPI, $idDepartment, $period);

			if ($currentDepartmentKPI) {
				$currentDepartmentKPI = reset($currentDepartmentKPI);
				$arValue = array(
					'UF_VALUE' => $newDepartmentKPI,
					'UF_CHANGED_BY' => $USER->GetID(),
					'UF_CHANGED' => new \Bitrix\Main\Type\DateTime(date('d.m.Y') . ' 00:00:00'),

				);
				$result = KPIDepartmentTable::update($currentDepartmentKPI['ID'], $arValue);
			} else {
				$arValue = array(
					'UF_VALUE' => $newDepartmentKPI,
					'UF_KPI' => $idKPI,
					'UF_DEPARTMENT' => $idDepartment,
					'UF_CREATED_BY' => $USER->GetID(),
					'UF_CREATED' => new \Bitrix\Main\Type\DateTime(date('d.m.Y') . ' 00:00:00'),
					'UF_PERIOD' => new \Bitrix\Main\Type\DateTime($period . ' 00:00:00'));
				$result = KPIDepartmentTable::add($arValue);
			}

			if ($result->isSuccess()) {
				return true;
			}
		}

		return false;
	}

	public static function GetKPIDepartment($idKPI, $idDepartment, $period){
		if(!$idKPI || !$idDepartment || !$period) {
			return array();
		}
		if (!($period = \Bitrix\Main\Type\DateTime::createFromUserTime($period))) {
			return array();
		}

		$filter = array('UF_KPI' => $idKPI, 'UF_DEPARTMENT' => $idDepartment, 'UF_PERIOD' => $period);
		$select = array('ID', 'UF_KPI', 'UF_VALUE');
		return KPIDepartmentTable::getList(['filter' => $filter, 'select' => $select])->fetchAll();
	}
}