<form onsubmit="return false">	<table class="table-1">		<tr>			<td class="small right"><b>Категория:</b></td>			<td class="left">				<select data-select="" name="category_id">					<option value="1" selected> - Выбрать - </option>					<?php foreach ($categories as $k):?>					<option value="<?=$k['id']?>" <?=$k['id'] == $category_id ? 'selected' : '';?>><?=str_repeat(' - ', $k['level']);?><?=$k['_name']?></option>					<?php endforeach;?>				</select>			</td>		</tr>		<tr>			<td class="right nowrap"><b>Код товара:</b></td>			<td class="left">				<input class="inf" type="text" name="serial_number" value="">			</td>		</tr>		<tr>			<td class="right"><b>Название:</b></td>			<td class="left">				<input class="inf" type="text" name="name" value="">			</td>		</tr>		<tr>			<td class="right"><b>Размер:</b></td>			<td class="left">				<select name="size_id">					<option value="0" selected> - Выбрать - </option>					<?php foreach ($sizes as $k):?>					<option value="<?=$k->id?>"><?=$k->name?></option>					<?php endforeach;?>				</select>			</td>		</tr>	</table>		<input type="hidden" name="add_product" value=""></form>