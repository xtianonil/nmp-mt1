<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$ci = &get_instance();
$ci->load->helper('form'); 
$ci->load->library('pagination'); 

/* $elements format:
 * 'label' => 'html_element'
 *
 */
function make_kvtable($elements) { ?>
<div class="kv_table">
<?php foreach ($elements as $label=>$elem): ?>
<div class="kv_row">
	<div class="kv_label"><?php echo $label ?></div>
	<div class="kv_value"><?php echo $elem  ?></div>
</div>
<?php endforeach ?>
</div>

<?php } 

/* $elements format:
 * 'label' => 'html_element'
 *
 */
function make_kvform($elements, $url, $actions, $hiddens = array(), $formclass='') { ?>
<form action="<?php echo site_url($url) ?>" method="post"<?php if (!empty($formclass)) echo ' class="'.$formclass.'"' ?>>
<span style="font-style: italic; font-size: small">* Required fields</span>
<div class="kv_table">
<?php foreach ($elements as $label=>$elem): ?>
<div class="kv_row">
	<div class="kv_label"><?php echo $label ?></div>
	<div class="kv_value"><?php echo $elem  ?></div>
</div>
<?php endforeach ?>
</div>
<br/>
<?php foreach ($actions as $action) {
	echo $action;
} ?>

<?php foreach ($hiddens as $name=>$val) {
	echo form_hidden($name, $val);
} ?>
</form>

<?php } 

//$elements = array(array($header=>elements), ...)
function make_kvform_multihead($elements, $url, $actions, $hiddens = array(), $formclass='') { ?>
<form action="<?php echo site_url($url) ?>" method="post"<?php if (!empty($formclass)) echo ' class="'.$formclass.'"' ?>>
<span style="font-style: italic; font-size: small">* Required fields</span>
<br><br>
<?php foreach ($elements as $header=>$subelem): ?>
<h3><?php echo $header ?></h3>
<div class="kv_table">
<?php foreach ($subelem as $label=>$elem): ?>
<div class="kv_row">
	<div class="kv_label"><?php echo $label ?></div>
	<div class="kv_value"><?php echo $elem  ?></div>
</div>
<?php endforeach ?>
</div>
<br/>
<?php endforeach ?>
<br/>
<?php foreach ($actions as $action) {
	echo $action;
} ?>

<?php foreach ($hiddens as $name=>$val) {
	echo form_hidden($name, $val);
} ?>
</form>

<?php } 

function make_kvtable_multihead($elements) { ?>
<div class="kv_table">
<?php foreach ($elements as $header=>$subelem): ?>
<h3><?php echo $header ?></h3>
<?php foreach ($subelem as $label=>$elem): ?>
<div class="kv_row">
	<div class="kv_label"><?php echo $label ?></div>
	<div class="kv_value"><?php echo $elem  ?></div>
</div>
<?php endforeach ?>
<br/>
<?php endforeach ?>
</div>

<?php } 


function make_uploadform($url, $file_types = array()) {
	//declare data as an array
	//will use later to name the CSV file defaulted to csvfile 
	$data = array('name'=>'csvfile');

	$form  = '<div class="csvupload" style="font-size: smaller">';
		//will go to Controller wallet, funtion csv
		$form .= '<form method="post" enctype="multipart/form-data" action="'.site_url($url).'">';
			//100kb file size limit from config/constant.php
			$form .= '<input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_CSV_SIZE.'"/>';
			$form .= '<label>Upload CSV: '. form_upload($data) .'</label>';

			//display radio button Wallets and Wallet notifications
			foreach($file_types as $key => $value) {
				$form .= '<input type="radio" name="file_type" value="'.$key.'">'.$value . '&nbsp;&nbsp;';
			}

			$form .= ui_button_add('Upload');//button Upload
			$form .= '<input type="reset" class="actionbutton" value="Remove File"/>';//button Remove
			$form .= '<button class="actionbutton helpbutton">Help</button>';//button Help
		$form .= '</form>';
	$form .= '</div>';

	return $form;
}

function ui_csvhelp_js() {
return <<<'JS'
$('.helpcontent').dialog({'autoOpen':false,'closeOnEscape': true,'height':'500','width':'650'});
$('.helpbutton').click(function(event){
	event.preventDefault(); 
	$('.helpcontent').css('display','block');
	$('.helpcontent').dialog('open');
	return false;
});
JS;
}

