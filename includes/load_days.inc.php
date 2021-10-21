<?php

#include 'mysqlidb.inc.php';
include 'dbconn.inc.php';

if(!empty($_POST["schedule_num"])){
  $query = "SELECT day_code
            FROM schedule_days
            WHERE schedule_num=".$_POST["schedule_num"].";";
  #$result = $db->query($query);
  $result = mysqli_query($conn, $query);
  $checked_m = '';
  $checked_t = '';
  $checked_w = '';
  $checked_th = '';
  $checked_f = '';
  $checked_s = '';
  while($row = mysqli_fetch_assoc($result)){
      if (isset($row['day_code'])){

        if ($row['day_code'] == 'M'){
          $checked_m = 'checked';
        }
        else if ($row['day_code'] == 'T'){
          $checked_t = 'checked';
        }
        else if ($row['day_code'] == 'W'){
          $checked_w = 'checked';
        }
        else if ($row['day_code'] == 'TH'){
          $checked_th = 'checked';
        }
        else if ($row['day_code'] == 'F'){
          $checked_f = 'checked';
        }
        else if ($row['day_code'] == 'S'){
          $checked_s = 'checked';
        }

      }
      else{


      }
  }


  echo  '
<div class="row">
  <div class="col-md-4">
    <div class="custom-control custom-checkbox">
      <input class="custom-control-input" type="checkbox" value="&quot;M&quot;" name="day" id="M" '.$checked_m.'>
      <label class="custom-control-label" for="M">Monday</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input class="custom-control-input" type="checkbox" value="&quot;T&quot;" name="day" id="T" '.$checked_t.'>
      <label class="custom-control-label" for="T">Tuesday</label>
    </div>
  </div>
  <div class="col-md-4">
    <div class="custom-control custom-checkbox">
      <input class="custom-control-input" type="checkbox" value="&quot;W&quot;" name="day" id="W" '.$checked_w.'>
      <label class="custom-control-label" for="W">Wednesday</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input class="custom-control-input" type="checkbox" value="&quot;TH&quot;" name="day" id="TH" '.$checked_th.'>
      <label class="custom-control-label" for="TH">Thursday</label>
    </div>
  </div>
  <div class="col-md-4">
    <div class="custom-control custom-checkbox">
      <input class="custom-control-input" type="checkbox" value="&quot;F&quot;" name="day" id="F" '.$checked_f.'>
      <label class="custom-control-label" for="F">Friday</label>
    </div>
    <div class="custom-control custom-checkbox">
      <input class="custom-control-input" type="checkbox" value="&quot;S&quot;" name="day" id="S" '.$checked_s.'>
      <label class="custom-control-label" for="S">Saturday</label>
    </div>
  </div>
</div>
  ';

}

?>
