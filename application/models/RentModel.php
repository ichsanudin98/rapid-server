<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RentModel extends CI_Model {
    private $_table = "t_rent";

    public $id;
    public $note;
    public $security;
    public $seat_id;
    public $seat_name;
    public $status;
    public $office_id;
    public $company_id;
    public $user_id;
    public $f_deleted;
    public $create_date;
    public $change_date;

    public function getAll() {
        return $this->db->query('SELECT * FROM ' + $_table)->row();
    }

    public function getAllByOfficeIdCompanyId($office_id, $company_id) {
        if ($office_id != -1)
            return $this->db->get_where($this->_table, ["office_id" => $office_id, "f_deleted" => 0])->result();
        else
            return $this->db->get_where($this->_table, ["company_id" => $company_id, "f_deleted" => 0])->result();    
    }
    
    public function getAllByUserId($user_id) {
        return $this->db->get_where($this->_table, ["user_id" => $user_id, "f_deleted" => 0])->row();
    }
    
    public function getRentById($id) {
        return $this->db->get_where($this->_table, ["id" => $id, "f_deleted" => 0])->row();
    }

    public function save($note, $security, $seat_id, $seat_name, $status, $office_id, $company_id, $user_id, $create_date) {
        $this->note = $note;
        $this->security = $security;
        $this->seat_id = $seat_id;
        $this->seat_name = $seat_name;
        $this->status = $status;
        $this->office_id = $office_id;
        $this->company_id = $company_id;
        $this->user_id = $user_id;
        $this->f_deleted = false;
        $this->create_date = $create_date;
        $this->db->insert($this->_table, $this);
    }

    public function update($id, $note, $security, $seat_id, $seat_name, $status, $user_id, $change_date) {
        $this->db->set('note', $note);                
        $this->db->set('security', $security);
        $this->db->set('seat_id', $seat_id);
        $this->db->set('seat_name', $seat_name);
        $this->db->set('status', $status);
        $this->db->set('user_id', $user_id);
        $this->db->set('change_date', $change_date);
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }

    public function delete($id, $change_date) {
        $this->db->set('f_deleted', true);
        $this->db->set('change_date', $change_date);
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }
}

?>