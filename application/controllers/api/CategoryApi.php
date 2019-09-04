<?php

class CategoryApi extends CI_Controller {

  function __construct(){
		parent::__construct();		
    $this->load->model('SessionModel');
    $this->load->model('UsersModel');
    $this->load->model('OfficeModel');
    $this->load->model('CategoryOfficeModel');
  }

  public function readCategory() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $response = null;
    
    if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["CD"])) {
      $response = array(
        'RC' => 1,
        'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
    } else {
      try {
        $timestamp = $_POST["TS"];
        $security_code = $_POST["SC"];
        $session_id = $_POST["SD"];
        $company_id = $_POST["CD"];

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
            $categoryModel = $this->CategoryOfficeModel->getAllByCompany($company_id);
            if (sizeof($categoryModel) > 0) {
                $list = array();
                foreach ($categoryModel as $data) {
                  $list[] = array(
                      "ID" => $data->id,
                      "NM" => $data->name,
                  );
                }
                $response = array(
                  'RC' => 0,
                  'RM' => '',
                  'LT' => $list
                );
            } else {
                $response = array(
                    'RC' => 0,
                    'RM' => 'Tidak ada data.');
            }
        } else {
          $response = array(
            'RC' => 2,
            'RM' => 'Session tidak ditemukan.');
        }
      } catch (Exception $e) { 
        $response = array(
          'RC' => 1,
          'RM' => $e->getMessage());
      }
    }

    $this->output
      ->set_status_header(200)
      ->set_content_type('application/json', 'utf-8')
      ->set_output(json_encode($response, JSON_PRETTY_PRINT))
      ->_display();
    exit;
  }

  public function deleteCategory() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $response = null;
    
    if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["ID"])) {
      $response = array(
        'RC' => 1,
        'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
    } else {
      try {
        $timestamp = $_POST["TS"];
        $security_code = $_POST["SC"];
        $session_id = $_POST["SD"];
        $category_id = $_POST["ID"];

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
            $categoryOfficeModel = $this->CategoryOfficeModel->getCategoryById($category_id);
            if ($categoryOfficeModel != null) {
                $this->CategoryOfficeModel->delete($category_id, date('Y-m-d H:i:s', strtotime($timestamp)));
                $response = array(
                'RC' => 0,
                'RM' => '');
            } else {
                $response = array(
                'RC' => 1,
                'RM' => 'Kategori tidak ditemukan.');
            }
        } else {
          $response = array(
            'RC' => 2,
            'RM' => 'Session tidak ditemukan.');
        }
      } catch (Exception $e) { 
        $response = array(
          'RC' => 1,
          'RM' => $e->getMessage());
      }
    }

    $this->output
      ->set_status_header(200)
      ->set_content_type('application/json', 'utf-8')
      ->set_output(json_encode($response, JSON_PRETTY_PRINT))
      ->_display();
    exit;
  }

  public function createUpdateCategory() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $response = null;
    if ($_POST["TY"] == 0) {
        // TODO Create category
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["NM"]) || !isset($_POST["TY"])) {
            $response = array(
                'RC' => 1,
                'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
        }
    } else {
        // TODO Update category
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["ID"]) || !isset($_POST["NM"]) || !isset($_POST["TY"])) {
            $response = array(
                'RC' => 1,
                'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
        }
    }

    if ($response == null) {
        try {
            $timestamp = $_POST["TS"];
            $security_code = $_POST["SC"];
            $session_id = $_POST["SD"];
            $category_name = $_POST["NM"];
            $type = $_POST["TY"];
    
            $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
            if ($sessionModel != null) {
                $userModel = $this->UsersModel->getUsersById($sessionModel->user_id);
                if ($type == 0) {
                    // TODO Create category
                    if ($userModel != null) {
                        $categoryOfficeModel = $this->CategoryOfficeModel->getCategoryByCompanyCategoryName($userModel->company_id, $category_name);
                        if ($categoryOfficeModel == null) {
                            $this->CategoryOfficeModel->save($category_name, $userModel->company_id, date('Y-m-d H:i:s', strtotime($timestamp)));
                            $response = array(
                            'RC' => 0,
                            'RM' => '');
                        } else {
                            $response = array(
                            'RC' => 1,
                            'RM' => 'Kategori sudah ada.');
                        }
                    } else {
                        $response = array(
                            'RC' => 1,
                            'RM' => 'User tidak ditemukan.');
                    }
                } else {
                    // TODO Update category
                    $category_id = $_POST["ID"];
                    $categoryOfficeModel = $this->CategoryOfficeModel->getCategoryById($category_id);
                    if ($categoryOfficeModel != null) {
                        $categoryOffice2Model = $this->CategoryOfficeModel->getCategoryByCompanyCategoryName($userModel->company_id, $category_name);
                        if ($categoryOffice2Model == null) {
                            $this->CategoryOfficeModel->update($category_id, $category_name, date('Y-m-d H:i:s', strtotime($timestamp)));
                            $response = array(
                            'RC' => 0,
                            'RM' => '');
                        } else {
                            $response = array(
                            'RC' => 1,
                            'RM' => 'Kategori sudah ada.');
                        }
                    } else {
                        $response = array(
                        'RC' => 1,
                        'RM' => 'Kategori tidak ditemukan.');
                    }
                }
            } else {
                $response = array(
                    'RC' => 2,
                    'RM' => 'Session tidak ditemukan.');
            }
        } catch (Exception $e) { 
            $response = array(
                'RC' => 1,
                'RM' => $e->getMessage());
        }
    }

    $this->output
      ->set_status_header(200)
      ->set_content_type('application/json', 'utf-8')
      ->set_output(json_encode($response, JSON_PRETTY_PRINT))
      ->_display();
    exit;
  }
}
?>