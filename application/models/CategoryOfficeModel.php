<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryOfficeModel extends CI_Model {
    private $_table = "m_category_office";

    public $id;
    public $name;
    public $company_id;
    public $f_deleted;
    public $create_date;
    public $change_date;

    public function getAll() {
        return $this->db->query('SELECT * FROM ' + $_table)->result();
    }

    public function getAllByCompany($company_id) {
        return $this->db->get_where($this->_table, ["company_id" => $company_id, "f_deleted" => 0])->result();
    }

    public function getCategoryById($id) {
        return $this->db->get_where($this->_table, ["id" => $id, "f_deleted" => 0])->row();
    }

    public function getCategoryByCompanyCategoryName($company_id, $category_name) {
        return $this->db->get_where($this->_table, ["company_id" => $company_id, "name" => $category_name, "f_deleted" => 0])->row();
    }

    public function save($name, $company_id, $create_date) {
        $this->name = $name;
        $this->company_id = $company_id;
        $this->f_deleted = false;
        $this->create_date = $create_date;
        $this->db->insert($this->_table, $this);
    }

    public function update($id, $name, $change_date) {
        $this->db->set('name', $name);
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