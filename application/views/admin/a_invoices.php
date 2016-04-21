<div class="body">
	
	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button green" data-bind="add_invoice" href="" title="Создать накладную">Создать накладную</a>
		</div>
	</div>

	
	<table class="table-1">
		<thead>
			<tr>
				<td class="small">№</td>
				<td class="small">Дата</td>
				<td>Тип</td>
				<td class="small"></td>
				<td class="small">
					<a class="link_add" data-bind="add_invoice" title="Создать накладную"></a>
				</td>
			</tr>
		</thead>
		<tbody id="invoices"></tbody>
	</table>
	
</div><!--END ID=BODY-->


<script> // ADD INVOICE
$(function(){
	$(document).on('click', '[data-bind="add_invoice"]', function(e){
		e.preventDefault();
		
		$.post('/admin/invoice', {getFormAddInvoice:''}, function(data){
			
			$(data['form_add_invoice']).dialog({
				type:'confirm',
				title:'Создать накладную',
				width:'90%',
				drag:true
			})
			.on('dialogConfirmAgree', function(a,b){
				var form = $(this).data('dialog').dialog.find('form');
				$.post('/admin/invoice/', form.serialize(), function(response){
					
					if (response['error'])
						return show_error('Ошибка', response['error']);
					
				}, 'json', 'loading')
				.fail(function(){
					show_error('Ошибка', ['Ошибка при добавлении товара']);
				});
			});
			
		}, 'json', 'loading')
		.fail(function(){
			show_error('Ошибка', ['ошибка при получении формы']);
		});
	});
});
</script>

<script> // EDIT INVOICE
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

<script> // DELETE INVOICE
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