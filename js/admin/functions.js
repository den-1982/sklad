/* LOG
---------------------------------------------------------------------------*/
function log(){
	if (window.console && window.console.log) for (var i in arguments) console.log(arguments[i]);
}

/* SHOW_ERROR
---------------------------------------------------------------------------*/
function show_error(info, data){
	
	var html = '';
	
	if ($.isArray(data)){
		html += '<ol>';
		for (var i in data) html += '<li>'+data[i]+'</li>';
		html += '<ol>';
	}else{
		html = data;
	}
	
	$('<div><p>'+ (info || '') +'</p>'+ (html || '') +'</div>').dialog({
		title:'<i class="icon-warning-sign"></i> Внимание!',
		width:'300px',
		drag:true
	});
}

/* AJAX loading (gif)
---------------------------------------------------------------------------*/
;(function($){
	$.each(["post"], function(i, method){		// ["post", "get"]
		var old = $[method];
		
		$[method] = function(){
			if (arguments[arguments.length-1] == "loading"){
				var l = $('<div class="load"></div>').appendTo(document.body);
			}
			
			return old.apply(this, arguments).always(function(){
				l && l.remove();
			})
		}
		
	});
})(jQuery);


/* MASK
---------------------------------------------------------------------------*/
;$(function(){
	$.mask.definitions['$']='[0-9,.]';
	$.mask.definitions['~']='[1-9]';
	$.mask.definitions['@']='[0-9,a-z,_,-]';
	$('[data-mask="price"]').mask("$?$$$$$$$$$$$",{ placeholder:"" });
	$('[data-mask="code"]').mask("99999",{ placeholder:"_" });
	$('[data-mask="phone"]').mask("+3 8(0~9) 999-99-99",{ placeholder:"_" });
	$('[data-mask="_phone"]').mask("(999) 999-99-99",{ placeholder:"_" });
	$('[data-mask="not-space"]').mask("@?@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@",{ placeholder:" " });
	 
});

/* DATEPICKER
---------------------------------------------------------------------------*/
;$(function(){
	$('[data-datepicker]').datepicker({
		dateFormat: 'yy-mm-dd',
		showOn:"both"
	});
});

/* SELECT-UI
---------------------------------------------------------------------------*/
;$(function(){
	$('[data-select]').each(function(){
		$(this).prop('value', $(this).find('option[selected]:last').val());
	}).selectmenu({
		width:'auto',
		change:function(){
			if ($(this).attr('data-select') == 'auto-submit'){
				this.form.submit();
			}
		}
	});
});
/* BUTTON-UI
---------------------------------------------------------------------------*/
$(function(){
	$('[data-button]').each(function(){
		$(this).button();
	});
});

/* SORTABLE-UI
---------------------------------------------------------------------------*/
;$(function(){
	$('[data-sortable="body"]').sortable({
		items:'> *:not(.not-sortable)',
		sort:function(e, ui) {
			ui.helper.find('td').addClass('pale-grey');
		},
		handle:'[data-sortable="handler"]',
		activate:function(e, ui){
			ui.placeholder.eq(0).css({height:ui.helper.eq(0).height() + 'px'});
			ui.placeholder.eq(0).find('td').each(function(i){
				ui.helper.eq(0).find('td').eq(i).css({width: $(this).outerWidth() + 'px'})
			});
		}
	}).on('sortupdate', function(e, ui){
		var _this = $(this);
		_this.find('[data-sortable="order"]').each(function(i){
			this.value = i;
		});
		_this.parents('table').find('[data-sortable="send-order"]').addClass('activ');
	});
	
	// BUTTON ORDER
	$('[data-sortable="send-order"]').on('click', function(e){
		e.preventDefault();
		var _this = $(this);
		
		if ( ! _this.hasClass('activ')) return;
		
		var order = _this.parents('table').find('[data-sortable="order"], [data-sortable="id"]');
		$(document.body).append($('<div class="load"></div>'));
		
		$.post('', order.serialize(), function(data){
			location.reload(false);
		});
	});
});

/* DELETE
---------------------------------------------------------------------------*/
$(document).on('click', '[data-delete]', function(e){
	e.preventDefault();
	
	var _this = $(this);
	
	var confirm = $('<p>Удалить <b>'+ _this.attr('data-delete') +'</b>?</p>').dialog({
		type:'confirm',
		width:'300px',
		title:'Удаление',
		drag:true
	}).on('dialogConfirmAgree', function(){
		location.href = _this.attr('href');
	});
});


