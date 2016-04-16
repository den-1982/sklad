<div class="body">

	<h1 class="title"><?=$h1?></h1>

	<div class="bookmark activ" data-id="data">
		<style>
		#container { min-width:320px; margin:0px auto 0 auto; background:white; border-radius:0px; padding:0px; overflow:hidden; }
		#tree { float:left; min-width:319px; border-right:1px solid silver; overflow:auto; padding:0px 0; }
		#data { margin-left:320px; }
		#data textarea { margin:0; padding:0; height:100%; width:100%; border:0; background:white; display:block; line-height:18px; }
		#data, #code { font: normal normal normal 12px/18px 'Consolas', monospace !important; }
		</style>
		
		<div id="container" role="main">
			<div id="tree"></div>
			<div id="data">
				<div class="content code" style="display:none;">
					<textarea id="code" readonly="readonly"></textarea>
				</div>
				<div class="content folder" style="display:none;"></div>
				<div class="content image" style="display:none; position:relative;">
					<img src="" alt="" style="display:block; position:absolute; left:50%; top:50%; padding:0; max-height:90%; max-width:90%;" />
				</div>
				<div class="content default" style="text-align:center;">Select a node from the tree.</div>
			</div>
		</div>
		
	</div>
	
</div>

<script> // JSTREE
$(function () {
	$('#tree').jstree({
		'core' : {
			'data' : {
				'url' : '/admin/jstree/?operation=get_node',
				'data' : function (node) {
					return { 'id' : node.id };
				}
			},
			'check_callback' : true,
			'themes' : {
				'responsive' : false
			}
		},
		'force_text' : true,
		'plugins' : [
			'state',		// запоминает позицию выбранных папок
			'unique',		// уникальные названия папок
			'dnd',			// drag & drop	
			'contextmenu',	// create/rename/del/cut/paste
			// 'wholerow'		// ???
		]
	})
	.on('delete_node.jstree', function (e, data) {
		var confirm = $('<p>Удалить</p>').dialog({
			type:'confirm',
			width:'300px',
			height:'auto',
			title:'Удаление',
			drag:true
		}).on('dialogConfirmAgree', function(){
			$.get('?operation=delete_node', { 'id' : data.node.id }).fail(function(){
				data.instance.refresh();
			});
		});
	})
	.on('create_node.jstree', function (e, data) {
		$.get('?operation=create_node', { 'id' : data.node.parent, 'position' : data.position, 'text' : data.node.text })
			.done(function (d) {
				data.instance.set_id(data.node, d.id);
			})
			.fail(function () {
				data.instance.refresh();
			});
	})
	.on('rename_node.jstree', function (e, data) {
		$.get('?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
			.fail(function () {
				data.instance.refresh();
			});
	})
	.on('move_node.jstree', function (e, data) {
		$.get('?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent, 'position' : data.position })
			.fail(function () {
				data.instance.refresh();
			});
	})
	.on('changed.jstree', function (e, data) {
		if(data && data.selected && data.selected.length) {
			$.get('?operation=get_content&id=' + data.selected.join(':'), function (d) {
				$('#data .default').text(d.content).show();
			});
		}
		else {
			$('#data .content').hide();
			$('#data .default').text('Select a file from the tree.').show();
		}
	});
	
	// function delete_node(){
		// var confirm = $('<p>Удалить</p>').dialog({
			// type:'confirm',
			// width:'300px',
			// height:'auto',
			// title:'Удаление',
			// drag:true
		// }).on('dialogConfirmAgree', function(){
			// $.get('?operation=delete_node', { 'id' : data.node.id }).fail(function(){
				// data.instance.refresh();
			// });
		// });
	// }
});
</script>