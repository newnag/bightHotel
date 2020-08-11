<?php
session_start();

require_once dirname(__DIR__).'/classes/dbquery.php';
require_once dirname(__DIR__).'/classes/preheader.php';
require_once './../../plugin/autoload.php';
$secret = '6LeynKYZAAAAAFrqRgy2RGI6qDc0MfNezqNhajXF';
$recaptcha = new \ReCaptcha\ReCaptcha($secret);
getData::init();
$dbcon = new DBconnect(); 
$password = md5(FILTER_VAR($_REQUEST['password'],FILTER_SANITIZE_STRING,FILTER_SANITIZE_MAGIC_QUOTES));
$encodePWD = password_hash($password, PASSWORD_BCRYPT);
$gRecaptchaResponse = $_REQUEST['tokenReCaptcha'];
$remoteIp = $_SERVER['REMOTE_ADDR'];
 
$resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
		->setExpectedAction('login')
		->setScoreThreshold(0.5)
		->verify($gRecaptchaResponse);

// if ($resp->isSuccess()) { 
if ( 1 == 1) { 
	
	if(isset($_POST['action'])) {
		switch($_POST['action']){
		
			case'login': 

				$sql = "SELECT * FROM user WHERE email = :uname ";
				$value = array(
					':uname' => $_POST['username']
				);
				$result = $dbcon->fetchObject($sql,$value);

				// echo json_encode([
				// 	'result' => $result,
				// 	'uname' => $_POST['username'],
				// 	'password' => $password,
				// ]);
				// exit();

				if(password_verify( $password , $result->password)){
				
					if($result->confirm_regis =='yes'){
		  
						$_SESSION['user_id']=$result->member_id;
						$_SESSION['modal_action']='success';	
						$_SESSION["login_user_id"]=$result->member_id;
						$_SESSION["uname"]=$result->username;
						if($result->member_type !='' && $result->status_user =='1'){
				 
							$_SESSION['admin']='yes';
							$type = getData::valuefromkey('user_type','user_type','id',$result->member_type);
							$_SESSION['role']=$type['user_type'];	
							$_SESSION['active_as']='admin';	
							$_SESSION['display_page']='backend';
							$lan = explode(',',$result->language);
							$_SESSION['backend_language']= $lan['0'];
							$_SESSION['available_language']=$result->language;
							$_SESSION['language_templete']=$result->language_templete;
						}
					}
					elseif($result->confirm_regis==''){
					 
						$_SESSION['user_id']=$result->member_id;
						$_SESSION['modal_action']='login';	
					}else{
				 
						$_SESSION['user_id']=$result->member_id;
						$_SESSION['modal_action']='confirm';	
					}
				}else{
					 
					$_SESSION['modal_action'] =' login';	
				}
				echo json_encode($_SESSION);

		
		break;
		case'resetpass':
			$email = FILTER_VAR($_REQUEST['email'],FILTER_SANITIZE_EMAIL,FILTER_SANITIZE_MAGIC_QUOTES);
	        $result = getData::check_email($email); 
	        if($result){
				$randpass = getData::randompassword(8);
				$password = md5($randpass);
				$encodePWD = password_hash($password, PASSWORD_BCRYPT); 
				$table = "user";
		        $set = "password = '".($encodePWD)."'";
		        $where = "email = '".$email."'";
				$result = $dbcon->update($table, $set, $where);

				if($result['message'] == 'OK'){
					/* get details before send mail */
					$getMail = "SELECT info_id,info_type,info_title,text_title,info_link,attribute FROM  web_info 
					WHERE info_type = 'system_email' ORDER BY info_id ASC ";
					$resultMail = $dbcon->query($getMail); 
					$getContact = "SELECT title,thumbnail,email FROM contact_sel";
					$resultContact = $dbcon->fetchObject($getContact,[]); 
					/* ส่งอีเมลแจ้งข้อมูลการสั่งซื้อสินค้า */
					$mail = array();  
					$mail['host'] = trim($resultMail[0]['attribute']);
					$mail['port'] = trim($resultMail[1]['attribute']); 
					$mail['user'] = trim($resultMail[2]['attribute']);
					$mail['password'] = trim($resultMail[3]['attribute']); 
					$mail['logo_web'] = $resultContact->logo; 
					$mail['store_name'] = $resultContact->title; 
					$mail['cont_email'] = $email;
					$mail['cont_email'] = $email;
					$message = 'You have a new password : '.$randpass;
					$subject = 'Your password to login to backend has been changed on date: '.date('d - m - Y').'';
					$statusEmail = getData::sendemailnew_google(  
						array(   
							'SMTP_USER' => $mail['user'],
							'SMTP_PASSWORD' => $mail['password'],
							'SMTP_HOST' => $mail['host'],
							'SMTP_PORT' => $mail['port'],
							'mail_system' => $resultContact->email, 
							'sendFromName' => $mail['store_name'],
							'email' => $resultContact->email,
							'subject' => $subject,
							'addBcc' => array( 
											array( 
												'email' => $mail['cont_email'],
												'name'=> "Wynnsoft Admins"
											),
										), 
							'content' =>  $message, 
						)
					);
					$output['text'] = "Your password has been reset Please check in your register email.";
					$output['message'] = "success";
				}


	        }
	        else{
	        	$output['text'] = "Invalid email address.";
	            $output['message'] = "not_success";
	        }
	        echo json_encode($output);	
		break;

		case'register': 
			$email = $_REQUEST['email'];
			$result = getData::check_email($email);
	        $output = array();
	        if (!$result) { 
	        	$table = "user";
		        $field = "social_type,member_type,username,display_name,password,email,phone,date_regis,status_user";
		        $value = "	'website',
		        			'4',
		        			'".$_REQUEST['display']."',
		        			'".$_REQUEST['display']."',
							'".$encodePWD."',  
		        			'".$_REQUEST['email']."',
		        			'".$_REQUEST['phone']."',
		        			'".date('Y-m-d H:i:m')."',
		        			'3'";
				$res = $dbcon->insert($table, $field, $value);
		
		        if ($res['message'] == 'OK') {
		        	$output['title'] = "Completed!";
		        	$output['text'] = "Please wait for Administrator to confirm your account.";
		        	$output['message'] = "success";
		        }else {
		        	$output['title'] = "Fail to register!";
		        	$output['text'] = "";
		        	$output['message'] = "not_success";
		        }
	        }else {
	        	$output['title'] = "Fail to register!";
	        	$output['text'] = "This email already exists.";
	        	$output['message'] = "not_success";
	        }
	        echo json_encode($output);	
		break;
		case 'destroy_session':
	      session_destroy();
		break;

		case'TEST_app':
			$getMail = "SELECT info_id,info_type,info_title,text_title,info_link,attribute FROM  web_info 
			WHERE info_type = 'system_email' ORDER BY info_id ASC ";
			$resultMail = $dbcon->query($getMail); 
			$getContact = "SELECT title,thumbnail,email FROM contact_sel";
			$resultContact = $dbcon->fetchObject($getContact,[]); 
		
			$mail = array();  
			$mail['host'] = trim($resultMail[0]['attribute']);
			$mail['port'] = trim($resultMail[1]['attribute']); 
			$mail['user'] = trim($resultMail[2]['attribute']);
			$mail['password'] = trim($resultMail[3]['attribute']); 
			$mail['logo_web'] = $resultContact->thumbnail; 
			$mail['store_name'] = $resultContact->title; 
			$mail['cont_email'] = $email;
			$mail['cont_email'] = $email;
			$message = 'You have a new password : '.$randpass;
			$subject = 'Your password to login to backend has been changed on date: '.date('d - m - Y').'';
			$statusEmail = getData::sendemailnew_google(  
				array(   
					'SMTP_USER' => $mail['user'],
					'SMTP_PASSWORD' => $mail['password'],
					'SMTP_HOST' => $mail['host'],
					'SMTP_PORT' => $mail['port'],
					'mail_system' => $resultContact->email, 
					'sendFromName' => $mail['store_name'],
					'email' => $resultContact->email,
					'subject' => $subject,
					'addBcc' => array( 
									array( 
										'email' => $mail['cont_email'],
										'name'=> "Wynnsoft Admins"
									),
								), 
					'content' =>  $message, 
				)
			);

			
		break;

		}
			
	}else {
		$errors = $resp->getErrorCodes();
		// print_r($errors);
		echo json_encode([
			'message' => 'error'
		]);
		exit();
	}
}

?>