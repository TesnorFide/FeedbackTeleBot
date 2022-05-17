<?php

// ====== Отправка сообщения ============
function sendMessage($bot_token, $chat_id, $text, $reply_markup = '')
{
    $ch = curl_init();
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            //'parse_mode' => 'HTML',
            'text' => $text,
            'reply_markup' => $reply_markup,
        ]
    ];

    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}
// ====== ****************** ============

// ====== Отправка изображения ============
function sendPhoto($bot_token, $chat_id, $photo, $text = '', $reply_markup = '')
{
    $ch = curl_init();
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendPhoto',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            'photo'     => new CURLFile(realpath($photo)),
            'caption' => $text,
            'reply_markup' => $reply_markup,
        ]
    ];

    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}
// ====== ******************** ============

// ====== Отправка кнопок ============
function mesButton($bot_token, $chat_id, $text, $keyboard)
{
    $key = json_encode(array('inline_keyboard' => $keyboard));
    sendMessage($bot_token, $chat_id, $text, $key);
}
// ====== *************** ============

// ====== Регистрация нажатия ============
function ansButton($bot_token, $chat_id, $id, $text)
{
    $ch = curl_init();
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/answerCallbackQuery',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'callback_query_id' => $id,
            'text' => $text,
        ]
    ];

    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}
// ====== ******************* ============

// ====== Редактирование сообщений ============
function editMes($bot_token, $chat_id, $message_id, $txt, $reply_markup = '')
{
  if (!empty($reply_markup)) {$reply_markup = json_encode(array('inline_keyboard' => $reply_markup));}
  $url = 'https://api.telegram.org/bot' . $bot_token . '/editMessageText?chat_id=' . $chat_id . '&message_id=' . $message_id . '&text=' . urlencode($txt) . '&reply_markup=' . $reply_markup;
  file_get_contents($url);
}
// ====== ************************ ============

// ====== Сохранение фотографии пользователя ============
function getProfilePh ($bot_token, $chat_id)
{
  $path = "img/$chat_id.jpg";
  $url = 'https://api.telegram.org/bot' . $bot_token . '/getUserProfilePhotos?user_id=' . $chat_id . '&offset=0&limit=1';
  $list = file_get_contents($url);
  $file_id = json_decode($list, true);
  $file_id = $file_id['result']['photos'][0][1]['file_id'];
  $file_id = json_encode($file_id, true);
  $file_id = mb_substr($file_id, 1, -1);
  $url = 'https://api.telegram.org/bot' . $bot_token . '/getFile?file_id=' . $file_id;
  $ray = file_get_contents($url);
  $file_path = mb_substr($ray, 180, -3);
  $url = 'https://api.telegram.org/file/bot' . $bot_token . '/' . $file_path;
  file_put_contents($path, file_get_contents($url));
  $img = imagecreatefromjpeg($path);
  $img = imagescale($img, 640, 640);
  imagejpeg($img, $path);
  return ($path);
}
// ====== ********************************** ============

// ====== Регистрация положения ============
function SetState($chat_id, $state)
{
  $set_bot_state = R::findOne('feedback', 'user_id = ?', [$chat_id]);
  $set_bot_state->state = $state;
  R::store($set_bot_state);
}
// ======= ******************** ============

// ====== Регистрация отзыва ============
function SetPlace($chat_id, $place)
{
  $set_bot_fb = R::findOne('feedback', 'user_id = ?', [$chat_id]);
  $set_bot_fb->place = $place;
  R::store($set_bot_fb);
}
function SetStar($chat_id, $stars)
{
  $set_bot_fb = R::findOne('feedback', 'user_id = ?', [$chat_id]);
  $set_bot_fb->stars = $stars;
  R::store($set_bot_fb);
}
function SetFeedback($chat_id, $fb)
{
  $set_bot_fb = R::findOne('feedback', 'user_id = ?', [$chat_id]);
  $set_bot_fb->fb = $fb;
  R::store($set_bot_fb);
}
// ======= ******************** ============

