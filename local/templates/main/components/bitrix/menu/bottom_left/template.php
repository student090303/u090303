<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):
	$cnt = count($arResult);
	?>
	<div class="one-fourth panel">
		<div class="nopad">
			<ul>
			<?
			$i = 0;
			foreach($arResult as $arItem):
				$i++;
				if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
					continue;
				?>
				<li<?if ($i == $cnt) {?> class="last"<?}?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
			<?endforeach?>
			</ul>
		</div>
	</div>
<?endif?>