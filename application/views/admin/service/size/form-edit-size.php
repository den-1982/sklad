<form onsubmit="return false">	<table class="table-1">		<thead>			<tr>				<td class="">Название</td>				<td class="">Prefix</td>			</tr>		</thead>		<tbody>			<tr>				<td>					<input class="inf" type="text" name="name" value="<?=htmlspecialchars($size->name)?>">				</td>				<td>					<input class="inf" type="text" name="prefix" value="<?=htmlspecialchars($size->prefix)?>">				</td>			</tr>		</tbody>	</table>		<input type="hidden" name="size_id" value="<?=$size->id?>">	<input type="hidden" name="update_size" value=""></form>