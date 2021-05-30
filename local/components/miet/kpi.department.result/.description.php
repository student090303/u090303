<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
	"NAME" => GetMessage("NAME_COMPONENT_RES"),
	"DESCRIPTION" => GetMessage("DESCRIPTION_COMPONENT_RES"),
	"SORT" => 20,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "intranet",
		"NAME" => GetMessage("NAME_INTRANET"),
		"CHILD" => array(
			"ID" => "KPI",
			"NAME" => GetMessage("NAME_KPI"),
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "KPIDepartmentRes",
			)
		),
	),
);