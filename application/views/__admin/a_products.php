<div class="body">

<!--ALL-->
	<?php if ($act == 'all'):?>
	
	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft">
			<form>
				<select data-select="auto-submit" name="parent" style="display:none;">
					<option value="0" selected> - Выбрать категорию - </option>
					<?php foreach($parents as $category):?>
					<option value="<?=$category['id']?>" <?=$parent == $category['id']?'selected':'';?>><?=$category['name']?></option>
					<?php endforeach;?>
				</select>
			</form>
		</div>
		<div class="fright">
			<a class="button green" href="<?=$path?>?add&parent=<?=$parent?>" title="добавить">добавить</a>
		</div>
	</div>

	<table class="table-1" data-scroll="head">
		<thead>
			<tr>
				<td class="small">№</td>
				<td class="small">
					<a class="icon-save send-order" data-sortable="send-order" title="применить сортировку"></a>
				</td>
				<td class="small"></td>
				<td>Название</td>
				<td class="small">Хит</td>
				<td class="small">New</td>
				<td class="small">URL</td>
				<td class="small">visible</td>
				<td class="small"></td>
				<td class="small"></td>
			</tr>
		</thead>
		<tbody data-sortable="body">
			<?php $i=1; foreach ($products as $k):?>
			<tr>
				<td><?=$i++?></td>
				<td>
					<span class="icon-reorder handler" data-sortable="handler"></span>
					<input data-sortable="id" type="hidden" name="product_id[]" value="<?=$k->id?>">
					<input data-sortable="order" type="hidden" name="product_order[]" value="<?=$k->order?>">
				</td>
				<td>
					<a class="image" href="/img/products/<?=$k->id?>/<?=$k->id?>.jpg">
						<img onerror="this.src = '/img/i/loading_mini.gif'" src="/img/products/<?=$k->id?>/<?=$k->id?>_82_82.jpg" width="82" height="82">
					</a>
				</td>
				<td class="left"><a href="<?=$path?>?update=<?=$k->id?>"><?=$k->name?></a></td>
				<td>
					<a class="toggle icon-star <?=$k->hit == 1 ? ' activ' : '' ?>" data-id="<?=$k->id?>" data-column="hit" data-bind="toggle" title="определить как популярный"></a>
				</td>
				<td>
					<a class="toggle icon-star <?=$k->new == 1 ? ' activ' : '' ?>" data-id="<?=$k->id?>" data-column="new" data-bind="toggle" title="определить как популярный"></a>
				</td>
				<td class="left nowrap"><?=$k->url?></td>
				<td>
					<a class="toggle icon-eye <?=$k->visibility == 1 ? ' activ' : '' ?>" data-id="<?=$k->id?>" data-column="visibility" data-bind="toggle" title="скрыть на сайте"></a>
				</td>
				<td>
					<a title="редактировать" class="link_edit" href="<?=$path?>?parent=<?=$parent?>&update=<?=$k->id?>"></a>
				</td>
				<td>
					<a title="удалить" class="link_del" data-delete="<?=htmlspecialchars($k->name)?>" href="<?=$path?>?parent=<?=$parent?>&delete=<?=$k->id?>"></a>
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
	
	<form id="form" action="" method="POST" enctype="multipart/form-data">
		
		<div class="toggle-box">
			<a class="bookmark-toggle activ" data-name="data" href="#data">Общие</a>
			<a class="bookmark-toggle" data-name="relations" href="#relations">Связи</a>
			<a class="bookmark-toggle" data-name="images" href="#images">Изображения</a>
		</div>
		
		<div class="bookmark activ" data-id="data">
			<table class="table-1">
				<tr>
					<td class="small right"><b>Название:</b></td>
					<td class="left"><input class="inf" type="text" name="name" value=""></td>
				</tr>
				<tr>
					<td class="right"><b>H1:</b></td>
					<td class="left"><input class="inf" type="text" name="h1" value=""></td>
				</tr>
				<tr>
					<td class="right"><b>Title:</b></td>
					<td class="left"><input class="inf" type="text" name="title" value=""></td>
				</tr>
				<tr>
					<td class="right"><b>Description:</b></td>
					<td class="left"><textarea class="inf" style="height:70px;" name="metadesc"></textarea></td>
				</tr>
				<tr>
					<td class="right"><b>Keywords:</b></td>
					<td class="left"><textarea class="inf" style="height:70px;" name="metakey"></textarea></td>
				</tr>
				<tr>
					<td class="right"><b>СПАМ:</b></td>
					<td class="left"><textarea class="inf" style="height:70px;" name="spam"></textarea></td>
				</tr>
				<tr>
					<td class="right"><b>Ссылка:</b></td>
					<td class="left"><input class="inf" type="text" name="url" value=""></td>
				</tr>
				<tr>
					<td class="right"><b>Изображение:</b></td>
					<td class="left">
						<table class="table-1">
							<tr data-preload="box">
								<td class="small">
									<div class="image">
										<img data-preload="image" src="/" alt="">
									</div>
								</td>
								<td class="left">
									<a class="button green" href="javascript:void(0)">
										загрузить
										<input class="upload" data-button-file="image" type="file" name="image" value="">
									</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="right"><b class="c-green">Цена (ua):</b></td>
					<td class="left"><input data-mask="price" class="inf min" type="text" name="price" value="0"></td>
				</tr>
				<tr>
					<td class="right"><b class="c-red">Цена ($):</b></td>
					<td class="left"><input data-mask="price" class="inf min" type="text" name="price_usa" value="0"></td>
				</tr>
				<tr>
					<td class="right"><b>Скидка (ua):</b></td>
					<td class="left"><input data-mask="price" class="inf min" type="text" name="price-discount" value="0"></td>
				</tr>
				<tr>
					<td class="right"><b>Дата окончания скидки:</b></td>
					<td class="left">
						<span>
							<input class="inf min" type="text" value="" name="end-discount" data-datepicker="">
						</span>
						<span>(сброс скидки) Распространяется как на одиночную цену так и на множество цен</span>
					</td>
				</tr>
				<tr>
					<td class="right"><b>Несколько цен:</b></td>
					<td class="left">
						<table class="table-1">
							<thead>
								<tr class="head">
									<td class="small">№</td>
									<td class="small">Размер</td>
									<td>Кол. опт.</td>
									<td>Кол. роз.</td>
									<td>Цена опт. (ua)</td>
									<td>Цена роз. (ua)</td>
									<td class="c-red">Цена ($) опт.</td>
									<td class="c-red">Цена ($) роз.</td>
									<td>Скидка</td>
									<td class="small">
										<a class="link_add" data-price="add" title="добавить"></a>
									</td>
								</tr>
							</thead>
							<tbody data-sortable="body" data-price="box"></tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td class="right"><b>Текст:</b></td>
					<td class="left"><textarea class="tiny" rows="30" style="width:100%;" name="text"></textarea></td>
				</tr>
			</table>
		</div>
		
		<div class="bookmark" data-id="relations">
			<table class="table-1">
				<tr>
					<td class="small right nowrap"><b>Категория:</b></td>
					<td class="left">
						<select data-select="" data-bind="category" name="parent">
							<option value="0" selected> - Выбрать категорию - </option>
							<?php foreach($parents as $k):?>
							<option value="<?=$k['id']?>" <?=$parent == $k['id']?'selected':'';?>><?=$k['name']?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="right"><b>Фильтры:</b></td>
					<td>
						<table class="table-1" data-filter="table"></table>
					</td>
				</tr>
				<tr>
					<td class="small right nowrap"><b>Производитель:</b></td>
					<td class="left">
						<select data-select="" name="manufacturer" style="display:none;">
							<option value="0" selected> - Выбрать -</option>
							<?php foreach($manufacturer as $k):?>
							<option value="<?=$k->id?>"><?=$k->name?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="small right nowrap"><b>Сопутствующие товары:</b></td>
					<td class="left">
						<table class="table-1">
							<thead>
								<tr>
									<td class="small"></td>
									<td class="small"></td>
									<td>Название</td>
									<td class="small">
										<a class="link_add" data-relation="add" title="добавить"></a>
									</td>
								</tr>
							</thead>
							<tbody data-relation="box" data-sortable="body"></tbody>
						</table>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="bookmark" data-id="images">
			<table class="table-1">
				<thead>
					<tr>
						<td class="small">№</td>
						<td class="small"></td>
						<td>alt</td>
						<td class="small">
							<a class="link_add" data-images="add" title="добавить"></a>
						</td>
					</tr>
				</thead>
				<tbody data-sortable="body" data-images="box"></tbody>
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
	
	<form id="form" action="" method="POST" enctype="multipart/form-data">
		
		<div class="toggle-box">
			<a class="bookmark-toggle activ" data-name="data" href="#data">Общие</a>
			<a class="bookmark-toggle" data-name="relations" href="#relations">Связи</a>
			<a class="bookmark-toggle" data-name="images" href="#images">Изображения</a>
		</div>
		
		<div class="bookmark activ" data-id="data">
			<table class="table-1">
				<tr>
					<td class="small right"><b>Название:</b></td>
					<td class="left"><input class="inf" type="text" name="name" value="<?=htmlspecialchars($product->name)?>"></td>
				</tr>
				<tr>
					<td class="right"><b>H1:</b></td>
					<td class="left"><input class="inf" type="text" name="h1" value="<?=htmlspecialchars($product->h1)?>"></td>
				</tr>
				<tr>
					<td class="right"><b>Title:</b></td>
					<td class="left"><input class="inf" type="text" name="title" value="<?=htmlspecialchars($product->title)?>"></td>
				</tr>
				<tr>
					<td class="right"><b>Description:</b></td>
					<td class="left"><textarea class="inf" style="height:70px;" name="metadesc"><?=$product->metadesc?></textarea></td>
				</tr>
				<tr>
					<td class="right"><b>Keywords:</b></td>
					<td class="left"><textarea class="inf" style="height:70px;" name="metakey"><?=$product->metakey?></textarea></td>
				</tr>
				<tr>
					<td class="right"><b>СПАМ:</b></td>
					<td class="left"><textarea class="inf" style="height:70px;" name="spam"><?=$product->spam?></textarea></td>
				</tr>
				<tr>
					<td class="right"><b>URL:</b></td>
					<td class="left"><input class="inf" type="text" name="url" value="<?=$product->url?>"></td>
				</tr>
				<tr>
					<td class="right"><b>Изображение:</b></td>
					<td class="left">
						<table class="table-1">
							<tr data-preload="box">
								<td class="small">
									<div class="image">
										<img data-preload="image" src="/img/products/<?=$product->id?>/<?=$product->id?>_82_82.jpg">
									</div>
								</td>
								<td class="left">
									<a class="button green" href="javascript:void(0)">
										загрузить
										<input class="upload" data-button-file="image" type="file" name="image" value="">
									</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="right"><b class="c-green">Цена (ua):</b></td>
					<td class="left"><input data-mask="price" class="inf min" type="text" name="price" value="<?=$product->price?>"></td>
				</tr>
				<tr>
					<td class="right"><b class="c-red">Цена ($):</b></td>
					<td class="left"><input data-mask="price" class="inf min" type="text" name="price_usa" value="<?=$product->price_usa?>"></td>
				</tr>
				<tr>
					<td class="right"><b>Скидка (ua):</b></td>
					<td class="left"><input data-mask="price" class="inf min" type="text" name="price-discount" value="<?=$product->discount?>"></td>
				</tr>
				<tr>
					<td class="right"><b>Дата окончания скидки:</b></td>
					<td class="left">
						<span>
							<input class="inf min" type="text" value="<?=$product->end_discount ? date('Y-m-d', $product->end_discount) : ''?>" name="end-discount" data-datepicker="">
						</span>
						<span>(сброс скидки) Распространяется как на одиночную цену так и на множество цен</span>
					</td>
				</tr>
				<tr>
					<td class="right"><b>Несколько цен:</b></td>
					<td class="left">
						<table class="table-1">
							<thead>
								<tr class="head">
									<td class="small">№</td>
									<td class="small">Размер</td>
									<td>Кол. опт.</td>
									<td>Кол. роз.</td>
									<td>Цена опт. (ua)</td>
									<td>Цена роз. (ua)</td>
									<td class="c-red">Цена ($) опт.</td>
									<td class="c-red">Цена ($) роз.</td>
									<td>Скидка</td>
									<td class="small">
										<a class="link_add" data-price="add" title="добавить"></a>
									</td>
								</tr>
							</thead>
							<tbody data-sortable="body" data-price="box">
							<?php foreach ($product->prices as $price):?>
								<tr data-price="item">
									<td>
										<span class="icon-reorder handler" data-sortable="handler"></span>
									</td>
									<td class="right">
										<select data-select="new" name="id_filter_item_price[]">
											<option value="0" selected> - Выбрать - </option>
											<?php foreach($filter_item_pricing as $item):?>
											<option value="<?=$item->id?>" <?=$item->id == $price->id_filter_item ? 'selected' : '';?>><?=$item->name?> <?=$item->prefix?></option>
											<?php endforeach;?>
										</select>
									</td>
									<td>
										<input class="inf min" type="text" name="cnt_opt[]" value="<?=$price->cnt_opt?>">
									</td>
									<td>
										<input class="inf min" type="text" name="cnt_roz[]" value="<?=$price->cnt_roz?>">
									</td>
									<td>
										<input data-mask="price" class="inf min" type="text" name="opt[]" value="<?=$price->opt?>">
									</td>
									<td>
										<input data-mask="price" class="inf min" type="text" name="roz[]" value="<?=$price->roz?>">
									</td>
									<td>
										<input data-mask="price" class="inf min" type="text" name="usa_opt[]" value="<?=$price->usa_opt?>">
									</td>
									<td>
										<input data-mask="price" class="inf min" type="text" name="usa_roz[]" value="<?=$price->usa_roz?>">
									</td>
									<td>
										<input data-mask="price" class="inf min" type="text" name="discount[]" value="<?=$price->discount?>">
									</td>
									<td>
										<a class="link_del" data-price="delete" title="удалить"></a>
									</td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td class="right"><b>Текст:</b></td>
					<td class="left"><textarea class="tiny" rows="30" style="width:100%;" name="text"><?=$product->text?></textarea></td>
				</tr>
			</table>
		</div>
		
		<div class="bookmark" data-id="relations">
			<table class="table-1">
				<tr>
					<td class="small right nowrap"><b>Категория:</b></td>
					<td class="left">
						<select data-select="" data-bind="category" name="parent">
							<option value="0" selected> - Выбрать - </option>
							<?php foreach($parents as $k):?>
							<option value="<?=$k['id']?>" <?=$product->parent == $k['id'] ? ' selected':'';?>><?=$k['name']?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="right"><b>Фильтры:</b></td>
					<td>
						<table class="table-1" data-filter="table"></table>
					</td>
				</tr>
				<tr>
					<td class="small right nowrap"><b>Производитель:</b></td>
					<td class="left">
						<select data-select="" name="manufacturer" style="display:none;">
							<option value="0" selected> - Выбрать -</option>
							<?php foreach($manufacturer as $k):?>
							<option value="<?=$k->id?>" <?=$product->manufacturer == $k->id ? ' selected':'';?>><?=$k->name?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="small right nowrap"><b>Сопутствующие товары:</b></td>
					<td class="left">
						<table class="table-1">
							<thead>
								<tr>
									<td class="small"></td>
									<td class="small"></td>
									<td>Название</td>
									<td class="small">
										<a class="link_add" data-relation="add" title="добавить"></a>
									</td>
								</tr>
							</thead>
							<tbody data-relation="box" data-sortable="body">
							<?php foreach($product->related as $k):?>
								<tr data-relation="item">
									<td>
										<span class="icon-reorder handler" data-sortable="handler"></span>
									</td>
									<td>
										<a class="image">
											<img alt="" src="/img/products/<?=$k->id?>/<?=$k->id?>_82_82.jpg" onerror="this.src = '/img/i/loading_mini.gif'">
										</a>
									</td>
									<td class="left"><?=$k->name?></td>
									<td>
										<input type="hidden" name="related[<?=$k->id?>]" value="<?=$k->id?>">
										<a class="link_del" data-relation="delete"></a>
									</td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="bookmark" data-id="images">
			<table class="table-1">
				<thead>
					<tr>
						<td class="small">№</td>
						<td class="small"></td>
						<td>alt</td>
						<td class="small">
							<a class="link_add" data-images="add" title="добавить"></a>
						</td>
					</tr>
				</thead>
				<tbody data-sortable="body" data-images="box">
				<?php foreach($product->images as $k):?>
					<tr data-images="item">
						<td>
							<span class="icon-reorder handler" data-sortable="handler"></span>
						</td>
						<td>
							<div class="FM-image-box" style="">
								<div class="i">
									<img class="FM-image" src="<?=htmlspecialchars($k->url)?>" alt="image">
								</div>
								<input type="hidden" name="product_image[]" value="<?=htmlspecialchars($k->url)?>">
								<br><a href="/" class="FM-overview">обзор</a> | <a href="/" class="FM-clear">очистить</a>
							</div>
						</td>
						<td class="left">
							<input class="inf" type="text" name="product_image_alt[]" value="">
						</td>
						<td>
							<a class="link_del" data-images="delete" title="удалить"></a>
						</td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>
			
		<input type="hidden" name="id" value="<?=$product->id?>">
		<input type="hidden" name="edit" value="">
	</form>
	<?php endif;?>

