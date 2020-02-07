<?php

use React\EventLoop\Factory;
use unreal4u\TelegramAPI\Abstracts\TraversableCustomType;
use unreal4u\TelegramAPI\HttpClientRequestHandler;
use unreal4u\TelegramAPI\Telegram\Methods\GetMe;
use unreal4u\TelegramAPI\Telegram\Methods\GetUpdates;
use unreal4u\TelegramAPI\Telegram\Methods\GetWebhookInfo;
use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\Telegram\Types\KeyboardButton;
use unreal4u\TelegramAPI\Telegram\Types\ReplyKeyboardMarkup;
use unreal4u\TelegramAPI\Telegram\Types\WebhookInfo;
use unreal4u\TelegramAPI\TgLog;


class ControllerExtensionModuleTgAdmin extends Controller
{

    private $error = array();
    private $webHook = array();
    private $updates = array();
    private $error_logfile = '404_log.log';
    private $error_logfile_admin = '404_admin_login.log';

    public function index()
    {
        $this->load->language('extension/module/tg_admin');

        $this->load->model('setting/setting');
        $this->load->model('extension/module/tg_admin');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('module_tg_admin', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        $this->document->addStyle('view/stylesheet/tg_admin/style.css?v=2');
        $this->document->addStyle('view/stylesheet/tg_admin/datatables.min.css');
        $this->document->addScript('view/javascript/tg_admin/datatables.min.js');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        // Кнопки действий
        $data['action'] = $this->url->link('extension/module/tg_admin', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['user_token'] = $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/tg_admin', 'user_token=' . $this->session->data['user_token'], true)
        );

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->request->post['module_tg_admin_status'])) {
            $data['module_tg_admin_status'] = $this->request->post['module_tg_admin_status'];
        } else {
            $data['module_tg_admin_status'] = $this->config->get('module_tg_admin_status');
        }

        if (isset($this->request->post['module_tg_admin_bot_apikey'])) {
            $data['module_tg_admin_bot_apikey'] = $this->request->post['module_tg_admin_bot_apikey'];
        } else {
            $data['module_tg_admin_bot_apikey'] = $this->config->get('module_tg_admin_bot_apikey');
        }

        if (isset($this->request->post['module_tg_admin_chat_id_admin'])) {
            $data['module_tg_admin_chat_id_admin'] = $this->request->post['module_tg_admin_chat_id_admin'];
        } else {
            $data['module_tg_admin_chat_id_admin'] = $this->config->get('module_tg_admin_chat_id_admin');
        }

        if (isset($this->request->post['module_tg_admin_cloud_mail'])) {
            $data['module_tg_admin_cloud_mail'] = $this->request->post['module_tg_admin_cloud_mail'];
        } else {
            $data['module_tg_admin_cloud_mail'] = $this->config->get('module_tg_admin_cloud_mail');
        }

        if (isset($this->request->post['module_tg_admin_cloud_api'])) {
            $data['module_tg_admin_cloud_api'] = $this->request->post['module_tg_admin_cloud_api'];
        } else {
            $data['module_tg_admin_cloud_api'] = $this->config->get('module_tg_admin_cloud_api');
        }

        $bot_api = $this->config->get('module_tg_admin_bot_apikey') ?: '74409123123:adadasdasdasdasd';

        try {
            $data['bot'] = $this->getMe($bot_api);
        } catch (Exception $e) {
            $data['error_bot'] = 'Ошибка: ' . $e->getMessage();
        }

        try {
            $this->getUpdates($bot_api);
            $data['updates'] = $this->updates;
        } catch (Exception $e) {
            $data['error_bot_updates'] = 'Ошибка: ' . $e->getMessage();
        }
//        var_dump($data['updates']);

