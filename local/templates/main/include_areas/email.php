<?
$value = file_get_contents(__DIR__ . '/email_clear.php');
?>
<a href="mailto:<?=$value?>"><? $APPLICATION->IncludeFile(
		SITE_TEMPLATE_PATH . "/include_areas/email_clear.php",
		[],
		[
			"MODE" => "text",
			"NAME" => "Изменить email",
		]
	); ?></a>
