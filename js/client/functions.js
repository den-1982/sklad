// запретить enter для inputs
$(document).on('keypress', 'input[type="text"], input[type="password"]', function(e){
	if (e.keyCode == 13) return false;
});

/* MASK
-------------------------------------------*/
;$(function(){
	$.mask.definitions['$']='[0-9,.]';
	$.mask.definitions['~']='[1-9]';
	$('[data-mask="price"]').mask("$?$$$$$$$$$$$",{ placeholder:"" });
	$('[data-mask="phone"]').mask("(999) 999-99-99",{ placeholder:"_" });
});


/* SELECT (styler)
-------------------------------------------*/
$(function(){
	setTimeout(function(){
		$('[data-styler]').styler({
			onFormStyled:function(){}
		});
	}, 100);
});

/* CAMERA
-------------------------------------------*/
$(function(){	
	$('[data-camera-slider]').each(function(){
		var _this = $(this);
		
		_this.camera(_this.data());
	});
});

/* OWL CARUSEL
-------------------------------------------*/
$(function(){
	$('[data-owl-carousel]').each(function(){
		var _this = $(this);
		
		_this.owlCarousel(_this.data());
	});
});


/* TOP MENU (SCROLL)
-------------------------------------------*/
$(function(){
	var box = $('[data-menu="fix"]'),
		size = box.offset().top;

	$(document).scroll(function(){
		size < $(this).scrollTop() ? box.addClass('fix') : box.removeClass('fix')
	}).trigger('scroll');
});


/* PHONE BOX
-------------------------------------------*/
$(function(){
	$('.phones-box').on('mouseleave', function(){
		var _this = $(this);
			
		if (_this.hasClass('open'))
			_this.removeClass('open');

	}).on('click', '.toggle', function(e){
		$(this).parent().toggleClass('open');
	});
});

/* CART TOP
-------------------------------------------*/
$(function(){
	$('[data-cart-top]').on('mouseenter', function(){
		var _this = $(this);
		
		_this.data('tm_enter', setTimeout(function(){
			var a = _this.find('a'),
				w = a.width(),
				left = a[0].offsetLeft;
			
			_this.find('.nib').css({left: left + (w/2) + 'px'});
			_this.addClass('activ');
			
			// get cart
			_this.find('.child').html(html);
			var html = 
			'<div class="center">'+
				'<img src="/img/i/preload.png" alt="loading">'+
			'</div>';
			
			$.post('/cart',{getCart:''}, function(data){
				c = data['html_top'];
				_this.find('.child').html(data['html_top']);
			}, 'json');
			
		}, 5000));	
		
	}).on('mouseleave', function(){						
		var _this = $(this);
		
		clearTimeout(_this.data('tm_enter'));
		
		if (_this.hasClass('activ'))
			_this.removeClass('activ');
	});
});

/* DROP-DOWN (MENU)
-------------------------------------------*/
$(function(){
	$('[data-dropdown] > li').on('mouseleave', function(){
		var _this = $(this);
		
		if (_this.hasClass('activ'))
			_this.removeClass('activ');
		
	}).on('click', function(e){
		var _this = $(this),
			w = _this.width(),
			parentLeft = e.currentTarget.offsetLeft;

		_this.find('.nib').css({left:parentLeft+(w/2)+'px'});
		_this.addClass('activ');
	});
});