// ====== Создание нового юзера ============
function NewUser($id, $name, $nick)
{
  $new_user = R::dispense('feedback');
  $new_user->user_id = $id;
  $new_user->first_name = $name;
  $new_user->username = $nick;
  $new_user->state = 'null';
  R::store($new_user);
}
// ====== ********************* ============

// ====== Создание холста ============
function newHolst($weight, $height, $r, $g, $b)
{
  $img = imagecreatetruecolor($weight, $height);
  //$img = imageCreate($weight, $height);
  $color = imagecolorallocatealpha($img, $r, $g, $b, 127);
  //$color = imageColorAllocate($img, $r, $g, $b);
  imagefill($img, 0, 0, $color);
  //imageFilledRectangle($img, 0, 0, imageSX($img), imageSY($img), $color);
  imagecolortransparent($img, $color);
  imagesavealpha($img, true);
  return($img);
}
// ====== *************** ============

// ====== Наложение текста ============
function makeText($chat_id, $img, $r, $g, $b, $font, $txt, $size, $angle, $x, $y)
{
  $color = imageColorAllocate($img, $r, $g, $b);
  //$path = "img/$chat_id.png";
  $russian_text = mb_convert_encoding($txt, 'utf-8', mb_detect_encoding($txt));
  imagettftext($img, $size, $angle, $x, $y, $color, $font, $russian_text);
  //imagepng($img, $path);
  return($img);
}
// ====== **************** ============

// ====== Разбивка текста ============
function focusText($text, $font, $font_size, $width_text)
{
  // Разбиваем наш текст на массив слов
  $arr = explode(' ', $text);
  // Возращенный текст с нужными переносами строк, пока пустая
  $ret = "";
  // Перебираем наш массив слов
  foreach($arr as $word)
  {
    // Временная строка, добавляем в нее слово
    $tmp_string = $ret.' '.$word;
    // Получение параметров рамки обрамляющей текст, т.е. размер временной строки
    $textbox = imagettfbbox($font_size, 0, $font, $tmp_string);
    // Если временная строка не укладывается в нужные нам границы, то делаем перенос строки, иначе добавляем еще одно слово
    if($textbox[2] > $width_text)
      $ret.=($ret==""?"":"\n").$word;
    else if ($textbox[2] < $width_text)
      $ret.=($ret==""?"":" ").$word;
  }
  return($ret);
}
// ====== *************** ============

// ====== Наложение изображения ============
function makeImage($chat_id, $src, $main_img, $x, $y)
{
  $path = "img/$chat_id.png";
  $w_src = imagesx($src);
  $h_src = imagesy($src);
  $w_dest = imagesx($main_img);
  $h_dest = imagesy($main_img);
  $transfer_x = $x;
  $transfer_y = $y;
  imagecopyresampled($main_img, $src, $transfer_x, $transfer_y, 0, 0, $w_src, $h_src, $w_src, $h_src);
  imagejpeg($main_img, $path);
  imagedestroy($src);
  imagedestroy($main_img);
  return($path);
}
// ====== ********************* ============

// ====== Закругление краев ============
function makeCornersForImage($chat_id, $image, $radius, $background){
    // загружаем картинку
    $path = "img/$chat_id.png";
    $img = imagecreatefromjpeg($image);
    imagepng($img, $path);
    imagedestroy($img);
    $img = imagecreatefrompng($path);
    // включаем режим сопряжения цветов
    imagealphablending($img, true);
    // размер исходной картинки
    $width = imagesx($img);
    $height = imagesy($img);
    // создаем изображение для углов
    $corner = imagecreatetruecolor($radius, $radius);
    imagealphablending($corner, false);
    // прозрачный цвет
    $trans = imagecolorallocatealpha($corner, 255, 255, 255, 127);
    // заливаем картинку для углов
    imagefill($corner, 0, 0, $background);
    // рисуем прозрачный эллипс
    imagefilledellipse($corner, $radius, $radius, $radius * 2, $radius * 2, $trans);
    // массив положений. Для расположения по углам
    $positions = array(
        array(0, 0),
        array($width - $radius, 0),
        array($width - $radius, $height - $radius),
        array(0, $height - $radius),
    );
    // накладываем на углы картинки изображение с прозрачными эллипсами
    foreach ($positions as $pos) {
        imagecopyresampled($img, $corner, $pos[0], $pos[1], 0, 0, $radius, $radius, $radius, $radius);
        // поворачиваем картинку с эллипсов каждый раз на 90 градусов
        $corner = imagerotate($corner, -90, $background, false);
    }
    // вернем картинку
    imagepng($img, $path);
    imagedestroy($img);
    $img = imagecreatefrompng($path);
    imagetruecolortopalette($img, true, 256);
    //imagepalettetotruecolor($img);
    $tran = imagecolorat($img, 0, 0);
    imagecolortransparent($img, $tran);
    //imagesavealpha($img, true);
    //imagepng($img, $path);
    //imagedestroy($img);
    return ($img);
}
// ====== ***************** ============

