<?php
include 'dbconn.inc.php';
if (!empty($_POST['curnum'])){
  $query = "SELECT year_level_section from schedule
            where curriculum_num=".$_POST['curnum']."
            AND year_level_section LIKE '%1%'
            GROUP BY year_level_section;";#change department_id
  $result = mysqli_query($conn, $query);
  echo '<br><div class="animated fadeInRight"><h4>First Year</h4></div>';
  while($row = mysqli_fetch_assoc($result)){
    echo '<details>';
    echo '<summary style="padding-left:1em" class="animated fadeIn">'.$row['year_level_section'].'</summary>';
    $query2 = "SELECT subject_code, schedule_num from schedule
               where curriculum_num=".$_POST['curnum']."
               AND year_level_section = '".$row['year_level_section']."';";
    $result2 = mysqli_query($conn, $query2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      echo '<span style="padding-left:2em"><div class="row">'.$row2['subject_code'].'
      <form action="includes/delete_sched.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete subject? &quot);">
        <input type="hidden" name="sched_num" value="'.$sched_num.'"></input>
        <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"></button>
      </form>
      </div>
      </span>';
    }
    echo '</details>';
  }

  $query = "SELECT year_level_section from schedule
            where curriculum_num=".$_POST['curnum']."
            AND year_level_section LIKE '%2%'
            GROUP BY year_level_section;";#change department_id
  $result = mysqli_query($conn, $query);
  echo '<br><div class="animated fadeInRight"><h4>Second Year</h4></div>';
  while($row = mysqli_fetch_assoc($result)){
    echo '<details>';
    echo '<summary style="padding-left:1em" class="animated fadeIn">'.$row['year_level_section'].'</summary>';
    $query2 = "SELECT subject_code, schedule_num from schedule
               where curriculum_num=".$_POST['curnum']."
               AND year_level_section = '".$row['year_level_section']."';";
    $result2 = mysqli_query($conn, $query2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      echo '<span style="padding-left:2em"><div class="row">'.$row2['subject_code'].'
      <form action="includes/delete_sched.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete subject? &quot);">
        <input type="hidden" name="sched_num" value="'.$sched_num.'"></input>
        <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"></button>
      </form>
      </div>
      </span>';
    }
    echo '</details>';
  }

  $query = "SELECT year_level_section from schedule
            where curriculum_num=".$_POST['curnum']."
            AND year_level_section LIKE '%3%'
            GROUP BY year_level_section;";#change department_id
  $result = mysqli_query($conn, $query);
  echo '<br><div class="animated fadeInRight"><h4>Third Year</h4></div>';
  while($row = mysqli_fetch_assoc($result)){
    echo '<details>';
    echo '<summary style="padding-left:1em" class="animated fadeIn">'.$row['year_level_section'].'</summary>';
    $query2 = "SELECT subject_code, schedule_num from schedule
               where curriculum_num=".$_POST['curnum']."
               AND year_level_section = '".$row['year_level_section']."';";
    $result2 = mysqli_query($conn, $query2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      echo '<span style="padding-left:2em"><div class="row">'.$row2['subject_code'].'
      <form action="includes/delete_sched.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete subject? &quot);">
        <input type="hidden" name="sched_num" value="'.$sched_num.'"></input>
        <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"></button>
      </form>
      </div>
      </span>';
    }
    echo '</details>';
  }

  $query = "SELECT year_level_section from schedule
            where curriculum_num=".$_POST['curnum']."
            AND year_level_section LIKE '%4%'
            GROUP BY year_level_section;";#change department_id
  $result = mysqli_query($conn, $query);
  echo '<br><div class="animated fadeInRight"><h4>Fourth Year</h4></div>';
  while($row = mysqli_fetch_assoc($result)){
    echo '<details>';
    echo '<summary style="padding-left:1em" class="animated fadeIn">'.$row['year_level_section'].'</summary>';
    $query2 = "SELECT subject_code, schedule_num from schedule
               where curriculum_num=".$_POST['curnum']."
               AND year_level_section = '".$row['year_level_section']."';";
    $result2 = mysqli_query($conn, $query2);
    while($row2 = mysqli_fetch_assoc($result2)){
      $sched_num = $row2['schedule_num'];
      echo '<span style="padding-left:2em"><div class="row">'.$row2['subject_code'].'
      <form action="includes/delete_sched.inc.php" method="POST" onsubmit="return confirm(&quot Are you sure you want to delete subject? &quot);">
        <input type="hidden" name="sched_num" value="'.$sched_num.'"></input>
        <button type="submit" class="btn btn-danger px-3 waves-effect waves-light btn-sm"></button>
      </form>
      </div>
      </span>';
    }
    echo '</details>';
  }
}