function ui_csvhelp_css() {
return <<<'CSS'
.helpcontent .code {
	font-family: monospace;
	white-space: pre;
	height: 5em;
	overflow: auto;
	background: white;
	border: 1px dotted black;
	margin-top: 1em;
}
.helpcontent ul {
	padding: 1em;
}
.helpcontent li {
	margin-bottom: 1em;
}
.csvupload{
	position: absolute;
	margin-top: -1.5em;
	right: 27px;
}
CSS;
}

function make_pagination($url, $currpage=1, $totalrows=0, $perpage=10, $filters = array()) {
	//init dropdown per
	$dd_perpage = array(5=>'5', 10=>'10', 25=>'25', 50=>'50', 100=>'100');
	$filterstr = '';
	$filterarr = array();

	//put each key val onto filterarr
	//implode filterarr with ampersand and put onto filterstr
	if (!empty($filters)) {
		foreach($filters as $key=>$val) {
			if ($val!=='') $filterarr[] = $key.'='.$val;
		}
		$filterstr = implode('&', $filterarr);
	}
	
	//form pagination
	$form  = '<div class="form_pagination">';
	$form .= '<form class="pagination_form" method="get" action="'.site_url($url).'">';//click pagination link will go here
	$form .= 'Page ';
	$totalpages = ceil($totalrows/$perpage);//ceil - round number to its nearest integer, row per page

	//only echo pagination links if
	if ($currpage >0 && $currpage <= $totalpages) {
		$form .= '<span class="entries_nav">';
		if ($currpage != 1)			  	$form .= '<a href="'.site_url($url).'?current=1&perpage='.$perpage.(!empty($filterstr) ? '&'.$filterstr : '').'"/>&larr;</a>';
		if ($currpage-2> 0)			  	$form .= '<a href="'.site_url($url).'?current='.($currpage-2).'&perpage='.$perpage.(!empty($filterstr) ? '&'.$filterstr : '').'"/>'.($currpage-2).'</a>';
		if ($currpage-1> 0)			  	$form .= '<a href="'.site_url($url).'?current='.($currpage-1).'&perpage='.$perpage.(!empty($filterstr) ? '&'.$filterstr : '').'"/>'.($currpage-1).'</a>';
						 		   	  	$form .= '<span class="currpage">'.($currpage).'</span>';
		if ($currpage+1 <= $totalpages) $form .= '<a href="'.site_url($url).'?current='.($currpage+1).'&perpage='.$perpage.(!empty($filterstr) ? '&'.$filterstr : '').'"/>'.($currpage+1).'</a>';
		if ($currpage+2 <= $totalpages) $form .= '<a href="'.site_url($url).'?current='.($currpage+2).'&perpage='.$perpage.(!empty($filterstr) ? '&'.$filterstr : '').'"/>'.($currpage+2).'</a>';
		if ($currpage+3 <= $totalpages) $form .= '<a href="'.site_url($url).'?current='.($totalpages).'&perpage='.$perpage.(!empty($filterstr) ? '&'.$filterstr : '').'"/>&rarr;</a>';
		$form .= '</span>&nbsp;&nbsp;'; 
	}

	//form dropdown per
	$form .= '<span class="entries_perpage">  '.ui_dropdown('perpage', $dd_perpage, $perpage, null) . ' per page</span>, total of '.$totalrows.' records.';
	
	foreach ($filters as $key=>$val) {
		$form .= '<input type="hidden" name="'.$key.'" value="'.$val.'"/>';
	}
	
	$form .= '</form>';
	$form .= '</div>';
	return $form;
}

function ui_input($name, $value, $error, $class='') {
	$data = array('name'=>$name,
				  'value'=>$value,
				  'class'=>trim((!empty($error) ? 'field_error ' : ' ').$class)
			  );
	return form_input($data) . (!empty($error) ? ' <span class="error_inline">'.$error.'</span>' : '');
} 

function ui_input_password($name, $error) {
	$data = array('name'=>$name,
				  'type'=>'password',
				  'class'=>(!empty($error) ? ' field_error' : '')
			  );
	return form_input($data) . (!empty($error) ? ' <span class="error_inline">'.$error.'</span>' : '');
} 

