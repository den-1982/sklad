<div class="body">

<!--ALL-->
	<?php if($act == 'all'):?>
	<h1 class="title"><?=$h1?></h1>

	<div class="nav">
		<div class="fleft">
			<?php if($crumbs):?>
			<div class="crumbs">
				<a href="<?=$path?>">Категории</a>
				<?php $last = array_pop($crumbs);?>
				<?php foreach($crumbs as $k):?>
					::
					<a href="<?=$path?>?parent=<?=$k['id']?>"><?=$k['name']?></a>
				<?php endforeach;?>
					:: <span><?=$last['name']?></span>
			</div>
			<?php endif;?>
		</div>
		<div class="fright">
			<a class="button green" title="добавить" href="<?=$path?>?add&parent=<?=$parent?>">добавить</a>
		</div>
	</div>
	
	<table class="table-1" data-scroll="head">
		<thead>
			<td class="small">№</td>
			<td class="small">
				<a class="icon-save send-order" data-sortable="send-order" title="применить сортировку"></a>
			</td>
			<td>Название</td>
			<td class="small"></td>
			<td class="small"></td>
		</thead>
		<tbody data-sortable="body">
		<?php $i = 1; if (isset($categories[$parent])) foreach($categories[$parent] as $category):?>
		<tr>
			<td><?=$i++;?></td>
			<td>
				<span class="icon-reorder handler" data-sortable="handler"></span>
				<input data-sortable="id" type="hidden" name="category_id[]" value="<?=$category->id?>">
				<input data-sortable="order" type="hidden" name="category_order[]" value="<?=$category->order?>">
			</td>
			<td class="left">
				<a href="<?=$path?>?parent=<?=$category->id?>"><?=$category->name?></a>
				<?=$category->cnt_childs ? '<span class="c-grey">&rarr; ('.$category->cnt_childs.')</span>' : '';?>
			</td>
			<td>	
				<a title="редактировать" class="link_edit" href="<?=$path?>?parent=<?=$parent?>&update=<?=$category->id?>"></a>
			</td>
			<td>
				<a title="удалить" class="link_del" data-delete="<?=htmlspecialchars($category->name)?>" href="<?=$path?>?parent=<?=$parent?>&delete=<?=$category->id?>"></a>
			</td>
		</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
	
	
	
	
<!--ADD-->		
	<?php if($act == 'add'):?>
	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button blue" onclick="$('#form').submit()" >Сохранить</a>
			<a class="button black" href="<?=$path?>?parent=<?=$parent?>">Отмена</a>
		</div>
	</div>
	
	<form id="form" action="<?=$path?>" method="POST" enctype="multipart/form-data">
		
		<div class="toggle-box">
			<a class="bookmark-toggle activ" data-name="data" href="#data">Общие</a>
		</div>
		
		<div class="bookmark activ" data-id="data">
			<table class="table-1">
				<tr>
					<td class="small right">
						<b class="c-red">Связь:</b>
					</td>
					<td class="left">
						<select data-select="" name="parent">
							<option value="0"> - Выбрать категорию - </option>
							<?php foreach($parents as $k):?>
							<option <?=$parent == $k['id'] ? 'selected' : ''?>  value="<?=$k['id']?>"><?=$k['name']?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="right"><b>Название:</b></td>
					<td class="left"><input class="inf" type="text" name="name" value=""></td>
				</tr>
			</table>
		</div>
		
		<input type="hidden" name="add" value="">
	</form>
	<?php endif;?>
	
	
	
	
<!--UPDATE-->
	<?php if($act == 'update'):?>
	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button orange" data-form-apply="" >Применить</a>
			<a class="button blue" onclick="$('#form').submit()" >Сохранить</a>
			<a class="button black" href="<?=$path?>?parent=<?=$parent?>">Отмена</a>
		</div>
	</div>
	
	<form id="form" action="<?=$path?>" method="POST" enctype="multipart/form-data">
		
		<div class="toggle-box">
			<a class="bookmark-toggle activ" data-name="data" href="#data">Общие</a>
		</div>
		
		<div class="bookmark activ" data-id="data">
			<table class="table-1">
				<tr>
					<td class="small right"><b class="c-red">Связь:</b></td>
					<td class="left">
						<select data-select="" name="parent">
							<option value="0"> - Выбрать категорию - </option>
							<?php foreach($parents as $k):?>
							<option <?=$parent == $k['id'] ? 'selected' : ''?>  value="<?=$k['id']?>"><?=$k['name']?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="right"><b>Название:</b></td>
					<td class="left"><input class="inf" type="text" name="name" value="<?=htmlspecialchars($category->name)?>"></td>
				</tr>
			</table>
		</div>

		<input type="hidden" name="id" value="<?=$category->id?>">
		<input type="hidden" name="edit" value="">
	</form>
	<?php endif;?>

<!--CENTER-->
</div>


<script> // APPLY
$(document).on('click', '[data-form-apply]', function(e){
	$(document.body).append($('<div class="load"></div>'));
	
	if (tinyMCE && tinyMCE.editors){
		$(tinyMCE.editors).each(function(){
			$(this.getElement()).html(this.getContent());
		});
	}
	
	AP.init('', $('#form')[0], function(){	
		location.reload(true);
	});
	return false;		
});
</script>