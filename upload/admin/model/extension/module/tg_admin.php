<?php
class ModelExtensionModuleTgAdmin extends Model {

    public function getUsers(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tg_users");
        return $query->rows;
    }

}