function ui_input_readonly($text, $error, $class='') {
	return '<div'.(empty($class) ? '' : ' class="'.$class.'"').'>'.$text.'</div>' . (!empty($error) ? ' <span class="error_inline">'.$error.'</span>' : ''); 	
}

function ui_radiobutton($name, $data, $value, $error, $class='') {
	$classes = trim((!empty($error) ? "field_error": null).' '.$class);
	$classes = !empty($classes) ? $classes = 'class="'.$classes.'"' : '';
	return form_radio($name, $data, $value, $classes) . 
		   (!empty($error) ? ' <span class="error_inline">'.$error.'</span>' : '');
}
function ui_dropdown($name, $data, $value, $error, $class='') {
	$classes = trim((!empty($error) ? "field_error": null).' '.$class);
	$classes = !empty($classes) ? $classes = 'class="'.$classes.'"' : '';
	return form_dropdown($name, $data, $value, $classes) . 
		   (!empty($error) ? ' <span class="error_inline">'.$error.'</span>' : '');
	
}
function ui_button_update($name="Update") {
	$data = array('value'=>$name,
				  'class'=>'actionbutton formbutton_update',
				  'type'=>'submit'
			  );
	return form_input($data);
}

function ui_button_cancel($url, $name = 'Cancel') {
	return ui_linkbutton($name, $url, 'actionbutton formbutton_cancel');
}	

function ui_button_add($name="Add") {
	$data = array('value'=>$name,
				  'class'=>'actionbutton formbutton_update',
				  'type'=>'submit'
			  );
	return form_input($data);
}

function ui_linkbutton($name, $url, $class) {
	return '<a href="'.site_url($url).'" class="'.$class. '">' .$name.'</a>';
}

function ui_checkbox($name, $value, $checked, $error) {
	$data = array('name'=>$name,
				  'value'=>$value,
				  'checked'=>$checked,
				  'class'=>(!empty($error) ? ' field_error' : '')
			  );
	return form_checkbox($data) . (!empty($error) ? ' <span class="error_inline">'.$error.'</span>' : '');
}

function ui_datepicker($name, $value, $error) {
	$data = array('name'=>$name,
				  'value'=>$value,
				  'class'=>'datepicker' . (!empty($error) ? ' field_error' : '')
			  );
	return form_input($data) .(!empty($error) ? ' <span class="error_inline">'.$error.'</span>' : '');

}

function ui_textarea($name, $value, $error, $class = '') {
	$data = array('name'=>$name,
				  'value'=>$value,
				  'class'=>trim((!empty($error) ? 'field_error' : '').' '.$class),
				  'rows'=>5,
				  'cols'=>80
			  );
	return form_textarea($data) .(!empty($error) ? ' <span class="error_inline">'.$error.'</span>' : '');

}

//value = array('hour'=>$hour, 'minute'=>$minute)
function ui_timepicker($name, $value, $error, $military=false) {
	$errorclass = !empty($error) ? 'class="field_error"': null;
	$hours = array();
	$minutes = array();
	$indicator = array('am'=>'am', 'pm'=>'pm');
	
	$hour_start = 1;
	$hour_end = 12;
	if ($military) {
		$hour_start = 0;
		$hour_end = 23;
		$indicator = array();
	}
	
	foreach (range($hour_start, $hour_end) as $hour) {
		$val = str_pad($hour, 2, '0', STR_PAD_LEFT);
		$hours[$val] = $val;
	}
	
	foreach (range(0, 59) as $minute) {
		$val = str_pad($minute, 2, '0', STR_PAD_LEFT);
		$minutes[$val] = $val;	
	}
	// print_r($value);
	return form_dropdown($name.'_hour', $hours, $value['hour'], $errorclass).' : '.
		   form_dropdown($name.'_minute', $minutes, $value['minute'], $errorclass).' '.
		   (!empty($indicator) ? form_dropdown($name.'_indicator', $indicator, $value['indicator'], $errorclass) : '').
		   (!empty($error) ? ' <span class="error_inline">'.$error.'</span>' : '');
}

function ui_addlink($url, $text) {
	return '<a href="'.site_url($url).'">+ '.$text.'</a>';
}
?>
