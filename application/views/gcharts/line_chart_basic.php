<h1><?php echo anchor('gchart_examples', 'Codeigniter gChart Examples'); ?> \ Basic Line Chart</h1>

<?php
    echo $this->gcharts->LineChart('Stocks')->outputInto('stock_div');
    echo $this->gcharts->div(800, 300);

    if($this->gcharts->hasErrors()){
        echo $this->gcharts->getErrors();
    }
?>