<div class="body">
	
	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button green" href="<?=$path?>?add&parent=<?=$parent?>" title="добавить">добавить</a>
		</div>
	</div>

	<?php if ($act == 'add'):?>
	<form onsubmit="return false">
		<table class="table-1">
			<tr>
				<td class="small"><b>Название</b></td>
				<td>
					<input class="inf" type="text" name="name" value="">
				</td>
			</tr>
			<tr>
				<td class=""><b>Сирийный номер</b></td>
				<td>
					<input class="inf" type="text" name="serial_number" value="">
				</td>
			</tr>
			<tr>
				<td class=""><b>Размер</b></td>
				<td>
				
				</td>
			</tr>
			<tr>
				<td class=""><b>Цвет</b></td>
				<td>
				
				</td>
			</tr>
			<tr>
				<td class=""><b></b></td>
				<td>
				
				</td>
			</tr>
		</table>
		
		<input type="hidden" name="add" value="">
	</form>
	
	
	<?php else:?>
	<table class="table-1">
		<thead>
			<tr>
				<td class="small">Категории</td>
				<td class="">Товары</td>
			</tr>
		</thead>
		<tbody>
			<td class="left top">
				<div id="tree-category" class="tree"></div>
			</td>
			<td class="left top">
				<table class="table-1" data-scroll="head">
					<thead>
						<tr>
							<td class="small">№</td>
							<td class="small">
								<a class="icon-save send-order" data-sortable="send-order" title="применить сортировку"></a>
							</td>
							<td>Название</td>
							<td class="small nowrap">Серийный номер</td>
							<td class="small"></td>
							<td class="small">
								<a id="add-product" class="link_add" title="Добавить товар"></a>
							</td>
						</tr>
					</thead>
					<tbody id="products" data-sortable="body"></tbody>
				</table>
			</td>
		</tbody>
	</table>
	<?php endif;?>
	
</div><!--END ID=BODY-->


<script> // ADD PRODUCT
$(function(){
	$(document).on('click', '#add-product', function(e){
		e.preventDefault();
		
		try{
			var category_id = +$.jstree.reference('#tree-category').get_selected();
		}catch(e){
			log(e.message);
		}
		
		// no changed category 
		if ( ! category_id){
			return $('<p>Не выбрана категория!</p>').dialog({
				title:'<div><i class="icon-warning-sign"></i> Внимание!</div>',
				width:'400px'
			});
		}
		
		var l = $('<div class="load"></div>').appendTo(document.body);
		$.post('/admin/products', {getFormAddProduct:''}, function(data){
			l.remove();
			
			$(data['form_add_product']).dialog({
				type:'confirm',
				title:'Добавить товар',
				width:'90%'
			}).on('dialogConfirmAgree', function(a,b){
				
				var l = $('<div class="load"></div>').appendTo(document.body);
				$.post('/admin/products/', $(this).serialize(), function(){
					l.remove();
					
					alert('Товар добавлен');
					
				}, 'json').fail(function(){
					alert('Ошибка при добавлении товара!');
				});
			});
			
		}, 'json')
		.fail(function(){
			alert('Ошибка при получении формы');
		});
	});
});
</script>


<script> // JS-TREE
$(function () {
	$('#tree-category').jstree({
		'core' : {
			'data' : {
				'method':'post',
				'url' : '/admin/category/',
				'data' : function (node) {
					return {'id':node.id, operation:'get_node'};
				}
			},
			'check_callback' : true,
			'themes' : {
				'name':'proton',
				'responsive' : false
			}
		},
		'force_text' : true,
		'plugins' : [
			'state',		// запоминает позицию выбранных папок
			'unique',		// уникальные названия папок
			'dnd',			// drag & drop	
			'contextmenu'	// create/rename/del/cut/paste
		]
	})
	.on('create_node.jstree', function (e, data) {
		$.post('/admin/category/', {'id':data.node.parent, 'position':data.position, 'text':data.node.text , operation:'create_node'})
			.done(function (d) {
				data.instance.set_id(data.node, d.id);
			})
			.fail(function () {
				data.instance.refresh();
			});
	})
	.on('rename_node.jstree', function (e, data) {
		$.post('/admin/category/', {'id':data.node.id, 'text':data.text, operation:'rename_node'})
			.fail(function () {
				data.instance.refresh();
			});
	})
	.on('move_node.jstree', function (e, data) {
		$.post('/admin/category/', {'id':data.node.id, 'parent':data.parent, 'position':data.position, operation:'move_node'})
			.fail(function () {
				data.instance.refresh();
			});
	})
	.on('changed.jstree', function (e, data) {
		if (data && data.selected && data.selected.length) {
			var l = $('<div class="load"></div>').appendTo(document.body);
			$.post('/admin/products/', {get_products_of_categories:data.selected.join(':'), format:'tr'}, function(data){
				l.remove();
				
				$('#products').html(data['products']);
				$('[data-scroll="head"] > thead:first').scrollHead('refresh');
				
			}, 'json');
		}
	})
	.on('delete_node.jstree', function (e, data) {
		var confirm = $('<p>Удалить категорию: <b>'+data.node.text+'</b> ?</p>').dialog({
			type:'confirm',
			width:'300px',
			height:'auto',
			title:'Удаление',
			drag:true
		}).on('dialogConfirmAgree', function(){
			var l = $('<div class="load"></div>').appendTo(document.body);
			$.post('/admin/category/', {'id':data.node.id, operation:'delete_node'})
				.done(function(response){
					l.remove();

					if (response['error']){
						var html = '<ol>';
						for (var i in response['error']){
							html += '<li>'+response['error'][i]+'</li>';
						}
						html += '<ol>';
					
						$('<div><p>Удаление невозможно!</p>'+html+'</div>').dialog({
							title:'<i class="icon-warning-sign"></i> Внимание!',
							width:'300px',
							drag:true
						});
					}
					data.instance.refresh();
				})
				.fail(function(data){
					l.remove();
					data.instance.refresh();
				});
		}).on('dialogConfirmCancel dialogClose', function(){
			data.instance.refresh();
		});
	});
});
</script>