</div><!--END ID=BODY-->

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

<script> // PRICES
$(function(){
	var S = {
		create:function(){
			var html = 
			'<tr data-price="item" class="new-tr">'+
				'<td><span class="icon-reorder handler" data-sortable="handler"></span></td>'+
				'<td class="right">'+
					'<select data-select="new" name="id_filter_item_price[]">'+
						'<option value="0" selected=""> - Выбрать - </option>';
					$(S.sizes).each(function(){
						html += 
						'<option value="'+this.id+'">'+ this.name +' '+ this.prefix +'</option>';
					});
					html += 
					'</select>'+
				'</td>'+
				'<td>'+
					'<input class="inf min" type="text" name="cnt_opt[]" value="">'+
				'</td>'+
				'<td>'+
					'<input class="inf min" type="text" name="cnt_roz[]" value="">'+
				'</td>'+
				'<td>'+
					'<input data-mask="price" class="inf min" type="text" name="opt[]" value="">'+
				'</td>'+
				'<td>'+
					'<input data-mask="price" class="inf min" type="text" name="roz[]" value="">'+
				'</td>'+
				'<td>'+
					'<input data-mask="price" class="inf min" type="text" name="usa_opt[]" value="">'+
				'</td>'+
				'<td>'+
					'<input data-mask="price" class="inf min" type="text" name="usa_roz[]" value="">'+
				'</td>'+
				'<td>'+
					'<input data-mask="price" class="inf min" type="text" name="discount[]" value="">'+
				'</td>'+
				'<td>'+
					'<a class="link_del" data-price="delete" title="удалить"></a>'+
				'</td>'+
			'</tr>';
			html = $(html);		
			
			$('[data-price="box"]').prepend(html);
			html.find('[data-select="new"]').selectmenu({width:'auto'});
			html.find('[data-mask="price"]').mask("$?$$$$$$$$$$$",{ placeholder:"" });
		},
		init:function(sizes){
			try{S.sizes = $.parseJSON(sizes)}catch(e){}
			
			if ( ! S.sizes) return;
			
			$(document).on('click','[data-price="add"]', S.create)
			.on('click','[data-price="delete"]',function(){
				$(this).parents('[data-price="item"]').hide(200, function(){$(this).remove()});
			});
		}
	}
	S.init('<?=isset($filter_item_pricing) ? json_encode($filter_item_pricing) : null?>');
});
</script>

