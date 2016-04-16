<!DOCTYPE html>
<html>
<head>
	<title>Склад :: <?=$h1?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<link rel="stylesheet" type="text/css" href="/css/admin/fonts/font.awesome/font-awesome.css">
	<link href="/css/admin/style.css" rel="stylesheet" type="text/css" />
	
	<link href="/css/admin/jquery/jquery-ui.1.11.0.css" rel="stylesheet" type="text/css" />
	<link href="/css/admin/jstree/proton/style.css" rel="stylesheet" type="text/css" />
	<link href="/css/admin/dialog/dialog.css" rel="stylesheet" type="text/css" />
	<link href="/css/admin/fm/fm.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="/js/admin/jquery.1.9.1.js"></script>
	<script type="text/javascript" src="/js/admin/jquery/jquery-ui.1.11.0.js"></script>
	<script type="text/javascript" src="/js/admin/jstree/jstree.js"></script>
	<!--<script type="text/javascript" src="/js/admin/tinymce/tinymce.min.js"></script>-->
	<script type="text/javascript" src="/js/admin/mask/mask.js"></script>
	<script type="text/javascript" src="/js/admin/dialog/dialog.js"></script>
	<script type="text/javascript" src="/js/admin/fm/fm.js"></script>
	<script type="text/javascript" src="/js/admin/scrollhead/scrollhead.js"></script>
	<script type="text/javascript" src="/js/admin/functions.js"></script>
</head>
<body>
	<div class="main">
	
		<div class="head">
		
			<ul class="menu">
				<li>
					<span class="has-child">Склад</span>
					<ul>
						<!--
						<li>
							<a class="<?=$action == 'category'?'activ':'';?>" href="/admin/category">Категории</a>
						</li>
						-->
						<li>
							<a class="<?=$action == 'products'?'activ':'';?>" href="/admin/products">Товары</a>
						</li>
						<li>
							<a class="<?=$action == 'filter'?'activ':'';?>" href="/admin/filter">Фильтр</a>
						</li>
						<li>
							<a class="<?=$action == 'manufacturer'?'activ':'';?>" href="/admin/manufacturer">Производители</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-child <?=$action == 'invoice'?'activ':'';?>" href="/admin/invoice">Накладная</a>
					<ul>
						<li>
							<a class="<?=$action == 'buy'?'activ':'';?>" href="/admin/invoice/buy">Приход</a>
						</li>
						<li>
							<a class="<?=$action == 'sale'?'activ':'';?>" href="/admin/invoice/sale">Продажа</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="<?=$action == 'settings'?'activ':'';?>" href="/admin/settings" title="Настройки"><i class="icon-cogs"></i></a>
				</li>
				<li>
					<a class="FM-overview" href="#" title="Файлы"><i class="icon-picture"></i></a>
				</li>
				
				<li class="logout">
					<a title="Выход" href="/admin/?logout" title="Выход"><i class="icon-signout"></i></a>
				</li>
			</ul>
			
			<!--
			<ul class="menu-status">
				<li>
					<a class="activ" href="/admin/">
						<i class="icon-shopping-cart"></i>Заказы (0)
					</a>
				</li>
				<li>
					<a class="" href="/admin/">
						<i class="icon-comments-alt"></i>Отзывы (0)
					</a>
				</li>
				<li>
					<a class="" href="/admin/">
						<i class="icon-time"></i>Ждут товаров (0)
					</a>
				</li>
			</ul>
			-->
			
		</div>