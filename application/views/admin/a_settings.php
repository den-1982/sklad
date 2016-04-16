<div class="body">

	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button orange" data-form-apply="">Применить</a>
		</div>
	</div>
	
	<form id="form" action="<?=$path?>" method="POST" enctype="multipart/form-data">
		
		<div class="toggle-box">
			<a class="bookmark-toggle activ" data-name="admin" href="#admin">Админ</a>
		</div>

		
		<div class="bookmark activ" data-id="admin">
			<table class="table-1">
				<tbody>
					<tr>
						<td class="small nowrap right">
							<b>Логин:</b>
						</td>
						<td class="left">
							<input class="inf" type="text" data-admin="login" name="admin[login]" value="<?=$admin->login?>" disabled>
						</td>
						<td class="small">
							<a class="link_edit" data-bind="enabled"></a>
						</dt>
					</tr>
					<tr>
						<td class="small nowrap right">
							<b>Пароль:</b>
						</td>
						<td class="left">
							<input class="inf" type="text" data-admin="password" name="admin[password]" value="" disabled>
						</td>
						<td class="small">
							<a class="link_edit" data-bind="enabled"></a>
						</dt>
					</tr>
					<tr>
						<td class="small nowrap right">
							<b>E-mail:</b>
						</td>
						<td class="left">
							<input class="inf" type="text" data-admin="email" name="admin[email]" value="<?=$admin->email?>" disabled>
						</td>
						<td class="small">
							<a class="link_edit" data-bind="enabled"></a>
						</dt>
					</tr>
				</tbody>
			</table>
		</div>
		
		<input type="hidden" name="edit" value="">
	</form>
	
</div><!-- END BODY -->

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

<script> // EDIT ADMIN
$(function(){
	$('input[data-admin]').prop('disabled', true);
	$('[data-bind="enabled"]').on('click', function(e){
		e.preventDefault();
		var _this = $(this).parents('tr').find('[data-admin]');
		_this.attr('disabled') ? _this.removeAttr('disabled') : _this.attr('disabled', 'disabled');
	});
});
</script>

<script> // SIZES IMAGE
;$(function(){
	var A = {
		create:function(){
			var html = $(
			'<tr data-sizeImage="item" class="new-tr">'+
				'<td></td>'+
				'<td class="left">'+
					'<input class="inf" type="text" data-mask="int" name="image_size[]" value="">'+
				'</td>'+
				'<td>'+
					'<a class="link_del" data-sizeImage="delete" title="Удалить размер"></a>'+
				'</td>'+
			'</tr>');
		
			$('[data-sizeImage="items"]').prepend(html);
			html.find('[data-mask="int"]').mask("9?999999999999",{ placeholder:""});
		},
		init:function(){
			$(document).on('click', '[data-sizeImage="add"]', A.create)
			.on('click', '[data-sizeImage="delete"]', function(){
				$(this).parents('[data-sizeImage="item"]').hide(200,function(){
					$(this).remove();
				});
			});
		}
	}
	A.init();
});
</script>