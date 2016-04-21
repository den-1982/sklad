<div class="body">
	
	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button green" data-bind="add_supplier" href="" title="Создать поставщика">Создать поставщика</a>
		</div>
	</div>

	
	<table class="table-1" data-scroll="head">
		<thead>
			<tr>
				<td class="small">№</td>
				<td class="small">
					<a class="icon-save send-order" data-sortable="send-order" title="применить сортировку"></a>
				</td>
				<td class="">Организация</td>
				<td class="">Контю лицо</td>
				<td class="small">Тел.</td>
				<td class="small">Email</td>
				<td class="small">Website</td>
				<td class="small">cart</td>
				<td class="small"></td>
				<td class="small">
					<a class="link_add" data-bind="add_supplier" title="Создать поставщика"></a>
				</td>
			</tr>
		</thead>
		<tbody id="suppliers" data-sortable="body">
			<?=$suppliers?>
		</tbody>
	</table>
	
</div><!--END ID=BODY-->


<script> // ADD, EDIT, DELETE SUPPLIER
$(function(){
	
	// ADD
	$(document).on('click', '[data-bind="add_supplier"]', function(e){
		e.preventDefault();
		
		$.post('/admin/supplier', {getFormAddSupplier:''}, function(data){
			
			$(data['form_add_supplier']).dialog({
				type:'confirm',
				title:'Создать поставщика',
				width:'90%',
				drag:true
			})
			.on('dialogConfirmAgree', function(a,b){
				var form = $(this).data('dialog').dialog.find('form');
				$.post('/admin/supplier/', form.serialize(), function(response){
					
					if (response['error'])
						return show_error('Ошибка', response['error']);
					
					getSuppliers();
					
				}, 'json', 'loading')
				.fail(function(){
					show_error('Ошибка', ['Ошибка при добавлении']);
				});
			});
			
		}, 'json', 'loading')
		.fail(function(){
			show_error('Ошибка', ['ошибка при получении формы']);
		});
	});
	
	// EDIT
	$(document).on('click', '[data-bind="edit_supplier"]', function(e){
		e.preventDefault();
		
		$.post('/admin/supplier', {getFormEditSupplier:'', supplier_id: $(this).data('id') }, function(data){
			$(data['form_edit_supplier']).dialog({
				type:'confirm',
				title:'Редактирование поставщиеа',
				width:'90%',
				drag:true
			})
			.on('dialogConfirmAgree', function(a,b){
				var form = $(this).data('dialog').dialog.find('form');
				$.post('/admin/supplier/', form.serialize(), function(response){
					
					if (response['error'])
						return show_error('Ошибка', response['error']);
					
					getSuppliers();
					
				}, 'json', 'loading')
				.fail(function(){
					show_error('Ошибка', ['Ошибка при редактировании']);
				});
			});
			
		}, 'json', 'loading')
		.fail(function(err){
			show_error('Ошибка', ['ошибка при получении формы']);
		});
	});
	
	// DELETE
	$(document).on('click', '[data-bind="delete_supplier"]', function(e){
		e.preventDefault();
		
		var _this = $(this).data();
		
		var confirm = $('<p>Удалить <b>'+ _this['name'] +'</b>?</p>').dialog({
			type:'confirm',
			width:'300px',
			title:'Удаление',
			drag:true
		}).on('dialogConfirmAgree', function(){
			$.post('', {delete_supplier:_this['id']}, function(data){
				
				getSuppliers()
				
			}, 'json', 'loading')
			.fail(function(err){
				show_error('Ошибка', ['Ошибка при удалении']);
			});
		});
	});
	
	// GET SUPPLIER
	$(document).on('click', '[data-bind="getSupplier"]', function(e){
		e.preventDefault();
		
		$.post('/admin/supplier', {getSupplier:$(this).data('id')}, function(data){
			
			var confirm = $(data['supplier']).dialog({
				width:'90%',
				title:'Поставщик',
				drag:true
			});
			
		}, 'json', 'loading');
	});
	
	// GET SUPPLIERS
	function getSuppliers(){
		$.post('/admin/supplier', {getSuppliers:''}, function(data){
			var suppliers = $(data['suppliers']);
			
			$('#suppliers').html(suppliers);
			
		}, 'json', 'loading');
	}
});
</script>