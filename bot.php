<?php

include 'func.php'; // Функции
include 'text.php'; // Текста
require 'db.php'; // База данных
header('Content-Type: text/html; charset=utf-8'); // на всякий случай досообщим PHP, что все в кодировке UTF-8

$site_dir = dirname(dirname(__FILE__)).'/'; // корень сайта
$bot_token = $token; // токен бота
$data = json_decode(file_get_contents('php://input'), true); // весь ввод перенаправляем в $data и декодируем json-закодированные-текстовые данные в PHP-массив

// =============================================
// ====== ВСЁ ЧТО ВЫШЕ НЕ ТРОГАТЬ! ============
// =============================================

// ====== Кнопки ============
 if (!empty($data['callback_query']))
 {
   $inline_keyboard = $data['callback_query']['message']['reply_markup']['inline_keyboard']; // текущее состояние кнопок при нажатии на одну из 5 кнопок
   $callback = $data['callback_query']['data']; // получаем значение с кнопки, а именно с параметра callback_data нажатой кнопки
   $message_id = $data['callback_query']['message']['message_id'];
   $callback_query_id = $data['callback_query']['id']; //ID полученного результата
   $id = $data['message']['from']['id'];
   $chat_id = $data['callback_query']['message']['chat']['id']; // ID пользователя
   if ($callback == "boom")
   {editMes($bot_token, $chat_id, $message_id, $startxt, $key2); $place = 'ТЦ "БУМ"'; ansButton($bot_token, $chat_id, $callback_query_id, "Вы выбрали $place"); SetPlace($chat_id, $place);}
   elseif ($callback == "guliver")
   {editMes($bot_token, $chat_id, $message_id, $startxt, $key2); $place = 'ТОЦ "Гулливер"'; ansButton($bot_token, $chat_id, $callback_query_id, "Вы выбрали $place"); SetPlace($chat_id, $place);}
   elseif ($callback == "geyrop")
   {editMes($bot_token, $chat_id, $message_id, $startxt, $key2); $place = 'ТРЦ "Европа"'; ansButton($bot_token, $chat_id, $callback_query_id, "Вы выбрали $place"); SetPlace($chat_id, $place);}
   elseif ($callback == "cry")
   {editMes($bot_token, $chat_id, $message_id, $startxt, $key2); $place = 'ТРЦ "Огни"'; ansButton($bot_token, $chat_id, $callback_query_id, "Вы выбрали $place"); SetPlace($chat_id, $place);}
   elseif ($callback == "yantar")
   {editMes($bot_token, $chat_id, $message_id, $startxt, $key2); $place = 'рынок "Янтарный"'; ansButton($bot_token, $chat_id, $callback_query_id, "Вы выбрали $place"); SetPlace($chat_id, $place);}
   elseif ($callback == "about")
   {editMes($bot_token, $chat_id, $message_id, $about); ansButton($bot_token, $chat_id, $callback_query_id, null);}
   if ($callback < 6 && $callback > 0)
   {editMes($bot_token, $chat_id, $message_id, $ordertxt); ansButton($bot_token, $chat_id, $callback_query_id, "Вы поставили $callback ⭐️"); SetStar($chat_id, $callback); SetState($chat_id, "order");}
 }
// ====== ****** ============

// ====== Наши переменные ============
$font = 'ttf/font.ttf';
$order_chat_id = '-1001728665630';  //chat_id для перенаправления сообщений
$chat_id = $data['message']['from']['id']; // ID пользователя
$user_name = $data['message']['from']['username']; // Username пользователя
$first_name = $data['message']['from']['first_name']; // Имя
$last_name = $data['message']['from']['last_name']; // Фамилия
$text = trim($data['message']['text']); // Тело сообщения
$text_array = explode(" ", $text); // Массив с сообщением
$cmd = $text_array[0]; // Первое слово сообщения
$msg_array = array_slice($text_array, 1); // Массив с остальной частью сообщения
$msg = implode(" ", $msg_array); // Строка с остальной частью сообщения
// ====== *************** ============

