<?php

class OfficeApi extends CI_Controller {

  function __construct(){
		parent::__construct();		
    $this->load->model('SessionModel');
    $this->load->model('UsersModel');
    $this->load->model('OfficeModel');
    $this->load->model('CategoryOfficeModel');
  }

  public function readOffice() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"])) {
      $response = array(
        'RC' => 1,
        'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
    } else {
      try {
        $timestamp = $_POST["TS"];
        $security_code = $_POST["SC"];
        $session_id = $_POST["SD"];

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
            $userModel = $this->UsersModel->getUsersById($sessionModel->user_id);
            $officeModel = $this->OfficeModel->getAllByCompanyId($userModel->company_id);
            if (sizeof($officeModel) > 0) {
                // TODO Belum dibuat untuk respond list
                $list = array();
                foreach ($officeModel as $data) {
                  $list[] = array(
                      "ID" => $data->id,
                      "NM" => $data->name,
                      "IG" => null,
                      "OD" => $data->address,
                      "ON" => $data->phone,
                      "OT" => $data->latitude,
                      "OG" => $data->longitude,
                      "LS" => $data->total_seat,
                      "CD" => $data->category_id,
                      "CN" => $this->CategoryOfficeModel->getCategoryById($data->category_id) != null ? $this->CategoryOfficeModel->getCategoryById($data->category_id)->name : null,
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

  public function deleteOffice() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    
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
        $office_id = $_POST["ID"];

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
            $officeModel = $this->OfficeModel->getOfficeById($office_id);
            if ($officeModel != null) {
                $this->OfficeModel->delete($office_id, date('Y-m-d H:i:s', strtotime($timestamp)));
                $response = array(
                'RC' => 0,
                'RM' => '');
            } else {
                $response = array(
                'RC' => 1,
                'RM' => 'Office tidak ditemukan.');
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

  public function createUpdateOffice() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $response = null;

    if ($_POST["TY"] == 0) {
        // TODO Create office
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["NM"]) || !isset($_POST["TY"])) {
            $response = array(
                'RC' => 1,
                'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
        }
    } else {
        // TODO Update office
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["ID"]) || !isset($_POST["NM"]) || !isset($_POST["TY"])) {
            $response = array(
                'RC' => 1,
                'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
        }
    }

    if($response == null) {
        try {
            $timestamp = $_POST["TS"];
            $security_code = $_POST["SC"];
            $session_id = $_POST["SD"];
            $office_name = $_POST["NM"];
            if (isset($_POST["IG"]))
              $office_image = $_POST["IG"];
            else
              $office_image = null;
            $office_address = $_POST["OD"];
            $office_phone = $_POST["ON"];
            if (isset($_POST["OT"]))
              $office_latitude = $_POST["OT"];
            else
              $office_latitude = null;
            if (isset($_POST["OG"]))
              $office_longitude = $_POST["OG"];
            else
              $office_longitude = null;
            if (isset($_POST["LS"]))
              $office_total_seat = $_POST["LS"];
            else
              $office_total_seat = null;
            $office_category_id = $_POST["CD"];
            $type = $_POST["TY"];
    
            $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
            if ($sessionModel != null) {
                $userModel = $this->UsersModel->getUsersById($sessionModel->user_id);
                if ($type == 0) {
                    // TODO Create office
                    if ($userModel != null) {
                        $officeModel = $this->OfficeModel->getOfficeByCompanyIdOfficeNameAddress($userModel->company_id, $office_name, $office_address, null);
                        if ($officeModel == null) {
                            $this->OfficeModel->save($office_name, $office_image, $office_address, $office_phone, $office_latitude, $office_longitude, $userModel->id, $office_total_seat, $userModel->company_id, $office_category_id, date('Y-m-d H:i:s', strtotime($timestamp)));
                            $response = array(
                            'RC' => 0,
                            'RM' => '');
                        } else {
                            $response = array(
                            'RC' => 1,
                            'RM' => 'Office sudah ada.');
                        }
                    } else {
                        $response = array(
                            'RC' => 1,
                            'RM' => 'User tidak ditemukan.');
                    }
                } else {
                    // TODO Update office
                    $office_id = $_POST["ID"];
                    $officeModel = $this->OfficeModel->getOfficeById($office_id);
                    if ($officeModel != null) {
                        $office2Model = $this->OfficeModel->getOfficeByCompanyIdOfficeNameAddress($userModel->company_id, $office_name, $office_address, $office_id);
                        if ($office2Model == null) {
                            $this->OfficeModel->update($office_id, $office_name, $office_image, $office_address, $office_phone, $office_latitude, $office_longitude, $office_total_seat, $office_category_id, date('Y-m-d H:i:s', strtotime($timestamp)));
                            $response = array(
                            'RC' => 0,
                            'RM' => '');
                        } else {
                            $response = array(
                            'RC' => 1,
                            'RM' => 'Office sudah ada.');
                        }
                    } else {
                        $response = array(
                        'RC' => 1,
                        'RM' => 'Office tidak ditemukan.');
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