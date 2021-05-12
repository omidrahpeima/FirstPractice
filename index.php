<?php
use AuthForms\AuthForms;

use JWT\JWTVerifier;
use JWT\JWTMaker;

/*session_start();
echo "SERVER: <br><br>";
print_r($_SERVER);
echo "<br><br>SESSION: <br><br>";
print_r($_SESSION);
echo "<br><br>COOKIE: <br><br>";
print_r($_COOKIE);
*/

// recording traffic of website in the file
$agent = $_SERVER['HTTP_USER_AGENT'];                // The type of browsers
$uri = $_SERVER['REQUEST_URI'];                      // URL in user's page
$ip = $_SERVER['REMOTE_ADDR'];                       // IP of user

if (isset( $_SERVER['PHP_AUTH_USER']))
  {
    $user = $_SERVER['PHP_AUTH_USER'];
  } else {
    $user = "None";
  }

if (isset($_SERVER['HTTP_REFERER']))
  {
    $ref = $_SERVER['HTTP_REFERER'];
  } else {
    $ref = "None";
  }

date_default_timezone_set("Asia/Tbilisi");
$visit_time = date("l jS \of F Y h:i:s A", time());

$traffic = "Date: " . $visit_time . " | IP: " . $ip . " | User: " . $user . " | URI: " . $uri . " | Agant: "
 . $agent . " | Ref: " . $ref . "\n";


$file = fopen("NeededFiles/SiteTraffic.txt", "a");
fputs($file, $traffic);
fclose($file);

// << Check token cookie for entering >>

if (isset($_COOKIE['MyJwtCookie']) && !empty($_COOKIE['MyJwtCookie']))
  {

    require 'ClassFile/JWTVerifier.php';

    $token = htmlspecialchars($_COOKIE['MyJwtCookie'], ENT_QUOTES);

    $jwtcode =  new JWTVerifier();
    $jwtcode->GetToken($token);
    $secret = "YlTIqoj2+sws14ss6P6imeVHPO4KZni4";
    $jwtcode->SecretCode = $secret;
    if ($jwtcode->VerifyToken())
      {
        $id = $jwtcode->Result['id'];
        // This is an id that we can use for our Database
      } else {
        // To go to sing in page
        //header("location: index.php");
        //exit;
      }

  } else {

    // << Set new token cookie after sign in >>

    require 'ClassFile/JWTMaker.php';
    $jwtcode =  new JWTMaker();
    $secret = "YlTIqoj2+sws14ss6P6imeVHPO4KZni4";
    $jwtcode->SecretCode = $secret;
    $jwtcode->GetId('mary');                       // mary is an id from my database after verifing with password
    if ($jwtcode->MakeJwt())
      {
        $token = $jwtcode->JwtCode;
        setcookie('MyJwtCookie', $token, time()+3600, '/', "localhost", false, true);
      } else {
        echo $error = $jwtcode->ErrorMake;

      }

    // << //Set new token cookie after sign in >>

  }

// << //Check token cookie for my website >>


//Important: after creating a session, HTTP_COOKIE is created automatically and after useing destroy() for session, it will exist
  //$_SESSION['MySite'] = 'mycode';

// << logout >>

//session_start();
//session_destroy();

// We can use the following session to remove global session variable (use it before destroy())
//session_unset();

// << //Logout >>

?>
<?php
header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
//Without any output

header("Content-type: text/html; charset:UTF-8");

//The following 5 code sentences is for muke sure a page is never cached with PHP
$tcache = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: " . $tcache);
header("Last-Modified: " . $tcache);
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

date_default_timezone_set("Asia/Tbilisi");

// for checking
//print_-r($_COOKIE);
?>
<?php
// for checking
//print_r($_FILES);
//print_r($_POST);
// In php.ini,set file_uploads=On and chage max_file_uploads=20

