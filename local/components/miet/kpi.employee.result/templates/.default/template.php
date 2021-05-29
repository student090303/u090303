<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<form action="<?=POST_FORM_ACTION_URI?>" method="POST">
	Данные за период
	<select name="UF_PERIOD">
		<?foreach($arResult['PERIOD_ITEMS'] as $arItem):?>
			<option value="<?=$arItem['VALUE'];?>"<?if ($arItem['VALUE'] == $_REQUEST['UF_PERIOD']) {?> selected="selected"<?}?>><?=$arItem['VALUE'];?></option>
		<?endforeach;?>
	</select>
	для польователя <?=$arResult['FIO_USER']?>
	<table style="border-width: 0px; border-spacing: 10px;">
		<thead>
			<tr>
				<th>Показатель</th>
				<th>Значение</th>
				<th>Метод расчёта</th>
			</tr>
		</thead>
		<tbody>
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<tr>
				<td><?=$arItem['NAME'];?></td>
				<td><?=(isset($arResult['KPI'][$arItem['ID']])?$arResult['KPI'][$arItem['ID']]['UF_VALUE']:'Не указан')?></td>
				<td>
					<?foreach($arItem['PROPERTY_REGULATIONS_VALUE'] as $fileRegulation):?>
						<a href="<?=$fileRegulation['SRC'];?>" target="_blank"><?=$fileRegulation['ORIGINAL_NAME'];?></a><br>
					<?endforeach;?>
				</td>
			</tr>
		<?endforeach;?>
		<tr>
			<td colspan="3">
				<input type="submit" name="showKPI" value="Показать">
			</td>
		</tr>
		</tbody>
	</table>
</form>
