<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
if (count($arResult['ITEMS']) > 0) {
?>
<div class="slide slide-roundabout bg1">
	<div class="containit ornament-right">
		<div class="roundaboutshadow">
			<script type="text/javascript" charset="utf-8">
			<?foreach ($arResult['ITEMS'] as $key=>$item) {?>
				function roundaboutimage<?=$key?>(){  $.prettyPhoto.open('<?=$item['PREVIEW_PICTURE']['SRC']?>', 'title', '<?=str_replace("'", "\'", $item['NAME'])?>'); }
			<?}?>
			</script>
			<!-- the actual roundabout -->
			<ul id="roundabout">
			<?foreach ($arResult['ITEMS'] as $key=>$item) {?>
				<li id="roundaboutimage<?=$key?>"><a href="javascript:roundaboutimage<?=$key?>();"><img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="" /></a></li>
			<?}?>
			</ul>
		</div>
		<!-- start display roundabout description and links -->
		<div class="boxed-harder clearfix roundaboutdescription roundabout-desclinkbox" style="-webkit-box-sizing:unset;-moz-box-sizing:unset;box-sizing:unset">
			<div class="fl roundabout-arrows">
				<img src="<?=SITE_TEMPLATE_PATH?>/images/arrow-next-smaller.png" alt="" />
			</div>
			<div class="fl roundabout-desc"><h1 id="roundaboutdescription" style="color: #606060; text-align: left;"></h1></div>
			<div class="fr roundabout-link clearfix"><div id="roundaboutlinkone" class="fl"></div><div id="roundaboutlinktwo" class="fl"></div></div>
		</div>
		<!-- end display roundabout description and links -->
		<!-- start the roundabout with descriptions -->
		<script type="text/javascript">
			//<![CDATA[
			var descs = {
			<?foreach ($arResult['ITEMS'] as $key=>$item) {?>
				roundaboutimage<?=$key?>: "<?=str_replace("'", "\'", $item['NAME'])?>",
			<?}?>
			};
			// settings for first button, for each roundabout image one setting
			var linkone = {
			<?foreach ($arResult['ITEMS'] as $key=>$item) {
				if ($item['PROPERTIES']['URL']['VALUE']) {?>
				roundaboutimage<?=$key?>: '<a class="btn-medium" href="<?=$item['PROPERTIES']['URL']['VALUE']?>"><span>View Details</span></a>',
				<?}?>
			<?}?>
			};
			// settings for second button, for each roundabout image one setting
			var linktwo = {
			};
			// what happens on focus and on blur
			$('#roundabout li').focus(function() {
				var useLinkone = (typeof linkone[$(this).attr('id')] != 'undefined') ? linkone[$(this).attr('id')] : '';
				$('#roundaboutlinkone').html(useLinkone).fadeIn(200);
				var useLinktwo = (typeof linktwo[$(this).attr('id')] != 'undefined') ? linktwo[$(this).attr('id')] : '';
				$('#roundaboutlinktwo').html(useLinktwo).fadeIn(200);
				var useText = (typeof descs[$(this).attr('id')] != 'undefined') ? descs[$(this).attr('id')] : '';
				$('#roundaboutdescription').html(useText).fadeIn(200);
				Cufon.replace('#roundaboutdescription, #roundaboutlinkone,  #roundaboutlinktwo', { hover: true, textShadow: '1px 1px 0 #ffffff', fontFamily: 'Museo' });
			}).blur(function() {
				$('#roundaboutlinkone').fadeOut(200);
				$('#roundaboutlinktwo').fadeOut(200);
				$('#roundaboutdescription').fadeOut(200);
			});

			$(document).ready(function() {
				var interval;
				$('#roundabout')
					.roundabout({
						shape: 'lazySusan',
						easing: 'swing',
						minOpacity: 1, // 1 fully visible, 0 invisible
						minScale: 0.5, // tiny!
						duration: 400,
						btnNext: '#roundaboutnext',
						btnPrev: '#roundaboutprevious',
						clickToFocus: true
					});
			});
			function startAutoPlay() {
				return setInterval(function() {
					$('#roundabout').roundabout_animateToNextChild();
				}, 3000);
			}
			//]]>
		</script>
	</div>
</div>
<?}?>