<?php
namespace AuthForms;

class AuthForms
{
  public $InfoPassword;
  public $InfoUser;
  public $InfoName;
  public $InfoEmail;
  public $InfoUrl;

  public function SpecialFilter($primary)
  {
    // \W : symbols and whitespace
    if (preg_match("#\W+#", $primary)) {
      return true;
    } else {
       return false;
    }
  }

  public function AuthMain($auth)
  {
    $auth = trim($auth);                                  // strips unnecessary characters (extra space, tab, newline)
    while (strpos($auth, "\\") !== false)
      {
        $auth = stripslashes($auth);                          // Removes backslashes (\)
      }
    $auth = htmlspecialchars($auth, ENT_QUOTES);          // Converts speccial chars to HTML entities
    return $auth;
  }

  public function AuthPassword($password)
  {
    $this->InfoPassword = "";
    $password = $this->AuthMain($password);
      if (!preg_match("#.*^(?=.{8,15})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $password) || $this->SpecialFilter($password))
        {
          $this->InfoPassword = false;
          return $password;
        } else {
           $this->InfoPassword = true;
           return $password;
         }
      }

  public function AuthUser($user)
  {
    $this->InfoUser = "";
    $user = $this->AuthMain($user);
      if (!preg_match("/^[a-zA-Z0-9]*$/", $user) || $this->SpecialFilter($password))
        {
          $this->InfoUser = false;
          return $user;
        } else {
           $this->InfoUser = true;
           return $user;
        }
  }

  public function AuthName($name)
  {
    $this->InfoName = "";
    $name = $this->AuthMain($name);
      if (!preg_match("/^[a-zA-Z]*$/", $name))
        {
          $this->InfoName = false;
          return $name;
        } else {
           $this->InfoName = true;
           return $name;
        }
  }

  public function AuthEmail($email)
  {
    $this->InfoEmail = "";
    $email = $this->AuthMain($email);
      if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
          $this->InfoEmail = false;
          return $email;
        } else {
           $this->InfoEmail = true;
           return $email;
        }
  }

  public function AuthUrl($url)
  {
    $this->InfoUrl = "";
    $url = $this->AuthMain($url);
      if (!filter_var($url, FILTER_VALIDATE_URL))
        {
          $this->InfoUrl = false;
          return $url;
        } else {
           $this->InfoUrl = true;
           return $url;
        }
  }

  public function AuthGender($gender)
  {
    $gender = $this->AuthMain($gender);
    return $gender;
  }

}
?>
