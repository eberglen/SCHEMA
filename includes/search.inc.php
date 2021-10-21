<?php
if(isset($_POST['q'])){
  include 'dbconn.inc.php';
  $q = $_POST['q'];

  $sql = "SELECT * FROM room
          WHERE room_num LIKE '%$q%'
          OR avail_start LIKE '%$q%'
          OR avail_end LIKE '%$q%'
          OR room_class LIKE '%$q%'
          ;";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0){
    echo '<H2>Results</h2>
    <div class="table-responsive col-md-6">
    <table class="table table-hover table-responsive-md">
      <thead>
        <tr>
          <th scope="col">Room Number</th>
          <th scope="col">Availability</th>
          <th scope="col">Type</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody>
    ';

    while($row = mysqli_fetch_assoc($result)) {
      $room_num = $row['room_num'];
      $a_start = $row['avail_start'];
      $a_end = $row['avail_end'];
      $type = $row['room_class'];
      

      echo '
      <tr>
        <th scope="row">'.$room_num.'</th>
        <td>'.$a_start.'-'.$a_end.'</td>
        <td>'.$type.'</td>';
        //<td>
          //<a href="" class="btn btn-primary btn-rounded mb-4 btn-sm" data-toggle="modal" data-target="#AV'.$room_num.'E"><i class="far fa-edit"></i></a>
        //</td>
        echo '
        <td><form action="includes/delete_room.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete room?&quot);">
          <input type="hidden" name="room_num" value="'.$room_num.'"></input>
          <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"><i class="far fa-trash-alt"></i></button>
        </form></td>
      </tr>
      ';




    }
    echo '
    </tbody>
  </table>
  </div>
    ';
  }
}

?>
