<!DOCTYPE html>
<html>
<head>
	<title>Админ :: <?=$h1?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<link rel="stylesheet" type="text/css" href="/css/admin/fonts/font.awesome/font-awesome.css">
	<link href="/css/admin/style.css" rel="stylesheet" type="text/css" />
	
	<link href="/css/admin/jquery/jquery-ui.1.11.0.css" rel="stylesheet" type="text/css" />
	<link href="/css/admin/dialog/dialog.css" rel="stylesheet" type="text/css" />
	<link href="/css/admin/fm/fm.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="/js/admin/jquery.1.9.1.js"></script>
	<script type="text/javascript" src="/js/admin/jquery/jquery-ui.1.11.0.js"></script>
	<script type="text/javascript" src="/js/admin/tinymce/tinymce.min.js"></script>
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
					<a class="<?=$action == 'user'?'activ':'';?>" href="/admin/user"><i class="icon-group"></i>Клиенты</a>
				</li>
				<li>
					<span class="has-child"><i class="icon-building"></i>Магазин</span>
					<ul>
						<li>
							<a class="<?=$action == 'category'?'activ':'';?>" href="/admin/category"><i class="icon-folder-open"></i>Категории</a>
						</li>
						<li>
							<a class="<?=$action == 'products'?'activ':'';?>" href="/admin/products"><i class="icon-file"></i>Продукты</a>
						</li>
						<li>
							<a class="<?=$action == 'filter'?'activ':'';?>" href="/admin/filter"><i class="icon-filter"></i>Фильтр</a>
						</li>
						<li>
							<a class="<?=$action == 'manufacturer'?'activ':'';?>" href="/admin/manufacturer"><i class="icon-wrench"></i>Производители</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="<?=$action == 'settings'?'activ':'';?>" href="/admin/settings"><i class="icon-cogs"></i>Настройки</a>
				</li>
				<li>
					<a class="FM-overview" href="#"><i class="icon-picture"></i>Файлы</a>
				</li>
				
				<li class="logout">
					<a title="Выход" href="/admin/?logout"><i class="icon-signout"></i></a>
				</li>
			</ul>

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
		</div>