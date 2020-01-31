<?php
class ModelExtensionModuleTgAdmin extends Model {

    public function getUsers(){
        $query = $this->db->query("SELECT otu.user_id, otu.first_name, otu.last_name, otu.username, otu.date_add, otu.telephone,otc.chat_id FROM " . DB_PREFIX . "tg_users otu JOIN " . DB_PREFIX . "tg_chats otc ON otu.user_id = otc.user_id");

        return $query->rows;
    }

}