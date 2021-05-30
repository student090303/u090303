<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подразделение");
?><?$APPLICATION->IncludeComponent(
	"miet:kpi.department.result",
	"",
	Array(
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"DEPARTMENT_ID" => $_REQUEST["DEPARTMENT_ID"],
		"IBLOCK_ID" => "10",
		"IBLOCK_TYPE" => "org",
		"SET_STATUS_404" => "N"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>