<script> // RELATED
$(function(){
	var R = {
		add:function(){
			var id = $(this).data('product'),
				name = $(this).data('name'),
				image = $(this).data('image'),
				html = $('<tr data-relation="item" class="new-tr">'+
							'<td>'+
								'<span class="icon-reorder handler" data-sortable="handler"></span>'+
							'</td>'+
							'<td>'+
								'<a class="image">'+
									'<img src="'+image+'" alt="image">'+
								'</a>'+
							'</td>'+
							'<td class="left">'+name+'</td>'+
							'<td>'+
								'<input type="hidden" name="related['+id+']" value="'+id+'">'+
								'<a class="link_del" data-relation="delete"></a>'+
							'</td>'+
						'</tr>');
						
			$('[data-relation="box"]').prepend(html);
		},

		create:function(){
			var html = '<div>'+
							'<div style="padding:0 0 5px 0;">'+
								'<select data-selectmenu="">'+
									'<option value="0">- Выбрать категорию товаров -</option>';
								$.each(R.categories, function(){
									html +=
									'<option value="'+ this.id +'">'+ this.name +'</option>';
								});
								html += 
								'</select>'+
							'</div>'+
							'<div style="max-height:300px;overflow-x:auto;border-top:1px solid #ccc;">'+
								'<table class="table-1"></table>'+
							'</div>'+
						'</div>';
			
			var D = $(html).dialog({
				width:'600px',
				height:'auto',
				drag:true,
				title:'Добавить сопутствующий товар'
			});
			
			var table = D.data('dialog').dialog.find('table');
			
			D.data('dialog').dialog.find('[data-selectmenu]').selectmenu({	
				width:'auto',
				change:function(e, ui){
					var id = ui.item.value,
						trs = '';
					
					D.dialog('load');
					$.post('', {getProductOfCategory:id}, function(data){
						
						$.each(data, function(){
							trs +=
							'<tr>'+
								'<td class="small">'+
									'<img width="30" src="'+this.image+'" alt="'+ this.name +'">'+
								'</td>'+
								'<td class="left">'+ this.name +'</td>'+
								'<td class="small">'+
									'<a class="button green" data-product="'+ this.id +'" data-image="'+ this.image +'" data-name="'+ this.name +'">add</a>'+
								'</td>'+
							'</tr>';
						});
						
						table.html(trs);
						
						table.find('[data-product]').click(R.add);
						
						D.dialog('endLoad');
					}, 'json');
				}
			});
		},
		
		init:function(data){
			try{R.categories = $.parseJSON(data);}catch(e){}
			if ( ! R.categories) return;

			$(document).on('click','[data-relation="add"]', R.create)
			.on('click','[data-relation="delete"]',function(){
				$(this).parents('[data-relation="item"]').hide(200, function(){
					$(this).remove();
				});
			});
		}
	}
	R.init('<?=isset($parents) ? json_encode($parents) : 0;?>');
});
</script>

