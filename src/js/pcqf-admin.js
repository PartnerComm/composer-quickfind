var pcqfadmin = (function($, w, undefined) {

	function init_qf_keyword_select()
	{
		$(document).ready(function() {
			$('#pcqf_keyword_select').change(function(){
			
				var term = $(this).val();
				
				var sel_data = {
					sel_term: term,
					action: 'pcqf_get_posts_by_keyword'
				};
				
				$.ajax({
					type: "POST",
					url: pcqf_ajax_admin.ajaxurl,
					data: sel_data,
					
					success: function(data){
						//alert(data);
						var id = '#pcqf_keyword_posts';
						$(id).empty();
						$(id).append(data);
						init_qf_table_rows();
					}
				});
				//return false;
				
			});

		});
	}

	function init_qf_keyword_tax_select()
	{
		$(document).ready(function() {
			$('#pcqf_keyword_tax_select').change(function(){
			
				var term = $(this).val();
				var loc = $('#pcqf_location_select').val();
				var salary = $('#pcqf_salary_select').val();
				var payment_schedule = $('#pcqf_payment_schedule_select').val();
				
				var sel_data = {
					sel_term: term,
					sel_location: loc,
					sel_salary: salary,
					sel_payment_schedule: payment_schedule,
					action: 'pcqf_get_posts_by_keyword_taxonomy'
				};
				
				$.ajax({
					type: "POST",
					url: pcqf_ajax_admin.ajaxurl,
					data: sel_data,
					
					success: function(data){
						//alert(data);
						var id = '#pcqf_keyword_posts';
						$(id).empty();
						$(id).append(data);
						init_qf_table_rows();
					}
				});
				//return false;
				
			});

		});
	}

	function init_qf_group_select()
	{
		$(document).ready(function() {
			$('#pcqf_group_select').change(function(){
			
				var term = $(this).val();
				
				var sel_data = {
					sel_group: term,
					action: 'pcqf_get_posts_by_group'
				};
				
				$.ajax({
					type: "POST",
					url: pcqf_ajax_admin.ajaxurl,
					data: sel_data,
					
					success: function(data){
						//alert(data);
						var id = '#pcqf_group_posts';
						$(id).empty();
						$(id).append(data);
						init_qf_group_table_rows();
					}
				});
				//return false;
				
			});

		});
	}

	function init_qf_group_table_rows()
	{
		$('tr.group_row').each(function(){
			var row = $(this);
			
			row.find('form.update_group_form').submit(function(){
				$(this).hide();
				var form_data = $(this).serialize();

				$.ajax({
					type: "POST",
					url: pcqf_ajax_admin.ajaxurl,
					data: form_data,
					
					success: function(data){
						row.replaceWith(data);
						//init quicktags
			            //quicktags({id : 'editgrouppost'});
			            //init tinymce
			            //tinymce.init(tinyMCEPreInit.mceInit['editgrouppost']);
			            init_qf_delete_group_keyword();
			            init_qf_add_group_keyword();
						init_qf_update_group_form();
					}
				});
				return false;
			});

			row.find('form.update_group_form').removeClass('update_group_form').addClass('update_group_form_hijacked');
		});
	}

	function init_qf_add_group_keyword() 
	{
		$('td.add_group_keyword a').click(function(){
			var row = $(this).parent().parent();
			var cell_data = $(this).parent().data();
			var data = $.param(cell_data);

			$.ajax({
				type: "POST",
				url: pcqf_ajax_admin.ajaxurl,
				data: data,

				success: function(response) {
					row.replaceWith(response);
				}
			});
			row.find('td.add_group_keyword').removeClass('add_group_keyword').addClass('add_group_keyword_hijacked');
			return false;
		});
	}

	function init_qf_add_group_keyword_form()
	{

	}

	function init_qf_delete_group_keyword()
	{
		//console.log("init delete");
		$('td.delete_group_keyword a').click(function(){
			var row = $(this).parent().parent();
			var row_data = $(this).parent().parent().data();
			var data = $.param(row_data);
			
			$.ajax({
				type: "POST",
				url: pcqf_ajax_admin.ajaxurl,
				data: data,

				success: function(response) {
					//console.log(response);
					row.remove();
				}
			});

			row.find('td.delete_group_keyword').removeClass('delete_group_keyword').addClass('delete_group_keyword_hijacked');
			return false;
		});

	}

	function init_qf_add_group_keyword_btn() 
	{
		$('td.add_group_keyword a').click(function(){
			var row = $(this).parent().parent();
			var cell_data = $(this).parent().data();
			var data = $.param(cell_data);

			$.ajax({
				type: "POST",
				url: pcqf_ajax_admin.ajaxurl,
				data: data,

				success: function(response) {
					row.replaceWith(response);
					init_qf_delete_group_keyword();
				}
			});
		});
	}

	function init_qf_update_group_form()
	{
		$('tr.group_update_row').each(function(){
			var update_row = $(this);

			update_row.find('form.update_group_post').submit(function(){
				var new_data = $(this).serialize();

				$.ajax({
					type: "POST",
					url: pcqf_ajax_admin.ajaxurl,
					data: new_data,

					success: function(updated){
						update_row.replaceWith(updated);
						//init quicktags
			            //quicktags({id : 'editgrouppost'});
			            //init tinymce
			            //tinymce.init(tinyMCEPreInit.mceInit['editgrouppost']);
						init_qf_group_table_rows();
					}
				});
				return false;
			});

			$(this).find('form.update_group_post').removeClass('update_group_post').addClass('update_group_post_hijacked');
		});
	}

	// $.fn.tinymce_textareas = function(){
	//   tinyMCE.init({
	//     skin : "wp_theme"
	//     // other options here
	//   });
	// };

	function init_qf_table_rows()
	{
		$('tr.keyword_row').each(function(){
			var row = $(this);
			
			row.find('form.update_form').submit(function(){
				$(this).hide();
				var form_data = $(this).serialize();

				$.ajax({
					type: "POST",
					url: pcqf_ajax_admin.ajaxurl,
					data: form_data,
					
					success: function(data){
						row.replaceWith(data);
						init_qf_update_form();
					}
				});
				return false;
			});

			row.find('form.update_form').removeClass('update_form').addClass('update_form_hijacked');
		});
	}

	function init_qf_update_form()
	{
		$('tr.keyword_update_row').each(function(){
			var update_row = $(this);

			update_row.find('form.update_keyword_order').submit(function(){

				var new_data = $(this).serialize();

				$.ajax({
					type: "POST",
					url: pcqf_ajax_admin.ajaxurl,
					data: new_data,

					success: function(updated){
						update_row.replaceWith(updated);
						init_qf_table_rows();
					}
				});
				return false;
			});

			$(this).find('form.update_keyword_order').removeClass('update_keyword_order').addClass('update_keyword_hijacked');
		});
	}

	function init_event_datepicker() {
		$(document).ready(function() {
			$( '.event_start_date' ).datepicker({
		        dateFormat: 'MM dd, yy',
		        onClose: function( selectedDate ){
		            $( '#uep-event-end-date' ).datepicker( 'option', 'minDate', selectedDate );
		        }
		    });
		    $( '.event_end_date' ).datepicker({
		        dateFormat: 'MM dd, yy',
		        onClose: function( selectedDate ){
		            $( '#uep-event-start-date' ).datepicker( 'option', 'maxDate', selectedDate );
		        }
		    });
		});
	}
	

	return {
		// return init function for anything that doesn't need to be called inlne
		init : function() {
			init_qf_keyword_select();
			init_qf_group_select();
			init_event_datepicker();
			init_qf_keyword_tax_select();
		}
		// return functions separately if they need to be called from the page
		// with parameters or for other reasons
		// like this:
		// placeholder: placeholder
	};


} (jQuery, window));

// initialize the pcqfadmin object
pcqfadmin.init();


