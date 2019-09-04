<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SeatModel extends CI_Model {
    private $_table = "m_seat";

    public $id;
    public $name;
    public $office_id;
    public $company_id;
    public $status;
    public $note;
    public $security;
    public $f_deleted;
    public $create_date;
    public $change_date;

    public function getAll() {
        return $this->db->querystar('SELECT * FROM ' + $_table)->result();
    }

    public function getAllByOfficeIdCompanyId($office_id, $company_id) {
        if ($office_id != -1)
            return $this->db->get_where($this->_table, ["office_id" => $office_id, "f_deleted" => 0])->result();
        else
            return $this->db->get_where($this->_table, ["company_id" => $company_id, "f_deleted" => 0])->result();    
    }

    public function getSeatByOfficeIdSeatName($office_id, $seat_id, $seat_name) {
        if ($seat_id == null)
            return $this->db->get_where($this->_table, ["office_id" => $office_id, "name" => $seat_name, "f_deleted" => 0])->row();
        else
            return $this->db->get_where($this->_table, ["office_id" => $office_id, "name" => $seat_name, "id !=" => $seat_id, "f_deleted" => 0])->row();
    }
    
    public function getSeatById($id) {
        return $this->db->get_where($this->_table, ["id" => $id, "f_deleted" => 0])->row();
    }

    public function save($name, $office_id, $company_id, $status, $create_date) {
        $this->name = $name;
        $this->office_id = $office_id;
        $this->company_id = $company_id;
        $this->status = $status;
        $this->f_deleted = false;
        $this->create_date = $create_date;
        $this->db->insert($this->_table, $this);
    }

    public function update($id, $name, $office_id, $status, $note, $security, $change_date) {
        $this->db->set('name', $name);                
        $this->db->set('office_id', $office_id);    
        $this->db->set('status', $status);
        $this->db->set('note', $note);
        $this->db->set('security', $security);      
        $this->db->set('change_date', $change_date);
        $this->db->where('id', $id);
        $this->db->update($this->_table,);
    }

    public function delete($id, $change_date) {
        $this->db->set('f_deleted', true);
        $this->db->set('change_date', $change_date);
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }
}

?>