<script> // IMAGES
$(function(){
	var A = {
		create:function(){
			var html = $('<tr data-images="item" class="new-tr">'+
							'<td>'+
								'<span class="icon-reorder handler" data-sortable="handler"></span>'+
							'</td>'+
							'<td>'+
								'<div class="FM-image-box">'+
									'<div class="i">'+
										'<img class="FM-image" src="/img/i_admin/loading_mini.gif" alt="image">'+
									'</div>'+
									'<input type="hidden" name="product_image[]" value="">'+
									'<br><a href="/" class="FM-overview">обзор</a> | <a href="/" class="FM-clear">очистить</a>'+
								'</div>'+
							'</td>'+
							'<td class="left">'+
								'<input class="inf" type="text" name="product_image_alt[]" value="">'+
							'</td>'+
							'<td>'+
								'<a class="link_del" data-images="delete" title="удалить"></a>'+
							'</td>'+
						'</tr>');
						
			$('[data-images="box"]').prepend(html);
		},
		init:function(){
			$(document).on('click','[data-images="add"]', A.create)
			.on('click','[data-images="delete"]',function(){
				$(this).parents('[data-images="item"]').hide(200,function(){
					$(this).remove();
				});
			});
		}
	}
	A.init();
});
</script>

<script> // FILTEERS
$(function(){
	var F = {
		change:function(e){
			var arr = [];
			$(this).parents('[data-filter="box"]').find('input:checked').each(function(){
				arr.push($(this).attr('data-filter-value'));
			});
			
			$(this).parents('[data-filter="box"]').find('[data-filter="change"]').html(
				arr.length != 0 ? arr.join('&nbsp;&bull;&nbsp;') : '--выбрать--'
			);
		},
		create:function(data){
			var html = '';
				$.each(data, function(){
					html += 
					'<tr>'+
						'<td class="small nowrap right '+(this.visibility == 0 ? ' disabled' : '')+'">'+this.name+'</td>'+
						'<td class="left">'+
							'<div class="filter-box" data-filter="box">'+
								'<div data-filter="change">--выбрать--</div>'+
								'<div class="filter-items" data-filter="items">';
									$.each(this.items, function(){
									html +=
									'<label>'+
										'<input type="checkbox" '+($.inArray(this.id, F.ids) == -1 ? '' : ' checked')+' name="id_filter_item[]" data-filter-value="'+this.name+'" value="'+this.id+'">'+
										this.name+
									'</label>';
									});
								html += 
								'</div>'+
							'</div>'+
						'</td>'+
					'</tr>';
				});
				
			// очищаем и вставляем новые фильтра
			F.table.html('').append(html);
			
			// надо сделать trigger (что-бы появились выбранные значения)
			F.table.find('[data-filter="items"]').find('input:checkbox:first').trigger('change'); 
		},
		open:function (e){
			// перехватываеи (чтоб не сработал F.close)
			e.stopPropagation ? e.stopPropagation() : (e.cancelBubble = true);
			
			F.close();
			
			$(this).find('[data-filter="items"]').css({display:'block'});
		},
		close:function(){
			F.table.find('[data-filter="items"]').css({display:'none'});
		},
		init:function(a){
			try{F.ids = $.parseJSON(a);}catch(e){};
			
			// определение категории
			$('[data-bind="category"]').on("selectmenuchange", function(){
				$.post('',{getFiltersOfCategory:this.value}, F.create, 'json');
			}).trigger('selectmenuchange');
			
			// таблица где все происходит
			F.table = $('[data-filter="table"]');
			
			// клик на блок с значениями
			F.table.on('click.filter', '[data-filter="box"]', F.open);
			
			// клик значения
			F.table.on('change.filter', 'input:checkbox', F.change); 
			
			// если клацам не на окно - закрываем
			$(document).bind('click.filter', F.close);
		}
	}
	
	F.init('<?=isset($product->filter_item) ? json_encode($product->filter_item) : 0;?>');
});
</script>