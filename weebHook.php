<?php

$access_token = 'API_token'; //Token

$api = 'https://api.telegram.org/bot' . $access_token; //api bot


/**
 * Зададим основные переменные.
 */

$output = json_decode(file_get_contents('php://input'), TRUE); // Получим то, что передано скрипту ботом в POST-сообщении и распарсим

$chat_id = $output['message']['chat']['id'] ; // Выделим идентификатор чата ( -1001212250728 чат айди ордер лист)

$first_name = $output['message']['chat']['first_name']; // Выделим имя собеседника

$message = $output['message']['text']; // Выделим сообщение собеседника

$callback_query = $output['callback_query'];
$data = $callback_query['data'];



$message_id = ['callback_query']['message']['message_id'];

switch(strtolower_ru($message)) {
    case ('/hello'):
    case ('привет'):
        sendMessage($chat_id, 'Добрый день хозяин, '. $first_name . '! ');
        sendMessage($chat_id, 'Ваш Айди чата' . $chat_id);
        break;
    case ('/start'):
        sendMessage($chat_id, "hello i test Bot");
        break;
    default:
        sendMessage($chat_id, 'Гав-гав');
        break;

}
/**
 * Функция отправки сообщения в чат sendMessage().
 * @param $chat_id
 * @param $message
 */

function sendMessage($chat_id, $message) {

    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));

}
function sendMessageWithHtml($chat_id, $message,$reply_markup,$parse_mode) {

    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message). '&reply_markup=' . $reply_markup . '&parse_mode=' . $parse_mode );

}
function sendMessageReply_markup($chat_id, $message,$reply_markup) {

    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message). '&reply_markup=' . $reply_markup);

}

/**
 * Функция перевода символов в нижний регистр, учитывающая кириллицу
 * @param $text
 * @return mixed
 */

function strtolower_ru($text) {

    $alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');

    $alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');

    return str_replace($alfavitupper,$alfavitlover,strtolower($text));

}
