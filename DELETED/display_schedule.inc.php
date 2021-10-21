<?php
include 'dbconn.inc.php';

$sched_num = $_POST['sched_num'];
$query = "SELECT *
          FROM schedule
          WHERE schedule_num = $sched_num;";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)){
  $yls = $row['year_level_section'];
  $cn = $row['curriculum_num'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script>document.getElementsByTagName("html")[0].className += " js";</script>
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Schedule Template | CodyHouse</title>
</head>
<body>
  <header class="cd-main-header text--center flex flex--column flex--center">
      <?php
        echo '<H1>'.$yls.'</H1>';
      ?>
  </header>

  <div class="cd-schedule cd-schedule--loading margin-top--lg margin-bottom--lg js-cd-schedule">
    <div class="cd-schedule__timeline">
      <ul>
        <li><span>07:00</span></li>
        <li><span>07:30</span></li>
        <li><span>08:00</span></li>
        <li><span>08:30</span></li>
        <li><span>09:00</span></li>
        <li><span>09:30</span></li>
        <li><span>10:00</span></li>
        <li><span>10:30</span></li>
        <li><span>11:00</span></li>
        <li><span>11:30</span></li>
        <li><span>12:00</span></li>
        <li><span>12:30</span></li>
        <li><span>13:00</span></li>
        <li><span>13:30</span></li>
        <li><span>14:00</span></li>
        <li><span>14:30</span></li>
        <li><span>15:00</span></li>
        <li><span>15:30</span></li>
        <li><span>16:00</span></li>
        <li><span>16:30</span></li>
        <li><span>17:00</span></li>
        <li><span>17:30</span></li>
        <li><span>18:00</span></li>
        <li><span>18:30</span></li>
        <li><span>19:00</span></li>
        <li><span>19:30</span></li>
        <li><span>20:00</span></li>
        <li><span>20:30</span></li>
        <li><span>21:00</span></li>

      </ul>
    </div> <!-- .cd-schedule__timeline -->

    <div class="cd-schedule__events">
      <ul>
        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Monday</span></div>

          <ul>
            <?php
          //LIST OF CLASSES ON MONDAY


                          $sql_mon = "select subject_code, s_start_at, s_end_at, room_num
                          from schedule, schedule_days
                          where schedule.schedule_num=schedule_days.schedule_num
                          AND year_level_section='$yls'
                          AND schedule_days.day_code='M'
                          AND curriculum_num=$cn;";
                          $result_mon = mysqli_query($conn, $sql_mon);


                          if (mysqli_num_rows($result_mon) > 0) {
                            while($row_mon = mysqli_fetch_assoc($result_mon)) {
                            $scm=$row_mon["subject_code"];
                            $ssam=$row_mon["s_start_at"];
                            $ssem=$row_mon["s_end_at"];
                            $rnm=$row_mon["room_num"];
                            $m=1;
                            $profm='No professor yet.';

                            include 'm.inc.php';
                            $sql_profm = "SELECT first_name, last_name
                            from schedule, employee, schedule_days
                            where schedule.employee_num=employee.employee_num
                            AND schedule_days.schedule_num=schedule.schedule_num
                            AND year_level_section='$yls'
                            AND schedule_days.day_code='M'
                            AND subject_code='$scm'
                            AND curriculum_num=$cn;";
                            $result_profm = mysqli_query($conn, $sql_profm);


                            if (mysqli_num_rows($result_profm) > 0) {
                              while($row_profm = mysqli_fetch_assoc($result_profm)) {
                              $profm=$row_profm["first_name"].' '.$row_profm["last_name"];
                            }}

                            echo '<li class="cd-schedule__event">
                              <a data-start="'.$ssam.'" data-end="'.$ssem.'"  data-content="event-yoga-1" data-event="event-'.$m.'" href="#0">
                                <em class="cd-schedule__name">'.$scm.' <br>'.$rnm.' <br>'.$profm.'</em>

                              </a>
                            </li>';
                          }}
                          else {

                          }
                    ?>




          </ul>
        </li>

        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Tuesday</span></div>

          <ul>

            <?php
            //LIST OF CLASSES ON TUESDAY


                          $sql_tue = "select subject_code, s_start_at, s_end_at, room_num
                          from schedule, schedule_days
                          where schedule.schedule_num=schedule_days.schedule_num
                          AND year_level_section='$yls'
                          AND schedule_days.day_code='T'
                          AND curriculum_num=$cn;";
                          $result_tue = mysqli_query($conn, $sql_tue);


                          if (mysqli_num_rows($result_tue) > 0) {
                            while($row_tue = mysqli_fetch_assoc($result_tue)) {
                            $sct=$row_tue["subject_code"];
                            $ssat=$row_tue["s_start_at"];
                            $sset=$row_tue["s_end_at"];
                            $rnt=$row_tue["room_num"];
                            $t=0;
                            $proft='No professor yet.';

                            include 't.inc.php';
                            $sql_proft = "SELECT first_name, last_name
                            from schedule, employee, schedule_days
                            where schedule.employee_num=employee.employee_num
                            AND schedule_days.schedule_num=schedule.schedule_num
                            AND year_level_section='$yls'
                            AND schedule_days.day_code='T'
                            AND subject_code='$sct'
                            AND curriculum_num=$cn;";
                            $result_proft = mysqli_query($conn, $sql_proft);


                            if (mysqli_num_rows($result_proft) > 0) {
                              while($row_proft = mysqli_fetch_assoc($result_proft)) {
                              $proft=$row_proft["first_name"].' '.$row_proft["last_name"];
                            }}
                            else{}

                            echo '<li class="cd-schedule__event">
                              <a data-start="'.$ssat.'" data-end="'.$sset.'"  data-content="event-yoga-1" data-event="event-'.$t.'" href="#0">
                                <em class="cd-schedule__name">'.$sct.' <br>'.$rnt.' <br>'.$proft.'</em>
                              </a>
                            </li>';
                          }}
                          else {

                          }
                    ?>




          </ul>
        </li>

        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Wednesday</span></div>

          <ul>
            <?php
            //LIST OF CLASSES ON WEDNESDAY


                          $sql_wed = "select subject_code, s_start_at, s_end_at, room_num
                          from schedule, schedule_days
                          where schedule.schedule_num=schedule_days.schedule_num
                          AND year_level_section='$yls'
                          AND schedule_days.day_code='W'
                          AND curriculum_num=$cn;";
                          $result_wed = mysqli_query($conn, $sql_wed);


                          if (mysqli_num_rows($result_wed) > 0) {
                            while($row_wed = mysqli_fetch_assoc($result_wed)) {
                            $scw=$row_wed["subject_code"];
                            $ssaw=$row_wed["s_start_at"];
                            $ssew=$row_wed["s_end_at"];
                            $rnw=$row_wed["room_num"];
                            $w=0;
                            $profw='No professor yet.';
                            include 'w.inc.php';
                            $sql_profw = "SELECT first_name, last_name
                            from schedule, employee, schedule_days
                            where schedule.employee_num=employee.employee_num
                            AND schedule_days.schedule_num=schedule.schedule_num
                            AND year_level_section='$yls'
                            AND schedule_days.day_code='W'
                            AND subject_code='$scw'
                            AND curriculum_num=$cn;";
                            $result_profw = mysqli_query($conn, $sql_profw);


                            if (mysqli_num_rows($result_profw) > 0) {
                              while($row_profw = mysqli_fetch_assoc($result_profw)) {
                              $profw=$row_profw["first_name"].' '.$row_profw["last_name"];
                            }}
                            else{}

                            echo '<li class="cd-schedule__event">
                              <a data-start="'.$ssaw.'" data-end="'.$ssew.'"  data-content="event-yoga-1" data-event="event-'.$w.'" href="#0">
                                <em class="cd-schedule__name">'.$scw.' <br>'.$rnw.' <br>'.$profw.'</em>
                              </a>
                            </li>';
                          }}
                          else {

                          }
                    ?>
          </ul>
        </li>

        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Thursday</span></div>

          <ul>
            <?php
            //LIST OF CLASSES ON thurSDAY


                          $sql_thur = "select subject_code, s_start_at, s_end_at, room_num
                          from schedule, schedule_days
                          where schedule.schedule_num=schedule_days.schedule_num
                          AND year_level_section='$yls'
                          AND schedule_days.day_code='TH'
                          AND curriculum_num=$cn;";
                          $result_thur = mysqli_query($conn, $sql_thur);


                          if (mysqli_num_rows($result_thur) > 0) {
                            while($row_thur = mysqli_fetch_assoc($result_thur)) {
                            $scth=$row_thur["subject_code"];
                            $ssath=$row_thur["s_start_at"];
                            $sseth=$row_thur["s_end_at"];
                            $rnth=$row_thur["room_num"];
                            $th=0;
                            $profth='No professor yet.';

                            include 'th.inc.php';
                            $sql_profth = "SELECT first_name, last_name
                            from schedule, employee, schedule_days
                            where schedule.employee_num=employee.employee_num
                            AND schedule_days.schedule_num=schedule.schedule_num
                            AND year_level_section='$yls'
                            AND schedule_days.day_code='TH'
                            AND subject_code='$scth'
                            AND curriculum_num=$cn;";
                            $result_profth = mysqli_query($conn, $sql_profth);


                            if (mysqli_num_rows($result_profth) > 0) {
                              while($row_profth = mysqli_fetch_assoc($result_profth)) {
                              $profth=$row_profth["first_name"].' '.$row_profth["last_name"];
                            }}
                            else{}

                            echo '<li class="cd-schedule__event">
                              <a data-start="'.$ssath.'" data-end="'.$sseth.'"  data-content="event-yoga-1" data-event="event-'.$th.'" href="#0">
                                <em class="cd-schedule__name">'.$scth.' <br>'.$rnth.' <br>'.$profth.'</em>
                              </a>
                            </li>';
                          }}
                          else {

                          }
                    ?>
          </ul>
        </li>

        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Friday</span></div>

          <ul>
            <?php
            //LIST OF CLASSES ON FRIDAY


                          $sql_fri = "select subject_code, s_start_at, s_end_at, room_num
                          from schedule, schedule_days
                          where schedule.schedule_num=schedule_days.schedule_num
                          AND year_level_section='$yls'
                          AND schedule_days.day_code='F'
                          AND curriculum_num=$cn;";
                          $result_fri = mysqli_query($conn, $sql_fri);


                          if (mysqli_num_rows($result_fri) > 0) {
                            while($row_fri = mysqli_fetch_assoc($result_fri)) {
                            $scf=$row_fri["subject_code"];
                            $ssaf=$row_fri["s_start_at"];
                            $ssef=$row_fri["s_end_at"];
                            $rnf=$row_fri["room_num"];
                            $f=0;
                            $proff='No professor yet.';
                            include 'f.inc.php';
                            $sql_proff = "SELECT first_name, last_name
                            from schedule, employee, schedule_days
                            where schedule.employee_num=employee.employee_num
                            AND schedule_days.schedule_num=schedule.schedule_num
                            AND year_level_section='$yls'
                            AND schedule_days.day_code='F'
                            AND subject_code='$scf'
                            AND curriculum_num=$cn;";
                            $result_proff = mysqli_query($conn, $sql_proff);


                            if (mysqli_num_rows($result_proff) > 0) {
                              while($row_proff = mysqli_fetch_assoc($result_proff)) {
                              $proff=$row_proff["first_name"].' '.$row_proff["last_name"];
                            }}
                            else{}

                            echo '<li class="cd-schedule__event">
                              <a data-start="'.$ssaf.'" data-end="'.$ssef.'"  data-content="event-yoga-1" data-event="event-'.$f.'" href="#0">
                                <em class="cd-schedule__name">'.$scf.' <br>'.$rnf.' <br>'.$proff.'</em>
                              </a>
                            </li>';
                          }}
                          else {

                          }
                    ?>
          </ul>
        </li>
        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Saturday</span></div>

          <ul>
            <?php
            //LIST OF CLASSES ON satSDAY


                          $sql_sat = "SELECT subject_code, s_start_at, s_end_at, room_num
                          from schedule, schedule_days
                          where schedule.schedule_num=schedule_days.schedule_num
                          AND year_level_section='$yls'
                          AND schedule_days.day_code='S'
                          AND curriculum_num=$cn;";
                          $result_sat = mysqli_query($conn, $sql_sat);


                          if (mysqli_num_rows($result_sat) > 0) {
                            while($row_sat = mysqli_fetch_assoc($result_sat)) {
                            $scs=$row_sat["subject_code"];
                            $ssas=$row_sat["s_start_at"];
                            $sses=$row_sat["s_end_at"];
                            $rns=$row_sat["room_num"];
                            $s=0;
                            $profs='No professor yet.';

                            include 's.inc.php';
                            $sql_profs = "SELECT first_name, last_name
                            from schedule, employee, schedule_days
                            where schedule.employee_num=employee.employee_num
                            AND schedule_days.schedule_num=schedule.schedule_num
                            AND year_level_section='$yls'
                            AND schedule_days.day_code='S'
                            AND subject_code='$scs'
                            AND curriculum_num=$cn;";
                            $result_profs = mysqli_query($conn, $sql_profs);


                            if (mysqli_num_rows($result_profs) > 0) {
                              while($row_profs = mysqli_fetch_assoc($result_profs)) {
                              $profs=$row_profs["first_name"].' '.$row_profs["last_name"];
                            }}
                            else{}


                            echo '<li class="cd-schedule__event">
                              <a data-start="'.$ssas.'" data-end="'.$sses.'"  data-content="event-yoga-1" data-event="event-'.$s.'" href="#0">
                                <em class="cd-schedule__name">'.$scs.' <br>'.$rns.' <br>'.$profs.'</em>
                              </a>
                            </li>';
                          }}
                          else {

                          }
                    ?>
          </ul>
        </li>
      </ul>
    </div>

    <div class="cd-schedule-modal">
      <header class="cd-schedule-modal__header">
        <div class="cd-schedule-modal__content">
          <span class="cd-schedule-modal__date"></span>
          <h3 class="cd-schedule-modal__name"></h3>
        </div>

        <div class="cd-schedule-modal__header-bg"></div>
      </header>

      <div class="cd-schedule-modal__body">
        <div class="cd-schedule-modal__event-info"></div>
        <div class="cd-schedule-modal__body-bg"></div>
      </div>

      <a href="#0" class="cd-schedule-modal__close text--replace">Close</a>
    </div>

    <div class="cd-schedule__cover-layer"></div>
  </div> <!-- .cd-schedule -->

  <script src="assets/js/util.js"></script> <!-- util functions included in the CodyHouse framework -->
  <script src="assets/js/main.js"></script>
</body>
</html>
