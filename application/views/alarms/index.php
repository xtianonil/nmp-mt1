<div class="formbox">

  <!--filter-->
  <div class="form_filter">
    <form method="post" action="<?php echo site_url('alarms/index')?>">
      <span>
      Date:
      <?php
      $alarms_stats_datepicker = array(
          'type'     => 'text',
          'name'     => 'alarms_stats_date',
          'id'     => 'alarms_stats_date',
          'class'    => 'datepicker',
          'value'    => $filters['alarms_stats_date'],
          'readonly' => true
      );
      echo form_input($alarms_stats_datepicker);
      ?>
      </span>
      
      <button type="submit" class="filter_button"> Filter</button>
    </form>
  </div>  

  <br>

  <?php
  if(empty($alarms_stats_of_date)){
    echo '<a style="color:red">No results found.</a>';
  } else {

  ?>  

  <table id="alarms_stats_table" class="datatable tablelayout_unfix sortable table-striped">
    <thead>
      <th>Datetime occur</th>
      <th class="nosort">Alarm Description</th>
      <th class="nosort">Server Affected</th>
      <th class="nosort">Component Affected</th>
    </thead>

    <tbody>

	    <?php

	    //alarms stats records - filtered date - avoid undefined offset
	    $count_asod = 1;

	    foreach ($alarms_stats_of_date as $row_asod) {
	    $alarm_id = $row_asod->alarm_id;
	    $alarm_description = $row_asod->alarm_description;
	    $server = $row_asod->server;
	    $component = $row_asod->component;
	    $start_datetime = $row_asod->start_datetime;
	    $end_datetime = $row_asod->end_datetime;
	    $is_read = $row_asod->is_read;

      if($component == null) $component = 'none';

	    echo '<tr>';
	    echo '<td>' . $start_datetime . '</td>';
	    echo '<td>' . $alarm_description . '</td>';
	    echo '<td>' . $server . '</td>';
	    echo '<td>' . $component . '</td>';
	    echo '</tr>';

	    $count_asod++;   

	    }

	    ?>

    </tbody>

  </table>

  <?php } ?>

</div>