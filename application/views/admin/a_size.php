<div class="body">

	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button green" data-bind="add_size" href="" title="добавить">добавить</a>
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
				<td>Префикс</td>
				<td class="small"></td>
				<td class="small">
					<a class="link_add" data-bind="add_size" title="добавить"></a>
				</td>
			</tr>
		</thead>
		<tbody id="size" data-sortable="body"> 
			<?=$sizes?> 
		</tbody>
	</table>
	
</div><!--CENTER-->

<script> // ADD / DELETE / EDIT SIZE
$(function(){
	
	// DELETE SIZE
	$(document).on('click', '[data-bind="delete_size"]', function(e){
		e.preventDefault();
		
		var item = $(this).data();
		
		$('<p>Удалить <b>'+ item['name'] +'</b></p>').dialog({
			type:'confirm',
			title:'Удаление',
			width:'300px',
			drag:true
		})
		.on('dialogConfirmAgree', function(){
			$.post('/admin/size', {delete_size:item['id']}, function(data){
				getSizes();
			}, 'json', 'loading');
		});
	});
	
	
	// ADD SIZE
	$(document).on('click','[data-bind="add_size"]', function(e){
		e.preventDefault();
		
		$.post('/admin/size', {getFormAddSize:''}, function(data){
			$(data['form_add_size']).dialog({
				type:'confirm',
				title:'Добавление',
				width:'90%',
				drag:true
			})
			.on('dialogConfirmAgree', function(){
				var form = $(this).data('dialog').dialog.find('form');
				$.post('/admin/size', form.serialize(), function(data){
					getSizes();
				}, 'json', 'loading');
			});
		}, 'json', 'loading');
	});
	
	
	// EDIT SIZE
	$(document).on('click','[data-bind="edit_size"]', function(e){
		e.preventDefault();
		
		$.post('/admin/size', {getFormEditSize:'', size_id:$(this).data('id')}, function(data){
			$(data['form_edit_size']).dialog({
				type:'confirm',
				title:'Редактирование',
				width:'90%',
				drag:true
			})
			.on('dialogConfirmAgree', function(){
				var form = $(this).data('dialog').dialog.find('form');
				$.post('/admin/size', form.serialize(), function(data){
					getSizes();
				}, 'json', 'loading');
			});
		}, 'json', 'loading');
	});
	
	
	// GET SIZES
	function getSizes(){
		$.post('/admin/size', {getSizes:''}, function(data){
			var sizes = $(data['sizes']);
			
			$('#size').html(sizes);
			
		}, 'json', 'loading');
	}
});
</script>