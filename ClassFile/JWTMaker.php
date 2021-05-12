<?php
namespace JWT;

class JWTMaker
{
  public $JwtCode;
  protected $Header = array('alg' => 'HS256', 'typ' => 'JWT');
  protected $Payload;
  public $SecretCode;
  public $ErrorMake;

  public function GetId($id)
  {
    $this->Payload = array('id' => $id);
  }

  public function MakeJwt()
  {
    $this->JwtCode = '';
    $this->ErrorMake = '';
    if (!empty($this->Payload)) {

      if (($jsonheader = $this->JsonEncode($this->Header)) && ($jsonpayload = $this->JsonEncode($this->Payload))) {

        $this->Payload = '';

        $base64urlheader = $this->Base64urlEncode($jsonheader);
        $base64urlpayload = $this->Base64urlEncode($jsonpayload);

        if ($signature = $this->HashMac($base64urlheader, $base64urlpayload))
          {
            $base64urlsignature = $this->Base64urlEncode($signature);

            $this->JwtCode = $base64urlheader . '.' . $base64urlpayload . '.' . $base64urlsignature;
             return true;

          } else {
             $this->ErrorMake = "Secret code isn't correct!";
             return false;
          }

      } else {
         $this->Payload = '';
         return false;
      }

    } else {

      $this->ErrorMake = "Payload isn't found!";
      return false;
    }

  }

  private function JsonEncode($jsonen)
  {
    $jsonencode = json_encode($jsonen);

    $this->ErrorMake = json_last_error();
    if ($this->ErrorMake == false) {
      return $jsonencode;
    } else {
       return false;
    }
  }

  private function Base64urlEncode($base64)
  {
    $base64url = str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($base64));
    return $base64url;
  }

  private function HashMac($header, $payload)
  {
    if (($this->SecretCode != '') && (strlen($this->SecretCode) == 32))
      {
        $signature = hash_hmac('sha256', $header . '.' . $payload, $this->SecretCode, true);
        return $signature;
      } else {
        return false;
      }
  }

 }
 ?>
