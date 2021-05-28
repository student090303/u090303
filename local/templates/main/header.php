<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;

Loc::loadMessages(__FILE__);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?=LANGUAGE_ID?>">
<head profile="http://gmpg.org/xfn/11">
	<title><?$APPLICATION->ShowTitle();?></title>
	<?$APPLICATION->ShowHead();?>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<?
	$asset = Asset::getInstance();
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.badBrowser.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.tools.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.easing.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.css-transform.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.css-rotate-scale.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.cycle.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/cufon.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/Museo_400-Museo_italic_400.font.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.prettyPhoto.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.hoverInt.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.bgiframe.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/superfish.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.roundabout.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.roundabout-shapes-1.1.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/swfobject.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/jquery.quicksand.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/custom.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/_include/js/styleswitch.js");

	$asset->addCss(SITE_TEMPLATE_PATH."/_include/css/jquery.cycle.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/_include/css/prettyPhoto.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/_include/css/jquery.roundabout.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/_include/css/style-orange.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/_include/css/style-dirtyblue.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/_include/css/style-redish.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/_include/css/style-green.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/_include/css/style-pink.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/_include/css/style-enhance.css"); ?>

	<!-- Load Pretty Photo -->
	<script type="text/javascript">
		/* pretty photo responds on rel='prettyPhoto' */
		jQuery(document).ready(function() { $("a[rel^='prettyPhoto']").prettyPhoto();   });
	</script>
	<!-- End Load -->

	<?
	$asset->addString('
	<!--[if lt IE 7]>
	<script type="text/javascript" src="'.CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/_include/js/pngfix.js', true).'"></script>
	<script type="text/javascript">DD_belatedPNG.fix("*");</script>
	<![endif]-->
	', AssetLocation::AFTER_JS);
	?>

	<?
	$asset->addString('
	<!--[if IE]>
	<link rel="stylesheet" href="'.CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/_include/css/ie.css', true).'" type="text/css" media="screen" />
	<![endif]-->

	<!--[if IE 6]>
	<link rel="stylesheet" href="'.CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/_include/css/ie6.css', true).'" type="text/css" media="screen" />
	<![endif]-->	
	', AssetLocation::AFTER_CSS);
	?>
</head>
<body>
<?$APPLICATION->ShowPanel();?>
<!-- start top and main menu -->
<div class="main-menu">
	<div class="ornament">
		<div class="containit">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"COMPONENT_TEMPLATE" => ".default",
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => SITE_TEMPLATE_PATH."/include_areas/logo.php"
				)
			);?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:menu",
				"top_menu",
				Array(
					"ALLOW_MULTI_SELECT" => "N",
					"CHILD_MENU_TYPE" => "left",
					"DELAY" => "N",
					"MAX_LEVEL" => "2",
					"MENU_CACHE_GET_VARS" => array(""),
					"MENU_CACHE_TIME" => "3600",
					"MENU_CACHE_TYPE" => "N",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"ROOT_MENU_TYPE" => "left",
					"USE_EXT" => "Y"
				)
			);?>
			<div class="clear"></div>
		</div>
	</div>
</div>

<!-- end top and main menu -->
<!-- start header alternate -->
<div class="header-alt">
	<?$APPLICATION->IncludeComponent(
		"bitrix:photo.section",
		"top_slider",
		Array(
			"ADD_SECTIONS_CHAIN" => "N",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "sort",
			"ELEMENT_SORT_ORDER" => "asc",
			"FIELD_CODE" => array("ID","NAME","SORT","PREVIEW_PICTURE",""),
			"FILTER_NAME" => "arrFilter",
			"IBLOCK_ID" => "7",
			"IBLOCK_TYPE" => "content",
			"LINE_ELEMENT_COUNT" => "3",
			"MESSAGE_404" => "",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Фотографии",
			"PAGE_ELEMENT_COUNT" => "20",
			"PROPERTY_CODE" => array("URL",""),
			"SECTION_CODE" => "",
			"SECTION_ID" => $_REQUEST["SECTION_ID"],
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array("",""),
			"SET_LAST_MODIFIED" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_404" => "N"
		)
	);?>
</div>
<!-- end header alternate-->

<!-- start main content -->
<div class="main-content pt-alt">
	<div class="containit">
