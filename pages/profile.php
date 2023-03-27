<?php
session_start();
include('../inc/header.php');

include '../queries/Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
$_POST['email'] = $_SESSION['email'];
$user = $invoice->loginUsers($_POST['email']);
?>

<style>
    a {
        text-decoration: none;
        color: black;
    }

    a:hover {
        text-decoration: none;
        color: black;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
<title>Invoice System</title>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- profile Image -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Profile Picture:
                    </h6>
                    <div class="bg-success ms-4 px-2 float-left" id="change_msg" style="color:white; border-radius:8px;float:left;font-size:18px;">
                        <?php
                        if (isset($_SESSION['change'])) {
                            echo $_SESSION['change'];
                        }
                        ?>
                    </div>

                </div>
                <!-- Card Body -->
                <div class="card-body text-center">
                    <img src="<?php echo $user[0]['image'] ?>" alt="avatar" class="rounded-circle img-fluid mb-4" style="width: 200px; height:200px;object-fit:cover;"><br>
                    <button class="btn btn-warning" title="Change Profile Picture" data-bs-toggle="modal" data-bs-target="#imgModal"><i class="fa-solid fa-pen-to-square text-white"></i>&nbsp;Edit</button>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Profile Details:
                    </h6>
                    <div class="bg-success ms-4 px-2 float-left" id="change1_msg" style="color:white; border-radius:8px;float:left;font-size:18px;">
                        <?php
                        if (isset($_SESSION['change1'])) {
                            echo $_SESSION['change1'];
                        }
                        ?>
                    </div>
                    <button type="button" class="btn btn-danger" title="Change Password" data-bs-toggle="modal" data-bs-target="#passwordModal"><i class="fa fa-lock"></i>&nbsp;Change Password</button>

                </div>
                <!-- Card Body -->
                <div class="card-body text-black">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Full Name:</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0"><?php echo $user[0]['username'] ?></p>
                        </div>
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Email:</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0"><?php echo $user[0]['email'] ?></p>
                        </div>
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Mobile No.:</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0 float-left"><?php echo $user[0]['mobile'] ?></p>
                            <button type="button" class="btn float-right" title="Change Mobile Number" data-bs-toggle="modal" data-bs-target="#numModal" style="background-color:#F5761A;color:white;">change</button>
                        </div>
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Address:</p>
                        </div>
                        <div class="col-sm-9">

                            <p class="mb-0 float-left"><?php echo $user[0]['address'] ?></p>
                            <button type="button" class="btn float-right" title="Change Address" data-bs-toggle="modal" data-bs-target="#addModal" style="background-color:#F5761A;color:white;">change</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Change Password:</h5>
                <p class="bg-danger ms-1 px-2" id="errorMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">Please enter all the fields.</p>
                <p class="bg-danger ms-1 px-2" id="errorMsg1" style="color:white; border-radius:8px;float:left;font-size:18px;">Old password doesn"t match.</p>
                <p class="bg-danger ms-1 px-2" id="errorMsg2" style="color:white; border-radius:8px;float:left;font-size:18px;">new & confirm password doesn"t match.</p>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <label for="oldPass" class="col-form-label">Enter old Password:</label>
                    <div class="input-group" id="doldPass">

                        <input class="form-control" type="password" id="oldPass">
                        <span class="input-group-text" id="basic-addon"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>

                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <label for="newPass" class="col-form-label">Enter New Password:</label>
                    <div class="input-group" id="dnewPass">

                        <input class="form-control" type="password" id="newPass">
                        <span class="input-group-text" id="basic-addon"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>

                    </div>
                    <label for="conPass" class="col-form-label">Confirm Password:</label>
                    <div class="input-group" id="dconPass">

                        <input class="form-control" type="password" id="conPass">
                        <span class="input-group-text" id="basic-addon"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="send">Change</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imgModal" tabindex="-1" aria-labelledby="imgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title" id="imgModalLabel">Change Profile Image:</h5>
                <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg3" style="color:white; border-radius:8px;float:left;font-size:18px;">Choose a Image.</p>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="imageForm" action="ajaxOpra/changeImage.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="changeImg" class="col-form-label ">Choose image:</label>
                        <input type="file" class="form-control" id="changeImg" name="image">
                    </div>



                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="send1" form="imageForm" type="submit">Change</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="numModal" tabindex="-1" aria-labelledby="numModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title" id="numModalLabel">Change Mobile Number:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="numForm" action="ajaxOpra/changeNumber.php" method="POST">
                    <div class="mb-3">
                        <label for="num" class="col-form-label ">Moblie No.:</label>
                        <input type="number" class="form-control" name="number" value="<?php echo $user[0]['mobile'] ?>" id="num">
                    </div>


                    <div class="mb-3">
                        <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg4" style="color:white; border-radius:8px;float:left;font-size:18px;">Enter valid Number</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="send2" form="numForm">Change</button>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Change Address:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="ajaxOpra/changeAddress.php" method="POST">
                    <div class="mb-3">
                        <label for="add" class="col-form-label ">Address:</label>
                        <input type="text" class="form-control" name="address" value="<?php echo $user[0]['address'] ?>" id="add">
                    </div>


                    <div class="mb-3">
                        <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg5" style="color:white; border-radius:8px;float:left;font-size:18px;">Field cannot be empty!</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="send3" form="addForm">Change</button>

            </div>
        </div>
    </div>
</div>
<?php include('../inc/footer.php'); ?>
<script>
    $(document).ready(() => {
        $('#errorMsg').hide();
        $('#errorMsg1').hide();
        $('#errorMsg2').hide();
        $('#errorMsg3').hide();
        $('#errorMsg4').hide();
        $('#errorMsg5').hide();
        $("#doldPass a").click((e) => {
            e.preventDefault();
            if ($('#doldPass input').attr("type") == "text") {
                $('#doldPass input').attr('type', 'password');
                $('#doldPass i').addClass("fa-eye-slash");
                $('#doldPass i').removeClass("fa-eye");
            } else if ($('#doldPass input').attr("type") == "password") {
                $('#doldPass input').attr('type', 'text');
                $('#doldPass i').removeClass("fa-eye-slash");
                $('#doldPass i').addClass("fa-eye");
            }
        });
        $("#dnewPass a").click((e) => {
            e.preventDefault();
            if ($('#dnewPass input').attr("type") == "text") {
                $('#dnewPass input').attr('type', 'password');
                $('#dnewPass i').addClass("fa-eye-slash");
                $('#dnewPass i').removeClass("fa-eye");
            } else if ($('#dnewPass input').attr("type") == "password") {
                $('#dnewPass input').attr('type', 'text');
                $('#dnewPass i').removeClass("fa-eye-slash");
                $('#dnewPass i').addClass("fa-eye");
            }
        });
        $("#dconPass a").click((e) => {
            e.preventDefault();
            if ($('#dconPass input').attr("type") == "text") {
                $('#dconPass input').attr('type', 'password');
                $('#dconPass i').addClass("fa-eye-slash");
                $('#dconPass i').removeClass("fa-eye");
            } else if ($('#dconPass input').attr("type") == "password") {
                $('#dconPass input').attr('type', 'text');
                $('#dconPass i').removeClass("fa-eye-slash");
                $('#dconPass i').addClass("fa-eye");
            }
        });
        $('#send').click(() => {
            if ($('#oldPass').val() == '' || $('#newPass').val() == '' || $('#conPass').val() == '') {
                $('#errorMsg').show();
                $('#errorMsg').delay(1500).fadeOut(500);
            } else {
                if ($('#newPass').val() != $('#conPass').val()) {
                    $('#errorMsg2').show();
                    $('#errorMsg2').delay(1500).fadeOut(500);
                } else {
                    var oldPass = $('#oldPass').val();
                    $.ajax({
                        url: 'ajaxOpra/checkPassword.php',
                        method: 'POST',

                        data: {

                            password: oldPass
                        },
                        success: (data) => {

                            if (data == 1) {
                                var newPass = $('#newPass').val();
                                $.ajax({
                                    url: 'ajaxOpra/changePassword.php',
                                    method: 'POST',

                                    data: {

                                        password: newPass
                                    },
                                    success: (data) => {
                                        if (data == 1) {

                                            location.reload('profile.php');
                                        }
                                    }
                                });
                            } else {
                                $('#errorMsg1').show();
                                $('#errorMsg1').delay(1500).fadeOut(500);
                            }
                        }
                    });
                }

            }
        });
        $('#imageForm').submit((e) => {
            e.preventDefault();

            if ($('#changeImg')[0].files.length === 0) {
                $('#errorMsg3').show();
                $('#errorMsg3').delay(1500).fadeOut(500);
            } else {
                $('#imageForm').unbind(e);

            }

        });
        $('#numForm').submit((e) => {
            e.preventDefault();

            if ($('#num').val().indexOf('9') == 0 && $('#num').val().length == 10) {
                $('#numForm').unbind(e);
            } else {

                $('#errorMsg4').show();
                $('#errorMsg4').delay(1500).fadeOut(500);
            }

        });
        $('#addForm').submit((e) => {
            e.preventDefault();

            if ($('#add').val() == '') {
                $('#errorMsg5').show();
                $('#errorMsg5').delay(1500).fadeOut(500);

            } else {
                $('#addForm').unbind(e);

            }

        });
        $('#change_msg').delay(2000).fadeOut(700);
        <?php $_SESSION['change'] = '' ?>
        $('#change1_msg').delay(2000).fadeOut(700);
        <?php $_SESSION['change1'] = '' ?>
    })
</script>