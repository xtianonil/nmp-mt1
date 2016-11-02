<a href="<?php echo base_url(); ?>index.php/Servers_stats/table_view" class="btn-custom1_default">Table View</a>
<a href="<?php echo base_url(); ?>index.php/Servers_stats/graph_view" class="btn-custom1_selected">Graph View</a>

<div class="formbox">

  <!--filters-->
  <div class="form_filter">
    <form method="post" action="<?php echo site_url('servers_stats/graph_view')?>">
      <span class="filterby">
      Server: 
      <select id="server_name" name="server_name">
        <?php foreach ($server_names as $row_sn): ?>
        <option value="<?php echo $row_sn->server_name ?>"<?php if($filters['server_name'] == $row_sn->server_name) echo 'selected' ?>><?php echo $row_sn->server_name ?></option>
        <?php endforeach ?>
      </select>
      </span>

      <span class="filterby">
      Stats: 
      <select id="util" name="util">
        <option value="idle"<?php if($filters['util'] == 'idle') echo 'selected' ?>>idle</option>
        <option value="memused"<?php if($filters['util'] == 'memused') echo 'selected' ?>>memory used</option>
        <option value="buffused"<?php if($filters['util'] == 'buffused') echo 'selected' ?>>buff used</option>
        <option value="bufffree"<?php if($filters['util'] == 'bufffree') echo 'selected' ?>>buff free</option>
        <option value="swapused"<?php if($filters['util'] == 'swapused') echo 'selected' ?>>swap used</option>
        </select>
      </span>

      <span>
      Date: 
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
      </span>

      <button type="submit" class="filter_button"> Filter</button>
    </form>
  </div> 

  <br>

  <?php
    if(empty($servers_stats_of_date)){
      echo '<a style="color:red">No results found.</a>';
    }else{

      if(!empty($_POST['util'])){
        $util = $_POST['util'];
      }else{
        $util = 'idle';
      }
      
      switch ($util) {
        case 'idle':
          $util = 'cpu idle in percent (%)';
          break;
        case 'memused':
          $util = 'memory used in kilobyte (kb)';
          break;
        case 'buffused':
          $util = 'buff used in kilobyte (kb)';
          break;
        case 'bufffree':
          $util = 'buff free in kilobyte (kb)';
          break;
        case 'swapused':
           $util = 'swap used in kilobyte (kb)';
          break;
        default:
           $util = 'cpu idle in percent (%)';
          break;
      }
  ?>


<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script src="<?php echo base_url('js/jquery-1.11.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/jquery-ui.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/jquery.tablesorter.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/pagelayout.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/jquery-ui-timepicker-addon.js') ?>" type="text/javascript"></script>
    <script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
      
    function drawChart() {

      var jsonData = $.ajax({
          type: 'POST',
          url: "http://192.168.0.68/nmp-emt/index.php/servers_stats/get_graphdata",
          data: { server_name : $("#server_name option:selected").val(), util : $("#util option:selected").val(), servers_stats_date : $("#servers_stats_date").val() },
          dataType: "json",
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      var options = {
        width: 800,
        height: 380,
        hAxis : { 
          title : '24 Hour Format',
          textStyle : {
            fontSize: 10,
          },
          slantedText: true, 
          slantedTextAngle: 45
        },
        vAxis : { 
          title : '<?php echo $util ?>',
          textStyle : {
            fontSize: 10,
          },
          maxValue: 20
        },
        chartArea: {
          width: "60%",
          height: "70%" 
        }
      };

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }

    </script>
  </head>



  <body>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>

  <?php } ?>

</div>