/* TOGGLE BOOKMARK
---------------------------------------------------------------------------*/
;$(function(){
	$(document).on('click.bookmark', '.bookmark-toggle', function(e){
		var _this = $(this);
		
		_this.siblings('.bookmark-toggle').removeClass('activ');
		_this.addClass('activ');
		
		var bookmark = $('[data-id="'+_this.attr('data-name')+'"]');
		bookmark.siblings('.activ').removeClass('activ');
		bookmark.addClass('activ');
	});
	
	if (location.hash){
		var hash = location.hash.replace('#', '');
		$('[data-name="'+hash+'"]').trigger('click.bookmark');
	}
});

/* TOGGLE VISIBILITY / HIT / NEW
---------------------------------------------------------------------------*/
$(document).on('click', '[data-bind="toggle"]', function(e){
	var _this = $(this),
		data = _this.data(),
		l = $('<div class="load"></div>').appendTo(document.body);
	
	$.post('', {
		toggle: data.column, 
		id: data.id, 
		activ: (+_this.hasClass('activ'))
	}, function(){
		l.remove();
		
		_this.toggleClass('activ');
		
		// меняем значение (дублирование) INPUT ???
		_this.siblings('.visibility').val(((+_this.hasClass('activ')) == 1 ? 0 : 1));
	}, 'json');
});

/* SCROLL THEAD
---------------------------------------------------------------------------*/
$(function(){
	$('[data-scroll="head"]').each(function(){
		$(this).find('thead:first').scrollHead();
	});
});

/* NUMBER_FORMAT (help)
---------------------------------------------------------------------------*/
;(function($){
	$.numberFormat = function(number, decimals, dec_point, thousands_sep){
		var i, j, kw, kd, km;

		// input sanitation & defaults
		if ( isNaN(decimals = Math.abs(decimals)) ) decimals = 2;
		if ( dec_point == undefined ) dec_point = ",";
		if ( thousands_sep == undefined ) thousands_sep = ".";
		
		i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

		if ( (j = i.length) > 3 ){
			j = j % 3;
		}else{
			j = 0;
		}

		km = (j ? i.substr(0, j) + thousands_sep : "");
		kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
		//kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
		kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


		return km + kw + kd;
	}
})(jQuery);


/* UPLOAD
--------------------------------------------------------------------------- */
$(document).on('change', '[data-button-file="image"]', function(e){
	var _this = $(this).parents('[data-preload="box"]').find('[data-preload="image"]'); 
	
	Read.init(e.target.files[0], function(data){
		if ( ! data) return;
		_this.attr('src', data.src);
	});
	return;
});

/* AP
---------------------------------------------------------------------------*/
var AP = {
	init:function(path, data, callback, dataType){
		if ( ! window.FormData) return;
		AP.form = new FormData();
		
		if (data.nodeName == "FORM"){
			data = data.elements;
			for (var i = 0; i < data.length; i++){
				// проверка checkbox
				if (data[i].type == "checkbox" && !data[i].checked) continue;
				
				// проверка radio
				if (data[i].type == "radio" && !data[i].checked) continue;
				
				// проверка на disabled
				if (data[i].disabled) continue;
				
				AP.form.append(data[i].name, (data[i].type == 'file' ? data[i].files[0] : data[i].value));
			}
		}else{
			for (var i in data)	AP.form.append(i, data[i]);
		}
		
		$.ajax({
			type: "POST",
			url: path,
			data: AP.form,
			dataType:dataType||'html',
			processData: false,
			contentType: false,
			success: callback,
			error:callback
		});
	}
}

/* FILE READER
---------------------------------------------------------------------------*/
;(function($){
	$.fileReader = function(file, callback){
		callback = $.isFunction(callback) ? callback : new Function;

		if ( !file || !window.FileReader ) return callback(0);
		
		var r = new FileReader();
		r.onload = function(e){
			var img = new Image();
			img.onload = function(){
				callback({src:e.target.result,width:img.width,height:img.height});
			}
			img.src = e.target.result;
		}
		r.onerror = callback;
		
		r.readAsDataURL(file)
	}
})(jQuery);


/* FM
---------------------------------------------------------------------------*/
$(document)
.on('click.filemanager', '.FM-overview', function(e){
	e.preventDefault();
	$(this).FM();
})
.on('click.filemanager', '.FM-clear', function(e){
	e.preventDefault();
	var _this = $(this);
	_this.parent().find('img').attr('src','/img/i_admin/loading_mini.gif');
	_this.parent().find('input').val('');
})
.on('dblclick.filemanager', '.mce-combobox input.mce-textbox', function(e){
	$(this).FM();
});