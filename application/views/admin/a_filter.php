<div class="body">

	<?php if($act == 'all'):?>
	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button green" title="добавить" href="<?=$path?>?add&parent=<?=$parent?>">добавить</a>
		</div>
	</div>
	
	<table class="table-1" data-scroll="head">
		<thead>
			<tr>
				<td class="small">№</td>
				<td class="small">
					<a class="icon-save send-order" data-sortable="send-order" title="применить сортировку"></a>
				</td>
				<td>Название</td>
				<td style="width:200px;">Значения</td>
				<td class="small"></td>
				<td class="small"></td>
			</tr>
		</thead>
		<tbody data-sortable="body">
			<?php $j=1; foreach($filters as $filter):?>
			<tr>
				<td><?=$j++?></td>
				<td>
					<span class="icon-reorder handler" data-sortable="handler"></span>
					<input type="hidden" data-sortable="id" name="filter_id[]" value="<?=$filter->id?>">
					<input type="hidden" data-sortable="order" name="filter_order[]" value="<?=$filter->order?>">
				</td>
				<td class="left"><?=$filter->name?></td>
				<td class="left">
					<?php 
						$_length = count($filter->items);
						$_shear = array_slice($filter->items, 0, 6);
						$_end = $_length - 6;
						$_res = array();
						foreach ($_shear as $i){$_res[] = $i->name;}
						echo implode($_res, ' &bull; ');
						if ($_end > 0){ echo '<span style="color:#bbb;">... еще '.$_end.' шт.</span>';}
					?>
				</td>
				<td>
					<a class="link_edit" href="<?=$path?>?parent=<?=$parent?>&update=<?=$filter->id?>" title="редактировать"></a>
				</td>
				<td>
					<a class="link_del" data-delete="<?=htmlspecialchars($filter->name)?>" href="<?=$path?>?parent=<?=$parent?>&delete=<?=$filter->id?>" title="удалить"></a>
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
		<table class="table-1">
			<tr>
				<td class="small right"><b>Название:</b></td>
				<td class="left"><input class="inf" type="text" name="name" value=""></td>
			</tr>
			<tr>
				<td class="right"><b>Значение фильтра:</b></td>
				<td class="left">
					<table class="table-1">
						<thead>
							<tr>
								<td class="small"></td>
								<td><span title="обязательго для заполнения"><i class="c-red" >*</i> Название</span></td>
								<td>Префикс</td>
								<td class="small">
									<a class="link_add" data-filter-item="add" title="добавить"></a>
								</td>
							</tr>
						</thead>
						<tbody data-sortable="body" data-filter-item="box"></tbody>
					</table>
				</td>
			</tr>
		</table>
		
		<input type="hidden" name="add" value="">
	</form>
	<?php endif;?>
	
	
	
<!--UPDATE-->	
	<?php if($act == 'update'):?>
	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button orange" data-form-apply="">Применить</a>
			<a class="button blue" onclick="$('#form').submit()" >Сохранить</a>
			<a class="button black" href="<?=$path?>?parent=<?=$parent?>">Отмена</a>
		</div>
	</div>
	
	<form id="form" action="<?=$path?>" method="POST" enctype="multipart/form-data">
		<table class="table-1">
			<tr>
				<td class="small right"><b>Название:</b></td>
				<td class="left"><input class="inf" type="text" name="name" value="<?=htmlspecialchars($filter->name)?>"></td>
			</tr>
			<tr>
				<td class="right"><b>Значение фильтра:</b></td>
				<td class="left">
					<table class="table-1">
						<thead>
							<tr>
								<td class="small"></td>
								<td><span title="обязательго для заполнения"><i class="c-red" >*</i> Название</span></td>
								<td>Префикс</td>
								<td class="small">
									<a class="link_add" data-filter-item="add" title="добавить"></a>
								</td>
							</tr>
						</thead>
						<tbody data-sortable="body" data-filter-item="box">
						<?php foreach($filter->items as $item):?>
							<tr data-filter-item="item">
								<td>
									<span class="icon-reorder handler" data-sortable="handler"></span>
									<input type="hidden" data-sortable="order" name="filter_item_order[update][<?=$item->id?>]" value="<?=$item->order?>">
								</td>
								<td class="left">
									<input class="inf" name="filter_item_name[update][<?=$item->id?>]" value="<?=htmlspecialchars($item->name)?>">
								</td>
								<td class="left">
									<input class="inf" name="filter_item_prefix[update][<?=$item->id?>]" value="<?=htmlspecialchars($item->prefix)?>">
								</td>
								<td>
									<a class="link_del" data-filter-item="delete" title="удалить"></a>
								</td>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>

		<input type="hidden" name="id" value="<?=$filter->id?>">
		<input type="hidden" name="edit" value="">
	</form>
	<?php endif;?>
	
</div><!--CENTER-->

<script> // APPLY
$(document).on('click', '[data-form-apply]', function(e){
	$(document.body).append($('<div>').addClass('_load'));
	
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

<script> // ADD ITEM FILTER
$(function(){
	var A = {
		create:function(){
			var html = $(
			'<tr data-filter-item="item" class="new-tr">'+
				'<td class="small">'+
					'<span class="icon-reorder handler" data-sortable="handler"></span>'+
					'<input type="hidden" data-sortable="order" name="filter_item_order[insert][]" value="">'+
				'</td>'+
				'<td class="left">'+
					'<input class="inf" name="filter_item_name[insert][]" value="">'+
				'</td>'+
				'<td class="left">'+
					'<input class="inf" name="filter_item_prefix[insert][]" value="">'+
				'</td>'+
				'<td>'+
					'<a class="link_del" data-filter-item="delete" title="удалить"></a>'+
				'</td>'+
			'</tr>');
			
			
			$('[data-filter-item="box"]').prepend(html).trigger('sortupdate');
		},
		init:function(){
			$(document).on('click','[data-filter-item="add"]', A.create)
			.on('click','[data-filter-item="delete"]',function(){
				$(this).parents('[data-filter-item="item"]').hide(200, function(){
					$(this).remove();
				});
			});
		}
	}
	A.init();
});
</script>