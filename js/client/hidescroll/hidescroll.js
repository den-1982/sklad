;(function($){
	
	var defaults = {
		height:'auto',	// auto/100px/ определить размер контейнера
		step:10			// шаг прокрутки (px)
	}
	
	var methods = {
		
		init:function(options){
			
			options = $.extend({}, defaults, options);

			return this.each(function(){
				var _this = $(this);
				
				// is init
				if (_this.data('hideScroll')) return;
				
				// init (wrap)
				_this.data('hideScroll', {
					'this':_this,
					'options':options
				});
				_this.css({position:'relative'});
				_this.wrap('<div class="hideScroll" style="overflow:hidden;position:relative;"></div>').parent('.hideScroll');

				// resize
				$(window).on('resize.hideScroll', function(){
					if (_this.data('hideScroll')) 
						methods.resize.apply(_this);
				}).trigger('resize.hideScroll');
				
				// start wheel (scroll)
				_this.on('mousewheel.hideScroll', methods.startWheel);
			});
		},
		
		resize:function(){
			var _this = $(this),
				wrap = _this.parent(),
				parent = wrap.parent(),
				h = _this.data('hideScroll').options.height;

			// если фиксированая высота
			if (h == 'auto'){
				var pp = parent.css('position'),
					ph = parent.css('height');
				
				// parent - должен быть {height:100%; position:relative;}
				if (pp == 'static') parent.css({position:'relative'});
				parent.css({height:'100%'});
				
				// console.log(pp);
				// console.log(ph);
				// console.log(getComputedStyle(parent[0]).height);
			
				var parentHeight = parent[0].clientHeight;
				var wrapOffset = wrap[0].offsetTop;
				
				wrap.css({height: (parentHeight - wrapOffset) + 'px'});
			}else{
				wrap.css({height: h});
			}

			// сброс. если wrap >= this
			if (_this[0].scrollHeight <= wrap[0].clientHeight)
				_this.css({top: 0});
		},
		
		startWheel:function(e){
			var _this = $(this),
				wrap = _this[0].offsetParent,
				top = _this[0].offsetTop,
				step = _this.data('hideScroll').options.step;
			
			if (e.deltaY > 0){
				top += step;
				if (top > 0) return;
				
				_this.css({top: top +'px'});
			}
			if (e.deltaY < 0 && 1==1){
				top -= step;
				if (_this[0].scrollHeight - Math.abs(top) <= wrap.clientHeight) return;
				
				_this.css({top: top +'px'});
			}
		},
		
		destroy:function(){
			var _this = $(this);
			_this.removeData('hideScroll');
			_this.off('.hideScroll');
			_this.css({top: 0});
			_this.unwrap();
		}
	}
	
	$.fn.hideScroll = function(method){
		if (methods[method]){
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		}else{
			$.error('Метод ' +  method + ' не существует в jQuery.hideScroll');
		}
	}
	
})(jQuery);