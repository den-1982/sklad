<?php

/* CLEAN
--------------------------------------------------------------*/
function clean($str = '', $tag = false, $sql = false)
{
	$CI = &get_instance();
	
	if ($tag) $str = strip_tags($str);
	$str = trim(preg_replace("/\ {2,}/", " ", $str));
	
	# mysql
	//if ($sql) $str = mysql_real_escape_string($str);
	
	# mysqli
	if ($sql) $str = $CI->db->escape_str($str);
	
	return $str;
}

/* TRANSLIT
--------------------------------------------------------------*/
function translit($str)
{
	$str = preg_replace("/[^а-яa-z\d\s\-\+]/u", '', $str);
	$str = preg_replace("/\s+/",' ',$str);
	$str = trim($str);
	
	$tr = array(
		"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
		"Д"=>"D","Е"=>"E","Ё"=>"Yo","Ж"=>"J","З"=>"Z","И"=>"I",
		"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
		"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
		"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
		"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
		"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
		"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"yo","ж"=>"j",
		"з"=>"z","и"=>"i","і"=>"i","й"=>"y","к"=>"k","л"=>"l",
		"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
		"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
		"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
		"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"-"
	);
	
	return strtr($str,$tr);
}

/* RUS DATE FORMAT (month)
--------------------------------------------------------------*/
function rus_date_format($datetime)
{
	$months = array(
		'01'=>'Январь',
		'02'=>'Февраль',
		'03'=>'Март',
		'04'=>'Апрель',
		'05'=>'Май',
		'06'=>'Июнь',
		'07'=>'Июль',
		'08'=>'Август',
		'09'=>'Сентябрь',
		'10'=>'Октябрь',
		'11'=>'Ноябрь',
		'12'=>'Декабрь'
	);

	$date = explode('.',  date('Y.m.d', $datetime));
	
	return $date[0].' '.(isset($months[$date[1]]) ? $months[$date[1]] : $date[1] ).' '.$date[2]; 
}
/* DEL DIR
--------------------------------------------------------------*/
function delDir($directory = '')
{	
	$dir = opendir($directory);
	while ($file = readdir($dir)){
		if (is_file($directory."/".$file)){
			unlink ($directory."/".$file);
		}else if (is_dir($directory."/".$file) && ($file != ".") && ($file != "..")){
			delDir($directory."/".$file);
		}
	}

	closedir($dir);
	rmdir($directory);
}





/* WATERMARK
--------------------------------------------------------------*/
function add_watermark($img = '', $out = '', $text = '', $font = ''){
	
	$font = $font ? $font : ROOT.'/css/fonts/arial.ttf';
	if ( ! file_exists($font)){
		log_message('error', 'Ненайден файл: '.$font.'. Watermark. function_helper.php');
		return;
	}
	
	$fontSize = 100;
	$angle = 0;
	
	# узнаем размеры холста при шрифте 70px
	$box = imagettfbbox($fontSize, $angle, $font, $text);
	$widthString = abs($box[2]) + 80;
	$heightString = abs($box[5]) + 100;
	
	$wm = imagecreatetruecolor($widthString, $heightString);
	$color = imagecolorallocatealpha($wm, 0, 0, 0, 127);
	imagefill($wm, 0, 0, $color);
	imagesavealpha($wm, true);

	# текст
	$x = 40;
	$y = $heightString - 50;
	$c = imagecolorallocatealpha($wm, 0, 0, 0, 100);
	
	# записываем строку на изображение
	imagettftext($wm, $fontSize, $angle, $x, $y, $c, $font, $text);
	
	
	# основное изображение
	$info = @getimagesize($img);
	if ( ! $info){
		log_message('error', 'Ошибка getimagesize(): '.$img.'. Watermark. function_helper.php '.__LINE__);
		return;
	}
	
	list($type, $mime) = explode('/', $info['mime']);
	$fnCreate = 'imagecreatefrom'.$mime;
	$fnImage = 'image'.$mime;
	
	if( ! function_exists($fnCreate)){
		log_message('error', 'Ошибка function not exists: '.$fnCreate.'. Watermark. function_helper.php '.__LINE__);
		return;
	}
	
	$img = $fnCreate($img);
	if ( ! $img){
		log_message('error', 'Ошибка '.$fnCreate.'(): '.$img.'. Watermark. function_helper.php '.__LINE__);
		return;
	}
	
	# для PNG
	imagealphablending($img, true);
	imagesavealpha($img, true);
	
	$width = imagesx($img);
	$height = imagesy($img);

	$kfx = $heightString / $widthString;
	$new_width = $width;
	$new_height = $new_width * $kfx;
	
	imagecopyresampled($img, $wm, 0, $height - $new_height, 0, 0, $new_width, $new_height, $widthString , $heightString);
	$q = $mime == 'jpeg' ? 100 : 0;
	$fnImage($img, $out, $q);
	
	imagedestroy($img);
	imagedestroy($wm);
}

