<?php


include 'dbconn.inc.php';

if(!empty($_POST["schedule_num"])){
  $query = "SELECT s_end_at
            FROM schedule
            WHERE schedule_num=".$_POST["schedule_num"].";";
  #$result = $db->query($query);
  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)){
      if (isset($row['s_end_at'])){
        $s_end_at = str_replace(':','',$row['s_end_at']);
        echo '<option selected value="'.$s_end_at.'">'.date("g:i a", strtotime($row['s_end_at'])).'</option>
              <option disabled value="">Select End...</option>
              <option value="070000">7:00 AM</option>
              <option value="073000">7:30 AM</option>
              <option value="080000">8:00 AM</option>
              <option value="083000">8:30 AM</option>
              <option value="090000">9:00 AM</option>
              <option value="093000">9:30 AM</option>
              <option value="100000">10:00 AM</option>
              <option value="103000">10:30 AM</option>
              <option value="110000">11:00 AM</option>
              <option value="113000">11:30 AM</option>
              <option value="120000">12:00 PM</option>
              <option value="123000">12:30 PM</option>
              <option value="130000">1:00 PM</option>
              <option value="133000">1:30 PM</option>
              <option value="140000">2:00 PM</option>
              <option value="143000">2:30 PM</option>
              <option value="150000">3:00 PM</option>
              <option value="153000">3:30 PM</option>
              <option value="160000">4:00 PM</option>
              <option value="163000">4:30 PM</option>
              <option value="170000">5:00 PM</option>
              <option value="173000">5:30 PM</option>
              <option value="180000">6:00 PM</option>
              <option value="183000">6:30 PM</option>
              <option value="190000">7:00 PM</option>
              <option value="193000">7:30 PM</option>
              <option value="200000">8:00 PM</option>
              <option value="203000">8:30 PM</option>
              <option value="210000">9:00 PM</option>
              ';
      }
      else{
        echo '<option selected disabled value="">Select End...</option>
              <option value="070000">7:00 AM</option>
              <option value="073000">7:30 AM</option>
              <option value="080000">8:00 AM</option>
              <option value="083000">8:30 AM</option>
              <option value="090000">9:00 AM</option>
              <option value="093000">9:30 AM</option>
              <option value="100000">10:00 AM</option>
              <option value="103000">10:30 AM</option>
              <option value="110000">11:00 AM</option>
              <option value="113000">11:30 AM</option>
              <option value="120000">12:00 PM</option>
              <option value="123000">12:30 PM</option>
              <option value="130000">1:00 PM</option>
              <option value="133000">1:30 PM</option>
              <option value="140000">2:00 PM</option>
              <option value="143000">2:30 PM</option>
              <option value="150000">3:00 PM</option>
              <option value="153000">3:30 PM</option>
              <option value="160000">4:00 PM</option>
              <option value="163000">4:30 PM</option>
              <option value="170000">5:00 PM</option>
              <option value="173000">5:30 PM</option>
              <option value="180000">6:00 PM</option>
              <option value="183000">6:30 PM</option>
              <option value="190000">7:00 PM</option>
              <option value="193000">7:30 PM</option>
              <option value="200000">8:00 PM</option>
              <option value="203000">8:30 PM</option>
              <option value="210000">9:00 PM</option>
              ';
      }
  }
}

?>