/* CART
-------------------------------------------*/
$(function(){
	var w = $(window),
		d = $(document),
		b = $(document.body),
		minWidth = 630,
		CART_BODY = $('[data-cart-body="middle"]'),
		CART_BOTTOM = $('[data-cart-body="bottom"]'),
		COUNT_ITEMS = $('[data-cart="count-items"]'),
		CART_TOTAL = $('[data-cart="cart-total"]'),
		box = $('[data-cart="bottom"]'),
		toggle = $('[data-cart="toggle"]'),
		helper = $('<div>').height(box.height()).insertBefore(box);
	
	// CART-TOGGLE(bottom) & SCROLL FIX 
	d.scroll(function(){	
		if (box.next().offset().top < (d.scrollTop() + $(window).height())){
			box.removeClass('fix');
			helper.css({display:'none'});
		}else{
			box.addClass('fix');
			helper.css({display:'block'});
		}
	}).trigger('scroll');
	
	// TOGGLE (show cart-bottom)
	d.on('click.cart-bottom', '[data-cart="toggle"]', _toggle);
	function _toggle(e){
		if ( $(window).width() < minWidth) return;
		e.preventDefault();
		box.toggleClass('activ');
		helper.height(box.height());
		
		d.trigger('scroll');
	}
	
	// RESIZE 
	w.resize(function(){
		d.unbind('.cart-bottom');
		
		if ($(this).width() > minWidth){ 
			d.on('click.cart-bottom', '[data-cart="toggle"]', _toggle);
		}else{
			if (box.hasClass('activ')){
				box.removeClass('activ');
				helper.height(box.height());
			}
		}
	});
	
	// ADD CART
	d.on('submit','[data-bind="add-cart"]', function(e){
		e.preventDefault();
		
		var load = $('<div class="load"></div>').appendTo(b);
				
		$.ajax({
			url: "/cart/add",
			type: "POST",
			data: $(this).serialize(),
			dataType: "json",
			error:function(data){
				location.reload(false);
			},
			success:function(data){
				load.remove();
				
				/* Новай html корзины + делаем новую карусель */
				CART_BOTTOM.html(data.html_bottom).find('[data-owl-carousel]').owlCarousel({singleItem:true});
				
				/* подложка (чтоб не прыгала) */
				helper.height(box.height());
				
				COUNT_ITEMS.html(data.cnt_items);
				CART_TOTAL.html(data.cart_total);
				
				/* открывваем нижнюю корзину */
				if ( ! box.hasClass('activ')){
					if ( $(window).width() < minWidth) return;
					toggle.trigger('click');
				}
			}
		});
	});
	
	// UPDATE CART
	d.on('submit', '[data-bind="cart"]', function(e){
		e.preventDefault();
		
		var _this = $(this),
			load = $('<div class="load"></div>').appendTo(b);
		
		$.post('', $(this).serialize()+'&update_cart_ajax=', function(data){
			load.remove();
			CART_BOTTOM.html(data.html_bottom).find('[data-owl-carousel]').owlCarousel({singleItem:true});
			
			/* подложка (чтоб не прыгала) */
			helper.height(box.height());
			
			COUNT_ITEMS.html(data.cnt_items);
			CART_TOTAL.html(data.cart_total);
			
			/* если 0 шт. убрать форму Оформление заказа */		
			data.cnt_items ? _this.replaceWith(data.html) : CART_BODY.html(data.html);		
		}, 'json');
	});

	// DELETE CART
	d.on('click', '[data-cart-delete]', function(e){
		e.preventDefault();
		
		var _this = $(this),
			load = $('<div class="load"></div>').appendTo(b);
		
		$.post('/cart', {del:_this.attr('data-cart-delete')}, function(data){
			load.remove();
			/* Новай html корзины bottom + делаем новую карусель*/
			CART_BOTTOM.html(data.html_bottom).find('[data-owl-carousel]').owlCarousel({singleItem:true});
			
			/* подложка (чтоб не прыгала) */
			helper.height(box.height());

			COUNT_ITEMS.html(data.cnt_items);
			CART_TOTAL.html(data.cart_total);
			
			/* если 0 шт. убрать форму Оформление заказа */
			data.cnt_items ? _this.parents('form').replaceWith(data.html) : CART_BODY.html(data.html);
		}, 'json');
	});
	
	// BUTTON QUANT (количество)
	d.on('click','[data-quantity-button]', function(e){
		e.preventDefault();
		
		var _this = $(this),
			cnt, 
			q = _this.siblings('[data-quantity]');
		
		if ( _this.attr('data-quantity-button') == 'plus'){
			cnt = parseInt( q.val().replace(/[^0-9]/g, '') ) + 1;
		}else{
			cnt = parseInt( q.val().replace(/[^0-9]/g, '') ) - 1;
		}
		q.val(isNaN(cnt) ? 1 : (cnt <= 0 ? 1 : cnt));
		
		// btn update cart
		_this.parents('[data-cart="item"]').find('[data-btn="update"]').show(500);
		
	}).on('blur','[data-quantity]', function(e){
		var _this = $(this),
			cnt = parseInt(_this.val().replace(/[^0-9]/g, ''));
		
		_this.val(isNaN(cnt) ? 1 : (cnt <= 0 ? 1 : cnt));
		
		// btn update cart
		_this.parents('[data-cart="item"]').find('[data-btn="update"]').show(500);;
	});
});


/* DELETE HISTORY (view)
-------------------------------------------*/
$(document).on('click', '[data-history="clear"]', function(e){
	e.preventDefault();
	var _this = $(this),
		l = $('<div class="load"></div>').appendTo(document.body);

	$.post('',{'remove-viewed':''},function(data){
		l.remove();
		_this.parents('[data-history="box"]').hide(500,function(){
			$(this).remove();
		});
	});
});


