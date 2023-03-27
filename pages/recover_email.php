<?php
session_start();
include('../inc/header.php');
include('../queries/Invoice.php');
$invoice = new Invoice();
$company = $invoice->companyInfo();
$error_msg = '';
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    if ($_POST['email'] != '') {

        $emailNum = $invoice->checkEmail($_POST['email']);



        if ($emailNum > 0) {
            $emailData = $invoice->emailDetail($_POST['email']);
            $userName = $emailData[0]['username'];
            $token = $emailData[0]['token'];

            //Load Composer's autoloader
            require 'utilities/phpmailer/vendor/autoload.php';

            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 1;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;
                //use your email                                    //Enable SMTP authentication1
                $mail->Username   = $company[0]['email'];                     //SMTP username
                // 2-factor authentication password
                $mail->Password   = $company[0]['password'];                               //SMTP password
                $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom($company[0]['email'], $company[0]['name']);
                $mail->addAddress($_POST['email']);     //Add a recipient


                //Content
                $body = "<h2>
                        Hi," . $userName . " <br>Click here to reset your password <br>
                        http://localhost/Invoice-Billing-php/pages/reset_password.php?token=$token
                    </h2>";
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Account Recovery';
                $mail->Body    = $body;
                $mail->AltBody = strip_tags($body);

                if ($mail->Send()) {
                    $_SESSION['message'] = "A link has been sent to: " . $_POST['email'] . ", click the link to reset your password";
?> <script>
                        location.replace('login.php');
                        //alert('Message has been sent');
                    </script>
<?php
                }
            } catch (Exception $e) {
                echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
            }
        } else {
            $error_msg = '!Email not found!';
        }
    } else {
        $error_msg = '!Enter valid Email';
    }
}                            ?>
<title>Invoice System</title>

</head>

<body>
    <!-- Nav Bar -->
    <!-- <nav class="navbar navbar-expand navbar-light bg-info topbar mb-4 static-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" title="Invoice Management Software">

                <div class="navbar-brand-text text-white mx-2 pt-2">
                    <img src="../img/logo.jpg" alt="logo" width="40" height="40" class="rounded img-fluid float-left">
                    <p class="float-left ml-1 mt-1">Inv</p><sup class="rotate-n-15 float-left pt-3 mt-1">E</sup>
                    <p class="float-left ml-2 mt-1"> Ware</p>
                </div>
            </a>
        </div>
    </nav> -->

    <!-- login content -->
    <div class="container pt-3 mt-5 mb-5">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="../img/forgot.jpg" class="img-fluid" alt="sing up image" />
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Forgot Your Password?</h1>

                                    </div>
                                    <form method="POST" class="register-form" id="register-form">
                                        <div class="form-group">
                                            <?php if ($error_msg) { ?>
                                                <div id="error_msg" style="background-color:#ce2029;color:white;padding:.25rem 1.5rem .25rem 1.5rem;border-radius:10px;"> <?php echo $error_msg; ?></div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name='email' class="form-control form-control-user text-black" placeholder="Enter Email Address..." autocomplete="off" required>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-danger btn-user btn-block">
                                            Send Reset Link
                                        </button>
                                    </form>
                                    <hr>
                                    <!-- <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div> -->
                                    <div class="text-center">
                                        <a class="small" href="login.php">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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