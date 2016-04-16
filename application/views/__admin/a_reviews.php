<div class="body">

	<?php
	$is_rating = array(
		0 => array('grey',''),
		1 => array('red','Очень плохо'),
		2 => array('orange','Плохо'),
		3 => array('yellow','Нормально'),
		4 => array('pale-green','Хорошо'),
		5 => array('green','Отлично')
	);
	$is_price_correct = array(
		0 => 'Не помню',
		1 => 'Да (хорошо)',
		2 => 'Нет (плохо)'
	);
	$is_delivery_in_time = array(
		0 => 'Не помню',
		1 => 'Да (хорошо)',
		2 => 'Нет (плохо)'
	);
	?>
	<style>
	.rating{
		position:relative;
		display:inline-block;
		width:87px;
		height:17px;
		overflow:hidden;
		vertical-align:middle;
	}
	.rating-text{display:inline;font-size:11px;vertical-align:middle;}
	.rating-indicator{
		position:absolute;
		top:0;bottom:0;left:0;
		background-color:#eee;
		z-index:2;
	}
	.rating-indicator.grey{background-color:#eee !important;width:100%;}
	.rating-indicator.red{background-color:#ff0000 !important;width:17px;}
	.rating-indicator.orange{background-color:#FF9400 !important;width:34px;}
	.rating-indicator.yellow{background-color:#FFFA00 !important;width:51px;}
	.rating-indicator.pale-green{background-color:#9DFF00 !important;width:69px;}
	.rating-indicator.green{background-color:#07DB00 !important;width:87px;}
	.rating-substrate{
		position:absolute;
		top:0;bottom:0;right:0;left:0;
		background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFcAAAARCAYAAACoyTAdAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjk3MzAyQTRGQUFDNzExRTRBOUQ2OEUzQUVFNTE3OEQ1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjk3MzAyQTUwQUFDNzExRTRBOUQ2OEUzQUVFNTE3OEQ1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTczMDJBNERBQUM3MTFFNEE5RDY4RTNBRUU1MTc4RDUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6OTczMDJBNEVBQUM3MTFFNEE5RDY4RTNBRUU1MTc4RDUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7haXkzAAAAmElEQVR42uyYMQrDMAxFJbujT+De/2jNbvBqKzXUoaJTnRZaeA+CvOTF/GiRVESyLGJmt3lW1Ssez+VRt4V3c+9dQggyKp5XT5AT3P+wq3g8p8KFL4ZL59K5/9m5QLiES7hAuIRLuEC4P8pc3OQP+fA8oaUUm9ufd8a9GKOklI7tUa1V8HiPttZsZZYeHx/PZFwAj/fsAgwAfkvFQCPNw8gAAAAASUVORK5CYII=');
		background-position:0 0;
		background-repeat:no-repeat;
		z-index:3;
	}
	</style>

	
<?php if ($act == 'all'):?>
	<h1 class="title"><?=$h1?></h1>
	
	<div class="nav">
		<div class="fleft"></div>
		<div class="fright">
			<a class="button green" title="добавить" href="<?=$path?>?add&parent=<?=$parent?>">добавить</a>
		</div>
	</div>

	<div class="toggle-box">
		<a class="bookmark-toggle activ" data-name="new" href="#new">Новые отзывы (<?=count($reviews->reviews_new) ? '+'.count($reviews->reviews_new) : 0?>)</a>
		<a class="bookmark-toggle" data-name="approved" href="#approved">Утвержденные отзывы (<?=count($reviews->reviews_approved) ? '+'.count($reviews->reviews_approved) : 0?>)</a>
	</div>

	<div class="bookmark activ" data-id="new">
		<table class="table-1">
			<thead>
				<tr>
					<td class="small">№</td>
					<td class="small">Имя</td>
					<td>Коментарий</td>
					<td class="small">Дата</td>
					<td class="small nowrap">Оценка компании</td>
					<td class="small nowrap">Оценка описания товара</td>
					<td class="small nowrap">Оценка выполнения заказов</td>
					<td class="small">visible</td>
					<td class="small"></td>
					<td class="small"></td>
				</tr>
			</thead>
			<tbody>
			<?php $i = 1; foreach ($reviews->reviews_new as $k):?>
				<tr>
					<td><?=$i++?></td>
					<td class="left nowrap"><?=$k->name?></td>
					<td class="left"><?=$k->comment?></td>
					<td><?=date('Y.m.d',  $k->date)?></td>
					<td>
						<?php $is = isset($is_rating[$k->rating]) ? $is_rating[$k->rating] : $is_rating[0];?>
						<div class="rating" title="<?=$is[1]?>">
							<div class="rating-indicator <?=$is[0]?>"></div>
							<div class="rating-substrate"></div>
						</div>
					</td>
					<td><?=isset($is_price_correct[$k->is_price_correct]) ? $is_price_correct[$k->is_price_correct] : $is_price_correct[0];?></td>
					<td><?=isset($is_delivery_in_time[$k->is_delivery_in_time]) ? $is_delivery_in_time[$k->is_delivery_in_time] : $is_delivery_in_time[0];?></td>
					<td>
						<a class="toggle icon-eye <?=$k->visibility == 1 ? ' activ' : '' ?>" data-id="<?=$k->id?>" data-column="visibility" data-bind="toggle" title="скрыть на сайте"></a>
					</td>
					<td>
						<a class="link_edit" href="<?=$path?>?parent=<?=$parent?>&update=<?=$k->id?>" title="редактировать"></a>
					</td>
					<td>
						<a class="link_del" data-delete="<?=htmlspecialchars($k->name)?>" href="<?=$path?>?parent=<?=$parent?>&delete=<?=$k->id?>" title="удалить"></a>
					</td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	</div>
	
	<div class="bookmark" data-id="approved">
		<table class="table-1">
			<thead>
				<tr>
					<td class="small">№</td>
					<td class="small">Имя</td>
					<td>Коментарий</td>
					<td class="small">Дата</td>
					<td class="small nowrap">Оценка компании</td>
					<td class="small nowrap">Оценка описания товара</td>
					<td class="small nowrap">Оценка выполнения заказов</td>
					<td class="small">visible</td>
					<td class="small"></td>
					<td class="small"></td>
				</tr>
			</thead>
			<tbody>
			<?php $i = 1; foreach ($reviews->reviews_approved as $k):?>
				<tr>
					<td><?=$i++?></td>
					<td class="left nowrap"><?=$k->name?></td>
					<td class="left"><?=$k->comment?></td>
					<td><?=date('Y.m.d',  $k->date)?></td>
					<td>
						<?php $is = isset($is_rating[$k->rating]) ? $is_rating[$k->rating] : $is_rating[0];?>
						<div class="rating" title="<?=$is[1]?>">
							<div class="rating-indicator <?=$is[0]?>"></div>
							<div class="rating-substrate"></div>
						</div>
					</td>
					<td><?=isset($is_price_correct[$k->is_price_correct]) ? $is_price_correct[$k->is_price_correct] : $is_price_correct[0];?></td>
					<td><?=isset($is_delivery_in_time[$k->is_delivery_in_time]) ? $is_delivery_in_time[$k->is_delivery_in_time] : $is_delivery_in_time[0];?></td>
					<td>
						<a class="toggle icon-eye <?=$k->visibility == 1 ? ' activ' : '' ?>" data-id="<?=$k->id?>" data-column="visibility" data-bind="toggle" title="скрыть на сайте"></a>
					</td>
					<td>
						<a class="link_edit" href="<?=$path?>?parent=<?=$parent?>&update=<?=$k->id?>" title="редактировать"></a>
					</td>
					<td>
						<a class="link_del" data-delete="<?=htmlspecialchars($k->name)?>" href="<?=$path?>?parent=<?=$parent?>&delete=<?=$k->id?>" title="удалить"></a>
					</td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	</div>
<?php endif;?>
	


<!--ADD-->		
<?php if ($act == 'add'):?>
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
					<td class="right top small nowrap"><b>Общая оценка компании</b></td>
					<td class="left">
						<label>
							<input type="radio" name="rating" value="1">	
							Очень плохо
						</label>
						<br>
						<label>
							<input type="radio" name="rating" value="2">	
							Плохо
						</label>
						<br>
						<label>
							<input type="radio" name="rating" value="3">	
							Нормально
						</label>
						<br>
						<label>
							<input type="radio" name="rating" value="4">	
							Хорошо
						</label>
						<br>
						<label>
							<input type="radio" name="rating" value="5">	
							Отлично 
						</label>
					</td>
				</tr>
				<tr>
					<td class="right top"><b>Цена, наличие и описание товара (услуги) были указаны правильно?</b></td>
					<td class="left">
						<label>
							<input type="radio" name="is_price_correct" value="0">	
							Не помню
						</label>
						<br>
						<label>
							<input type="radio" name="is_price_correct" value="1">	
							Да
						</label>
						<br>
						<label>
							<input type="radio" name="is_price_correct" value="2">	
							Нет
						</label>
					</td>
				</tr>
				<tr>
					<td class="right top"><b>Заказ был выполнен в оговоренные сроки?</b></td>
					<td class="left">
						<label>
							<input type="radio" name="is_delivery_in_time" value="0">	
							Не помню
						</label>
						<br>
						<label>
							<input type="radio" name="is_delivery_in_time" value="1">	
							Да
						</label>
						<br>
						<label>
							<input type="radio" name="is_delivery_in_time" value="2">	
							Нет
						</label>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="right nowrap"><b>Ваше имя: </b></td>
					<td class="left">
						<input  class="inf" type="text" name="name" value="">	
					</td>
				</tr>
				<tr>
					<td class="right nowrap"><b>Дата:</b></td>
					<td class="left">
						<input data-datepicker="" class="inf min" type="text" name="date" value="<?=date('Y-m-d', time())?>">	
					</td>
				</tr>
				<tr>
					<td class="right nowrap"><b>Отзыв:</b></td>
					<td class="left">
						<textarea style="height:70px;" class="inf" name="comment"></textarea>
					</td>
				</tr>
				<tr>
					<td class="right nowrap"><b>Дата публикации ответа:</b></td>
					<td class="left">
						<input data-datepicker="<?=time()?>" class="inf min" type="text" name="answer_date" value="<?=date('Y-m-d', time())?>">	
					</td>
				</tr>
				<tr>
					<td class="right nowrap"><b>Ответ:</b></td>
					<td class="left">
						<textarea style="height:100px;" class="inf" name="answer_text"></textarea>
					</td>
				</tr>
			</table>
		</div>
		
		<input type="hidden" name="add" value="">
	</form>
<?php endif;?>



<!--UPDATE-->		
<?php if ($act == 'update'):?>
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
					<td class="right top small nowrap"><b>Общая оценка компании</b></td>
					<td class="left">
						<label>
							<input type="radio" name="rating" value="1" <?=$review->rating == 1 ? 'checked': '';?>>	
							Очень плохо
						</label>
						<br>
						<label>
							<input type="radio" name="rating" value="2" <?=$review->rating == 2 ? 'checked': '';?>>	
							Плохо
						</label>
						<br>
						<label>
							<input type="radio" name="rating" value="3" <?=$review->rating == 3 ? 'checked': '';?>>	
							Нормально
						</label>
						<br>
						<label>
							<input type="radio" name="rating" value="4" <?=$review->rating == 4 ? 'checked': '';?>>	
							Хорошо
						</label>
						<br>
						<label>
							<input type="radio" name="rating" value="5" <?=$review->rating == 5 ? 'checked': '';?>>	
							Отлично 
						</label>
					</td>
				</tr>
				<tr>
					<td class="right top"><b>Цена, наличие и описание товара (услуги) были указаны правильно?</b></td>
					<td class="left">
						<label>
							<input type="radio" name="is_price_correct" value="0" <?=$review->is_price_correct == 0 ? 'checked': '';?>>	
							Не помню
						</label>
						<br>
						<label>
							<input type="radio" name="is_price_correct" value="1" <?=$review->is_price_correct == 1 ? 'checked': '';?>>	
							Да
						</label>
						<br>
						<label>
							<input type="radio" name="is_price_correct" value="2" <?=$review->is_price_correct == 2 ? 'checked': '';?>>	
							Нет
						</label>
					</td>
				</tr>
				<tr>
					<td class="right top"><b>Заказ был выполнен в оговоренные сроки?</b></td>
					<td class="left">
						<label>
							<input type="radio" name="is_delivery_in_time" value="0" <?=$review->is_delivery_in_time == 0 ? 'checked': '';?>>	
							Не помню
						</label>
						<br>
						<label>
							<input type="radio" name="is_delivery_in_time" value="1" <?=$review->is_delivery_in_time == 1 ? 'checked': '';?>>	
							Да
						</label>
						<br>
						<label>
							<input type="radio" name="is_delivery_in_time" value="2" <?=$review->is_delivery_in_time == 2 ? 'checked': '';?>>	
							Нет
						</label>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="right"><b>Ваше имя:</b></td>
					<td class="left">
						<input  class="inf" type="text" name="name" value="<?=$review->name?>">
					</td>
				</tr>
				<tr>
					<td class="right"><b>Дата:</b></td>
					<td class="left">
						<input data-datepicker="<?=$review->date?>" class="inf min" type="text" name="date" value="<?=date('Y-m-d', $review->date)?>">
					</td>
				</tr>
				<tr>
					<td class="right"><b>Отзыв:</b></td>
					<td class="left">
						<textarea style="height:70px;" class="inf" name="comment"><?=$review->comment?></textarea>
					</td>
				</tr>
				<tr>
					<td class="right"><b>Дата публикации ответа:</b></td>
					<td class="left">
						<input data-datepicker="<?=$review->answer_date?>" class="inf min" type="text" name="answer_date" value="<?=$review->answer_date ? date('Y-m-d', $review->answer_date) : date('Y-m-d', time())?>">	
					</td>
				</tr>
				<tr>
					<td class="right"><b>Ответ:</b></td>
					<td class="left">
						<textarea style="height:100px;" class="inf" name="answer_text"><?=$review->answer_text?></textarea>
					</td>
				</tr>
			</table>
		</div>
			
		<input type="hidden" name="id" value="<?=$review->id?>">
		<input type="hidden" name="edit" value="">
	</form>
<?php endif;?>
</div>

<script> // APPLY
$(document).on('click', '[data-form-apply]', function(){
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