// ====== Изменение размера ============
function resize($image, $w_o = false, $h_o = false)
{
  if (($w_o < 0) || ($h_o < 0))
  {
      return false;
  }
  list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
  $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
  $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
  if ($ext)
  {
    $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
    $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
  }
  else
  {
    echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый
    return false;
  }
  /* Если указать только 1 параметр, то второй подстроится пропорционально */
  if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
  if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
  $img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
  imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
  $func = 'image'.$ext; // Получаем функция для сохранения результата
  return $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
}
// ====== ***************** ============

function FeedBack ($bot_token, $chat_id, $msg, $font, $star)
{
  $text = focusText($msg, $font, 75, 1200);
  $arr_txt = explode("\n", $text);
  $arr_txt = count($arr_txt);
  if ($arr_txt > 15)
  {
    sendMessage($bot_token, $chat_id, "Артём пока запретил такие длинные приколы");
    exit;
  }
  $h = 160 * $arr_txt;
  if ($h < 1080)
    {
      $h = 1080;
      $ht = 640;
      $hs = 150;
    }
  else
  {
    $ht = 27 * $arr_txt + 75;
    $hs = $ht - 150;
  }
  $hi = $h / 2 - 300;
  $i = newHolst(1920, $h, 255, 255, 255); // Создание холста
  $ava = getProfilePh($bot_token, $chat_id); // Получаем путь аватара
  $src = makeCornersForImage($chat_id, $ava, 300, 0xff00ff); // Закругляем аватар и присваеваем его
  /*$image = new Imagick($src);
  $image->setImageFormat('png');
  $image->borderImage('#ffffff',1, 1);
  $image->trimImage(0);
  $image->setImagePage(0, 0, 0, 0);
  $image->writeImage("img/2.png");*/
  //$src = imagecreatefrompng("img/2.png");
  //$remove = imagecolorallocate($src, 255, 255, 255);
  //$src = imagecolortransparent($src, $remove);
  $main = makeText($chat_id, $i, 0, 0, 0, $font, $text, 75, 0, 700, $ht); // Накладываем текст на фон и получаем путь до него
  for ($i=0; $i<$star; $i++)
  {
    $st[$i] = "a";
  }
  $st = implode("", $st);
  $font = 'ttf/star.ttf';
  $main_img = makeText($chat_id, $main, 0, 0, 0, $font, $st, 75, 0, 700, $hs); // Накладываем текст на фон и получаем путь до него
  $path = "img/$chat_id.png";
  imagepng($main_img, $path);
  $main_img = imagecreatefrompng($path);
  //imagepalettetotruecolor($main_img);
  imagedestroy($i); // Удаляем ресурс холста
  //imagejpeg($src, "img/$chat_id.jpg"); // Изменяем размер аватара
  //resize("img/$chat_id-ava.jpg", 400);
  //$src = imagecreatefromjpeg("img/$chat_id-ava.jpg");
  $final = makeImage($chat_id, $src, $main_img, 15, $hi); // Наложение изображения
  //sendPhoto($bot_token, $chat_id, $final); // Отправка сообщения
  return ($final);
}
