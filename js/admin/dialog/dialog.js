/* DIALOG
-------------------------------------------*/
;(function($){
	var w = $(window),
		d = $(document),
		index = 1,
		defaults = {
			type:'',				// (string) video, map
			content:'',
			title:'',
			width:'auto',
			height:'auto',
			maxWidth:'auto',
			maxHeight:'auto',
			minHeight:'none',
			minWidth:'none',
			position:'',			// (string) top, center 
			drag:false,				// (boolean)
			
			resizable:false,		// (boolean)
			create:function(){},	// callback
			
			cancel:function(){},	// callback confirm
			agree:function(){}		// callback confirm
		};
	
	var methods = {
		init:function(options){

			var settings;
			
			// если пустой объект
			return this.length ? this.each(_create) : _create.apply($('<div>'));

			function _create(){
				settings = $.extend({}, defaults, options);

				var _this = $(this),
					data = _this.data('dialog'),
					html = this.outerHTML;

				// INIT есле уже есть
				if (data) return this;

				var dialogBox = $(
				'<div data-dialog-box="'+index+'">'+
					'<div class="dialog-front-layer" style="z-index:'+index+'"></div>'+
					'<div class="dialog  '+(settings.type ? ' dialog-'+settings.type : '')+' '+ (settings.resizable ? ' resizable' : '') +'" style="z-index:'+index+'">'+
						'<div class="dialog-title-box">'+
							'<div class="dialog-title">Test</div>'+
						'</div>'+
						'<div class="dialog-content-box">'+
							'<div class="dialog-content"></div>'+
						'</div>'+
						'<div class="dialog-buttons-box"></div>'+
						'<a class="dialog-close"></a>'+
					'</div>'+
				'</div>');
				
				
							
				_this.data('dialog',{
					dialog:dialogBox,
					settings:settings,
					index:index++
				});
				
				// SIZE (параметры размера)
				dialogBox.find('.dialog').css({
					width:settings.width,
					height:settings.height,
					maxWidth:settings.maxWidth,
					maxHeight:settings.maxHeight
				});
				
				// TITLE
				methods.title.call(_this, settings.title);
				
				// CONTENT
				methods.content.call(_this, html);
				
				// CONFIRM
				if (settings.type == 'confirm'){
					dialogBox.find('.dialog-buttons-box').append(
					'<div class="dialog-buttons">'+
						'<button class="dialog-button white" data-confirm="cancel">Отмена</button>'+
						'<button class="dialog-button white" data-confirm="agree">OK</button>'+
					'</div>');
					
					dialogBox.find('[data-confirm="cancel"]').on('click', function(e){
						e.preventDefault();
						settings.cancel.call();
						_this.trigger('dialogConfirmCancel');
						methods.close.apply(_this);
					});
					dialogBox.find('[data-confirm="agree"]').on('click', function(e){
						e.preventDefault();
						settings.agree.call();
						_this.trigger('dialogConfirmAgree');
						methods.close.apply(_this);
					});
				}
				
				
				// APPEND to document
				d.find('html').css({overflow:'hidden'});
				$(document.body).append(dialogBox);
				methods.position.apply(_this);

				// CLOSE
				dialogBox.find('.dialog-close, .dialog-front-layer').click(function(){
					methods.close.apply(_this);
				});
				
				// DRAG
				methods.drag.call(_this, settings.drag);
				
				// CALLBACKS
				settings.create.call(_this);
				
				return this;
			};
		},
		
		title:function(html){
			this.data('dialog').dialog.find('.dialog-title').html(html);
			return this;
		},
		
		content:function(html){
			this.data('dialog').dialog.find('.dialog-content').html(html);
			return this;
		},
		
		position:function(){
			var dialogWindow = this.data('dialog').dialog.find('.dialog'),
				settings = this.data('dialog').settings;
			
			var	bh  = dialogWindow.height(),
				bw  = dialogWindow.width(),
				wh  = w.height(),
				ww  = w.width(),
				top = (wh-bh)/2+'px',
				left = (settings.width == '100%') ? 0 : (ww-bw)/2+'px';
			
			// ???
			if (settings.position == 'center'){
				
			}else if (settings.position == 'top'){
				
			}

			dialogWindow.css({
				top:top,
				left:left
			}).animate({opacity:1}, 200);
				
			return this;
		},
		
		drag:function(){
			var startX,
				startY,
				box = this.data('dialog').dialog.find('.dialog'),
				t = box.find('.dialog-title-box');
			
			if (arguments[0] != true){
				w.unbind('.dialog.drag');
				t.unbind('.dialog.drag');
				return this;
			}
			
			// если ставить на document - все окна двигаются одновремен.
			t.on('mousedown.dialog.drag touchstart.dialog.drag', function(e){
				startX = e.originalEvent.pageX - box.offset().left;
				startY = e.originalEvent.pageY - box.offset().top;
						
				w.on('mousemove.dialog.drag touchmove.dialog.drag', function(e){	
					box.offset({
						left: e.originalEvent.pageX - startX,
						top: e.originalEvent.pageY - startY
					});
				});
			}).on('mouseup.dialog.drag touchend.dialog.drag',function(){
				w.unbind('mousemove.dialog.drag touchmove.dialog.drag');
			});

			return this;
		},

		close:function(){
			return this.each(function(){
				if ($('.dialog').length < 2){
					d.find('html').css({overflow:''});
				}
				
				var _this = $(this),
					data = _this.data('dialog');
					
				data.dialog.remove();
				_this.removeData('dialog');
				// callback
				$(this).trigger('dialogClose');
			});
		},
		
		load:function(){
			this.data('dialog').dialog.find('.dialog').addClass('dialog-load');
			return this;
		},
		
		endLoad:function(){
			this.data('dialog').dialog.find('.dialog').removeClass('dialog-load');
			return this;
		}
	};
	
	$.fn.dialog = function(method){
		if (methods[method]){
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method){
			return methods.init.apply(this, arguments);
		}else{
			$.error('Метод ' +  method + ' не существует в jQuery.dialog');
		}
	}

})(jQuery);