<div class="body">

	<form id="form" method="post" enctype="multipart/form-data">
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
				<td>
					<input type="test" name="lol" value="">
				</td>
				<td>
					<button type="submit" data-form-apply="">GO</button>
				</td>
			</tr>
		</table>
	</form>
</div><!--END ID=BODY-->

<script> // APPLY
$(function(){
	return;
	$(document).on('click', '[data-form-apply]', function(e){
		$(document.body).append($('<div>').addClass('_load'));
		
		if (tinyMCE && tinyMCE.editors){
			$(tinyMCE.editors).each(function(){
				$(this.getElement()).html(this.getContent());
			});
		}
		
		AP.init('', $('#form')[0], function(){	
			//location.reload(true);
		});
		return false;	
	});
});
</script>
