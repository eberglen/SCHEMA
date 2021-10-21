<?php
class sqldata extends dbh{

  public function getData($sql, $rowname){
    $this->rowname = $rowname;
    $this->sql = $sql;
    $stmt = $this->connect()->query($this->sql);
    while ($row = $stmt->fetch()){
      $data = $row[$this->rowname]."<br>";
      return $data;
    }
  }

  public function getMultipleData($sql, $rowname, $rowname2, $rowname3){
    $data = '';
    $this->rowname3 = $rowname3;
    $this->rowname2 = $rowname2;
    $this->rowname = $rowname;
    $this->sql = $sql;
    $stmt = $this->connect()->query($this->sql);
    while ($row = $stmt->fetch()){
      $data .= '<option value="'.$row[$this->rowname].'">'.$row[$this->rowname2].' '.$row[$this->rowname3].'</option>';

    }
    return $data;
  }

  public function getSubjectData($sql, $rowname, $rowname2, $rowname3, $rowname4, $rowname5){
    $data = '';
    $this->rowname5 = $rowname5;
    $this->rowname4 = $rowname4;
    $this->rowname3 = $rowname3;
    $this->rowname2 = $rowname2;
    $this->rowname = $rowname;
    $this->sql = $sql;
    $stmt = $this->connect()->query($this->sql);
    while ($row = $stmt->fetch()){
      if (($row[$this->rowname3] == 0) && ($row[$this->rowname4] == 0) && ($row[$this->rowname5] == 0)){
        $data .= '<option value="'.$row[$this->rowname].'">'.$row[$this->rowname2].' (Blank)</option>';
      }
      else if (($row[$this->rowname3] == 0) || ($row[$this->rowname4] == 0) || ($row[$this->rowname5] == 0)){
        $data .= '<option style="color:orange" value="'.$row[$this->rowname].'">'.$row[$this->rowname2].' (Incomplete)</option>';
      }
      else{
        $data .= '<option style="color:green" value="'.$row[$this->rowname].'">'.$row[$this->rowname2].' (Complete)</option>';
      }
    }
    return $data;
  }

}

?>