$nameErr = $passwordErr = $genderErr = $emailErr = $websiteErr = $check_image= "";
$name = $password = $gender = $email = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    //use AuthForms\AuthForms;     // Must be at first of pages
    require 'ClassFile/AuthForms.php';

    $auth = new AuthForms();

    if (!empty($_POST["Firstname"]))
      {
        $name = $auth->AuthName($_POST["Firstname"]);
        if ($auth->InfoName == 1)
          {
            $nameErr = "Name is valid!";
          } else {
            $nameErr = "Name is invalid!";
          }
      } else {
        $nameErr = "Name is required!";
      }

    if (!empty($_POST["password"]))
      {
        $auth->AuthPassword($_POST["password"]);
        if ($auth->InfoPassword == 1)
          {
            $passwordErr = "Password is valid!";

            // Hash is for setting password in database
            $passwordHASH = password_hash($password, PASSWORD_DEFAULT);
          } else {
            $passwordErr = "Password is invalid!";
          }
      } else {
        $passwordErr = "Password is required!";
      }

    // Notice: before setting value into radio buttons, its array key is not created
    // print_r($_POST);
    if (!empty($_POST["gender"]))
      {
        $gender = $auth->AuthGender($_POST["gender"]);
      } else {
        $genderErr = "Gender is required!";
      }

    if (!empty($_POST["email"]))
      {
        $email = $auth->AuthEmail($_POST["email"]);
        if ($auth->InfoEmail == 1)
          {
            $emailErr = "Email is valid!";
          } else {
            $emailErr = "Email is invalid!";
          }
      } else {
         $emailErr = "Email is required!";
      }

    if (!empty($_POST["website"]))
      {
        $website = $auth->AuthUrl($_POST["website"]);
        if ($auth->InfoUrl == 1)
          {
            $websiteErr = "Website is valid!";
          } else {
            $websiteErr = "Website is invalid!";
          }
      } else {
        $websiteErr = "Website is required!";
      }


    if (!empty($_FILES['file']['name']))
      {
        $dir = "FileUploads/";
        $filedir = $dir . basename($_FILES['file']['name']);
        $uploadok = 1;
        $filetype = strtolower(pathinfo($filedir , PATHINFO_EXTENSION));
        // To check the file if is an actual image or fake image
        $check = getimagesize($_FILES['file']['tmp_name']);

        if ($check !== false)
        {

          // To check if exists in Directory
          if (!file_exists($filedir))
          {
            // To check to allow for uploading
            if ($_FILES['file']['size'] <= 512000)
            {
              // To check for appropriate extansion
              if ($filetype == "jpg" || $filetype == "png" || $filetype == "gif")
              {
                // To check to do uploading
                // This fuction uploads into the dir and < the name > that we want
                if (move_uploaded_file($_FILES['file']['tmp_name'], $filedir))
                {
                  // This fuction is necessary for security
                  $check_image="The " . htmlspecialchars(basename($_FILES['file']['name'])) . " file has been successfully uploaded!";
                  $okupload = 1;

                } else {
                  $check_image="Sorry, there was an uploading error to your file, please try again!";
                }

              } else {
                $check_image="Sorry, only JPG, PNG and GIF files are allowed!";
              }

            } else {
              $check_image="Sorry, you can upload by 500K byte!";
            }

          } else {
            $check_image="Sorry, this file already exists!";
          }

        } else {
          $check_image="This file is not an image!";
        }
      }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>first example</title>
  <meta name="keywords" content="html,meta" />
  <meta name="description" content="MY FIRST LESSON" />
  <meta name="author" content="omid rah">
  <meta name="revised" content="10/31/2020" />
  <!--<meta http-equiv="refresh" content="3;URL='../pre-html/index.htm'" />-->
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  <meta http-equiv="cookie" content="userid=omid;expires=;" />
  <!--<base href="https://www.learningchocolate.com/" target="">-->

  <!--To set an icon for bar title-->
  <!--size should be 16*16 or 32, 48, 64, 128-->
  <!--color should be 8, 24, 32 bites-->
  <link rel="shortcut icon" type="image/png" href="Images/Icon-website.png">

  <link rel="stylesheet" type="text/css" href="">
  <script type="text/javascript">
    function hello() {
      alert('Hello!');
    }
  </script>
  <style type="text/css" media="screen">
    body {
      background: url("images/background.png");
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
      background-size: 71% 100%;
      font-size: 100%;
      margin: 0em;
      padding: 0em;
      text-transform: capitalize;
    }
    article, header, aside, footer {
      margin: auto;
      width: 70%;
      padding: 1% 0%;
    }
    article, aside {
      background-color: white;
    }
    header {
      background-color: #33CC00;
      padding: 0.5% 0%;
    }
    footer {
      margin-bottom: 0.5%;
      padding: 5% 0% 0% 0%;
    }
    nav {
      font-size: 1em;
    }
    footer p {
      margin: 0em;
      padding: 0.8%;
      background-color: white;
      width: 98.4%;
      font-size: 1.1em;
      font-style: italic;
      font-weight: bold;
    }
    #foothr {
      background-color: black;
      border: 0.08em solid black;

    }
    .difart {
      text-align: center;
    }
    table {
      margin: auto;
      width: 98%;
      border-collapse: separate;
      border-spacing: 0em 0em;
    }
    td{
      padding-top: 0em;
      padding-bottom: 0em;
    }
    #mytabletd {
      text-align: center;
      vertical-align: top;
    }
    h1 {
     padding-left: 1%;
    }

    /*for main head <begin>*/
    .mainhead {
      width: 72%;
      background-color: white;
      box-shadow: 0em 0em 0.1em 0.1em #888888;
      padding: 0.1% 0em 0em;
    }
    .headtable {
      width: 100%;
      text-align: center;
    }
    #headtd {
      height: 30%;
    }
    #headimg {
      width: 100%;
    }
    #hello {
      font-size: 0.85em;
      padding: 0.3% 0.9%;
      border: 0.1em solid black;
      border-radius: 0.25em;
    }
    .spwihead {
      width: 1.6%;
    }
    .spheihead {
      padding-top: 0.2%;
    }
    /*for main head <end>*/

    /*for big space area <begin>*/
    #spacehead {
      margin: auto;
      width: 60%;
      padding: 10% 0%;
    }
    .spacemain {
      padding: 3% 0%;
    }
    /*for big space area <end>*/

    /*base form table <begin>*/
    #sptd {
      height: 4em;
    }
    table hr{
      margin: 0.3em 0em;
      border: 0.04em solid grey;
    }
    article input, textarea, select {
      border: 0.11em solid black;
      border-radius: 0.3em;
      width: 100%;
      padding: 5% 0%;
      transition: width 0.5s ease-in-out;
    }
    textarea {
      padding: 15% 0%;
      resize: none;
      /*for textarea tags*/
    }
    input[type=date]{
      padding: 4.1% 0%;
    }
    select{
      width: 100%;
      padding: 4.7% 0%;
    }
    #upload{
      border: 0em solid;
      border-radius: 0em;
    }
    input[type=url] {
      width: 40%;
      padding: 2% 0%;
    }
    input[type=url]:focus {
      width: 54%;
    }
    label {
      font-size: 1.1em;
    }
    .diflabel, #formatlabel {
      font-size: 1em;
    }
    #formatlabel{
      padding-left: 2%;
    }
    input[type=checkbox], input[type=radio] {
      width: 1.4em;
      height: 1.4em;
    }
    input[type=submit], input[type=reset], button[name=canvasbutton] {
      width: 25%;
      padding: 0.5% 0%;
      border: 0.14em solid grey;
      border-radius: 0.3em;
      font-size: 0.95em;
      text-transform: capitalize;
      transition: 0.1s ease-in-out;
    }
    input[type=submit]:hover, input[type=reset]:hover, button[name=canvasbutton]:hover {
      width: 30%;
      background-color: white;
      border-color: black;
    }
    /*base form table <end>*/

    ul, ol, dl {
      font-style: italic;
    }
    ul {
      list-style-type: square;
    }
    ol {
      list-style-type: upper-roman;
    }
    li, dt, dd {
      background-color: aqua;
      margin-bottom: 1.4%;
    }
    dt {
      font-style: normal;
      margin-bottom: 0.5%;
    }
    dd {
      margin-bottom: 2%;
    }
    .my {
      margin: auto;
      width: 95%;
      text-align: center;
      border: 0.11em solid green;
      border-collapse: separate;
      border-spacing: 0.09em 0.09em;
    }
    .my tr, .my td, th {
     border: 0.11em solid green;
    }
    .my td, th {
     padding: 2% 0em;
    }
    th {
     background-color: green;
     font-weight: bold;
    }
    .particulartd {
      width: 50%;
      text-align: center;
    }
    iframe {
      border: 0.11em solid black;
      margin: auto;
      width: 96%;
      height: 30em;
      box-shadow: 0em 0em 1em 0.1em #888888;
    }
    object {
       width: 60%;
       height: 47em;
       border: 0.11em solid black;
       box-shadow: 0em 0em 1em 0.1em #888888;
    }
    canvas {
      border: 0.18em solid green;
      width: 70%;
    }
    video {
      width: 70%;
    }
    a:hover, .none:hover {
      color: maroon;
    }
    .between {
      font-weight: bold;
      color: blue;
    }
    aside div {
      text-align: right;
      width: 99%;
      margin: 1% 1% 0% 0%;
    }
    .none {
      background-color: white;
      border: 0.01em solid blue;
      border-radius: 0.3em;
      padding: 0.05% 0.3%;
      font-size: 1em;
      color: blue;
      text-decoration: none;
    }
    .imgmap {
      margin: auto;
      width: 35%;
      opacity: 1;
    }
    .imgmap:hover {
      opacity: 0.9;
      cursor: pointer;
    }
    #phpcodes {
      margin: auto;
      width: 97%;
    }
    @media only screen and (max-width: 75em) {
      body {
        font-size: 90%;
        background-size: 81% 100%;
      }
      header, article, aside, footer{
        width: 80%;
      }
      .mainhead {
        width: 82%;
      }
      .spacemain, #spacehead {
        width: 80%;
      }
      object {
        width: 80%;
      }
    }
    @media only screen and (max-width: 40em) {
      body {
        font-size: 80%;
        background-size: cover;
      }
      header, article, aside, footer{
        width: 100%;
      }
      .mainhead {
        width: 100%;
        box-shadow: 0em;
      }
      .spacemain, #spacehead {
        width: 100%;
      }
      object {
        width: 96%;
      }
    }
  </style>
