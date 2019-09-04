<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OfficeModel extends CI_Model {
    function __construct(){
		parent::__construct();		
        $this->load->model('SeatModel');
    }

    private $_table = "m_office";

    public $id;
    public $name;
    public $image;
    public $address;
    public $phone;
    public $latitude;
    public $longitude;
    public $user_id;
    public $total_seat;
    public $company_id;
    public $category_id;
    public $f_deleted;
    public $create_date;
    public $change_date;

    public function getAll() {
        return $this->db->query('SELECT * FROM ' + $_table)->result();
    }

    public function getAllByCompanyId($company_id) {
        return $this->db->get_where($this->_table, ["company_id" => $company_id, "f_deleted" => 0])->result();
    }

    public function getOfficeByCompanyIdOfficeNameAddress($company_id, $office_name, $office_address, $office_id) {
        if($office_id == null)
            return $this->db->get_where($this->_table, ["company_id" => $company_id, "name" => $office_name, "address" => $office_address, "f_deleted" => 0])->row();
        else
            return $this->db->get_where($this->_table, ["company_id" => $company_id, "name" => $office_name, "address" => $office_address, "id !=" => $office_id, "f_deleted" => 0])->row();
    }
    
    public function getOfficeById($id) {
        return $this->db->get_where($this->_table, ["id" => $id, "f_deleted" => 0])->row();
    }

    public function save($name, $image, $address, $phone, $latitude, $longitude, 
            $user_id, $total_seat, $company_id, $category_id, $create_date) {
        $this->name = $name;
        $this->image = $image;
        $this->address = $address;
        $this->phone = $phone;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->user_id = $user_id;
        $this->total_seat = $total_seat;
        $this->company_id = $company_id;
        $this->category_id = $category_id;
        $this->f_deleted = false;
        $this->create_date = $create_date;
        $this->db->insert($this->_table, $this);
    }

    public function update($id, $name, $image, $address, $phone, $latitude, $longitude, 
            $total_seat, $category_id, $change_date) {
        $this->db->set('name', $name);                
        $this->db->set('image', $image);
        $this->db->set('address', $address);
        $this->db->set('phone', $phone);
        if ($latitude != null)
            $this->db->set('latitude', $latitude);
        else
            $this->db->set('latitude', null);
        if ($longitude != null)
            $this->db->set('longitude', $longitude);
        else
            $this->db->set('longitude', null);
        $size = sizeof($this->SeatModel->getAllByOfficeId($id));
        if ($size != null)    
            $this->db->set('total_seat', $size);
        $this->db->set('category_id', $category_id);
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