        try {
            $this->getWebhookinfo($bot_api);
            $data['infoweb_url'] = $this->webHook['info']->url;
        } catch (Exception $e) {
            $data['error_bot_webhookinfo'] = 'Ошибка: ' . $e->getMessage();
        }
//        try {
//            $data['users_rules_cloud'] = $this->curlGet('https://api.cloudflare.com/client/v4/user/firewall/access_rules/rules');
//        }catch (Exception $e){
//            $data['error_users_rules_cloud'] = 'Ошибка: ' . $e->getMessage();
//        }
//        var_dump($data['users_rules_cloud']);



        $file       = DIR_LOGS . $this->error_logfile;
        $file_admin = DIR_LOGS . $this->error_logfile_admin;

        $data['errors'] = $this->ErrorTxtToArray($file);
        $data['errors_admin'] = $this->ErrorAdminTxtToArray($file_admin);
//        $this->sendKeyboardForTelephone($this->config->get('module_tg_admin_chat_id_admin'));

        $data['users'] = $this->model_extension_module_tg_admin->getUsers();

        // Загрузка шаблонов для шапки, колонки слева и футера
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        // Выводим в браузер шаблон
        $this->response->setOutput($this->load->view('extension/module/tg_admin', $data));
    }

    public function sendMessage($botApiKey, $chatId, $message)
    {
        $loop = \React\EventLoop\Factory::create();
        $handler = new HttpClientRequestHandler($loop);
        $tgLog = new TgLog($botApiKey, $handler);

        $sendMessage = new SendMessage();
//        $sendMessage->chat_id = 205993908;
        $sendMessage->chat_id = $chatId;
        $sendMessage->text = $message;

        $tgLog->performApiRequest($sendMessage);
        $loop->run();
    }


    protected function getMe($api)
    {
        $loop = Factory::create();
        $tgLog = new TgLog($api, new HttpClientRequestHandler($loop));

        $response = Clue\React\Block\await($tgLog->performApiRequest(new GetMe()), $loop);


        $data['first_name'] = $response->first_name;
        $data['id'] = $response->id;
        $data['username'] = $response->username;

        return $data;
    }

    protected function getUpdates($api)
    {
        $loop = Factory::create();
        $tgLog = new TgLog($api, new HttpClientRequestHandler($loop));

        $getUpdates = new GetUpdates();

        $updatePromise = $tgLog->performApiRequest($getUpdates);
        $updatePromise->then(
            function (TraversableCustomType $updatesArray) {
                foreach ($updatesArray as $update) {
                    //Тут можно достать больше инфы о боте через апдейт
                    $this->updates['update_id'] = $update->update_id;
                }
            },
            function (\Exception $exception) {
                // Onoes, an exception occurred...
                $this->updates['update_error'] = 'Exception ' . get_class($exception) . ' caught, message: ' . $exception->getMessage();
            }
        );

        $loop->run();
    }

    protected function getWebhookinfo($api)
    {
        $loop = Factory::create();
        $tgLog = new TgLog($api, new HttpClientRequestHandler($loop));

        $webHookInfo = new GetWebhookInfo();

        $promise = $tgLog->performApiRequest($webHookInfo);

        $promise->then(
            function (WebhookInfo $info) {

                $this->webHook['info'] = $info;
            },
            function (\Exception $e) {
                $this->webHook['webHook_error'] = $e;
            }
        );
        $loop->run();
    }

    public function sendKeyboardForTelephone()
    {
        $this->load->language('extension/module/tg_admin');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            if ((utf8_strlen($this->request->post['chat_id']) < 9) || (utf8_strlen($this->request->post['chat_id']) > 10)) {
                $json['error'] = $this->language->get('error_chat_id');
            }

//            $json['error_length'] = utf8_strlen($this->request->post['chat_id']);

            if (!$json) {
                $loop = Factory::create();
                $tgLog = new TgLog($this->config->get('module_tg_admin_bot_apikey'), new HttpClientRequestHandler($loop));

                $sendMessage = new SendMessage();
                $sendMessage->chat_id = $this->request->get['chat_id'];
                $sendMessage->text = 'Хотите поделится номером телефона .. Для больших скидок';
                $sendMessage->reply_markup = new ReplyKeyboardMarkup();
                $sendMessage->reply_markup->one_time_keyboard = true;

// Create the first button
                $keyboardButton = new KeyboardButton();
                $keyboardButton->text = 'Да поделится';
                $keyboardButton->request_contact = true;
                $sendMessage->reply_markup->keyboard[0][] = $keyboardButton;

                $promise = $tgLog->performApiRequest($sendMessage);

                $promise->then(
                    function ($response) {
//                echo '<pre>';
//                var_dump($response);
//                echo '</pre>';
                    },
                    function (\Exception $exception) {
                        // Onoes, an exception occurred...
                        echo 'Exception ' . get_class($exception) . ' caught, message: ' . $exception->getMessage();
                    }
                );

                $loop->run();
            }

            if (!isset($json['error'])) {
                $json['success'] = $this->language->get('text_telephone_success');
            }

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function clearError()
    {
        $this->load->language('extension/module/tg_admin');

        $file = DIR_LOGS . $this->error_logfile;

        $handle = fopen($file, 'w+');

        fclose($handle);

        $this->session->data['success'] = $this->language->get('text_success');

        $this->response->redirect($this->url->link('extension/module/tg_admin', 'user_token=' . $this->session->data['user_token'], true));
    }
    public function clearErrorAdmin()
    {
        $this->load->language('extension/module/tg_admin');

        $file = DIR_LOGS . $this->error_logfile_admin;

        $handle = fopen($file, 'w+');

        fclose($handle);

        $this->session->data['success'] = $this->language->get('text_success');

        $this->response->redirect($this->url->link('extension/module/tg_admin', 'user_token=' . $this->session->data['user_token'], true));
    }

    protected function ErrorTxtToArray($file)
    {

        $file_handle = fopen($file, "r");

        $rows_array = array();

        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            if ($line) {
                $rows_array[] = explode(":::", preg_split('/[\n\r]+/', $line, 1)[0]);
            }

        }
        fclose($file_handle);

        return $rows_array;

    }
    protected function ErrorAdminTxtToArray($file)
    {

        $file_handle = fopen($file, "r");

        $rows_array = array();

        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            if ($line) {
                $rows_array[] = explode(":::", preg_split('/[\n\r]+/', $line, 1)[0]);
            }

        }
        fclose($file_handle);

        return $rows_array;

    }

    protected function  ListAccessRules(){

    }
    public function CreateAccessRule(){

    }
    public function DeleteAccessRule(){

    }
    public function curlPostCloud($action ='https://api.cloudflare.com/client/v4/user/firewall/access_rules/rules'){

        $this->load->language('extension/module/tg_admin');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            if(isset($this->request->post['ip'])){
                $data_to_cloud = json_encode(array(
                    "mode" => "js_challenge",
                    "configuration" => array(
                        "target" => "ip",
                        "value" => $this->request->post['ip']
                    ),
                    "notes" => "Ban user"
                ));
            }else{
                $json['error_ip'] = $this->language->get('error_ip');
            }


            try {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $action);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "X-Auth-Email:" . $this->config->get('module_tg_admin_cloud_mail'),
                    "X-Auth-Key: " . $this->config->get('module_tg_admin_cloud_api'),
                    "Content-Type: application/json"
                ));
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_cloud);
                $out = curl_exec($curl);
                curl_close($curl);
            } catch (Exception $e) {
                $json['error'] = $e->getMessage();
            }

            if(!$json){
                $json['success'] = json_decode($out);
            }

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function curlGetCloud($action){
        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $action);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "X-Auth-Email:" . $this->config->get('module_tg_admin_cloud_mail'),
                "X-Auth-Key: " . $this->config->get('module_tg_admin_cloud_api'),
                "Content-Type: application/json"
            ));
            $out = curl_exec($curl);
            curl_close($curl);
        } catch (Exception $e) {
            return false;
        }
        return $out;
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/tg_admin')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}