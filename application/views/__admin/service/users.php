<?php $i=1; foreach($users as $k):?>
<tr class="new-tr">
	<td><?=$i++;?></td>
	<td><?=$k->user_cart_discount;?></td>
	<td class="left">
		<a href="/admin/user/<?=$k->id?>" title="Смотреть историю заказов <?=htmlspecialchars($k->name)?>"><?=$k->name?></a>
		<span class="c-grey">(заказов: <?=$k->allcount?>)</span>
	</td>
	<td class="left nowrap"><?=number_format($k->allsumm, 2, ",", "'")?> грн.</td>
	<td class="left">
		<select data-select="" data-discount="<?=$k->id?>">
			<option value="0"> - Выбрать скидку - </option>
			<?php foreach ($discounts as $item):?>
			<option value="<?=$item->id?>" <?=$item->id == $k->discount? ' selected' : '';?>><?=$item->name.' - '.(int)$item->percent?>%</option>
			<?php endforeach;?>
		</select>
	</td>
	<td class="left nowrap"><?=$k->email?></td>
	<td class="left nowrap"><?=$k->phone?></td>
	<td>
		<a class="button blue" href="/admin/user/<?=$k->id?>" title="Смотреть историю заказов <?=htmlspecialchars($k->name)?>">история</a>
	</td>
	<td>
		<a class="link_edit" href="/admin/user?edit=<?=$k->id?>" title="Редактировать"></a>
	</td>
	<td>
		<a class="link_del" data-delete="клиента <b><?=htmlspecialchars($k->name)?></b>" href="/admin/user/?delete=<?=$k->id?>" title="удалить клиента"></a>
	</td>
</tr>
<?php endforeach;?>