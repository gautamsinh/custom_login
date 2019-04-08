<?php
/*
* This file contain Custom Registration form of User
* @author: Gautamsinh Mori<morigautam13@gmail.com>
*/

add_shortcode('custom_user_registration', 'custom_user_registration');
function custom_user_registration () 
{   
	if(isset($_POST['register'])){
		$error_msg=new WP_Error;
		if(!is_user_logged_in()){
			$user_login     = esc_attr($_POST["user_login"]);
			$user_password  = esc_attr($_POST["password"]);
			$user_email     = esc_attr($_POST["email"]);
			$first_name     = esc_attr($_POST["first_name"]);
			$last_name      = esc_attr($_POST["last_name"]);
			$cpassword      = esc_attr($_POST['cpassword']);


			//Email Validation
			if(empty($user_email)){
				$error_msg->add('field',"Email address required");
			}elseif(!is_email($user_email)){
				$error_msg->add('field', "Email address Not valid");
			}elseif(email_exists($user_email)){
				$error_msg->add('field',"Email Already Exist");
			}

			//Check user name
			if(empty($user_login)){
				$error_msg->add('field', "User login field required");
			}elseif(username_exists($user_login)){
				$error_msg->add('field', "User name already exist");
			}
			
			//Validate password
			if(empty($user_password)){
				$error_msg->add('field',"Password is required");
			}elseif($user_password!=$cpassword){
				$error_msg->add($user->get_error_message());
			}

				
			if (1 > count( $error_msg->get_error_messages())) {
				$user_data = array(
				    'user_login' => $user_login,
				    'user_pass'  => $user_password,
				    'user_email' => $user_email,
				    'first_name' => $first_name,
				    'last_name'  => $last_name,
				    'role'       => ''
				);
				wp_insert_user($user_data);
			}
		}else{

		}
	}
	
	if (is_wp_error($error_msg)) {
		foreach($error_msg->get_error_messages() as $msg) {
			echo '<p class="alert" style="color:red;">'.$msg .'</p>';
	   }
	}


	echo '
	<div class="custom-registration-wraper">
		<form action="" method="POST"  class="custom-registration-form" enctype="multipart/form-data" >
		    <div class="form-group">
		        <label class="control-label">First name : <span class="field_required">*</span></label>
		        <input type="text" name="first_name"> 
		    </div>
		    <div class="form-group">
		        <label class="control-label">Last name : <span class="field_required">*</span></label>
		        <input type="text" name="last_name"> 
		    </div>
		    <div class="form-group">
		        <label class="control-label">Email address : <span class="field_required">*</span></label>
				<input type="email" name="email"> 
		    </div>
		    <div class="form-group">
		        <label class="control-label">User name : <span class="field_required">*</span></label>
		        <input type="text" name="user_login"> 
		    </div>
		    <div class="form-group">
			    <label  class="control-label">Password <span class="field_required">*</span></label>
				<input type="password" name="password">
			</div>
			<div class="form-group">
			    <label  class="control-label">Confirm Password <span class="field_required">*</span></label>
				<input type="password" name="cpassword">
			</div>
		    <div class="form-group">
				<div class="">
		            <button type="submit" value="register" name="register" class="btn btn-default">Register yourself</button>
		        </div>
		    </div>
		</form>
	</div>';
}

?>
