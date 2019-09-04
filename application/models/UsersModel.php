<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersModel extends CI_Model {
    function __construct(){
		parent::__construct();		
        $this->load->model('OfficeModel');
    }

    private $_table = "m_users";

    public $id;
    public $email;
    public $password;
    public $name;
    public $image;
    public $address;
    public $phone;
    public $gender;
    public $office_id;
    public $company_id;
    public $role_id;
    public $f_activated;
    public $f_deleted;
    public $create_date;
    public $change_date;

    public function getAll() {
        return $this->db->query('SELECT * FROM ' + $_table)->result();
    }

    public function getAllNotActivated() {
        return $this->db->get_where($this->_table, ["f_activated" => false, "f_deleted" => 0])->result();
    }

    public function getAllByCompanyId($company_id) {
        return $this->db->get_where($this->_table, ["company_id" => $company_id, "f_deleted" => 0])->result();
    }

    public function getAllByOfficeId($office_id) {
        return $this->db->get_where($this->_table, ["office_id" => $office_id, "f_deleted" => 0])->result();
    }

    public function getAllByOfficeIdNull() {
        return $this->db->get_where($this->_table, ["office_id" => -1, "role_id !=" => 1, "f_deleted" => 0])->result();
    }
    
    public function getUsersById($id) {
        return $this->db->get_where($this->_table, ["id" => $id, "f_deleted" => 0])->row();
    }

    public function getUsersByEmailPassword($email, $password) {
        return $this->db->get_where($this->_table, ["email" => $email, "password" => $password, "f_deleted" => 0])->row();
    }

    public function getUsersByUserIDPhone($user_id, $phone) {
        return $this->db->get_where($this->_table, ["id" => $user_id, "phone" => $phone, "f_deleted" => 0])->row();
    }

    public function getUsersByEmailPhone($email, $phone) {
        return $this->db->get_where($this->_table, ["email" => $email, "phone" => $phone, "f_deleted" => 0])->row();
    }

    public function save($email, $password, $name, $image, $address, $phone, $gender, $office_id, $role_id, $f_activated, $create_date) {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->image = $image;
        $this->address = $address;
        $this->phone = $phone;
        $this->gender = $gender;
        if ($office_id != -1) {
            $this->office_id = $office_id;
            $this->company_id = $this->OfficeModel->getOfficeById($office_id)->company_id;
        } else {
            $this->office_id = -1;
            $this->company_id = -1;
        }
        $this->role_id = $role_id;
        $this->f_activated = $f_activated;
        $this->f_deleted = false;
        $this->create_date = $create_date;
        $this->db->insert($this->_table, $this);
    }

    public function update($id, $email, $password, $name, $image, $address, $phone, $gender, $office_id, $company_id, $role_id, $f_activated, $change_date) {
        $this->db->set('email', $email);                
        $this->db->set('password', $password);                
        $this->db->set('name', $name);                
        $this->db->set('image', $image); 
        $this->db->set('address', $address);
        $this->db->set('phone', $phone);                
        $this->db->set('gender', $gender);                
        $this->db->set('office_id', $office_id);   
        if ($office_id != -1) {
            $this->db->set('company_id', $this->OfficeModel->getOfficeById($office_id)->company_id);
        } else {
            if ($company_id != -1)
                $this->db->set('company_id', $company_id);
            else
                $this->db->set('company_id', -1);
        }
        $this->db->set('role_id', $role_id);
        $this->db->set('f_activated', $f_activated);                
        $this->db->set('change_date', $change_date);                
        $this->db->where('id', $id);
        $this->db->update($this->_table);
    }

    public function activation($id, $f_activated, $change_date) {
        $this->db->set('f_activated', $f_activated);
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