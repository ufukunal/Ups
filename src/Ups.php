<?php

namespace KS\Ups;
use KS\Ups\UpsException;
use SoapClient;
use SOAPHeader;

class Ups {

  private $url = 'http://ws.ups.com.tr/wsCreateShipment/wsCreateShipment.asmx?wsdl';
  private $session_id;
  private $client;
  private $conf;
  function __construct($conf){
    if(!isset($conf['customer_id']) || !isset($conf['username']) || !isset($conf['password'])) {
      throw new UpsException("Ups Kargo Ayarları Girilmedi");
    }

    $this->conf = $conf;

    if(is_null($this->session_id)){
      $this->login();
    }

  }

  private function login(){

    try {
    $this->client = new SoapClient($this->url);

    $login_data = $this->client->Login_Type1(
            array(
                "CustomerNumber" => $this->conf['customer_id'],
                "UserName" => $this->conf['username'],
                "Password" => $this->conf['password']
            )
        );
        if(empty($login_data->Login_Type1Result->ErrorCode) && empty($login_data->Login_Type1Result->ErrorDefinition)){
          $this->session_id = $login_data->Login_Type1Result->SessionID;
          return;
        } else {
          throw new UpsException("Kullanıcı adı ve şifrenizi kontrol edin!");
        }
      } catch (SoapFault $sf) {
        throw new UpsException($sf);
      }
  }

  function CreateShipment($params){

    try {

      $CreateShipmentData = $this->client->CreateShipment_Type3(
        array("SessionID" => $this->$session_id,
              "ShipmentInfo" => $params,
              "ReturnLabelLink" => true,
              "ReturnLabelImage" => true));

      if(empty($CreateShipmentData->CreateShipment_Type3Result->ErrorCode) && empty($CreateShipmentData->CreateShipment_Type3Result->ErrorDefinition)){
        return $CreateShipmentData->CreateShipment_Type3Result;
      } else {
        throw new UpsException("Gönderdiğiniz parametreleri kontrol ediniz!");
      }

    } catch (SoapFault $sf) {
      throw new UpsException($sf);
    }
  }
}
