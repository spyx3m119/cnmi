<?php 
$reservation_number = $_GET['reservation_number'];
if( empty($reservation_number) || !isset($reservation_number)  ){
    echo '
        <div class="navistar-session-selection error">
            <span class="error-message">Unknown reservation number</span>
        </div>
        ';
//    exit;
}
$options = get_option( 'CMNI_settings' );
$AuthKey = $options['CMNI_auth_key'];
$company_name = $options['CMNI_company_name'];

$post_fields = [
    'AuthKey' => $AuthKey,
    'Company' => $company_name,
    'ReservationNumber' => $reservation_number
    ];

$data_string = json_encode($post_fields);

$ch = curl_init();

//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, 'https://reservationsync.issportals.com/service1.svc/find_reservation');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$content = curl_exec($ch);
if (curl_error($ch)) {
    $error_msg = curl_error($ch);
}
curl_close($ch);

if (isset($error_msg)) {
    var_dump($error_msg);
    echo('<script  type="text/javascript"> console.log('.$error_msg.')</script>');
}

$data = json_decode( $content, true ); 
$student_data = $data;
echo('<script  type="text/javascript"> console.log('.$content.')</script>');
if ($student_data['Message'] !== ''){ 
    if ($student_data['Message'] == 'Reservation Number Not Found'){
        echo('<div class="curl-msg">
    <ul>
        <li>We can not find the Reservation Number that you entered.</li>
        <li>Please check your Reservation Number carefully.</li>
        <li>You may talk to a live Reservation Agent at <strong>(866) 642-4752</strong> who will help you through this process.</li>
    </ul>
    </div>
    <div class="navistar-find-reservation" >
    <form method="post" class="navistar-form navistar-find-reservation-form" >
        <fieldset>
            <label for="reservation_number">Reservation #</label>
            <input id="reservation_number" name="reservation_number" type="text" value="' .$reservation_number . '" />
            <span class="error-message" >* Required</span>
        </fieldset>
        <button type="submit">Find Reservation</button>
    </form>        
</div>

');

    }
} 
?>

<?php 
if ($student_data['Message'] == ''){ ?>
<!-- <div><img  class="image-head" width="525" height="175" src="https://caamarketing.issportals.com/wp-content/uploads/2018/12/Stay-connected-2.png"></div> -->
 <div id="navistar-find-reservation-errors" class="alert" >
    <span>You are currently registered for Dec 15 2018 at 2:00 PM, in Lake Charles, LA</span>
    
</div>
<section>
<div class="navistar-find-reservation" >
    <form method="post" class="navistar-form navistar-select-workshop-form">
        <fieldset>
            <label for="the_student">The Student</label>
            <input id="the_student" name="the_student" placeholder="The Student" type="text" value="Student - <?php echo $student_data['first_name'] . ' ' . $student_data['last_name']; ?>" disabled />
            
        </fieldset>
        <fieldset>
            <label for="reservation_number">Reservation Number</label>
            <input id="reservation_number" name="reservation_number" placeholder="Reservation Number" type="text" value="<?php echo $student_data['reservation_number']; ?>" disabled />
        </fieldset>
        <fieldset>
            <label for="first_name">Student First Name</label>
            <input id="first_name" name="first_name" placeholder="First Name" type="text" value="<?php echo $student_data['first_name']; ?>" />
            <span class="required_field" >* Required</span>
        </fieldset>
        <fieldset>
            <label for="last_name">Student Last Name</label>
            <input id="last_name" name="last_name" placeholder="Last Name" type="text" value="<?php echo $student_data['last_name']; ?>" />
        </fieldset>
        <fieldset>
            <label class="showme" for="graduation_class">Graduation Class </label>
            <!-- <input id="graduation_class" name="graduation_class" placeholder="Graduation Class" type="number" value="<?php //echo $student_data['graduation_class']; ?>" /> -->
            <select name="year_graduated" id="year_graduated" >
                <option value="2019" <?php if ($student_data['graduation_class'] == '2019') echo('selected'); ?>>2019</option>
                <option value="2020" <?php if ($student_data['graduation_class'] == '2020') echo('selected'); ?>>2020</option>
                <option value="2021" <?php if ($student_data['graduation_class'] == '2021') echo('selected'); ?>>2021</option>
                <option value="2022" <?php if ($student_data['graduation_class'] == '2022') echo('selected'); ?>>2022</option>
                <option value="2023" <?php if ($student_data['graduation_class'] == '2023') echo('selected'); ?>>2023</option>
            </select>
        </fieldset>
        <fieldset>
            <label for="parent_first_name">Parent First Name</label>
            <input id="parent_first_name" name="parent_first_name" placeholder="Parent First Name " type="text" value="<?php echo $student_data['parent_first_name']; ?>" />
            <span class="required_field" >* Required</span>
        </fieldset>
        <fieldset>
            <label for="parent_last_name">Parent Last Name</label>
            <input id="parent_last_name" name="parent_last_name" placeholder="Parent Last Name " type="text" value="<?php echo $student_data['parent_last_name']; ?>" />
            <span class="required_field" >* Required</span>
        </fieldset>
        <fieldset >
            <label class="showme" for="phone_number">Phone Number</label>
            <select hidden class="half" name="phone_type" id="phone_type">
                <option <?php if ($student_data['phone_type'] == 'Cell') echo('selected'); ?> value="Cell">Cell</option>
                <option <?php if ($student_data['phone_type'] == 'Home') echo('selected'); ?> value="Home">Home Phone</option>
                <option <?php if ($student_data['phone_type'] == 'Cell') echo('selected'); ?>value="Business">Work Landline</option>
            </select>
            <input class="half" id="phone_number" name="phone_number" placeholder="Phone Number" type="text" value="<?php echo $student_data['phone_number']; ?>" />
            
        </fieldset>
        <fieldset >
            <label class="showme" for="email_type">Email Address</label>
            <select class="half" name="email_type" id="email_type">
                <option value="Parent" <?php if ($student_data['email_type'] == 'Parent') echo('selected'); ?>>Parent</option>
                <option value="Student" <?php if ($student_data['email_type'] == 'Student') echo('selected'); ?>>Student</option>
                <option value="Other" <?php if ($student_data['email_type'] == 'Other') echo('selected'); ?>>Other</option>
            </select>
            <input class="half" id="email_address" name="email_address" placeholder="Email Address" type="text" value="<?php echo $student_data['email_address']; ?>" />
            <span class="required_field" >* Required</span>
        </fieldset>
         <fieldset class="hide_me">
            <label for="workshop_id">Workshop ID</label>
            <input id="workshop_id" name="workshop_id" placeholder="Workshop Id" type="text" value="<?php echo $student_data['workshop']; ?>" disabled/>
        </fieldset>
         <fieldset class="hide_me">
            <label for="company">Company</label>
            <input id="company" name="company" type="text" value="CAA" disabled/>
        </fieldset>
        <button type="submit">Find Available Workshop</button>
    </form>        
</div>
<?php } ?>
</section>