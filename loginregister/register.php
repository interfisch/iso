<?php require('includes/config.php');

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: /db-interface'); exit(); }

//if form has been submitted process it
if(isset($_POST['submit'])){

    if (!isset($_POST['username'])) $error[] = "Please fill out the username";
    if (!isset($_POST['email'])) $error[] = "Please fill out the email address";
    if (!isset($_POST['password'])) $error[] = "Please fill out the password";

	$username = $_POST['username'];
	$institution = $_POST['institution'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$secondaryemail = $_POST['secondaryemail'];
	$institutionalphone = $_POST['institutionalphone'];
	$personalphone = $_POST['personalphone'];
	$ORCID = $_POST['ORCID'];
	$googlescholarprofile = $_POST['googlescholarprofile'];
	$researchGateprofile = $_POST['researchgateprofile'];
	$academiaprofile = $_POST['academiaprofile'];
	$personalwebsite = $_POST['personalwebsite'];

	//very basic validation
	if(!$user->isValidUsername($username)){
		$error[] = 'Usernames must be at least 3 Alphanumeric characters';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $username));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'Username provided is already in use.';
		}

	}

	if(strlen($_POST['password']) < 3){
		$error[] = 'Password is too short.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirm password is too short.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}

	//email validation
	$email = htmlspecialchars_decode($_POST['email'], ENT_QUOTES);
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $email));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}

	}

	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare("INSERT INTO members (username,password,email,active,institution,`First name`,`Last name`,`Secondary Email`,`Institutional phone`,`Personal phone`,ORCID,`Google scholar profile`,`ResearchGate profile`,`Academia profile`,`Personal website`) 
			VALUES (:username, :password, :email, :active, :institution, :firstname, :lastname, :secondaryemail, :institutionalphone, :personalphone, :ORCID, :googlescholarprofile, :researchGateprofile, :academiaprofile, :personalwebsite)");
			$stmt->execute(array(
				':username' => $username,
				':password' => $hashedpassword,
				':email' => $email,
				':active' => $activasion,
				':institution' => $institution,
				':firstname' => $firstname,
				':lastname' => $lastname,
				':secondaryemail' => $secondaryemail,
				':institutionalphone' => $institutionalphone,
				':personalphone' => $personalphone,
				':ORCID' => $ORCID,
				':googlescholarprofile' => $googlescholarprofile,
				':researchGateprofile' => $researchGateprofile,
				':academiaprofile' => $academiaprofile,
				':personalwebsite' => $personalwebsite
			));
			$id = $db->lastInsertId('memberID');

			//send email
			$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "<p>Thank you for registering at demo site.</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activasion'>".DIR."activate.php?x=$id&y=$activasion</a></p>
			<p>Regards Site Admin</p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index page
			header('Location: register.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Isomemo user';

//include header template
require('layout/header.php');
?>


<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<h2>Please Sign Up</h2>
				<p>Already a member? <a href='login.php'>Login</a></p>
				<hr>

				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}

				//if action is joined show sucess
				if(isset($_GET['action']) && $_GET['action'] == 'joined'){
					echo "<h2 class='bg-success'>Registration successful, please check your email to activate your account.</h2>";
				}
				?>

				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['username'], ENT_QUOTES); } ?>" tabindex="1">
				</div>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="2">
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="3">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="text" name="firstname" id="firstname" class="form-control input-lg" placeholder="First name" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['firstname'], ENT_QUOTES); } ?>" tabindex="4">
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="text" name="lastname" id="lastname" class="form-control input-lg" placeholder="Last name" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['lastname'], ENT_QUOTES); } ?>" tabindex="5">
						</div>
					</div>
				</div>
				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['email'], ENT_QUOTES); } ?>" tabindex="6">
				</div>
				<div class="form-group">
					<input type="email" name="secondaryemail" id="secondary-email" class="form-control input-lg" placeholder="secondary Email Address" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['secondaryemail'], ENT_QUOTES); } ?>" tabindex="7">
				</div>
				<div class="form-group">
					<input type="text" name="institution" id="institution" class="form-control input-lg" placeholder="Institution" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['institution'], ENT_QUOTES); } ?>" tabindex="8">
				</div>
				<div class="form-group">
					<input type="text" name="institutionalphone" id="institutionalphone" class="form-control input-lg" placeholder="Institutional phone" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['institutionalphone'], ENT_QUOTES); } ?>" tabindex="9">
				</div>
				<div class="form-group">
					<input type="text" name="personalphone" id="personalphone" class="form-control input-lg" placeholder="Personal phone" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['personalphone'], ENT_QUOTES); } ?>" tabindex="10">
				</div>
				<div class="form-group">
					<input type="text" name="ORCID" id="ORCID" class="form-control input-lg" placeholder="ORCID" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['ORCID'], ENT_QUOTES); } ?>" tabindex="11">
				</div>
				<div class="form-group">
					<input type="text" name="googlescholarprofile" id="googlescholarprofile" class="form-control input-lg" placeholder="Google scholar profile" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['googlescholarprofile'], ENT_QUOTES); } ?>" tabindex="12">
				</div>
				<div class="form-group">
					<input type="text" name="researchgateprofile" id="researchgateprofile" class="form-control input-lg" placeholder="ResearchGate profile" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['researchgateprofile'], ENT_QUOTES); } ?>" tabindex="13">
				</div>
				<div class="form-group">
					<input type="text" name="academiaprofile" id="academiaprofile" class="form-control input-lg" placeholder="Academia profile" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['academiaprofile'], ENT_QUOTES); } ?>" tabindex="14">
				</div>
				<div class="form-group">
					<input type="text" name="personalwebsite" id="personalwebsite" class="form-control input-lg" placeholder="Personal website" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['personalwebsite'], ENT_QUOTES); } ?>" tabindex="15">
				</div>
				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="16"></div>
					<div class="col-xs-6 col-md-6"><a class="btn btn-primary btn-block btn-lg" href="/db-interface">Cancel</a></div>
				</div>
			</form>
		</div>
	</div>

</div>

<?php
//include header template
require('layout/footer.php');
?>
