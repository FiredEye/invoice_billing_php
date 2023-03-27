<?php
session_start();
include('../inc/header.php');
$loginError = '';
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    if ($_POST['name'] != '' && $_POST['email'] != '' && $_POST['phone'] != '' && $_POST['address'] != '' && $_POST['password'] != '' && $_POST['cpassword'] != '') {
        include '../queries/Invoice.php';
        $invoice = new Invoice();
        $emailNum = $invoice->checkEmail($_POST['email']);



        if ($emailNum > 0) {
            $loginError = "Email already exist!!";
        } else {
            if ($_POST['password'] === $_POST['cpassword']) {

                $result = $invoice->signinUser($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['password'], $_POST['cpassword'], $_FILES['image']);
                if ($result[0]) {
                    //Load Composer's autoloader
                    require 'utilities/phpmailer/vendor/autoload.php';

                    //Create an instance; passing `true` enables exceptions
                    $mail = new PHPMailer(true);

                    try {
                        //Server settings
                        $mail->SMTPDebug = 1;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = ''; //add your  email                     //SMTP username
                        $mail->Password   = ''; //add 2-factor authetication password          //SMTP password
                        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients //↓ add you email
                        $mail->setFrom('', 'Invoice System');
                        $mail->addAddress($_POST['email']);     //Add a recipient


                        //Content
                        $body = "<h2>
                             Hi," . $_POST['name'] . " <br>Click here to activate your account <br>
                              http://localhost/Invoice-Billing-php/pages/utilities/activate.php?token=$result[1]
                            </h2>";

                        $mail->isHTML(true);                                   //Set email format to HTML
                        $mail->Subject = 'Email Activation';
                        $mail->Body    = $body;
                        $mail->AltBody = strip_tags($body);

                        if ($mail->Send()) {
                            $_SESSION['message'] = "A link has been sent to: " . $_POST['email'] . ", click the link to activate your account";
?> <script>
                                location.replace('login.php');
                                //alert('Message has been sent');
                            </script>
<?php
                        }
                    } catch (Exception $e) {
                        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
                    }
                }
            } else {
                $loginError = "Password doesn't match!!";
            }
        }
    } else {
        $loginError = "Fill all the fields!!";
    }
}                            ?>
<title>Invoice System</title>
<style>
    body,
    #head {
        font-family: 'Montserrat', sans-serif;
    }
</style>

<link href="../css/signin_login.css" rel="stylesheet">
</head>

<body>
    <!-- Nav Bar -->
    <nav class="navbar navbar-expand navbar-light bg-info topbar mb-4 static-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" title="Invoice Software Company">

                <div class="navbar-brand-text text-white mx-2 pt-2">
                    <i class="fa-solid fa-warehouse float-left pt-1 pr-1"></i>
                    <p class="float-left">Inv</p><sup class="rotate-n-15 float-left pt-2">E</sup>
                    <p class="float-left ps-1"> Ware</p>
                </div>
            </a>
        </div>
    </nav>

    <!-- signin content -->
    <div class="container w-75 my-1 shadow p-3 mb-5 bg-white rounded mt-3">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 mt-1 text-center mb-1">
                <h3 id="head">Sign In</h3>
                <form method="POST" class="register-form p-lg-4 " id="register-form" style="margin-top:-35px ;color:black;" enctype="multipart/form-data">
                    <div class="form-group">
                        <?php if ($loginError) { ?>
                            <div id="error_msg" style="background-color:#ce2029;color:white;padding:.25rem 1.5rem .25rem 1.5rem;border-radius:10px;"> <?php echo $loginError; ?></div>
                        <?php } ?>
                    </div>
                    <div class="form-group p-1">

                        <div class="form-group input-group">
                            <label for="name">
                                <i class="fa-solid fa-user"></i>
                            </label>
                            <input class="form-control ms-4" type="text" name="name" id="name" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Name" autoComplete="off" style=" width: 300px;" />
                        </div>
                    </div>

                    <div class="form-group p-1">

                        <div class="form-group input-group">
                            <label for="email">
                                <i class="fa-solid fa-envelope"></i>
                            </label>
                            <input class="form-control ms-4" type="email" name="email" id="email" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Email-ID" autoComplete="off" style=" width: 300px;" />
                        </div>
                    </div>
                    <div class="form-group p-1">

                        <div class="form-group input-group">
                            <label for="phone">
                                <i class="fa-solid fa-phone"></i>
                            </label>
                            <input class="form-control ms-4" type="number" name="phone" id="phone" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Phone Number" autoComplete="off" style=" width: 300px;" />
                        </div>
                    </div>
                    <div class="form-group p-1">

                        <div class="form-group input-group">
                            <label for="address">
                                <i class="fa-solid fa-location-dot"></i>
                            </label>
                            <input class="form-control ms-4" type="text" name="address" id="address" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Address" autoComplete="off" style=" width: 300px;" />
                        </div>
                    </div>
                    <div class="form-group p-1">

                        <div class="form-group input-group">
                            <label for="password">
                                <i class="fa-solid fa-lock"></i>
                            </label>
                            <input class="form-control ms-4" type="password" name="password" id="password" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password" autoComplete="off" style=" width: 300px;" />
                        </div>
                    </div>
                    <div class="form-group p-1">

                        <div class="form-group input-group">
                            <label for="cpassword">
                                <i class="fa-solid fa-lock"></i>
                            </label>
                            <input class="form-control ms-4" type="password" name="cpassword" id="cpassword" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Repeat your password" autoComplete="off" style=" width: 300px;" />
                        </div>
                    </div>
                    <div class="form-group p-1">

                        <div class="form-group input-group">
                            <label for="image">
                                <i class="fa-solid fa-image"></i>
                            </label>
                            <input class="form-control ms-4" type="file" name="image" id="image" autoComplete="off" style=" width: 300px;" />
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-info" style="width:100%;">Sign In</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <figure style="margin-top:-30px;">
                    <img src="../img/signin.svg" class="img-fluid" alt="sing up image" />
                </figure>
                <a href="login.php" class="signup-image-link text-center" id="Signin">
                    I am already a member
                </a>
            </div>
        </div>
    </div>
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; InveWare 2022</span>
            </div>
        </div>
    </footer>
</body>
<script>
    $(document).ready(() => {
        $('#error_msg').delay(1500).fadeOut(500);


    })
</script>