<?php
session_start();
include('../inc/header.php');

include '../queries/Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();

$company = $invoice->companyInfo();
?>
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

<title>Invoice System</title>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">
                Company's Information:
            </h6>
            <a href="utilities/report_print/invoiceOutlet.php" class="btn btn-primary float-right " title="Preview Invoice Outlet" target="_blank">

                Preview&nbsp;&nbsp;<i class="fa fa-eye"></i>

            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- profile Image -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Company Logo:
                            </h6>
                            <div class="bg-success ms-4 px-2 float-left" id="change_msg" style="color:white; border-radius:8px;float:left;font-size:18px;">
                                <?php
                                if (isset($_SESSION['company'])) {
                                    echo $_SESSION['company'];
                                }
                                ?>
                            </div>

                        </div>
                        <!-- Card Body -->
                        <div class="card-body text-center">
                            <img src="<?php echo $company[0]['logo'] ?>" alt="Company Logo" class="rounded-circle img-fluid mb-4" style="width: 160px;"><br>
                            <?php if ($_SESSION['role'] == 'Admin') { ?>
                                <button class="btn btn-warning" title="Change Company Logo" data-bs-toggle="modal" data-bs-target="#logoModal"><i class="fa-solid fa-pen-to-square text-white"></i>&nbsp;Edit</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Company's Details:
                            </h6>
                            <div class="bg-success ms-4 px-2 float-left" id="change1_msg" style="color:white; border-radius:8px;float:left;font-size:18px;">
                                <?php
                                if (isset($_SESSION['company1'])) {
                                    echo $_SESSION['company1'];
                                }
                                ?>
                            </div>

                        </div>
                        <!-- Card Body -->
                        <div class="card-body text-black">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Company Name:</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="mb-0"><?php echo $company[0]['name'] ?></p>
                                </div>
                            </div>
                            <hr class="divider d-md-block bg-dark" />
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email:</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="mb-0"><?php echo $company[0]['email'] ?></p>
                                </div>
                            </div>
                            <hr class="divider d-md-block bg-dark" />
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Mobile No.:</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="mb-0 float-left"><?php echo $company[0]['number'] ?></p>
                                </div>
                            </div>
                            <hr class="divider d-md-block bg-dark" />
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Address:</p>
                                </div>
                                <div class="col-sm-9">

                                    <p class="mb-0 float-left"><?php echo $company[0]['address'] ?></p>

                                </div>
                            </div>
                            <hr class="divider d-md-block bg-dark" />
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Signature:(image)</p>
                                </div>
                                <div class="col-sm-9">
                                    <img src="<?php echo $company[0]['signature'] ?>" alt="Company Signature" width="30px" height="30px">

                                </div>
                            </div>
                        </div>
                        <div class="card-footer py-3 d-flex flex-row align-items-center justify-content-center">

                            <?php if ($_SESSION['role'] == 'Admin') { ?>
                                <button type="button" class="btn text-white" title="Update Company Details" data-bs-toggle="modal" data-bs-target="#comModal" style="background-color:#F5761A;"><i class="fa-solid fa-paintbrush"></i>&nbsp;Change </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<div class="modal fade" id="logoModal" tabindex="-1" aria-labelledby="logoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title" id="logoModalLabel">Change Company Logo:</h5>
                <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">Choose a Logo.</p>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="imageForm" action="ajaxOpra/changeLogo.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="changeLogo" class="col-form-label ">Choose Logo:</label>
                        <input type="file" class="form-control" id="changeLogo" name="logo">
                    </div>



                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="send" form="imageForm" type="submit">Change</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="comModal" tabindex="-1" aria-labelledby="comModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title" id="comModalLabel">Change Company Details:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="comForm" action="ajaxOpra/changeCompanyDetail.php" method="POST" enctype="multipart/form-data">
                    <div>
                        <label for="name" class="col-form-label ">Company Name:</label>
                        <input type="text" class="form-control" name="name" value="<?php echo $company[0]['name'] ?>" id="name">
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div>
                        <label for="email" class="col-form-label ">Email:</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $company[0]['email'] ?>" id="email">
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div>
                        <label for="num" class="col-form-label ">Moblie No.:</label>
                        <input type="text" class="form-control" name="number" value="<?php echo $company[0]['number'] ?>" id="num">
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div>
                        <label for="add" class="col-form-label ">Address:</label>
                        <input type="text" class="form-control" name="address" value="<?php echo $company[0]['address'] ?>" id="add">
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div class="mb-3">
                        <label for="changeSig" class="col-form-label ">Signature(image):</label>
                        <input type="file" class="form-control" id="changeSig" name="signature">
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <label for="Pass" class="col-form-label">Enter Password:(2FA Password)</label>
                    <div class="input-group" id="dPass">

                        <input class="form-control" type="password" name="password" id="Pass" value="<?php echo $company[0]['password'] ?>">
                        <span class="input-group-text" id="basic-addon"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>

                    </div>
                    <hr class="divider d-md-block bg-dark" />

                    <div class="mb-3">
                        <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg1" style="color:white; border-radius:8px;float:left;font-size:18px;">Enter all Fields.</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="send1" form="comForm">Change</button>

            </div>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>
<script>
    $(document).ready(() => {
        $('#errorMsg').hide();
        $('#errorMsg1').hide();
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
        $('#imageForm').submit((e) => {
            e.preventDefault();

            if ($('#changeLogo')[0].files.length === 0) {
                $('#errorMsg').show();
                $('#errorMsg').delay(1500).fadeOut(500);
            } else {
                $('#imageForm').unbind(e);

            }

        });
        $('#comForm').submit((e) => {
            e.preventDefault();

            if ($('#name').val() == '' || $('#email').val() == '' || $('#num').val() == '' || $('#pass').val() == '' || $('#add').val() == '') {
                $('#errorMsg1').show();
                $('#errorMsg1').delay(1500).fadeOut(500);

            } else {
                $('#comForm').unbind(e);

            }

        });
        $('#change_msg').delay(2000).fadeOut(700);
        <?php $_SESSION['company'] = '' ?>
        $('#change1_msg').delay(2000).fadeOut(700);
        <?php $_SESSION['company1'] = '' ?>
    })
</script>