/* USER
-------------------------------------------*/
$(function(){
	var D;
	
	if (_getHash() == 'auth') auth();
			
	$(document).on('click', '[data-user="auth"]', auth);
	function auth(e){
		e ? e.preventDefault() : false;
		
		D = $('<div class=""></div>').dialog({
			width:'450px',
			height:'auto',
			title:'Вход в личный кабинет'
		}).dialog('load');

		$.post('/user/login', {getFormLogin:''}, function(data){
			
			D.dialog('content', data)
				.dialog('position');
			
			var content = D.data('dialog').dialog;
			
			content.find('[data-mask="phone-1"]')
				.mask("(999) 999-99-99",{ placeholder:"_" })
				.trigger('focus');
			
			content.find('[data-bind="login"]').on('submit', function(e){
				e.preventDefault();
				
				D.dialog('load');
				$.post('/user/login', $(this).serialize(), function(data){
					if (data == 1) window.location = '/user';
					D.dialog('endLoad');
				});
			});
			
			content.find('[data-bind="get-recover"]').on('click', recover)
			
			D.dialog('endLoad');
		});
	}

	function recover(e){
		e ? e.preventDefault() : false;
		
		D.dialog('load');
		$.post('/user/recover', {getFormRecover:''}, function(data){
			D.dialog('content', data).dialog('title', 'Восстановление пароля');
			
			var content = D.data('dialog').dialog;
			
			content.find('[data-mask="phone-2"]')
				.mask("(999) 999-99-99",{ placeholder:"_" })
				.trigger('focus');
				
			content.find('[data-bind="recover"]').bind('submit', function(e){
				e.preventDefault();
				
				D.dialog('load');
				$.post('/user/recover', $(this).serialize(), function(data){
					if ( ! data.error){
						D.dialog('content', data.text).dialog('position');
					}
					
					D.dialog('endLoad');
				}, 'json');
			});
			
			D.dialog('endLoad');
		});
	}
	
	function _getHash(){
		return $.trim(window.location.hash.replace(/#/g,''));
	}
});


/* GET DISCOUNT
-------------------------------------------*/
;$(document).on('click', '[data-bind="get-discounts"]', function(e){
	e.preventDefault();
	
	var D = $('<div class=""></div>').dialog({
			title:'Условия программы лояльности',
			width:'600px',
			height:'500px'
		}).dialog('load');
	
	$.post('/biznes-predlojenie', {getdiscounts:''},function(data){
		data = $(data).length == 1 ? $(data) : $('<div>').html(data);
		D.dialog('content', data).dialog('endLoad');
	}, 'json');
});


/* YANDEX API MAP
-------------------------------------------*/
function YaApiMap(callback){
	if (window.ymaps){
		ymaps.ready(callback);
		return;
	}
	
	var a = document.createElement('script'); 
	a.type = 'text/javascript'; 
	a.async = true;     
	a.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'api-maps.yandex.ru/2.1/?lang=ru_RU';  
	var s = document.getElementsByTagName('script')[0]; 
	s.parentNode.insertBefore(a, s);
	
	a.onload = function(){
		ymaps.ready(callback);
	};
}


/* NOVA-POSHTA
-------------------------------------------*/
$(function(){
	var dialogMap,
		offices,
		map;
		
	// getOffices();
	
	var np = $('#city, #office, [data-bind="yamap"]');

	np.filter('#city').on('change', changeCity);
	np.filter('[data-bind="yamap"]').on('click', _map);
			
	function changeCity(e){
		var city_ref = this.value;
			
		getOffices(function(list){
			var select = np.filter('#office');
			
			select.html($('<option value="0" selected> - выбрать - </option>'));
			if (list[city_ref]) $.each(list[city_ref], function(){
				select.append(
					$('<option>', {value:this.ref})
						.data('coordinates', [this.y, this.x])
						.data('cityRu', this.cityRu)
						.html('Отд. № '+this.number+' - '+this.addressRu)
				);
			});
			select.trigger('refresh');
		});
	}
	
	// выбор отделения на карте
	$(document).on('click', '[data-bind="change-office"]', changeOfficeMap);
	function changeOfficeMap(){
		dialogMap.dialog('close');

		var data = $(this).data();
		
		getOffices(function(list){
			var city = np.filter('#city').find('option[value="'+data.city_ref+'"]');
			
			// добавить город если нет в списки
			if ( ! city.length){
				np.filter('#city').append('<option value="'+data.city_ref+'">'+data.city_ru+'</option>');
			}
			
			np.filter('#city')
				.find('option[value="'+data.city_ref+'"]')
				.prop('selected', true)
				.parent()
				.trigger('refresh');
			
			
			np.filter('#office')
				.html($('<option value="0" selected> - выбрать - </option>'));
			if (list[data.city_ref]) $.each(list[data.city_ref], function(){
				np.filter('#office').append(
					$('<option>', {value:this.ref})
						.data('coordinates', [this.y, this.x])
						.data('cityRu', this.cityRu)
						.html('Отд. № '+this.number+' - '+this.addressRu)
						.prop('selected', (this.ref == data.ref ? true : false))
				);
			});
			np.filter('#office').trigger('refresh');
		});
	}
	
	function _map(e){
		dialogMap = $('<div id="yamap" style="height:100%;"></div>').dialog({
			title:'Почтовые отделеня "НОВАЯ ПОЧТА"',
			type:'map',
			width:'95%',
			height:'95%'
		});
		
		// координаты украины (по умолчанию)
		var coord = [49.1782,31.5398],
			zoom = 6,
			ref = '';
		
		// если уже выбран office
		var refSelected = np.filter('#office').find(':selected');
		if (refSelected.length && refSelected.data().coordinates){
			coord = refSelected.data().coordinates;
			zoom = 16;
			ref = np.filter('#office').val();
		}
		
		getOffices(function(list){
			YaApiMap(function(){
				
				map = new ymaps.Map('yamap', {
					center: coord,
					zoom: zoom,
					controls: ['typeSelector', 'zoomControl']
				});
				// запрещаем ZOOM, copyrights
				map.behaviors.disable('scrollZoom');
				$('[class $= "copyrights-pane"]').remove();
				
				// кластер
				map.clusterer = new ymaps.Clusterer({clusterDisableClickZoom: false, preset:'twirl#blueClusterIcons'});

				$.each(list, function(){
					$.each(this, function(){
						var k = this;

						if (!k.y || !k.x) return;
						
						var str = 
						'<div class="yamaps-baloon">'+
							'<div class="city">'+k.cityRu+'</div>'+
							'<div class="number">№'+k.number+' Отделение</div>'+
							'<div class="adress">'+k.addressRu+'</span></div>'+
							'<div class="phone">Клиентская поддержка: +38 '+k.phone+'</div>'+
							'<div class="button-box">'+
								'<a class="btn btn-pink" '+
									'data-bind="change-office" '+
									'data-city_ru="'+k.cityRu+'" '+
									'data-city_ref="'+k.city_ref+'" '+
									'data-ref="'+k.ref+'" '+
									'href="javascript:void(0)">выбрать</a>'+
							'</div>'+
						'</div>';
						
						var Placemark = new ymaps.GeoObject({
							geometry: {
								type: "Point", 
								coordinates:[k.y, k.x]
							},
							properties: {
								balloonContentBody: str,
								iconContent: k.number,
								hintContent: "Отделение № " + k.number
							}
						},
						{
							preset: (ref == k.ref ? 'islands#redStretchyIcon' : 'islands#greenStretchyIcon')
						});

						Placemark.events.add('click', function (e) {
							map.setCenter(Placemark.geometry.getCoordinates(), 16);
						});
						map.clusterer.add(Placemark);
						
					});
				});
				
				map.geoObjects.add(map.clusterer);
			});
		});
	}
	
	function getOffices(callback){
		callback = (typeof callback == 'function') ? callback : new Function; 
		
		if (offices) return callback(offices);
		
		$.post('/novaposhta', {getOfficesNovaPoshta:''}, function(data){
			offices = data['response'];
			callback(offices);
		}, 'json');
	}
});

/* SHOW FORM NOVA-POSHTA (button)
-------------------------------------------*/
$(document).on('click', '[data-bind="invoice-novaposhta"]', function(e){
	e.preventDefault();
	var html = $(
	'<form data-bind="get-invoice-novaposhta" action="/novaposhta" method="post">'+
		'<div class="form-group">'+
			'<label>Номер накладной:</label>'+
			'<input class="form-control" type="text" name="invoice" value="">'+
		'</div>'+
		'<div class="form-group">'+
			'<input type="hidden" name="get_invoice" value="">'+
			'<button class="btn btn-white" type="submit">отследить</button>'+
		'</div>'+
		'<div class="box-result" data-novaposhta="result"></div>'+
	'</form>');
	
	var D = html.dialog({
		width:'370px',
		height:'auto',
		title:'Отследить заказ НОВА ПОШТА:',
		drag:true
	});
});


/* GET INVOICE NOVA-POSHTA 
-------------------------------------------*/
$(document).on('submit', '[data-bind="get-invoice-novaposhta"]', function(e){
	e.preventDefault();
	
	var l = $('<div class="load"></div>').appendTo(document.body);

	$.ajax({
		url: "/novaposhta",
		type: "POST",
		data: $(this).serialize(),
		dataType: "json",
		error:function(data){
			l.remove();
			alert('Ошибка');
		},
		success:function(data){
			l.remove();
			data = $(data);
			data.find('a').each(function(){
				$(this).attr('href', 'http://novaposhta.ua'+$(this).attr('href')).attr('target','_blank');
			});
			$('[data-novaposhta="result"]').html(data);
		}
	});
});


/* NUMBER FORMAT (helper)
-------------------------------------------*/
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
		// kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
		kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");

		return km + kw + kd;
	}
})(jQuery);