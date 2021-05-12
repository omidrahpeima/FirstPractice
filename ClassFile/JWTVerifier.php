<?php
namespace JWT;

use JWT\JWTMaker;

require 'JWTMaker.php';

class JWTVerifier extends JWTMaker
{
  private $Token;
  public $Result;
  public $ErrorVerify;

  public function GetToken($token)
  {
    $this->Token = $token;
  }

  public function VerifyToken()
  {
    $this->Result = '';
    $this->ErrorVerify = '';
    if (!empty($this->Token))
      {
        $this->Header = '';
        $tokenparts = explode('.', $this->Token);

        $this->Token = '';

        if (count($tokenparts) == 3 && (!empty($tokenparts[0])) && (!empty($tokenparts[1])) && (!empty($tokenparts[2])))
          {

            if (($headerjsoncode = $this->Base64urlDecode($tokenparts[0])) && ($payloadjsoncode = $this->Base64urlDecode($tokenparts[1])))
              {
                $signaturereceived = $tokenparts[2];

                if (($header = $this->JsonDecode($headerjsoncode))  && ($payload = $this->JsonDecode($payloadjsoncode)))
                  {
                    // To encode to Verify Token
                    $this->Header = $header;
                    $this->Payload = $payload;
                    JWTMaker::MakeJwt();
                    $tokennew = explode('.', $this->JwtCode);

                    if ($tokennew[2] === $signaturereceived)
                      {
                        $this->Header = array('alg' => 'HS256', 'typ' => 'JWT');
                        $this->Result = $payload;
                        return true;

                      } else {
                         $this->InValid("Invalid due to signature!");
                         return false;
                      }

                  } else {
                     $this->Header = array('alg' => 'HS256', 'typ' => 'JWT');
                     return false;
                  }

              } else {
                 $this->InValid("Invalid due to base64_decode!");
                 return false;
              }

          } else {
             $this->InValid("Not Enough!");
             return false;
          }

      } else {
         $this->InValid("Not Found!");
         return false;
      }
  }

  private function Base64urlDecode($base64)
  {
    if ($jsoncode = base64_decode(str_replace(array('-', '_', ''), array('+', '/', '='), $base64)))
    {
        return $jsoncode;
    } else {
        return false;
    }
  }

  private function JsonDecode($json)
  {
    $array = json_decode($json, true);

    $this->ErrorVerify = json_last_error();
    if ($this->ErrorVerify == false) {
      return $array;
    } else {
      return false;
    }
  }

  private function InValid($value)
  {
    $this->Header = array('alg' => 'HS256', 'typ' => 'JWT');
    $this->ErrorVerify = "Token is " . $value;
  }

}
?>
