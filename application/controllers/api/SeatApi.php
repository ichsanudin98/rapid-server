<?php

class SeatApi extends CI_Controller {

  function __construct(){
		parent::__construct();		
    $this->load->model('SessionModel');
    $this->load->model('UsersModel');
    $this->load->model('SeatModel');
    $this->load->model('RentModel');
  }

  public function readSeat() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $response = null;
    
    if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) ||
        !isset($_POST["TY"])) {
      $response = array(
        'RC' => 1,
        'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
    } else {
      try {
        $timestamp = $_POST["TS"];
        $security_code = $_POST["SC"];
        $session_id = $_POST["SD"];
        $type = $_POST["TY"];

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
            $userModel = $this->UsersModel->getUsersById($sessionModel->user_id);
            if($userModel != null) {
              if($type == 0) {
                $seatModel = $this->SeatModel->getAllByOfficeIdCompanyId($userModel->office_id, $userModel->company_id);
                if (sizeof($seatModel) > 0) {
                    $list = array();
                    foreach ($seatModel as $data) {
                      $list[] = array(
                          "ID" => $data->id,
                          "NM" => $data->name,
                          "OD" => $data->office_id,
                          "ST" => $data->status == 0 ? false : true,
                          "NT" => $data->note,
                          "KP" => $data->security
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
                $rentModel = $this->RentModel->getAllByOfficeIdCompanyId($userModel->office_id, $userModel->company_id);
                if (sizeof($rentModel) > 0) {
                    $list = array();
                    foreach ($rentModel as $data) {
                      $list[] = array(
                          "ID" => $data->id,
                          "NM" => $data->seat_name,
                          "OD" => $data->office_id,
                          "ST" => $data->status == 0 ? false : true,
                          "NT" => $data->note,
                          "KP" => $data->security
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
              }
            } else {
                $response = array(
                    'RC' => 0,
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

  public function deleteSeat() {
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
        $seat_id = $_POST["ID"];

        $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
        if ($sessionModel != null) {
            $seatModel = $this->SeatModel->getSeatById($seat_id);
            if ($seatModel != null) {
                $this->SeatModel->delete($seat_id, date('Y-m-d H:i:s', strtotime($timestamp)));
                $response = array(
                'RC' => 0,
                'RM' => '');
            } else {
                $response = array(
                'RC' => 1,
                'RM' => 'Seat tidak ditemukan.');
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

  public function createUpdateSeat() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $response = null;

    if ($_POST["TY"] == 0) {
        // TODO Create seat
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["NM"]) || !isset($_POST["OD"]) || !isset($_POST["ST"]) ||
          !isset($_POST["TY"])) {
            $response = array(
                'RC' => 1,
                'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
        }
    } else {
        // TODO Update seat
        if (!isset($_POST["TS"]) || !isset($_POST["SC"]) || !isset($_POST["SD"]) || 
          !isset($_POST["ID"]) || !isset($_POST["NM"]) || !isset($_POST["OD"]) || 
          !isset($_POST["ST"]) || !isset($_POST["TY"])) {
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
            $seat_name = $_POST["NM"];
            $office_id = $_POST["OD"];
            $seat_status = $_POST["ST"];
            $type = $_POST["TY"];
    
            $sessionModel = $this->SessionModel->getSessionBySessionID($session_id);
            if ($sessionModel != null) {
                $userModel = $this->UsersModel->getUsersById($sessionModel->user_id);
                if ($type == 0) {
                    // TODO Create seat
                    if ($userModel != null) {
                        $seatModel = $this->SeatModel->getSeatByOfficeIdSeatName($userModel->office_id, null, $seat_name);
                        if ($seatModel == null) {
                            $this->SeatModel->save($seat_name, $office_id, $userModel->company_id, $seat_status, date('Y-m-d H:i:s', strtotime($timestamp)));
                            $response = array(
                            'RC' => 0,
                            'RM' => '');
                        } else {
                            $response = array(
                            'RC' => 1,
                            'RM' => 'Seat sudah ada.');
                        }
                    } else {
                        $response = array(
                            'RC' => 1,
                            'RM' => 'User tidak ditemukan.');
                    }
                } else {
                  if($type == 1) {
                    // TODO Update seat
                    $seat_id = $_POST["ID"];
                    $seatModel = $this->SeatModel->getSeatById($seat_id);
                    if ($seatModel != null) {
                        $seat2Model = $this->SeatModel->getSeatByOfficeIdSeatName($userModel->office_id, $seat_id, $seat_name);
                        if ($seat2Model == null) {
                            $this->SeatModel->update($seat_id, $seat_name, $office_id, $seat_status, $seatModel->note, $seatModel->security, date('Y-m-d H:i:s', strtotime($timestamp)));
                            $response = array(
                            'RC' => 0,
                            'RM' => '');
                        } else {
                            $response = array(
                            'RC' => 1,
                            'RM' => 'Seat sudah ada.');
                        }
                    } else {
                        $response = array(
                        'RC' => 1,
                        'RM' => 'Seat tidak ditemukan.');
                    }
                  } else {
                    // TODO Update for reserve
                    $seat_id = $_POST["ID"];

                    if (!isset($_POST["NT"]) || !isset($_POST["KP"])) {
                        $response = array(
                            'RC' => 1,
                            'RM' => 'Terdapat parameter yang kurang, silahkan hubungi admin.');
                    } else {
                      $note = $_POST["NT"];
                      $keypass = $_POST["KP"];

                      $seatModel = $this->SeatModel->getSeatById($seat_id);
                      if ($seatModel != null) {
                          $seat2Model = $this->SeatModel->getSeatByOfficeIdSeatName($userModel->office_id, $seat_id, $seat_name);
                          if ($seat2Model == null) {
                            if ($seatModel->security != null) {
                              if($seatModel->security == $keypass) {
                                $this->SeatModel->update($seat_id, $seat_name, $office_id, $seat_status, null, null, date('Y-m-d H:i:s', strtotime($timestamp)));
                                $this->RentModel->save($note, $keypass, $seat_id, $seat_name, $seat_status, $office_id, $userModel->company_id, $userModel->id, date('Y-m-d H:i:s', strtotime($timestamp)));
                                $response = array(
                                'RC' => 0,
                                'RM' => '');
                              } else {
                                $response = array(
                                  'RC' => 1,
                                  'RM' => 'Sandi tidak sesuai.');
                              }
                            } else {
                              $this->SeatModel->update($seat_id, $seat_name, $office_id, $seat_status, $note, $keypass, date('Y-m-d H:i:s', strtotime($timestamp)));
                              $this->RentModel->save($note, $keypass, $seat_id, $seat_name, $seat_status, $office_id, $userModel->company_id, $userModel->id, date('Y-m-d H:i:s', strtotime($timestamp)));
                              $response = array(
                              'RC' => 0,
                              'RM' => '');
                            }
                          } else {
                              $response = array(
                              'RC' => 1,
                              'RM' => 'Seat sudah ada.');
                          }
                      } else {
                          $response = array(
                          'RC' => 1,
                          'RM' => 'Seat tidak ditemukan.');
                      }
                    }
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