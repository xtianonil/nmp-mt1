$(document).ready(function() {
	var navbarHeight = 100 - Math.ceil(parseInt($('#webtool_navbar').css('marginTop'))/$(window).height() * 100);
	$('#webtool_navbar').css('height', navbarHeight + '%');

	$('.pagination_form select[name="perpage"]').change(function() {
		$('.pagination_form').submit();	
	});

	$('.charcount_input textarea').keyup(function (){
		$(this).parent().next('.charcount:first').find('span').html($(this).val().trim().length);
	});

	$('#message_type').change(function(){
		if ($('#message_type option:selected').attr('id') == "message_type_expiry") {
			$('#preexpiry_offset').val('');
			$('#preexpiry_offset').attr('disabled', 'disabled');
			$('#preexpiry_offset').parent('div').css('color', 'lightgray');	
			
		} else {
			$('#preexpiry_offset').removeAttr('disabled');
			$('#preexpiry_offset').parent('div').css('color', 'black');	
		}	
	});
	
	add_sort_tables();
	
});
function add_sort_tables() {
	
	$.each($(".sortable"), function (i,table) {
		var options = {}; 
		
		$.each($(this).find("thead tr .parseDate"), function (j,k) {
				options[$(this).index()] = {'sorter':'dateSorter'};
		});
		
		$.each($(this).find("thead tr .nosort"), function (j,k) {
			options[$(this).index()] = {'sorter':false};
		});
		$(this).tablesorter({'headers':options});
	}); 
		
}

$.tablesorter.addParser({
    id: "dateSorter",
    is: function (s) {
        return false;
    },
    format: function (s, table) {
        return new Date(s).getTime() || '';
    },
    type: "numeric"
});
