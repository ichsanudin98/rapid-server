<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SessionModel extends CI_Model {
    private $_table = "t_session";

    public $user_id;
    public $session_id;
    public $time_active;
    public $f_deleted;
    public $create_date;
    public $change_date;

    public function getAll() {
        return $this->db->query('SELECT * FROM ' + $_table)->row();
    }

    public function getSessionByUserID($user_id) {
        return $this->db->get_where($this->_table, ["user_id" => $user_id, "f_deleted" => 0])->row();
    } 

    public function getSessionBySessionID($session_id) {
        return $this->db->get_where($this->_table, ["session_id" => $session_id, "f_deleted" => 0])->row();
    }

    public function save($user_id, $session_id, $time_active, $create_date) {
        $this->user_id = $user_id;
        $this->session_id = $session_id;
        $this->time_active = $time_active;
        $this->f_deleted = false;
        $this->create_date = $create_date;
        $this->db->insert($this->_table, $this);
    }

    public function update($user_id, $session_id, $time_active, $change_date) {
        $this->db->set('session_id', $session_id);                
        $this->db->set('time_active', $time_active);                
        $this->db->set('change_date', $change_date);
        $this->db->where('user_id', $user_id);
        $this->db->update($this->_table);
    }

    public function delete($id, $change_date) {
        $this->db->set('f_deleted', true);
        $this->db->set('change_date', $change_date);
        $this->db->where('user_id', $id);
        $this->db->update($this->_table);
    }
}

?>