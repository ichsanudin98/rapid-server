<?php

class UsersApi extends CI_Controller {

  function __construct(){
		parent::__construct();		
    $this->load->model('SessionModel');
    $this->load->model('UsersModel');
    $this->load->model('RolesModel');
    $this->load->model('CompanyModel');
    $this->load->model('OfficeModel');
  }
  
// PHP function to print a  
// random string of length n 
function randomStringGenerator($n) { 
    // Variable which store final string 
    $generated_string = ""; 
      
    // Create a string with the help of  
    // small letters, capital letters and 
    // digits. 
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
      
    // Find the lenght of created string 
    $len = strlen($domain); 
      
    // Loop to create random string 
    for ($i = 0; $i < $n; $i++) 
    { 
        // Generate a random index to pick 
        // characters 
        $index = rand(0, $len - 1); 
          
        // Concatenating the character  
        // in resultant string 
        $generated_string = $generated_string . $domain[$index]; 
    } 
      
    // Return the random generated string 
    return $generated_string; 
  } 
   
  public function login() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["EM"]) || 
          !isset($_POST["PW"])) {
      $response = array(
        'RC' => 1,
        'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
    } else {
      try {
        $timestamp = $_POST["TS"];
        $security_code = $_POST["SC"];
        $email = $_POST["EM"];
        $password = $_POST["PW"];

        $usersModel = $this->UsersModel->getUsersByEmailPassword($email, $password);
        if ($usersModel != null) {
          if ($usersModel->f_activated) {
            $rolesModel = $this->RolesModel->getRolesById($usersModel->role_id);
            $session_id = $this->randomStringGenerator(20);
            if ($this->SessionModel->getSessionByUserID($usersModel->id) != null) {
              // TODO Update session
              $this->SessionModel->update($usersModel->id, $session_id, date('Y-m-d H:i:s', strtotime($timestamp)), date('Y-m-d H:i:s', strtotime($timestamp)));
            } else {
              // TODO Create session
              $this->SessionModel->save($usersModel->id, $session_id, date('Y-m-d H:i:s', strtotime($timestamp)), date('Y-m-d H:i:s', strtotime($timestamp)));
            }
            $response = array(
              'RC' => 0,
              'RM' => '',
              'SD' => $session_id,
              'UD' => $usersModel->id,
              'NM' => $usersModel->name,
              'CD' => $usersModel->company_id,
              'RN' => $rolesModel->name,
              'RD' => $usersModel->role_id);
          } else {
            $response = array(
              'RC' => 1,
              'RM' => 'User belum aktif.');
          }
        } else {
          $response = array(
            'RC' => 1,
            'RM' => 'User tidak ditemukan.');
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

  public function createUpdateAccount() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    if ($_POST["TY"] == 0) {
      if (isset($_POST["OD"])) {
        // TODO Create keeper
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["EM"]) || !isset($_POST["PW"]) || !isset($_POST["NM"]) ||
          !isset($_POST["AR"]) || !isset($_POST["GD"]) || !isset($_POST["PN"]) ||
          !isset($_POST["OD"]) || !isset($_POST["TY"])) {
          $response = array(
            'RC' => 1,
            'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
        } else {
          $timestamp = $_POST["TS"];
          $security_code = $_POST["SC"];
          $session_id = $_POST["SD"];
          $email = $_POST["EM"];
          $password = $_POST["PW"];
          $name = $_POST["NM"];
          $address = $_POST["AR"];
          $gender = $_POST["GD"];
          $phone = $_POST["PN"];
          $office_id = $_POST["OD"];
          $this->UsersModel->save($email, $password, $name, !isset($_POST["IG"]) ? null : $_POST["IG"], $address, $phone, $gender, $office_id, 3, true, date('Y-m-d H:i:s', strtotime($timestamp)));
          $response = array(
            'RC' => 0,
            'RM' => '');
        }
      } else {
        // TODO Create owner
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || 
          !isset($_POST["EM"]) || !isset($_POST["PW"]) || !isset($_POST["NM"]) ||
          !isset($_POST["AR"]) || !isset($_POST["GD"]) || !isset($_POST["PN"]) ||
          !isset($_POST["TY"]) || !isset($_POST["BN"]) || !isset($_POST["BR"]) || 
          !isset($_POST["BP"])) {
          $response = array(
            'RC' => 1,
            'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
        } else {
          $timestamp = $_POST["TS"];
          $security_code = $_POST["SC"];
          $email = $_POST["EM"];
          $password = $_POST["PW"];
          $name = $_POST["NM"];
          $address = $_POST["AR"];
          $gender = $_POST["GD"];
          $phone = $_POST["PN"];
          $type = $_POST["TY"];

          $business_name = $_POST["BN"];
          $business_address = $_POST["BR"];
          $business_phone = $_POST["BP"];
          $this->UsersModel->save($email, $password, $name, !isset($_POST["IG"]) ? null : $_POST["IG"], $address, $phone, $gender, -1, 2, false, date('Y-m-d H:i:s', strtotime($timestamp)));
          $user_id = $this->db->insert_id();
          $this->CompanyModel->save($business_name, $business_address, $business_phone, $user_id, date('Y-m-d H:i:s', strtotime($timestamp)));
          $company_id = $this->db->insert_id();
          $this->UsersModel->update($user_id, $email, $password, $name, !isset($_POST["IG"]) ? null : $_POST["IG"], $address, $phone, $gender, -1, $company_id, 2, false, date('Y-m-d H:i:s', strtotime($timestamp)));
          $response = array(
            'RC' => 0,
            'RM' => '');
        }
      }
    } else {
      if (isset($_POST["OD"]) && $_POST["OD"] != -1) {
        // TODO Change account keeper
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) ||
          !isset($_POST["ID"]) || !isset($_POST["EM"]) || !isset($_POST["PW"]) || 
          !isset($_POST["NM"]) || !isset($_POST["AR"]) || !isset($_POST["GD"]) || 
          !isset($_POST["PN"]) || !isset($_POST["OD"]) || !isset($_POST["TY"])) {
          $response = array(
            'RC' => 1,
            'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
        } else {
          $timestamp = $_POST["TS"];
          $security_code = $_POST["SC"];
          $session_id = $_POST["SD"];
          $user_id = $_POST["ID"];
          $email = $_POST["EM"];
          $password = $_POST["PW"];
          $name = $_POST["NM"];
          $address = $_POST["AR"];
          $gender = $_POST["GD"];
          $phone = $_POST["PN"];
          $office_id = $_POST["OD"];
          $usersModel = $this->UsersModel->getUsersById($user_id);
          if ($usersModel != null) {
            $this->UsersModel->update($user_id, $email, $password, $name, !isset($_POST["IG"]) ? null : $_POST["IG"], $address, $phone, $gender, $office_id, null, 3, true, date('Y-m-d H:i:s', strtotime($timestamp)));
            $response = array(
              'RC' => 0,
              'RM' => '');
          } else {
            $response = array(
              'RC' => 1,
              'RM' => 'User tidak ditemukan.');
          }
        }
      } else {
        // TODO Change account owner
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) ||
          !isset($_POST["EM"]) || !isset($_POST["PW"]) || 
          !isset($_POST["NM"]) || !isset($_POST["AR"]) || !isset($_POST["GD"]) || 
          !isset($_POST["PN"]) || !isset($_POST["OD"]) || !isset($_POST["TY"]) ||
          !isset($_POST["BN"]) || !isset($_POST["BR"]) || !isset($_POST["BP"])) {
          $response = array(
            'RC' => 1,
            'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
        } else {
          $timestamp = $_POST["TS"];
          $security_code = $_POST["SC"];
          $session_id = $_POST["SD"];
          $user_id = $_POST["ID"];
          $email = $_POST["EM"];
          $password = $_POST["PW"];
          $name = $_POST["NM"];
          $address = $_POST["AR"];
          $gender = $_POST["GD"];
          $phone = $_POST["PN"];
          $office_id = $_POST["OD"];

          $business_name = $_POST["BN"];
          $business_address = $_POST["BR"];
          $business_phone = $_POST["BP"];
          $usersModel = $this->UsersModel->getSessionBySessionID($session_id);
          if ($usersModel != null) {
            $companyModel = $this->CompanyModel->getCompanyByUserId($usersModel->id);
            if ($companyModel != null) {
              $this->CompanyModel->update($business_name, $business_address, $business_phone, $user_id, date('Y-m-d H:i:s', strtotime($timestamp)));
            }
            $this->UsersModel->update($user_id, $email, $password, $name, !isset($_POST["IG"]) ? null : $_POST["IG"], $address, $phone, $gender, $office_id, null, 3, true, date('Y-m-d H:i:s', strtotime($timestamp)));
            $response = array(
              'RC' => 0,
              'RM' => '');
          } else {
            $response = array(
              'RC' => 1,
              'RM' => 'User tidak ditemukan.');
          }
        }
      }
    }

    $this->output
      ->set_status_header(200)
      ->set_content_type('application/json', 'utf-8')
      ->set_output(json_encode($response, JSON_PRETTY_PRINT))
      ->_display();
    exit;
  }

  public function updatePassword() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["PN"]) || !isset($_POST["OP"]) || !isset($_POST["NP"])) {
      $response = array(
        'RC' => 1,
        'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
    } else {
      try {
        $timestamp = $_POST["TS"];
        $security_code = $_POST["SC"];
        $session_id = $_POST["SD"];
        $phone_number = $_POST["PN"];
        $old_password = $_POST["OP"];
        $new_password = $_POST["NP"];

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
          $userModel = $this->UsersModel->getUsersByUserIDPhone($sessionModel->user_id, $phone_number);
          if ($userModel != null) {
            if ($userModel->password == $old_password) {
              $this->UsersModel->update($userModel->id, $userModel->email, $new_password, $userModel->name, $userModel->image, $userModel->address, $userModel->phone, $userModel->gender, $userModel->office_id, $userModel->company_id, $userModel->role_id, $userModel->f_activated, date('Y-m-d H:i:s', strtotime($timestamp)));
              $response = array(
                'RC' => 0,
                'RM' => '');
            } else {
              $response = array(
                'RC' => 1,
                'RM' => 'User tidak ditemukan.');
            }
          } else {
            $response = array(
              'RC' => 1,
              'RM' => 'User tidak ditemukan.');
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

  public function forgetPassword() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["EM"]) || 
          !isset($_POST["PN"]) || !isset($_POST["NP"])) {
      $response = array(
        'RC' => 1,
        'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
    } else {
      try {
        $timestamp = $_POST["TS"];
        $security_code = $_POST["SC"];
        $email = $_POST["EM"];
        $phone_number = $_POST["PN"];
        $new_password = $_POST["NP"];

        $userModel = $this->UsersModel->getUsersByEmailPhone($email, $phone_number);
        if ($userModel != null) {
          $this->UsersModel->update($userModel->id, $userModel->email, $new_password, $userModel->name, $userModel->image, $userModel->address, $userModel->phone, $userModel->gender, $userModel->office_id, $userModel->company_id, $userModel->role_id, $userModel->f_activated, date('Y-m-d H:i:s', strtotime($timestamp)));
          $response = array(
            'RC' => 0,
            'RM' => '');
        } else {
          $response = array(
            'RC' => 1,
            'RM' => 'User tidak ditemukan.');
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

  public function deleteProfile() {
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
        $user_id = $_POST["ID"];

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
          $userModel = $this->UsersModel->getUsersById($user_id);
          if ($userModel != null) {
            $this->UsersModel->delete($user_id, date('Y-m-d H:i:s', strtotime($timestamp)));
            $response = array(
              'RC' => 0,
              'RM' => '');
          } else {
            $response = array(
              'RC' => 1,
              'RM' => 'User tidak ditemukan.');
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

  public function readProfile() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $type = $_POST["TY"];
    $response = null;
    
    if ($type == 0 || $type == 2) {
      if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"])) {
        $response = array(
          'RC' => 1,
          'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
      }
    } else if ($type == 1) {
      if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"])) {
        $response = array(
          'RC' => 1,
          'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
      } else {
        $company_id = !isset($_POST["CD"]) ? null : $_POST["CD"];
        $office_id = !isset($_POST["OD"]) ? null : $_POST["OD"];
      }
    }
    
    if ($response == null) {
      try {
        $timestamp = $_POST["TS"];
        $security_code = $_POST["SC"];
        $session_id = $_POST["SD"];
        $userModel = null;

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
          if ($type == 0) {
            $userModel = $this->UsersModel->getUsersById($sessionModel->user_id);
          } else if ($type == 1) {
            if ($company_id != null) {
              $userModel = $this->UsersModel->getAllByCompanyId($company_id);
            } else {
              $userModel = $this->UsersModel->getAllByOfficeId($office_id);
            }
          } else if ($type == 2) {
            $userModel = $this->UsersModel->getAllByOfficeIdNull();
          }

          if ($userModel != null) {
            $list = array();
          
            if (is_array($userModel)) {
              foreach ($userModel as $data) {
                $list[] = array(
                    "ID" => $data->id,
                    "EM" => $data->email,
                    "NM" => $data->name,
                    "AR" => $data->address,
                    "PN" => $data->phone,
                    "GD" => $data->gender,
                    "OD" => $data->office_id,
                    "CD" => $data->company_id,
                    "ON" => $data->office_id != -1 ? $this->OfficeModel->getOfficeById($data->office_id)->name : null,
                    "AD" => $data->f_activated == 0 ? false : true
                );
              }

            } else {
              $list[] = array(
                "ID" => $userModel->id,
                "EM" => $userModel->email,
                "NM" => $userModel->name,
                "AR" => $userModel->address,
                "PN" => $userModel->phone,
                "GD" => $userModel->gender,
                "OD" => $userModel->office_id,
                "CD" => $userModel->company_id,
                "ON" => $userModel->office_id != -1 ? $this->OfficeModel->getOfficeById($userModel->office_id)->name : null,
                "AD" => $userModel->f_activated == 0 ? false : true
            );
            }

            $response = array(
              'RC' => 0,
              'RM' => '',
              'LT' => $list
            );
          } else {
            $response = array(
              'RC' => 1,
              'RM' => 'User tidak ditemukan.');
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

  public function activationProfile() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["ID"]) || !isset($_POST["AD"])) {
      $response = array(
        'RC' => 1,
        'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
    } else {
      try {
        $timestamp = $_POST["TS"];
        $security_code = $_POST["SC"];
        $session_id = $_POST["SD"];
        $user_id = $_POST["ID"];
        $activation = $_POST["AD"];

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
          $userModel = $this->UsersModel->getUsersById($user_id);
          if ($userModel != null) {
            $this->UsersModel->activation($user_id, $activation, date('Y-m-d H:i:s', strtotime($timestamp)));
            $response = array(
              'RC' => 0,
              'RM' => '');
          } else {
            $response = array(
              'RC' => 1,
              'RM' => 'User tidak ditemukan.');
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