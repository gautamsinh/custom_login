<?php
/*
* This file contain Custom Login form of User
* @author: Gautamsinh Mori<morigautam13@gmail.com>
*/

add_shortcode('custom_user_login', 'custom_user_login');
function custom_user_login () 
{   
	if(isset($_POST['login'])){
		$error_msg=new WP_Error;
		if(!is_user_logged_in()){
			
			$user_login     = esc_attr($_POST["user_login"]);
			$user_password  = esc_attr($_POST["password"]);
			
			if(isset($_POST["remember_me"])){
				$user_rember_me= true;
			}else{
				$user_rember_me= false;
			}
			
			//validate Login
			if(empty($user_login)){
				$error_msg->add('field', "User login field required");
			}else{	
				if(is_email($user_login)){
					$user = get_user_by('email',$user_login);
					if (empty($user)) {
						$error_msg->add('field', "We can not find  account for this email address");
					}
				}else{
					$user = get_user_by('login',$user_login);

	        		if (empty($user)) {
	        			$error_msg->add('field', "We can not find  account for this user name");	
	        		}
				}
			}
			
			//Validate password
			if(empty($user_password)){
				$error_msg->add('field',"Password is required");
			}

			if (1 > count( $error_msg->get_error_messages())) {
				$creds = array();
				$creds['user_login'] = $user_login;
				$creds['user_password'] = $user_password;
				$creds['remember'] = $user_rember_me;
				$user = wp_signon($creds,false);
				if (is_wp_error($user)){
					$error_msg->add('field',"You have entered an Incorrect Password");
				}else{
					$userID = $user->ID;
					wp_set_current_user( $userID, $user_login );
					wp_set_auth_cookie( $userID, true, false );
					do_action( 'wp_login', $user->user_login, $user );
				}
			}
		}else{
		}
	}
	
	if (is_wp_error($error_msg)) {
		foreach($error_msg->get_error_messages() as $msg) {
			echo '<p class="alert" style="color:red;">'.$msg .'</p>';
	   }
	}

	if(!is_user_logged_in()){
		echo '
		<div class="custom-login-wraper">
			<form action="" method="POST"  class="custom-login-form" enctype="multipart/form-data" >
			    <div class="form-group">
			        <label class="control-label">User name or Email : <span class="field_required">*</span></label>
			        <input type="text" name="user_login"> 
			    </div>
			    <div class="form-group">
				    <label  class="control-label">Password <span class="field_required">*</span></label>
					<input type="password" name="password">
				</div>
				 <div class="form-group">
					<input type="checkbox" name="remember_me" value="1"> Rember me
				</div>
			    <div class="form-group">
					<div class="">
			            <button type="submit" value="login" name="login" class="btn btn-default">Login</button>
			        </div>
			    </div>
			</form>
		</div>';
	}else{
		echo '<a  class="btn" href="'.wp_logout_url( get_permalink()).'">Logout</a>';
	}
}

?>
