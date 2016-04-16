;(function($){
	var w = $(window),
		d = $(document),
		isFirefox = (navigator.userAgent.search(/firefox/i) != -1),		// true (margin:-1px)
		collection = [],
		index = 1,			// для позиционировании при нескольких thead
		defaults = {
			onCreate:function(){}
		};
	
	var methods = {
		init:function(options){
			
			return this.each(function(){
				var settings = $.extend({}, defaults, options);
				
				var _this = $(this);
				
				// If inited
				if (_this.data('scrollHead')) return;
				
				
				// clone
				var clone = _this.clone(true).css({
					position: 'fixed',
					top: 0,
					marginLeft: (isFirefox ? '-1px' : 0),
					opacity: 0.9,
					visibility: 'hidden',
					zIndex: 2
				});
				
				// data
				_this.data('scrollHead',{
					settings: settings,
					clone: clone,
					index: index++
				});
				
				// scroll
				_this.data('scrollHead').scroll = function(e){
					clone.css({
						visibility: (_this.offset().top < d.scrollTop() ? 'visible' : 'hidden')
					});
				}
				w.on('scroll', _this.data('scrollHead').scroll);
				
				// resize
				_this.data('scrollHead').resize = function(){
					_this.find('td').each(function(i){
						clone.find('td').eq(i).css({
							width: this.offsetWidth + 'px'
						});
					});
				}
				
				// insert
				w.load(function(){
					w.on('resize', _this.data('scrollHead').resize).trigger('resize');
					clone.insertBefore(_this);
					
					// callback
					settings.onCreate.call(_this);
				});

				return _this;
			});
		},
		
		refresh:function(){
			w.trigger('resize');
			
			return this;
		},
		
		destroy:function(){
			return this.each(function(){
				var _this = $(this);
				
				if ( ! _this.data('scrollHead')) return this;
				
				w.unbind('scroll', _this.data('scrollHead').scroll);
				w.unbind('resize', _this.data('scrollHead').resize);
				_this.data('scrollHead').clone.remove();
				_this.removeData('scrollHead');
				
				return _this;
			});
		}
	}
	
	$.fn.scrollHead = function(method){
		if (methods[method]){
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}else if (typeof method === 'object' || !method){
			return methods['init'].apply(this, arguments);
		}else{
			$.error('Метод ' +  method + ' не существует в jQuery.scrollHead');
		}
	}
	
})(jQuery);