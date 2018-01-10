<?php
/**
 * Created by PhpStorm.
 * User: Feng Feng
 * Date: 2018/1/2
 * Time: 22:09
 */
require('includes/config.php');

if( !$user->is_logged_in() ){ header('Location: /db-interface'); exit(); }

try {
    $stmt = $db->prepare('SELECT username,password,email,active,institution,`First name`,`Last name`,`Secondary Email`,`Institutional phone`,`Personal phone`,ORCID,`Google scholar profile`,`ResearchGate profile`,`Academia profile`,`Personal website`
 FROM members WHERE memberID = :memberID AND active="Yes" ');
    $stmt->execute(array('memberID' =>$_SESSION['memberID']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $old_name = $row['username'];
    $old_email = $row['email'];
    $old_institution = $row['institution'];
    $old_firstname = $row['First name'];
    $old_lastname = $row['Last name'];
    $old_secondaryemail = $row['Secondary Email'];
    $old_institutionalphone = $row['Institutional phone'];
    $old_personalphone = $row['Personal phone'];
    $old_ORCID = $row['ORCID'];
    $old_googlescholarprofile = $row['Google scholar profile'];
    $old_researchGateprofile = $row['ResearchGate profile'];
    $old_academiaprofile = $row['Academia profile'];
    $old_personalwebsite = $row['Personal website'];


} catch(PDOException $e) {
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
}

//if form has been submitted process it
if(isset($_POST['submit'])){

    if (!isset($_POST['username'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['email'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['password'])) $error[] = "Please fill out all fields";

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
    }

    if(isset($_POST['editpass'])){
        if(strlen($_POST['password']) < 3){
            $error[] = 'Password is too short.';
        }

        if(strlen($_POST['passwordConfirm']) < 3){
            $error[] = 'Confirm password is too short.';
        }

        if($_POST['password'] != $_POST['passwordConfirm']){
            $error[] = 'Passwords do not match.';
        }
    }

    //email validation
    $email = htmlspecialchars_decode($_POST['email'], ENT_QUOTES);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error[] = 'Please enter a valid email address';
    } else {
    }

    //if no errors have been created carry on
    if(!isset($error)){

        //hash the password
        $hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

        //create the activasion code
        $activasion = md5(uniqid(rand(),true));

        try {

            //Update database with a prepared statement
            if(isset($_POST['editpass'])) {
                $stmt = $db->prepare('UPDATE members SET password = :password, username = :username, email = :email, institution = :institution, 
                `First name` = :firstname, `Last name` = :lastname, `Secondary Email` = :secondaryemail, `Institutional phone` = :institutionalphone, 
                `Personal phone` = :personalphone, `ORCID` = :ORCID, `Google scholar profile` = :googlescholarprofile, 
                `ResearchGate profile` = :researchGateprofile, `Academia profile` = :academiaprofile, `Personal website` = :personalwebsite
                where memberID = :memberID');
                $stmt->execute(array(
                    ':password' => $hashedpassword,
                    ':username' => $username,
                    ':email' => $email,
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
                    ':personalwebsite' => $personalwebsite,
                    ':memberID' => $_SESSION['memberID']
                ));
            }else{
                $stmt = $db->prepare('UPDATE members SET username = :username, email = :email, institution = :institution, 
                `First name` = :firstname, `Last name` = :lastname, `Secondary Email` = :secondaryemail, `Institutional phone` = :institutionalphone, 
                `Personal phone` = :personalphone, `ORCID` = :ORCID, `Google scholar profile` = :googlescholarprofile, 
                `ResearchGate profile` = :researchGateprofile, `Academia profile` = :academiaprofile, `Personal website` = :personalwebsite
                where memberID = :memberID');
                $stmt->execute(array(
                    ':username' => $username,
                    ':email' => $email,
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
                    ':personalwebsite' => $personalwebsite,
                    ':memberID' => $_SESSION['memberID']
                ));
            }

            //send email
            $to = $_POST['email'];
            $subject = "Registration Confirmation";
            $body = "<p>Your data has already been changed</p>";

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
                    <h2>Please edit your data</h2>
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
                        <input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php echo $old_name; ?>" tabindex="1">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php echo $old_email; ?>" tabindex="2">
                    </div>
                    <div class="form-group">
                        <div class="input-group-prepend" style="float: left; margin-right: 10px">
                            <div class="input-group-text">
                                <input type="checkbox" name="editpass" id="editpass" aria-label="Checkbox for following text input">
                            </div>
                        </div>
                        <label>Please confirm if you want to change the password</label>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="4">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="5">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="firstname" id="firstname" class="form-control input-lg" placeholder="First name" value="<?php echo $old_firstname; ?>" tabindex="6">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="lastname" id="lastname" class="form-control input-lg" placeholder="Last name" value="<?php echo $old_lastname; ?>" tabindex="7">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php echo $old_email; ?>" tabindex="8">
                    </div>
                    <div class="form-group">
                        <input type="email" name="secondaryemail" id="secondary-email" class="form-control input-lg" placeholder="secondary Email Address" value="<?php echo $old_secondaryemail; ?>" tabindex="9">
                    </div>
                    <div class="form-group">
                        <input type="text" name="institution" id="institution" class="form-control input-lg" placeholder="Institution" value="<?php echo $old_institution; ?>" tabindex="10">
                    </div>
                    <div class="form-group">
                        <input type="text" name="institutionalphone" id="institutionalphone" class="form-control input-lg" placeholder="Institutional phone" value="<?php echo $old_institutionalphone; ?>" tabindex="11">
                    </div>
                    <div class="form-group">
                        <input type="text" name="personalphone" id="personalphone" class="form-control input-lg" placeholder="Personal phone" value="<?php echo $old_personalphone; ?>" tabindex="12">
                    </div>
                    <div class="form-group">
                        <input type="text" name="ORCID" id="ORCID" class="form-control input-lg" placeholder="ORCID" value="<?php echo $old_ORCID; ?>" tabindex="13">
                    </div>
                    <div class="form-group">
                        <input type="text" name="googlescholarprofile" id="googlescholarprofile" class="form-control input-lg" placeholder="Google scholar profile" value="<?php echo $old_googlescholarprofile; ?>" tabindex="14">
                    </div>
                    <div class="form-group">
                        <input type="text" name="researchgateprofile" id="researchgateprofile" class="form-control input-lg" placeholder="ResearchGate profile" value="<?php echo $old_researchGateprofile; ?>" tabindex="15">
                    </div>
                    <div class="form-group">
                        <input type="text" name="academiaprofile" id="academiaprofile" class="form-control input-lg" placeholder="Academia profile" value="<?php echo $old_academiaprofile; ?>" tabindex="16">
                    </div>
                    <div class="form-group">
                        <input type="text" name="personalwebsite" id="personalwebsite" class="form-control input-lg" placeholder="Personal website" value="<?php echo $old_personalwebsite; ?>" tabindex="17">
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Edit" class="btn btn-primary btn-block btn-lg" tabindex="18"></div>
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