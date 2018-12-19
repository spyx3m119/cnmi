<?php  if( empty($_GET) ) :?>
	
	<div class="navistar-session-selection error">
		<span class="error-message">An error has occured. Please try again.</span>
	</div>
		
<?php else: ?>
<?php 


$reservation_number = $_GET['reservation_number'];
$workshop_id = $_GET['workshop_id'];
$demographic_information = $_GET;
$options = get_option( 'CMNI_settings' );
$AuthKey = $options['CMNI_auth_key'];
$qry_str = array(
	"workshopID" => $workshop_id,
	"AuthKey" => $AuthKey
);
//var_dump( date('F j, Y h:i a', strtotime('1544896800000-0600'));
//echo('<script  type="text/javascript"> console.log('.$_GET['co'] .')</script>');

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, 'https://reservationsync.issportals.com/service1.svc/session_selection');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $qry_str) );
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen( json_encode($qry_str)))                                                                       
);   
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$content = curl_exec($ch);
curl_close($ch);

$data = json_decode( $content, true ); 
$student_data = $data['data'];
//echo('<script  type="text/javascript"> console.log('.$content.')</script>');
?>

<?php 
	$session_schedules_by_day = array(); 
	$session_schedules_by_time = array();
	$number_of_sessions = count( $student_data["Sessions"] );

	foreach ($student_data["Sessions"] as $i=>$session_opt){
		if( $i == 0 ){
			$datetime1 = new DateTime( date('Y-m-d', strtotime($session_opt["StartDateTime"])) );
			$datetime2 = new DateTime( date('Y-m-d', strtotime($session_opt["StartDateTime"])) );
			$interval = date_diff($datetime1, $datetime2, true);
		}else{
			$datetime2 = new DateTime( date('Y-m-d', strtotime($session_opt["StartDateTime"])) );
			$interval = date_diff($datetime1, $datetime2, true);
			$datetime1 = new DateTime( date('Y-m-d', strtotime($session_opt["StartDateTime"])) );
		}

		if(	$interval->d == 1 ){
			array_push( $session_schedules_by_day, $session_schedules_by_time );
			$session_schedules_by_time = array();
		}

		array_push( $session_schedules_by_time, $session_opt );

		if( $i == $number_of_sessions - 1 ){
			array_push( $session_schedules_by_day, $session_schedules_by_time );
			$session_schedules_by_time = array();
		}
	}
?>

<?php if( isset($data['Message']) ): ?>

<div class="navistar-session-selection error">
	<span class="error-message">An error occured.</span>
</div>


