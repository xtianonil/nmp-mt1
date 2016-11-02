
 
  <?php

  if(empty($latest_alarms)){
    echo '<a style="color:red">No results found.</a>';
  } else {

      foreach ($latest_alarms as $row_la) {
        $alarm_id = $row_la->alarm_id;
        $alarm_description = $row_la->alarm_description;
        $server = $row_la->server;
        $component = $row_la->component;
        $start_datetime = $row_la->start_datetime;
        $end_datetime = $row_la->end_datetime;
        $is_read = $row_la->is_read;

        echo '<p><small>' . $start_datetime . '</small></p>';
        echo '<p><small>' . $alarm_description .  ' at the server ';
        echo  $server . '</small></p>';
        if(!empty($component)) echo  $component . '</small></p>';
        echo '<hr style="margin: 0px">'; 
      }

  }

?>

<a href="<?php echo base_url(); ?>index.php/alarms" class="">View All</a>