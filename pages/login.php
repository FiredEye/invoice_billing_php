<?php
session_start();
include('../inc/header.php');
$loginError = '';
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    include '../queries/Invoice.php';
    $invoice = new Invoice();
    $user = $invoice->loginUsers($_POST['email']);
    if (!empty($user)) {
        $db_password = $user[0]['password'];
        $user_password = $_POST['password'];
        $password_check = password_verify($user_password, $db_password);
        if ($password_check) {
            $_SESSION['user'] = $user[0]['username'];
            $_SESSION['userid'] = $user[0]['id'];
            $_SESSION['email'] = $user[0]['email'];
            $_SESSION['address'] = $user[0]['address'];
            $_SESSION['password'] = $user[0]['password'];
            $_SESSION['image'] = $user[0]['image'];
            $_SESSION['mobile'] = $user[0]['mobile'];
            $_SESSION['role'] = $user[0]['role'];
            echo "<script> alert('Login successfull!!')</script>";
?> <script>
                location.replace("home.php")
            </script><?php

                    } else {
                        $loginError = "Invalid Credentials!";
                    }
                } else {
                    $loginError = "Invalid Credentials!";
                }
            }
                        ?>
<title>Invoice System</title>
<style>
    #dPass a {
        text-decoration: none;
        color: black;
    }

    #dPass a:hover {
        text-decoration: none;
        color: black;
    }
</style>

<!-- <link href="../css/signin_login.css" rel="stylesheet"> -->
</head>

<body>
    <!-- Nav Bar -->
    <!-- <nav class="navbar navbar-expand navbar-light bg-info topbar mb-4 static-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" title="Invoice Management Software">

                <div class="navbar-brand-text text-white mx-2 pt-2">
                    <img src="../img/logo_i.png" alt="logo" width="60" height="40" class="rounded img-fluid float-left"> -->
    <!-- <p class="float-left ml-1 mt-1">Inv</p><sup class="rotate-n-15 float-left pt-3 mt-1">E</sup>
                    <p class="float-left ml-2 mt-1"> Ware</p> -->
    <!-- </div>
            </a>
        </div>
    </nav> -->

    <!-- login content -->
    <div class="container mt-5 mb-5">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg mt-4">
                    <div class="card-body p-0  mt-3">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="px-5 pb-5 pt-2">
                                    <div class="text-center mt-2 mb-3">

                                        <img src="../img/logo_i.png" alt="logo" width="2000" height="130" class="img-fluid" />
                                    </div>
                                    <div class="text-center text-black">
                                        <h4>Log In</h4>
                                    </div>
                                    <div id="sesMsg" class="rounded" style="background-color:#009000;color:white;">
                                        <?php if (isset($_SESSION['message'])) {
                                            echo $_SESSION['message'];
                                        } ?>
                                    </div>
                                    <form method="POST" class="register-form" id="register-form">
                                        <div class="form-group">
                                            <?php if ($loginError) { ?>
                                                <div id="error_msg" style="background-color:#ce2029;color:white;padding:.25rem 1.5rem .25rem 1.5rem;border-radius:10px;"> <?php echo $loginError; ?></div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user text-black" id="email" name="email" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;Enter Email Address..." autocomplete="off" required />
                                        </div>

                                        <div class="input-group form-group" id="dPass">

                                            <input class="form-control text-black" type="password" name="password" id="Pass" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;Password" autoComplete="off" required>
                                            <span class="input-group-text" id="basic-addon"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>

                                        </div>

                                        <button type="submit" class="btn btn-info btn-user btn-block" style="background-color:#1877f2;" name="login" id="login">
                                            Login
                                        </button>
                                        <hr />
                                    </form>

                                    <div class="text-center">
                                        <a class="small" href="recover_email.php" id="forgetLink">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-block pt-5">
                                <img src="../img/login.jpg" /> <br>

                                <!-- <a href="sign_in.php" class="signup-image-link text-center float-right mr-5" id="Login">
                                    Create an account
                                </a> -->
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
        $("#dPass a").click((e) => {
            e.preventDefault();
            if ($('#dPass input').attr("type") == "text") {
                $('#dPass input').attr('type', 'password');
                $('#dPass i').addClass("fa-eye-slash");
                $('#dPass i').removeClass("fa-eye");
            } else if ($('#dPass input').attr("type") == "password") {
                $('#dPass input').attr('type', 'text');
                $('#dPass i').removeClass("fa-eye-slash");
                $('#dPass i').addClass("fa-eye");
            }
        });
        $('#error_msg').delay(1500).fadeOut(500);
        $('#sesMsg').delay(1500).fadeOut(500);
        <?php $_SESSION['message'] = '' ?>
    })
</script>