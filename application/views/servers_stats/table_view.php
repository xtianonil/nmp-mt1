<a href="<?php echo base_url(); ?>index.php/Servers_stats/table_view" class="btn-custom1_selected" style="text-decoration: none">Table View</a>
<a href="<?php echo base_url(); ?>index.php/Servers_stats/graph_view" class="btn-custom1_default" style="text-decoration: none">Graph View</a>

<button style="float:right" class="export_as_csv_button table_view servers_stats btn btn-default">Export as CSV</button>

<div class="formbox">

  <!--filter-->
  <div class="form_filter">
    <form method="post" action="<?php echo site_url('servers_stats/table_view')?>">
      <table>
        <tr>
          <td>Server Id: </td>
          <td>
            <select name="server_name">
              <?php foreach ($server_names as $row_sn): ?>
              <option value="<?php echo $row_sn->server_name ?>"<?php if($filters['server_name'] == $row_sn->server_name) echo 'selected' ?>><?php echo $row_sn->server_name ?></option>
              <?php endforeach ?>
            </select>
          </td>
          <td>Date: </td>
          <td>
            <?php
            $servers_stats_datepicker = array(
                'type'     => 'text',
                'name'     => 'servers_stats_date',
                'id'     => 'servers_stats_date',
                'class'    => 'datepicker',
                'value'    => $filters['servers_stats_date'],
                'readonly' => true
            );
            echo form_input($servers_stats_datepicker);
            ?>
          </td>
          <td>
            <button type="submit" class="filter_button"> Filter</button>
          </td>
        </tr>
      </table>
    </form>
  </div>  

  <br>

  <?php
  if(empty($servers_stats_of_date)){
    echo '<a style="color:red">No results found.</a>';

    //uncheck checkbox - will use later to disable export as csv
    echo '<input style="display:none" class="allow_export" type="checkbox" />';
  } else {
    //check checkbox - will use later to disable export as csv
    echo '<input style="display:none" class="allow_export" type="checkbox" checked="checked" />';
  ?>  

  <table id="servers_stats_table" class="datatable tablelayout_unfix sortable table-striped" style="padding-left: 25px;">
    <thead>
      <!--<th>Datetime</th>-->
      <th style="text-align: left;">Time</th>
      <th style="text-align: right;">Server Name</th>
      <th style="text-align: right;">cpu idle (%)</th>
      <th style="text-align: right;">mem used (kb)</th>
      <th style="text-align: right;">buff used (kb)</th>
      <th style="text-align: right;">buff free (kb)</th>
      <th style="text-align: right;">swap used (kb)</th>
    </thead>

    <tbody>

      <?php

        foreach ($servers_stats_of_date as $row_ssod) {
          
          $servers_stats_datetime = $row_ssod->servers_stats_datetime;

          date_default_timezone_set('Asia/Manila');//set timezone

          $strtotime_servers_stats_datetime = strtotime($servers_stats_datetime);
          $minute = date('H:i:s', $strtotime_servers_stats_datetime);

          echo '<tr>';
          echo '<td style="text-align: left;">' . $minute. '</td>';
          echo '<td style="text-align: right;">' . $row_ssod->server_name . '</td>';
          echo '<td style="text-align: right;">' . $row_ssod->idle . '</td>';
          echo '<td style="text-align: right;">' . number_format($row_ssod->mem_used) . '</td>';
          echo '<td style="text-align: right;">' . number_format($row_ssod->buff_used) . '</td>';
          echo '<td style="text-align: right;">' . number_format($row_ssod->buff_free) . '</td>';
          echo '<td style="text-align: right;">' . number_format($row_ssod->swap_used) . '</td>';
          echo '</tr>'; 
        }

      ?>

    </tbody>

  </table>

  <?php } ?>

</div>