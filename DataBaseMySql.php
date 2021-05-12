<?php
// In httpd-xampp.conf must be like below

/*Alias /phpmyadmin "D:/xampp/phpMyAdmin"
<Directory "D:/xampp/phpMyAdmin">
    AllowOverride AuthConfig Limit
    Require all granted
    Order allow,deny
    Allow from all
    #ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>*/

// To recommend anyone to set the mysql encoding to utf8mb4 and never use utf8 in mysqli

$servername = "localhost";
$dbname = "firstexample";
$username = "root";
$password = "";

class Set
{
  public $Id;
  public $Password;
  public $Name;

  /*function __construct($id, $pass, $name)
  {
    $this->Id = $id;
    $this->Password = $pass;
    $this->Name = $name;
  }*/
}


try {
  /*$connect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // Set the PDO error mode to exception
  $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected!<br><br>";*/

  // prepare sql to avoid the attacks of SQL Injection

  // << INSERT >>
  /*$insert = $connect->prepare("INSERT INTO user (id, password, name) VALUES (:id, :password, :name)");

  // << Binds a parameter to the specified variable name >>
  $insert->bindParam(':id', $id, PDO::PARAM_INT);
  $insert->bindParam(':password', $password, PDO::PARAM_STR);
  $insert->bindParam(':name', $name, PDO::PARAM_STR);
  $id = "1543";
  $password = "4574";
  $name = "woo!!";
  // << // Binds a parameter to the specified variable name >>

  // << Binds a value to a parameter >>
  //$insert->bindValue(':id', $id, PDO::PARAM_INT);
  //$insert->bindValue(':password', $password, PDO::PARAM_STR);
  //$insert->bindValue(':name', $name, PDO::PARAM_STR);
  // << // Binds a value to a parameter >>


  $insert->execute();

  echo "<br>Record created successfully!<br>";
  */
  // << //INSERT >>


  // << SELECT >>
  // Return only 2 records, start on record 4 (OFFSET 3)
  /*$select = $connect->prepare("SELECT id, password, name FROM user LIMIT 2 OFFSET 3");
  $select->execute();*/

  /*$select = $connect->prepare("SELECT id, password, name FROM user WHERE name=:query_name and id=:query_id");
  $select->bindParam(':query_name', $query_name);
  $select->bindParam(':query_id', $query_id);
  $query_name = "omid";
  $query_id = 1;
  $select->execute();*/

  // Order by letters
  /*$select = $connect->prepare("SELECT id, password, name FROM user ORDER BY name");
  $select->execute();

  echo $select->columnCount() . " Columns exist in result Successfully!<br><br>";

  // << Binds a column to a php variable >>
  $select->bindColumn(1, $column1);
  $select->bindColumn(2, $column2);
  $select->bindColumn(3, $column3);
  // << // Binds a column to a php variable >>

  // We can use FETCH_ASSOC, FETCH_NUM, FETCH_BOTH with slightly differences
  // And we could use FETCH_CLASS and FETCH_INFO in different way
  */
  //<< FETCH_ASSOC >>
  /*$result = $select->setFetchMode(PDO::FETCH_ASSOC);

  //$s = $select->fetchAll();
  //print_r($s);

  while ($row = $select->fetch())
    {
      echo "Id: " . $row['id'] . " | Password: " . $row['password'] . " | Name: " . $row['name'] . "<br>";
      echo "columnId: " . $column1 . " | columnPass: " . $column2 . " | columnName: " . $column3 . "<br><br>";
    }

  echo "<br>Selected Successfully!<br>";

  $select2 = $connect->prepare("SELECT COUNT(id) FROM user");
  $select2->execute();
  $num = $select2->fetchColumn();
  echo "<br>The number of the id column: ".$num."<br><br>";
  */
  // << FETCH_OBJ >>
  /*$result = $select->setFetchMode(PDO::FETCH_OBJ);

  while ($row = $select->fetch())
    {
      echo "Id: " . $row['id'] . " | Password: " . $row['password'] . " | Name: " . $row['name'] . "<br>";
    }
  */
  // << FETCH_CLASS >>
  /*$result = $select->setFetchMode(PDO::FETCH_CLASS, 'Set');

  // PDO::FETCH_CLASS: after setting properties the function __construct is called
  // PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE: before setting properties the function __construct is called

  while ($row = $select->fetch())
    {
      echo "Id: " . $row['id'] . " | Password: " . $row['password'] . " | Name: " . $row['name'] . "<br>";
    }
  */
  // << //SELECT >>


  // << DELETE >>
  // To delete a record
  /*$delete = $connect->prepare("DELETE FROM user WHERE id=:query_name");
  $delete->bindParam(':query_name', $query_name);
  $query_name = "2v";
  $delete->execute();

  echo "<b>Deleted Successfully!<br>";
  */
  // << //DELETE >>


  // << UPDATE >>
  /*$update = $connect->prepare("UPDATE user SET password=:query_pass WHERE name=:query_name");
  $update->bindParam(':query_pass', $query_pass);
  $update->bindParam(':query_name', $query_name);
  $query_pass = "niceamir";
  $query_name = "amir";
  $update->execute();

  // rowCount(); returns the number of row
  echo "<br>" . $update->rowCount() . " (Row or rows) Updated Successfully!<br>";
  */
  // << //UPDATE >>


  // << create a table >>

  // The example from mysql
  /*$sql = "CREATE TABLE `firstexample`.`options` ( `option1` INT(10) NOT NULL AUTO_INCREMENT ,  `option2` VARCHAR(20) CHA
  RACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,  `option3` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci N
  ULL ,      PRIMARY KEY  (`option1`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";*/

  /*$create = $connect->prepare("CREATE TABLE example3 (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  lastname VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  email VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;");

  $create->execute();

  echo "<br>Table created Successfully!<br>";*/

  // << //create a table >>


  // << create a database >>
  /*$connect2 = new PDO("mysql:host=$servername", $username, $password);
  // Set the PDO error mode to exception
  $connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "CREATE DATABASE secondexample";

  // Return a result set as a PDOStatement object
  //$object = $connect2->query($sql);
  //echo "<br><br>The object of result: <br>";
  //print_r($object);

  // Return a number of affected rows
  $row = $connect2->exec($sql);
  //echo "<br><br>The number of rows: " . $row;

  echo "<br><br>Database created successfully!<br>";*/

  // << create a database >>

} catch (PDOException $e) {

  /*echo "<b>DataBase Error: </b>" . $e->getMessage() . " | <b>On line: </b>" . $e->getLine() . " |
  <b>In file: </b>" . $e->getFile() . " | <b>With code: </b>" . $e->getCode();
  $error = "\n" . $e->getMessage();
  file_put_contents('NeededFiles/PDOErrors.txt', $error, LOCK_EX | FILE_APPEND);*/
}

// Close the connection
//$connect = null;
echo "<h3>please read the PHP note document for Database of Mysql in your editor!</h3>";

?>
