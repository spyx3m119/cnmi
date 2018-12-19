jQuery(document).ready(function($){

	$(".navistar-find-reservation-form").on("submit",function(e){

		e.preventDefault();

		var $form = $(this),
			$container = $form.parent();

		if( $container.hasClass('working') ) return;

		$form.find(".error").removeClass("error");

		$("#navistar-find-reservation-errors").hide();

		if( $.trim( $("#reservation_number").val() ).length < 3 ){
			$("#reservation_number").parent().addClass('error');
		}

		if( $form.find(".error").length ) return;

		$container.addClass('working');

		$reservation_number = $("#reservation_number").val();
		$last_name = $("#last_name").val();
		$data_query = cmni_options.select_workshop + '?reservation_number=' + $reservation_number;// + '&last_name=' + $last_name;
		$my_query = encodeURI($data_query);			
		$(location).attr('href',$my_query);
	});

	$(".navistar-select-workshop-form").on("submit",function(e){

		e.preventDefault();
		var $form = $(this),
			$container = $form.parent();

		if( $container.hasClass('working') ) return;

		$form.find(".error").removeClass("error");

		$("#navistar-find-reservation-errors").hide();

		if( $.trim( $("#first_name").val() ).length < 3 ){
			$("#first_name").parent().addClass('error');
		}

		$email_address = $("#email_address").val();
		

		if( $.trim( $("#email_address").val() ).length < 5 ) {
		 	$("#email_address").parent().addClass('error');
		}
		if( !validateEmail($email_address)) { 
			$("#email_address").parent().addClass('error');
		}
		if( $.trim( $("#parent_first_name").val() ).length < 2 ){
			$("#parent_first_name").parent().addClass('error');
		}
		if( $.trim( $("#parent_last_name").val() ).length < 2 ){
			$("#parent_last_name").parent().addClass('error');
		}
		if( $.trim( $("#reservation_number").val() ).length < 3 ){
			$("#reservation_number").parent().addClass('error');
		}
		if( $form.find(".error").length ) return;
		$container.addClass('working');
		$workshop_id = $("#workshop_id").val();
		$reservation_number = $("#reservation_number").val();
		$first_name = $("#first_name").val();
		$last_name = $("#last_name").val();
		$year_graduated = $("#year_graduated").val();
		$parent_first_name = $("#parent_first_name").val();
		$parent_last_name = $("#parent_last_name").val();
		$phone_type = $("#phone_type").val();
		$phone_number = $("#phone_number").val();
		$email_type = $("#email_type").val();
		$email_address = $("#email_address").val();
		$company = $("#company").val();

		$data_query = cmni_options.session_selection + '?workshop_id=' + $workshop_id +
					'&reservation_number=' + $reservation_number +
					'&company=' + $company +
					'&first_name=' + $first_name +
					'&last_name=' + $last_name +
					'&year_graduated=' + $year_graduated +
					'&parent_first_name=' + $parent_first_name +
					'&parent_last_name=' + $parent_last_name +
					'&phone_type=' + $phone_type +
					'&phone_number=' + $phone_number +
					'&email_type=' + $email_type +
					'&email_address=' + $email_address;
		$my_query = encodeURI($data_query);			
		$(location).attr('href',$my_query);

	});

	$(".navistar-new-reservation-form").on("submit",function(e){

		e.preventDefault();
		var $form = $(this),
			$container = $form.parent();

		if( $container.hasClass('working') ) return;

		$form.find(".error").removeClass("error");
		$email_address = $("#email_address").val();
		
		//$("#navistar-find-reservation-errors").hide();

		if( $.trim( $("#first_name").val() ).length < 3 ){
			$("#first_name").parent().addClass('error');
		}
		if( $.trim( $("#last_name").val() ).length < 2 ){
			$("#last_name").parent().addClass('error');
		}
		if( $.trim( $("#email_address").val() ).length < 5 ) {
		 	$("#email_address").parent().addClass('error');
		}
		if( !validateEmail($email_address)) { 
			$("#email_address").parent().addClass('error');
		}
		if( $.trim( $("#parent_first_name").val() ).length < 2 ){
			$("#parent_first_name").parent().addClass('error');
		}
		if( $.trim( $("#parent_last_name").val() ).length < 2 ){
			$("#parent_last_name").parent().addClass('error');
		}
		if( $.trim( $("#phone_type").val() ).length < 2 ){
			$("#phone_type").parent().addClass('error');
		}
		if( $.trim( $("#phone_number").val() ).length < 2 ){
			$("#phone_number").parent().addClass('error');
		}
		if( $.trim( $("#email_type").val() ).length < 2 ){
			$("#email_type").parent().addClass('error');
		}

		if( $form.find(".error").length ) return;
		$container.addClass('working');

		$workshop_id = $("#workshop_id").val();
		$first_name = $("#first_name").val();
		$last_name = $("#last_name").val();
		$year_graduated = $("#year_graduated").val();
		$parent_first_name = $("#parent_first_name").val();
		$parent_last_name = $("#parent_last_name").val();
		$phone_type = $("#phone_type").val();
		$phone_number = $("#phone_number").val();
		$email_type = $("#email_type").val();
		$email_address = $("#email_address").val();
		$company = $("#company").val();

		$data_query = cmni_options.session_selection + '?workshop_id=' + $workshop_id +
					'&first_name=' + $first_name +
					'&company=' + $company +
					'&last_name=' + $last_name +
					'&year_graduated=' + $year_graduated +
					'&parent_first_name=' + $parent_first_name +
					'&parent_last_name=' + $parent_last_name +
					'&phone_type=' + $phone_type +
					'&phone_number=' + $phone_number +
					'&email_type=' + $email_type +
					'&email_address=' + $email_address;
		$my_query = encodeURI($data_query);			
		$(location).attr('href',$my_query);

	});

	 function validateEmail($email) {
  		emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;
  		return ( $email.length > 0 && emailReg.test($email));
	}
	$("input[type='radio']").change(function(){

        $(".session-selection-submit input[type='submit']").prop("disabled", false);
    });
});