// =============================================
// ====== Тут мы получили сообщение ============
// =============================================
if (!empty($data['message']['text'])) {

  if ($chat_id == '5145561954')
  {
    sendMessage($bot_token, $chat_id, "Пошел нахуй");
    exit;
  }
  // ====== База данных ============
  $get_user = R::findOne('feedback', 'user_id = ?', [$chat_id]);
  if (!$get_user)
  {
    NewUser($chat_id, $first_name, $user_name);
  }
  else
  {
    $bot_state = $get_user['state']; // получим текущее состояние бота, если оно есть
  }
  // ====== *********** ============

  // ====== Состояние заявки ============
  if ($bot_state == 'order')
  {
    /*sendMessage($bot_token, $order_chat_id, "Заявка от @$user_name:
    Имя: $first_name $last_name
    $text");*/
    sendMessage($bot_token, $chat_id, $last_thanks);
    $star = $get_user['stars'];
    for ($i=0; $i<$star; $i++)
    {
      $st[$i] = '⭐️';
    }
    $st = implode("", $st);
    $ph = FeedBack($bot_token, $chat_id, $text, $font, $star);
    sendPhoto($bot_token, $order_chat_id, $ph, "Отзыв от @$user_name\nИмя: $first_name $last_name\nОценка: $st");
    SetState($chat_id, "null");
    exit;
  }
  // ====== **************** ============

  else { // если состояние бота пустое -- то обычные запросы

    // ====== Старт ============
    if ($cmd == '/start')
    {
      mesButton($bot_token, $chat_id, $start, $key1);
      SetState($chat_id, "start");
    }
    // ====== ***** ============

    // ====== Старт ============
    if ($cmd == '/ph')
    {
      /*$text = focusText($msg, $font, 75, 1200);
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
          $ht = 540;
        }
      else
      {
        $ht = 27 * $arr_txt;
      }
      $hi = $h / 2 - 300;
      $i = newHolst(1920, $h, 255, 255, 255); // Создание холста
      $ava = getProfilePh($bot_token, $chat_id); // Получаем путь аватара
      $src = makeCornersForImage($ava, 300, 0xff00ff); // Закругляем аватар и присваеваем его
      $image = new Imagick($src);
      $image->setImageFormat('png');
      $image->borderImage('#ffffff',1, 1);
      $image->trimImage(0);
      $image->setImagePage(0, 0, 0, 0);
      $image->writeImage("img/2.png");
      $src = imagecreatefrompng("img/2.png");
      //$remove = imagecolorallocate($src, 255, 255, 255);
      //$src = imagecolortransparent($src, $remove);
      $main = makeText($chat_id, $i, 0, 0, 0, $font, $text, 75, 0, 700, $ht); // Накладываем текст на фон и получаем путь до него
      $main_img = imagecreatefrompng($main); // Присваеваем фон
      //imagepalettetotruecolor($main_img);
      imagedestroy($i); // Удаляем ресурс холста
      //imagejpeg($src, "img/$chat_id.jpg"); // Изменяем размер аватара
      //resize("img/$chat_id-ava.jpg", 400);
      //$src = imagecreatefromjpeg("img/$chat_id-ava.jpg");
      $final = makeImage($chat_id, $src, $main_img, 15, $hi); // Наложение изображения
      sendPhoto($bot_token, $chat_id, $final); // Отправка сообщения*/
      $star = $get_user['stars'];
      for ($i=0; $i<$star; $i++)
      {
        $st[$i] = '⭐️';
      }
      $st = implode("", $st);
      $ph = FeedBack($bot_token, $chat_id, $text, $font, $star);
      sendPhoto($bot_token, $chat_id, $ph, "Отзыв от @$user_name\nИмя: $first_name $last_name\nОценка: $st");
      SetState($chat_id, "null");
      exit;
    }
    // ====== ***** ============

    // ====== Помощь ============
    if ($cmd == '/help')
    {
      sendMessage($bot_token, $chat_id, $help);
      SetState($chat_id, "help");
      exit;
    }
    // ====== ****** ============

    // ====== О нас ============
    elseif ($cmd == '/about')
    {
      sendMessage($bot_token, $chat_id, $about);
      SetState($chat_id, "about");
      exit;
    }
    // ====== ***** ============

    // ====== Заявка ============
    elseif ($cmd == '/order')
    {
      sendMessage($bot_token, $chat_id, "$first_name $last_name, $ordertxt");
      SetState($chat_id, "order");
      exit;
    }
    // ====== ****** ============

  } // Пустое состояние бота
} // Получение сообщения
