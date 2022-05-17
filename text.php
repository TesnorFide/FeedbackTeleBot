<?php
header('Content-Type: text/html; charset=utf-8');
$token = '5130305823:AAEZ50RUWdt_vz2AzIuB-MnYYoXIbcdNTTc';
$fbchat = 'https://t.me/test_feedbackbot';
$help = 'Привет, вот команды, что я понимаю:
/help - справка по изпользованию бота
/about - о нас
/start - начать процесс заполнения отзыва';
$about = "Бот для отзывов на вейпшоп PAROMARKET
Канал: https://t.me/paromarket
Канал с отзывами: $fbchat";
$start = 'Привет! Выбери место, где назначал встречу😇';
$startxt = 'Количество звёзд';
$key1 = array(
   array(
      array('text'=>'ТЦ "БУМ"','callback_data'=>'boom'),
      array('text'=>'ТОЦ "Гулливер"','callback_data'=>'guliver')
   ),
   array(
      array('text'=>'ТРЦ "Европа"','callback_data'=>'geyrop'),
      array('text'=>'ТРЦ "Огни"','callback_data'=>'cry')
   ),
   array(
      array('text'=>'О нас','callback_data'=>'about'),
      array('text'=>'Рынок "Янтарный"','callback_data'=>'yantar')
   )
);
$key2 = array(
   array(
      array('text'=>'⭐️','callback_data'=>'1'),
      array('text'=>'⭐️','callback_data'=>'2'),
      array('text'=>'⭐️','callback_data'=>'3'),
      array('text'=>'⭐️','callback_data'=>'4'),
      array('text'=>'⭐️','callback_data'=>'5')
   )
);
$ordertxt = "Напиши текст своего отзыва";
$last_thanks = "Спасибо за твой отзыв, благодаря тебе мы можем стать лучше!🥰
Посмотреть на свой отзыв можно в канале $fbchat";
