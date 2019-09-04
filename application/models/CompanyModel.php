<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyModel extends CI_Model {
    private $_table = "m_company";

    public $id;
    public $name;
    public $address;
    public $phone;
    public $user_id;
    public $f_deleted;
    public $create_date;
    public $change_date;

    public function getAll() {
        return $this->db->query('SELECT * FROM ' + $_table)->row();
    }

    public function getCompanyByUserId($user_id) {
        return $this->db->get_where($this->_table, ["user_id" => $user_id, "f_deleted" => 0])->row();
    }
    
    public function getCompanyById($id) {
        return $this->db->get_where($this->_table, ["id" => $id, "f_deleted" => 0])->row();
    }

    public function save($name, $address, $phone, $user_id, $create_date) {
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
        $this->user_id = $user_id;
        $this->f_deleted = false;
        $this->create_date = $create_date;
        $this->db->insert($this->_table, $this);
    }

    public function update($id, $name, $address, $phone, $change_date) {
        $this->db->set('name', $name);
        $this->db->set('address', $change_date);
        $this->db->set('phone', $id);
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