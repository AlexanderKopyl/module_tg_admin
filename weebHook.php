<?php

$access_token = 'BOT_TOKEN'; //Token

$api = 'https://api.telegram.org/bot' . $access_token; //api bot

$user = 'BD_LOGIN'; //User DB
$pass = 'BD_PASSWORD';//Password DB

$dbh = new PDO('mysql:host=localhost;dbname={BD_NAME}', $user, $pass); //Connect to DB
/**
 * Зададим основные переменные.
 */

$output = json_decode(file_get_contents('php://input'), TRUE); // Получим то, что передано скрипту ботом в POST-сообщении и распарсим

$chat_id = $output['message']['chat']['id'] ;

$first_name = $output['message']['chat']['first_name']; // Выделим имя собеседника
$contact = $output['message']['contact']['phone_number']; // Выделим имя собеседника

$message = $output['message']['text']; // Выделим сообщение собеседника

$name = $output["message"]["from"]["username"]; //Юзернейм пользователя
$first_name = $output["message"]["from"]["first_name"];
$last_name = $output["message"]["from"]["last_name"];
$date = $output["message"]['date'];

//$user_data = array(
//    'chat' => gettype($output['message']['contact']['phone_number'])
//);
//file_put_contents("$name.json", json_encode($user_data));

$callback_query = $output['callback_query'];
$data = $callback_query['data'];



$message_id = ['callback_query']['message']['message_id'];


$arrayComands = array(
    'hello'     => '/hello - С вами поздароваються',
    'help'      => '/help or help - вывести команды',
);

$pattern_telephone = "/^\+380\d{3}\d{2}\d{2}\d{2}$/";

$text = "";
$textHelp = "";


$todayTime = date("H:i:s");



foreach ($arrayComands as $comand => $value){
    $textHelp .= $value . "\r\n";
}
if(preg_match($pattern_telephone, $contact)){
    sendMessage($chat_id, 'Cпасибо что поделился');
    $user_chat = $dbh->prepare("UPDATE `oc_tg_users` SET `telephone`= :telephone WHERE `user_id` = :user_id");
    $user_chat->execute(array('user_id' => getIdUser($name,$dbh),'telephone' => $contact));
    exit;
}

/**
 * Получим команды от пользователя.
 * Переведём их для удобства в нижний регистр
 */

switch(strtolower_ru($message)) {
    case ('/hello'):
    case ('привет'):
        sendMessage($chat_id, 'Привет, '. $first_name . '! ');
        sendMessage($chat_id, 'Ваш айди: ' . $chat_id);
        break;
    case ('/start'):
        sendMessage($chat_id, $textHelp);


        if(getIdUser($name,$dbh)){
            sendMessage($chat_id, "Вы уже подписанны на бота");
        }else{
            $user_data = $dbh->prepare("INSERT INTO `oc_tg_users`(`first_name`, `last_name`, `username`,`date_add`,`chat_id`) VALUES (:first_name,:last_name,:username,:date_add,:chat_id)");
            $user_data->execute(array('first_name' => $first_name,'date_add' => date('Y-m-d', $date),'username' => $name,'last_name' => $last_name,'chat_id'=> $chat_id));
        }

        break;
    case ('/help'):
    case ('help'):
        sendMessage($chat_id, $textHelp);
        break;
    case ('не делится телефоном'):
        sendMessage($chat_id, "Очень жаль.. А мы можем тебе дать за эту скидку");
        break;
    case('/test'):
        $inline_button1 = array("text"=>"Google url","url"=>"http://google.com");
        $inline_keyboard = [[$inline_button1]];
        $keyboard=array("inline_keyboard"=>$inline_keyboard);
        $replyMarkup = json_encode($keyboard);
        sendMessageReply_markup($chat_id, "$chat_id", $replyMarkup);
        break;
    case('/test1'):
        $inline_button1 = array("text"=>"Google url","url"=>"http://google.com");
        $inline_keyboard = [[$inline_button1]];
        $keyboard=array("inline_keyboard"=>$inline_keyboard);
        $replyMarkup = json_encode($keyboard);
        $message = "<a href=\"https://zoocomplex.com.ua/all-absorb-oll-absorb-basic-pelenki-dlya-sobak-56h58sm-100sht\">inline URL</a>";
        sendMessageWithHtml($chat_id,$message,$replyMarkup,'HTML');
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
function getIdUser($username,$dbh){

    $user_id = 0;

    $user = $dbh->prepare( "SELECT `user_id` FROM `oc_tg_users` WHERE `username` = :username LIMIT 1");
    $user->execute(array('username' => $username));

    foreach ($user as $row){
        $user_id = $row['user_id'];
    }

    return $user_id;

}

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
/**
 * Функция перевода символов в номера телефона
 */
function phn_numb($numb) {
    if (!is_numeric(substr($numb, 0, 1))  && !is_numeric(substr($numb, 1, 1))) { return $numb; }

    $chars = array(' ', '(', ')', '-', '.');
    $numb = str_replace($chars, "", $numb);

    if (strlen($numb) > 10) {
        // a 10 digit number, format as 1-800-555-5555
        $numb = "+" . substr($numb, 0, 1) . substr($numb, 1, 1) . '(' . substr($numb, 2, 3) . ')' . substr($numb, 4, 3) . "-" . substr($numb, 8, 2). "-". substr($numb, 10, 2) ;
    }
    else {
        $numb = substr($numb, 0, 3) . '-' . substr($numb, 3, 3) . '-' . substr($numb, 5, 4);
    }

    return $numb;
}