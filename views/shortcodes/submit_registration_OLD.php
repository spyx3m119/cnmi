<script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
<?php  if( empty ($_POST) ): ?> 
	<div class="error-notification"><span>There was an error submitting your registration. Please try again.</span></div>

<?php else: ?>
<?php 
	$validate_registration = true;
	$registration_information = $_POST;
	$options = get_option( 'CMNI_settings' );
	$AuthKey = $options['CMNI_auth_key'];	

	foreach( $registration_information as $key => $registration_field ){
		switch( $key ){
			// case 'reservation_number':
			// 	if( empty($registration_field) )	{
			// 		echo 'Reservation Number cannot be empty!';
			// 		$validate_registration = false;
			// 	}
			// 	break;
			case 'first_name':
				if( empty($registration_field) )	{
					echo 'First Name cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'last_name':
				if( empty($registration_field) )	{
					echo 'Last Name cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'year_graduated':
				if( empty($registration_field) )	{
					echo 'Graduation Class cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'phone_type':
				if( empty($registration_field) )	{
					echo 'Phone Type cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'phone_number':
				if( empty($registration_field) )	{
					echo 'Phone Number cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'email_type':
				if( empty($registration_field) )	{
					echo 'Email Type cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'email_address':
				if( empty($registration_field) )	{
					echo 'Email Address cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'selected_session':
				if( empty($registration_field) )	{
					echo 'Selected Session cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'authkey':
				if( empty($registration_field) )	{
					echo 'AuthKey cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'parent_first_name':
				if( empty($registration_field) )	{
					echo 'Parent First Name cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'parent_last_name':
				if( empty($registration_field) )	{
					echo 'Parent Last Name cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'workshop_id':
				if( empty($registration_field) )	{
					echo 'Workshop Id cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'workshopname':
				if( empty($registration_field) )	{
					echo 'Workshop Name cannot be empty!';
					$validate_registration = false;
				}
				break;
			case 'sessionname':
				if( empty($registration_field) )	{
					echo 'Session Name cannot be empty!';
					$validate_registration = false;
				}
				break;
		}
	}
?>

<?php if( $validate_registration ):


$registration = array( 
	"reservation_number" =>  $registration_information['reservation_number'],
	"first_name" =>  $registration_information['first_name'],
	"last_name" =>  $registration_information['last_name'],
	"graduation_class" =>  $registration_information['year_graduated'],
	"parent_first_name" =>  $registration_information['parent_first_name'],
	"parent_last_name" =>  $registration_information['parent_last_name'],
	"phone_type" =>  $registration_information['phone_type'],
	"phone_number" =>  $registration_information['phone'],
	"email_type" =>  $registration_information['email_type'],
	"email_address" =>  $registration_information['email_address'],
	"selected_session_id" =>  $registration_information['SessionId'],
	"AuthKey" => $AuthKey,
	"company" => $registration_information['company'],
	"parent1_first_name" => $registration_information['parent_first_name'],
	"parent1_last_name" =>  $registration_information['parent_last_name'],
	"parent2_first_name" =>'',
	"parent2_last_name" => '',
	"address1" => $registration_information['address1'],
	"address2" => $registration_information['address2'],
	"city" => $registration_information['city'],
	"state" => $registration_information['state'],
	"zip" => $registration_information['zip'],
	"workshop_id" => $registration_information['workshop_id'],
	"workshopname" => $registration_information['workshopname'],
	"sessionname" => $registration_information['sessionname']
);

//var_dump($registration_information);
$ch = curl_init();
if ($registration_information['reservation_number'] =='') {
	$submit_url = 'https://reservationsync.issportals.com/service1.svc/submit_registration_withoutaccountID';	
}
else {
	$submit_url = 'https://reservationsync.issportals.com/service1.svc/submit_registration';	
}

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $submit_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $registration ) );
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen( json_encode($registration)))                                                                       
);   
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$content = curl_exec($ch);
$data = json_decode( $content, true ); 
$status	 = $data['status'];
curl_close($ch);
//echo('<script  type="text/javascript"> console.log('.$content.')</script>');

?>

<?php if( $status == 'success' ): ?>
	<div class="navistar-submit-registration">
		<div class="site-notification"><span>Notice: We will be sending additional details to your email within the next 24 hours.  Please check your inbox for a message from Caaworkshop.</span></div>
		<h1 class="thank-you">Thank You</h1>
		<div class="reservation-details">
			<span class="reservation-detail-label"><?php echo $registration_information['first_name'] . ' ' . $registration_information['last_name']; ?></span> <span>has been registered for the upcoming Workshop with the following details: </span>
			<div class="reservation-details-table">
				<table width="100%" class="table table-bordered registration-schedule-details">
					<thead>
						<tr>
							<th>Student: </th>
					        <th><?php echo $registration_information['first_name'] . ' ' . $registration_information['last_name']; ?></th>
					    </tr>
					</thead>
					<tbody>
						<tr>
					        <td class="session-schedule-left">Reservation Number:
					        </td>
					        <td class="session-schedule-right"><?php 
								if (($data['reservationNumber'] !== null) || ($data['reservationNumber'] !=='')){
									echo $data['reservationNumber']; 
								}
								else if (($registration_information['reservation_number'] !== null) || ($registration_information['reservation_number'] !== '')){
									echo $registration_information['reservation_number'];
								}
								else echo "You will be receiving an email about this within 24 hours.";
							
							?>
					        </td>
					    </tr>
					    <tr>
					        <td class="session-schedule-left">Session Name:
					        </td>
					        <td class="session-schedule-right"><?php echo $data['sessionname']; ?>
					        </td>
					    </tr>
					     <tr>
					        <td class="session-schedule-left">Location:
					        </td>
					        <td class="session-schedule-right"><?php echo $registration_information['where'] . ' ' . $registration_information['located'];?>
					        </td>
					    </tr>
					    <tr>
					        <td class="session-schedule-left">Room:
					        </td>
					        <td class="session-schedule-right"><?php echo $registration_information['meeting_room'] ;?>
					        </td>
					    </tr>
					    <tr>
					        <td class="session-schedule-left">Date:
					        </td>
					        <td class="session-schedule-right"><?php 
					        $session_date = $data['SessionStartTime']; 
					        echo date('F j, Y h:i a', strtotime($session_date));
					        ?> 
					        </td>
					    </tr>
					    <tr>
					        <td class="session-schedule-left">Direction:
					        </td>
					        <td class="session-schedule-right"><?php echo $registration_information['directions'];?>
					        </td>
					    </tr>
					</tbody>
				</table>
			</div>
		</div>	


		<a class="print-reservation-confirmation" href="javascript:void(0);" onclick="window.print();">Print Confirmation</a>
		
			<?php 
			$location_map = $registration_information['where'] . ' ' . $registration_information['located'];
			$map_query = urlencode($location_map);
			?>

		<!-- ADD to Calendar Button code -->
		<div title="Add to Calendar" class="addeventatc">
		    Add to Calendar
		    <span class="start"><?php 
					        $session_date = $data['SessionStartTime']; 
					        echo date('F j, Y h:i a', strtotime($session_date));
					        ?></span>
		    <span class="timezone">America/Los_Angeles</span>
		    <span class="title">Workshop Reservation</span>
		    <span class="description">
		    				Reservation #: <?php 
								if (($data['reservationNumber'] !== null) || ($data['reservationNumber'] !=='')){
									echo $data['reservationNumber']; 
								}
								else if (($registration_information['reservation_number'] !== null) || ($registration_information['reservation_number'] !== '')){
									echo $registration_information['reservation_number'];
								}
								else echo "You will be receiving an email about this within 24 hours.";
							
							?> <br />
							Meeting Room: <?php echo $registration_information['meeting_room'] ;?> <br />
							Direction: <?php echo $registration_information['directions'];?> 

			</span>
		    <span class="location"><?php echo $registration_information['where'] . ' ' . $registration_information['located'];?></span>
		</div>
		<div class="location_map">

		<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo $map_query; ?>&amp;t=m&amp;z=10&amp;output=embed&amp;iwloc=near" aria-label="<?php echo $location_map; ?>"></iframe>
		</div>
	</div>
<?php endif;?>

<?php endif;?>

<?php endif;?>