</head>
<body>
  <a name="begin"></a>
  <header class="mainhead">
    <table class="headtable">
      <tr>
        <td rowspan="3" style="width: 10%;">
          <img id="headimg" src="Images/edu.png" alt="header's icon" />
        </td>
        <td style="padding-bottom: 3.3%;text-align: right;">
          <input id="hello" type="button" onclick="hello();" name="ok" value="get hello">
        </td>
        <td class="spwihead"></td>
      </tr>
      <tr>
        <td style="background-color: silver;padding-bottom: 0.5%;padding-top: 0.5%;">
          <nav>
            <a href="index.php" target="_blank" title="Home">HOME</a>
            <span class="between">  |  </span>
            <a href="DataBaseMySql.php" title="MySql">MYSQL</a>
          </nav>
        </td>
        <td class="spwihead"></td>
      </tr>
      <tr>
        <td colspan="2" style="padding-bottom: 0.6%;"></td>
      </tr>
    </table>
  </header>
  <div id="spacehead">
  </div>
  <header>
    <h1>My first lesson</h1>
  </header>
  <article>
    <table>
      <tr>
        <td colspan="4" style="text-align: right;">
          <a class="none" href="#form">GO To Form</a>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="width: 50%;">
          <h2>Key words</h2>
          <dl>
            <dt>HTTP</dt>
            <dd>stands for ...</dd>
            <dt>HTML</dt>
            <dd>stands for ...</dd>
            <dt>XHTML</dt>
            <dd>stands for ...</dd>
          </dl>
        <td id="mytabletd" rowspan="2" colspan="2">
          <h2 style="color: green;">My Table</h2>
          <table class="my">
            <thead>
              <tr>
                <td colspan="3">Math grades</td>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="3">University</td>
              </tr>
            </tfoot>
            <tbody>
              <tr>
                <th>NAME</th>
                <th>POINTS</th>
                <th>RESULT</th>
              </tr>
              <tr>
                <td><mark>omid</mark></td>
                <td>40</td>
                <td>pass</td>
              </tr>
              <tr>
                <td rowspan="2">jon</td>
                <td>15</td>
                <td>fail</td>
              </tr>
              <tr>
                <td>50</td>
                <td>pass</td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td style="width: 25%;">
          <h2>My Result</h2>
            <ol>
              <li>Result 1</li>
              <li>Result 2</li>
              <li>Result 3</li>
              <li>Result 4</li>
            </ol>
        </td>
        <td>
          <h2>My stage</h2>
            <ul>
              <li>stage 1</li>
              <li>stage 2</li>
              <li>stage 3</li>
              <li>stage 4</li>
            </ul>
        </td>
      </tr>
    </table>
  </article>
  <div class="spacemain">
  </div>
  <header>
    <h1>This image is map</h1>
  </header>
  <article class="difart">
    <!--<img class="imgmap" src="GDpractice.php?warn=YOU CAN NOT USE HOTLINKING!">-->
    <p>
      <h3>Guess where is enlish learning page (only firefox browser)!</h3>
    </p>
    <img class="imgmap" src="images/university.png" usemap="#map1">
      <!--only firefox browser can support-->
    <map name="map1">
      <area shape="rect" coords="0em,0em,9.5em,9.5em" href="" alt="part1" />
      <area shape="rect" coords="9.5em,0em,19em,9.5em" href="" alt="part2" />
      <area shape="rect" coords="0em,9.5em,9.5em,19em" href="" alt="part3" />
      <area shape="rect" coords="9.5em,9.5em,19em,19em" href="https://www.englishclub.com/grammar/verb-tenses.htm" alt="part4" />
    </map>
    <div style="width: 20%;margin: auto;">
      <form action="GDpractice.php" method="get">
        <label>Write your warning on it!</label></br>
        <input type="text" name="warn" placeholder="your warning">
        <input type="submit" name="submit" value="GO" style="margin-top: 2%;">
      </form>
    </div>
    <!--all browsers can support-->
    <!--<div style="margin: auto;width: 50%;">
      <a href="#" target="_blank">
        <img ismap src="image/university.png" width="300px" height="300px" alt="">
      </a>
    </div>-->
  </article>
  <div class="spacemain">
  </div>
  <header>
    <h1>View of this website</h1>
  </header>
  <article class="difart">
    <iframe src="index.php" name="frame1">
      sorry ...
    </iframe>
  </article>
  <div class="spacemain">
  </div>
  <header>
    <h1>Form</h1>
  </header>
  <article>
    <table>
      <a name="form"></a>
      <!--action="<?php //echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" is very important for security-->
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES);?>" method="post" enctype="multipart/form-data" autocomplete="on">
        <tr>
          <td style="width: 35%;">
            <label for="Firstname">Firstname :</label>
          </td>
          <td style="width: 26%;">
            <!--we can use autofocus in following tag-->
            <input type="text" id="Firstname" name="Firstname" value="<?php echo $name;?>"
             maxlength="20" placeholder="name"/>
             <!--We can use "required" in the input tags instead of makeing an error -->
            <span style="color: red;"><?php echo $nameErr;?></span>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label for="password1">Password :</label>
          </td>
          <td>
            <input type="password" id="password" name="password" value="<?php echo $password;?>"
             placeholder="Password"/>
             <!--We can use "required" in the input tags instead of makeing an error -->
             <span style="color: red;"><?php echo $passwordErr;?></span>
          </td>
          <td>
              <babel id="formatlabel">8-15 characters(include small,capital letters & number)</label>
          </td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label for="description">Description :</label>
          </td>
          <td rowspan="2">
            <textarea id="description" name="description" rows="" cols="" placeholder="Description..." style="vertical-align: top;"></textarea>
          </td>
          <td rowspan="2"></td>
        </tr>
        <tr>
          <td style="padding: 4% 0%;"></td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label for="email">Email : </label>
          </td>
          <td>
            <input type="text" id="email" name="email" value="<?php echo "$email"; ?>"
             placeholder="Type your email" maxlength="40">
             <span style="color: red;"><?php echo $emailErr;?></span>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label for="birth">Birthday : </label>
          </td>
          <td>
            <input type="date" id="birth" name="birth" min="1920-01-01" max="2021-01-01">
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label for="tel1">Phone number : </label>
          </td>
          <td>
            <input type="tel" id="tel1" name="tel1" pattern="[5]{1}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}"
            placeholder="Format: 5-00-00-00-00">
          </td>
          <td>
            <babel id="formatlabel">(Format: 5-00-00-00-00)</label>
          </td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label for="url1">Type URL : </label>
          </td>
          <td colspan="2">
            <input type="url" id="url" name="url" list="dataurl" placeholder="Type your URL">
            <datalist id="dataurl">
              <option value="https://www.facebook.com/"></option>
              <option value="https://atom.io/"></option>
            </datalist>
            <span style="color: red;"><?php echo $websiteErr;?></span>
          </td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label>Option choosing :</label>
          </td>
          <td>
            <input type="checkbox" id="option1" name="option1" checked value="on"/>
            <label class="diflabel" for="option1">Option 1</label>
            <br />
            <input type="checkbox" id="option2" name="option2" value="on"/>
            <label class="diflabel" for="option2">Option 2</label>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label>Gender :</label>
          </td>
          <td>
            <input type="radio" id="male" name="gender" <?php if (isset($gender) && $gender == "male") echo "checked"; ?> value="male">
            <label class="diflabel" for="male">Male</label>
            <br />
            <input type="radio" id="female" name="gender" <?php if (isset($gender) && $gender == "female") echo "checked"; ?> value="female">
            <label class="diflabel" for="female">Female</label>
            <br />
            <input type="radio" id="other" name="gender" <?php if (isset($gender) && $gender == "other") echo "checked"; ?> value="other">
            <label class="diflabel" for="other">other</label>
            <!--isset() determine if a variable is declared and is not null-->
           <span style="color: red;"><?php if ($gender != "male" && $gender != "female" && $gender != "other")
             { $gender = ''; } echo $genderErr;?></span>

          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label for="transport">Type 1 option :</label>
          </td>
          <td>
            <select id="transport" name="transport" size="1" placeholder="Transport"/>
              <optgroup label="small">
                <option value="car">car</option>
                <option value="boat">boat</option>
              </optgroup>
              <optgroup label="large">
                <option value="ship">ship</option>
                <option value="train">train</option>
              </optgroup>
            </select>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td>
            <label for="rate">Give a rate (0 to 10) : </label>
          </td>
          <td>
            <input type="range" id="rate" name="rate" min="0" max="10" value="5">
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3"><h-r/></td>
        </tr>
        <tr>
          <td>
            <label for="upload">just image file uploading :</label>
          </td>
          <td>
            <input type="file" id="upload" name="file" placeholder="Please upload your image file" spellcheck="false" accept="image/*">
            <!--<input type="file" id="upload" name="file" accept="file_extension">-->
            <span style="color: red;"><?php echo $check_image;?></span>
          </td>
          <td>
            <div>
              <?php if (isset($okupload) && $okupload === 1)
              echo '<img src=' . $filedir . ' title=' . $_FILES['file']['name'] . ' style="width: 120px;border: 1px solid black;" />';?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="3"><hr/></td>
        </tr>
        <tr>
          <td id="sptd" colspan="3"></td>
        </tr>
        <tr>
          <td colspan="3">
            <table>
              <tr>
                <td class="particulartd">
                  <input type="submit" name="submit" value="Go">
                </td>
                <td class="particulartd">
                  <input type="reset" name="reset" value="Reset">
                </td>
              </tr>
            </table>
            <!--<div><input type="image" src="images/university.png" name="submit" width="50px" height="50px"></div>-->
          </td>
        </tr>
      </form>
    </table>
  </article>
  <div class="spacemain">
  </div>
  <header>
    <h1>Fantastic experience</h1>
  </header>
  <article>
    <table>
      <tr>
        <td class="particulartd">
          <video controls poster="images/university.png">
          <source src="images/Homes in Britain.mp4" type="video/mp4" />
          <source src=".ogv" type="video/ogg" />
          browser does not support
          </video>
        </td>
        <td class="particulartd">
          <table>
            <tr>
              <td>
                <h2 style="margin-top: 0em;">painting (just for testing)</h2>
              </td>
            </tr>
            <tr>
              <td>
                <canvas id="canvas1"></canvas>
              </td>
            </tr>
            <tr>
              <td>
                <button type="button" name="canvasbutton" onclick="">paint</button>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <!--<div class="" style="">
      <audio src="" controls autoplay loop />
        <source src="" type="audio/mpeg" />
        <source src="" type="audio/ogg" />
      </audio>
    </div>-->
  </article>
  <div class="spacemain">
  </div>
  <header>
    <h1>Education</h1>
  </header>
  <aside class="difart">
    <object type="application/pdf" data="DownloadingFile/HTMLlearning.pdf">
      <a href="images/HTML5StepByStepByFaitheWempen-1.pdf">Get a pdf file</a>
    </object>
    <div style="text-align: center;">
      <a class="none" href="Readpdf.php" style="font-size: 0.9em;border: 0;color: green;">CLICK For Downloading (only one time you can)</a>
    </div>
    <div>
      <a class="none" href="#begin">Go To Up</a>
    </div>
  </aside>
  <footer>
    <hr id="foothr">
    <p>copyright</p>
  </footer>
</body>
</html>