/* WATERMARK
--------------------------------------------------------------*/
function add_watermark_image($img = '', $out = '', $wm = ''){
	
	$wm = $wm ? $wm : ROOT.'/img/i/wm.png';
	if ( ! file_exists($wm)){
		log_message('error', 'Ненайден файл: '.$wm.'. Watermark. function_helper.php');
		return;
	}
	
	# wm изображение
	$infoWm = @getimagesize($wm);
	
	list($type, $mime) = explode('/', $infoWm['mime']);
	$fnCreate = 'imagecreatefrom'.$mime;
	$fnImage = 'image'.$mime;
	
	$wm = $fnCreate($wm);
	
	$widthWm = imagesx($wm);
	$heightWm = imagesy($wm);
	
	
	# основное изображение
	$infoImg = @getimagesize($img);
	
	list($type, $mime) = explode('/', $infoImg['mime']);
	$fnCreate = 'imagecreatefrom'.$mime;
	$fnImage = 'image'.$mime;
	
	$img = $fnCreate($img);
	
	$width = imagesx($img);
	$height = imagesy($img);

	$kfx = $heightWm / $widthWm;
	$new_width = $width;
	$new_height = $new_width * $kfx;
	
	imagecopyresampled($img, $wm, 0, $height - $new_height, 0, 0, $new_width, $new_height, $widthWm , $heightWm);
	$q = $mime == 'jpeg' ? 100 : 0;
	$fnImage($img, $out, $q);
	
	imagedestroy($img);
	imagedestroy($wm);
}


/* WATERMARK ANGLE
--------------------------------------------------------------*/
function add_watermark_angle($img = '', $out = '', $text = '', $font = '', $r = 128, $g = 128, $b = 128, $alpha = 100){
	$font = $font ? $font : ROOT.'/css/fonts/arial.ttf';
	if ( ! file_exists($font)){
		log_message('error', 'Ненайден файл: '.$font.'. Watermark. function_helper.php '.__LINE__);
		return;
	}
	
	# основное изображение
	$info = @getimagesize($img);
	if ( ! $info){
		log_message('error', 'Ошибка getimagesize(): '.$img.'. Watermark. function_helper.php '.__LINE__);
		return;
	}
	
	list($type, $mime) = explode('/', $info['mime']);
	$fnCreate = 'imagecreatefrom'.$mime;
	$fnImage = 'image'.$mime;
	
	if( ! function_exists($fnCreate)){
		log_message('error', 'Ошибка function not exists: '.$fnCreate.'. Watermark. function_helper.php '.__LINE__);
		return;
	}

	$img = $fnCreate($img);
	if ( ! $img){
		log_message('error', 'Ошибка '.$fnCreate.'(): '.$img.'. Watermark. function_helper.php '.__LINE__);
		return;
	}
	
	# для PNG
	imagealphablending($img, true);
	imagesavealpha($img, true);
	
	# получаем ширину и высоту исходного изображения
	$width = imagesx($img);
	$height = imagesy($img);
	
	# угол поворота текста
	$angle =  -rad2deg(atan2((-$height),($width))); 

	# добавляем пробелы к строке
	$text = "  ".$text."  ";

	$c = imagecolorallocatealpha($img, $r, $g, $b, $alpha);
	$size = ($width+$height) / strlen($text);
	$box  = imagettfbbox ( $size, $angle, $font, $text );
	$x = $width/2 - abs($box[4] - $box[0])/2;
	$y = $height/2 + abs($box[5] - $box[1])/2;

	# записываем строку на изображение
	imagettftext($img, $size ,$angle, $x, $y, $c, $font, $text);
	
	$q = $mime == 'jpeg' ? 100 : 0;
	$fnImage($img, $out, $q);

	imagedestroy($img);
}