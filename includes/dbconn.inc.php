<?php
class mysqlidbh{
  private $servername;
  private $username;
  private $password;
  private $database;

  public function connect(){
    $this->servername = "localhost";
    $this->username = "root";
    $this->password = "";
    $this->database = "SCHEMAZ";

    $conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);
    return $conn;
  }

  public function sql_query($query, $rowname){
    //$conn = mysqli_connect("localhost", "root", "", "SCHEMAZ");
    $conn = $this->connect();
    $this->query = $query;
    $this->rowname = $rowname;
    $data = '';
    $result = mysqli_query($conn, $this->query);
    while($row = mysqli_fetch_assoc($result)){
      $data .= $row[$this->rowname];
    }
    return $data;
  }
  public function new_sql_query($query, $rowname){
  //  $conn = mysqli_connect("localhost", "root", "", "SCHEMAZ");
    $conn = $this->connect();
    $this->query = $query;
    $this->rowname = $rowname;
    $data = '';
    $result = mysqli_query($conn, $this->query);
    while($row = mysqli_fetch_assoc($result)){
      $data .= $row[$this->rowname].' ';
    }
    return $data;
  }
  public function sql_insert_update($query){
    //$conn = mysqli_connect("localhost", "root", "", "SCHEMAZ");
    $conn = $this->connect();
    $this->query = $query;
    $data = '';
    $result = mysqli_query($conn, $this->query);
    return $result;
  }
  public function subject_list(){
    //$conn = mysqli_connect("localhost", "root", "", "SCHEMAZ");
    $conn = $this->connect();
    $query = "SELECT subject_code from subjects order by subject_code;";
    $data = '';
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
      $data .= '<option value="'.$row['subject_code'].'">'.$row['subject_code'].'</option>';
    }
    return $data;
  }
  public function school_yr(){
    //$conn = mysqli_connect("localhost", "root", "", "SCHEMAZ");
    $conn = $this->connect();
    $query = "SELECT * from curriculum ORDER BY curriculum_num desc;";
    $data = '';
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
      $data .= '<option value="'.$row['curriculum_num'].'">'.$row['academic_year'].' '.$row['semester'].'</option>';
    }
    return $data;
  }
  public function dept_list(){
    //$conn = mysqli_connect("localhost", "root", "", "SCHEMAZ");
    $conn = $this->connect();
    $query = "SELECT * from department;";
    $data = '';
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
      $data .= '<option value="'.$row['department_id'].'">'.$row['department_desc'].'</option>';
    }
    return $data;
  }
}
#$query = "SELECT COUNT(*) as 'count' from schedule;";
#$rowname = 'count';
$mysqlidb = new mysqlidbh;
$conn = $mysqlidb->connect();
 ?>
