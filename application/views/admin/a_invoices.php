<div class="body">
	
	<h1 class="title"><?=$h1?> <?=time()?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button green" href="<?=$path?>?add&parent=<?=$parent?>" title="добавить">добавить</a>
		</div>
	</div>

	
	<table class="table-1">
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
					<a class="link_add" data-bind="add_product" title="Добавить товар"></a>
				</td>
			</tr>
		</thead>
		<tbody id="products" data-sortable="body"></tbody>
	</table>
	
</div><!--END ID=BODY-->


<script> // ADD PRODUCT
$(function(){
	$(document).on('click', '[data-bind="add_product"]', function(e){
		e.preventDefault();
		
		try{
			var category_id = $.jstree.reference('#tree-category').get_selected();
			category_id = (!category_id.length || category_id.length > 1) ? 0 : +category_id[0];
		}catch(e){
			log(e.message);
		}
		
		if ( ! category_id)
			return show_error('Не выбрана категория!');
		
		$.post('/admin/products', {getFormAddProduct:'', category_id:category_id}, function(data){
			$(data['form_add_product']).dialog({
				type:'confirm',
				title:'Добавить товар',
				width:'90%',
				drag:true
			})
			.on('dialogConfirmAgree', function(a,b){
				var form = $(this).data('dialog').dialog.find('form');
				$.post('/admin/products/', form.serialize(), function(response){
					
					if (response['error'])
						return show_error('Ошибка', response['error']);
					
					$.jstree.reference('#tree-category').refresh();
					
				}, 'json', 'loading')
				.fail(function(){
					show_error('Ошибка', ['Ошибка при добавлении товара']);
				});
			});
			
		}, 'json', 'loading')
		.fail(function(err){
			show_error('Ошибка', ['ошибка при получении формы']);
		});
	});
});
</script>

<script> // EDIT PRODUCT
$(function(){
	$(document).on('click', '[data-bind="edit_product"]', function(e){
		e.preventDefault();
		
		$.post('/admin/products', {getFormEditProduct:'', product_id: $(this).data('id') }, function(data){
			$(data['form_edit_product']).dialog({
				type:'confirm',
				title:'Редактирование товара',
				width:'90%',
				drag:true
			})
			.on('dialogConfirmAgree', function(a,b){
				var form = $(this).data('dialog').dialog.find('form');
				$.post('/admin/products/', form.serialize(), function(response){
					
					if (response['error'])
						return show_error('Ошибка', response['error']);
					
					$.jstree.reference('#tree-category').refresh();
					
				}, 'json', 'loading')
				.fail(function(){
					show_error('Ошибка', ['Ошибка при редактировании товара']);
				});
			});
			
		}, 'json', 'loading')
		.fail(function(err){
			show_error('Ошибка', ['ошибка при получении формы']);
		});
	});
});
</script>

<script> // DELETE PRODUCT
$(document).on('click', '[data-bind="delete_product"]', function(e){
	e.preventDefault();
	
	var _this = $(this).data();
	
	var confirm = $('<p>Удалить <b>'+ _this['name'] +'</b>?</p>').dialog({
		type:'confirm',
		width:'300px',
		title:'Удаление',
		drag:true
	}).on('dialogConfirmAgree', function(){
		$.post('', {delete_product:_this['id']}, function(data){
			
			$.jstree.reference('#tree-category').refresh();
		
		}, 'json', 'loading')
		.fail(function(err){
			show_error('Ошибка', ['Ошибка при удалении товара']);
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
			$.post('/admin/products/', {get_products_of_categories:data.selected.join(':'), format:'tr'}, function(data){
				
				$('#products').html(data['products']);
				$('[data-scroll="head"] > thead:first').scrollHead('refresh');
				
			}, 'json', 'loading');
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
			$.post('/admin/category/', {'id':data.node.id, operation:'delete_node'})
				.done(function(response){
					
					if (response['error']) 
						show_error('Удаление невозможно', response['error']);
					
					data.instance.refresh();
				})
				.fail(function(data){
					data.instance.refresh();
				});
		}).on('dialogConfirmCancel dialogClose', function(){
			data.instance.refresh();
		});
	});
});
</script>