<?php else: ?>
<?php 
$options = get_option( 'CMNI_settings' );
?>
<div class="navistar-session-selection">
	<form action="<?php echo $options['CMNI_submit_registration'];//echo site_url('submit-registration');?>" method="post">
		<div class="student-details">
			<table width="100%" class="table table-bordered session-schedule-details">
				<thead>
					<tr>
						<th>Student: </th>
				        <th><?php echo $demographic_information['first_name'] . ' ' . $demographic_information['last_name']; ?></th>
				    </tr>
				</thead>
				<tbody>
					<tr>
				        <td class="session-schedule-left">When:
				        </td>
				        <td class="session-schedule-right"><?php 
						$date1 = date('F j, Y', strtotime($student_data["EventDate1"]));
						$date2 = date('F j, Y', strtotime($student_data["EventDate2"]));
						echo 'Saturday, '.$date1 . " and " . 'Sunday, '. $date2; 
						?>
				        </td>
				    </tr>
				    <tr>
				        <td class="session-schedule-left">Where:
				        </td>
				        <td class="session-schedule-right"><?php echo $student_data['FacilityName']; ?>
				        <input type="hidden" name="where" value="<?php echo $student_data['FacilityName']; ?>">
				        </td>
				    </tr>
				    <tr>
				        <td class="session-schedule-left">Located:
				        </td>
				        <td class="session-schedule-right"><?php 
						$located = $student_data['Address1'] . ' ' . $student_data['Address2'] . ' ' .  $student_data['City'] . ' ' . $student_data['State'] . ' ' . $student_data['Zip'];
						echo $located; 
						?>
							<input type="hidden" name="located" value="<?php echo $located; ?>">
							<input type="hidden" name="address1" value="<?php echo $student_data['Address1']; ?>">
							<input type="hidden" name="address2" value="<?php echo $student_data['Address2']; ?>">
							<input type="hidden" name="city" value="<?php echo $student_data['City']; ?>">
							<input type="hidden" name="state" value="<?php echo $student_data['State']; ?>">
							<input type="hidden" name="zip" value="<?php echo $student_data['Zip']; ?>">
				        </td>
				    </tr>
				    <tr>
				        <td class="session-schedule-left">Phone:
				        </td>
				        <td class="session-schedule-right"><?php echo $student_data['Phone']; ?>
				        <input type="hidden" name="phone" value="<?php echo $student_data['Phone']; ?>">
				        </td>
				    </tr>
				    <tr>
				        <td class="session-schedule-left">Room:
				        </td>
				        <td class="session-schedule-right"><?php echo $student_data['RoomNumber']; ?>
						<input type="hidden" name="meeting_room" value="<?php echo $student_data['RoomNumber']; ?>">
				        </td>
				    </tr>
				    <tr>
				        <td class="session-schedule-left">Directions:
				        </td>
				        <td class="session-schedule-right"><?php echo $student_data['Directions']; ?>
						<input type="hidden" name="directions" value="<?php echo $student_data['Directions']; ?>">
				        </td>
				    </tr>
			   </tbody>
			</table>
		</div>

		<div class="session-schedule col-<?php echo count($session_schedules_by_day);?>">
		    <?php foreach( $session_schedules_by_day as $i=>$session_day ): ?>
		    	<div class="column column-number-<?php echo $i+1;?>">
			    	<h5 class="session-schedule-day"><?php echo date('F j, Y', strtotime($session_day[$i]["StartDateTime"])); ?></h5>
					<table class="table table-bordered session-schedule-time">
						<thead>
							<tr>
						        <th>Select</th>
						        <th>Session #</th>
						        <th>Time</th>
						    </tr>
						</thead>
					    <tbody>
					    	<?php foreach( $session_day as $session_time ): ?>

					        <tr>
					            <td class="session-schedule-table-btn">
					                <input <?php if( $session_time["Closed"] ) echo "disabled"; ?> class="selected-session radio" name="SessionId" type="radio" value="<?php echo $session_time['SessionId'];?>">
					            </td>
					            <td class="session-schedule-table-session-number"> <?php echo $session_time["Number"]; ?> </td>
					            
					            <td class="session-schedule-table-session-start-time"> 
					            	<?php 
					            		$session_start_time = date('h:i a', strtotime($session_time["StartDateTime"]));
					            		echo $session_start_time;
					            	?> 
									<input type="hidden" name="session_start_time" value="<?php echo $session_start_time; ?>">

					            </td>
					            	<input type="hidden" name="session_end_time" value="<?php echo $session_time['Name']; ?>">
					        </tr>

					    	<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endforeach; ?>
			<div style="clear:both;"></div>
		</div>
		
		<!-- 
			Pass hidden details
		-->
		<input type="hidden" name="parent_first_name" value="<?php echo $demographic_information['parent_first_name']; ?>">
		<input type="hidden" name="parent_last_name" value="<?php echo $demographic_information['parent_last_name']; ?>">
		<input type="hidden" name="phone_type" value="<?php echo $demographic_information['phone_type']; ?>">
		<input type="hidden" name="email_type" value="<?php echo $demographic_information['email_type']; ?>">
		<input type="hidden" name="email_address" value="<?php echo $demographic_information['email_address']; ?>">
		<input type="hidden" name="authkey" value="<?php echo $qry_str['AuthKey']; ?>">
		<input type="hidden" name="company" value="<?php echo $demographic_information['company']; ?>">
		<input type="hidden" name="workshop_id" value="<?php echo $demographic_information['workshop_id']; ?>">
		<input type="hidden" name="workshopname" value="<?php echo $student_data['Name']; ?>">
		<input type="hidden" name="first_name" value="<?php echo $demographic_information['first_name'] ?>">
		<input type="hidden" name="last_name" value="<?php echo $demographic_information['last_name']; ?>">
		<input type="hidden" name="year_graduated" value="<?php echo $demographic_information['year_graduated']; ?>">
		<input type="hidden" name="reservation_number" value="<?php echo $reservation_number; ?>">
		<!-- 
			End hidden details
		-->

		<div class="session-selection-submit">
			<input type="submit" disabled name="Register" value="Register">
			<span class="submit-reminder">Please select an available session and click register.</span>
		</div>
	</form>
</div>

<?php endif; ?>
<?php endif; ?>