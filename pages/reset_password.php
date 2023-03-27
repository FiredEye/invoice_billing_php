<?php
session_start();
include('../inc/header.php');
$error_msg = '';

if (isset($_GET['token'])) {

    if (isset($_POST['submit'])) {
        if (!empty($_POST['password']) && !empty($_POST['cpassword'])) {
            include '../queries/Invoice.php';
            $invoice = new Invoice();


            if ($_POST['password'] === $_POST['cpassword']) {


                $result = $invoice->resetPassword($_POST['password'], $_POST['cpassword'], $_GET['token']);
                if ($result) {
                    $_SESSION['message'] = "Your password has been updated!!";
                    header('location:login.php');
                } else {
                    $_SESSION['passmsg'] = "Something went wrong!!, password not updated";
                    header('location:reset_password.php');
                }
            } else {
                //$_SESSION['passmsg'] = "Password are not matching!!";
                $error_msg = "Password are not matching!!";
            }
        } else {
            $error_msg = "Fields cannot be empty.";
        }
    }
} else {
    $_SESSION['message'] = "Token not found!!";
    header('location:login.php');
}
?>
<title>Invoice System</title>
<style>
    a {
        text-decoration: none;
        color: black;
    }

    a:hover {
        text-decoration: none;
        color: black;
    }
</style>
</head>

<body>
    <!-- Nav Bar -->
    <nav class="navbar navbar-expand navbar-light bg-info topbar mb-4 static-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" title="Invoice Management Software">
                <div class="navbar-brand-text text-white mx-2 pt-2">
                    <img src="../img/logo.jpg" alt="logo" width="40" height="40" class="rounded img-fluid float-left">
                    <p class="float-left ml-1 mt-1">Inv</p><sup class="rotate-n-15 float-left pt-3 mt-1">E</sup>
                    <p class="float-left ml-2 mt-1"> Ware</p>
                </div>
            </a>
        </div>
    </nav>

    <!-- login content -->
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-3">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="../img/reset.jpg" class="img-fluid" alt="sing up image" />
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Reset Your Password</h1>

                                    </div>
                                    <form method="POST" class="register-form" id="register-form">
                                        <?php if ($error_msg) { ?>
                                            <div class="form-group">

                                                <div id="error_msg" style="background-color:#ce2029;color:white;padding:.25rem 1.5rem .25rem 1.5rem;border-radius:10px;"> <?php echo $error_msg; ?></div>

                                            </div>
                                        <?php } ?>

                                        <div class="input-group form-group" id="Pass">

                                            <input class="form-control text-black" type="password" name="password" id="password" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;Enter Password" autoComplete="off" required>
                                            <span class="input-group-text" id="basic-addon"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>

                                        </div>
                                        <div class="input-group form-group" id="cPass">

                                            <input class="form-control text-black" type="password" name="cpassword" id="cpassword" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;Confirm Your Password" autoComplete="off" required>
                                            <span class="input-group-text" id="basic-addon"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>

                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                            Submit
                                        </button>
                                    </form>
                                    <hr>

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
        $("#Pass a").click((e) => {
            e.preventDefault();
            if ($('#Pass input').attr("type") == "text") {
                $('#Pass input').attr('type', 'password');
                $('#Pass i').addClass("fa-eye-slash");
                $('#Pass i').removeClass("fa-eye");
            } else if ($('#Pass input').attr("type") == "password") {
                $('#Pass input').attr('type', 'text');
                $('#Pass i').removeClass("fa-eye-slash");
                $('#Pass i').addClass("fa-eye");
            }
        });
        $("#cPass a").click((e) => {
            e.preventDefault();
            if ($('#cPass input').attr("type") == "text") {
                $('#cPass input').attr('type', 'password');
                $('#cPass i').addClass("fa-eye-slash");
                $('#cPass i').removeClass("fa-eye");
            } else if ($('#cPass input').attr("type") == "password") {
                $('#cPass input').attr('type', 'text');
                $('#cPass i').removeClass("fa-eye-slash");
                $('#cPass i').addClass("fa-eye");
            }
        });
        $('#error_msg').delay(1500).fadeOut(500);

    })
</script>