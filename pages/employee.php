<?php
session_start();
include('../inc/header.php');



include '../queries/Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
$loginError = '';
?>



<title>Invoice System</title>
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>
<div class="container-fluid" id="mydiv">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">
                Employee List Table:
            </h6><?php $value = $invoice->getTotalEmpSal()
                    ?>



            <!-- <a href="utilities/report_print/inventoryReport_print.php" class="btn btn-success float-right" title="Print Employee Details" target="_blank">

                <i class="fa fa-print"></i>&nbsp;Print

            </a> -->
            <a href="#" data-bs-toggle="modal" data-bs-target="#inventoryModal" class="btn float-right text-white mr-3 bg-primary" title="Add New Branch">
                <i class="fa fa-code-branch"></i>&nbsp;Add Branch
            </a>
            <div class="bg-success ms-4 px-2 float-left" id="email_msg" style="color:white; border-radius:8px;float:left;font-size:18px;">
                <?php
                if (isset($_SESSION['email_msg'])) {
                    echo $_SESSION['email_msg'];
                }
                ?>
            </div>
            <div class="bg-success ms-4 px-2 float-left" id="sesMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">
                <?php

                if (isset($_SESSION['message'])) {
                    echo $_SESSION['message'];
                } ?>
            </div>
            <?php if ($loginError) { ?>
                <div id="error_msg" class="bg-danger ms-4 px-2 float-left" style="color:white; border-radius:8px;float:left;font-size:18px;"> <?php echo $loginError; ?></div>
            <?php } ?>

        </div>
        <div class="card-body text-black">
            <div class="row mb-2 text-black">
                <div class="col-sm-12 col-md-4 col-lg-4" id="period1">Date: <?php echo date('Y-m-d'); ?></div>
                <div class="col-sm-12 col-md-4 col-lg-4" id="invoiceCount">Total Employee:<?php echo $value[0]['EmpNo']; ?></div>

                <div class="col-sm-12 col-md-4 col-lg-4" id="totalSum">Total Salary (Monthly):<?php echo $value[0]['totalSalary']; ?></div>
            </div>
            <hr class="divider d-md-block bg-dark " />
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-black" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr style="font-size:13px ;">
                            <th>Branch</th>
                            <th>Empoyee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Contact</th>

                            <th>Salary</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody style="font-size:15px;">
                        <?php

                        $salesPersonList = $invoice->getSalesPersonlist();
                        foreach ($salesPersonList as $Details) {
                            if ($Details["username"] != '') {
                                $name = explode(" ", $Details["username"]);
                                $fname = $name[0];
                                $lname = $name[1];
                            }
                            echo '    <tr>
                                            <td>' . $Details["branch"] . '</td>
                                            <td>' . $Details["id"] . '</td>
                                            <td>' . $Details["username"] . '</td>
                                            <td>' . $Details["email"] . '</td>
                                            <td>' . $Details["address"] . '</td>
                                            <td>' . $Details["mobile"] . '</td>
                                            <td>' . $Details["salary"] . '</td>';
                            if ($Details["email"] == '') {
                                echo '               <td><button class="btn btn-primary" id="addItem' . $Details["id"] . '" data-bs-toggle="modal" data-bs-target="#addModal" ><i class="fa fa-user"></i>&nbsp;Add</button></td>
    </tr>';
                            } else {
                                echo '           <td><button class="btn" id="addItem' . $Details["id"] . '" data-bs-toggle="modal" data-bs-target="#addModal" style="background-color:#F5761A;color:white;"><i class="fa fa-edit"></i>&nbsp;Edit</button></td>
    </tr>
  ';
                            }
                        ?>
                            <script>
                                $('#addItem' + <?php echo $Details["id"]; ?>).click(() => {
                                    $('#id').val(<?php echo $Details["id"]; ?>);

                                    if ('<?php echo $Details['username'] ?>' == '' && '<?php echo $Details['email'] ?>' == '' && '<?php echo $Details['salary'] ?>' == '' && '<?php echo $Details['mobile'] ?>' == '' && '<?php echo $Details['address'] ?>' == '') {
                                        $('#addEdit').val('add');

                                    } else {
                                        $('#addEdit').val('edit');
                                    }
                                    if ('<?php echo $Details['username'] ?>' != '') {
                                        $('#fname').val('<?php echo $fname; ?>');
                                        $('#lname').val('<?php echo $lname; ?>');
                                    } else {
                                        $('#fname').val('');
                                        $('#lname').val('');
                                    }
                                    if ('<?php echo $Details["email"]; ?>' == '') {
                                        $('#password').val('salesperson');
                                        $('#cpassword').val('salesperson');
                                        $('#addFirst').html('*Password:(salesperson)');
                                        $('.itemName').html('Add Employee Details:(Branch:<?php echo $Details["branch"]; ?>)');

                                    } else {
                                        $('#addFirst').html('');
                                        $('.itemName').html('Edit Employee Details:(Branch:<?php echo $Details["branch"]; ?>)');

                                    }

                                    $('#phone').val('<?php echo $Details["mobile"]; ?>');
                                    $('#email').val('<?php echo $Details["email"]; ?>');
                                    $('#address').val('<?php echo $Details["address"]; ?>');

                                })
                            </script>

                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryModalLabel">Create New Branch:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="branchForm">
                    <div class="mb-3">
                        <label for="recipient_name" class="col-form-label">Branch Name:</label>
                        <input type="text" class="form-control" id="recipient_name" placeholder="Enter Valid Branch name" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">Please enter valid Branch name</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="send" form="branchForm">Add</button>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">

    <div class="modal-dialog mw-100 w-75">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title itemName" id="addModalLabel">Add Employee Details:(Branch:)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color:#E5E4E2;">
                <form action="" method="post" id="addForm">
                    <input type="hidden" name="uId" class="form-control" id="id">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-lg-6 col-md-8 col-sm-12">
                                <div class="row mt-2">
                                    <div class="col-md-6 col-sm-12">
                                        <label for="fname">First Name:</label>
                                        <input type="text" class="form-control" name="firstName" id="fname" autocomplete="off" autofocus placeholder="Enter First Name" required>

                                    </div>
                                    <div class="col-md-6 col-sm-12" style="border-right:1px solid black;">
                                        <label for="lname">Last Name:</label>
                                        <input type="text" class="form-control" name="lastName" id="lname" autocomplete="off" autofocus placeholder="Enter Last Name" required>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-12">
                                <label for="email">Enter Email:</label>
                                <input class="form-control" type="email" name="email" id="email" autocomplete="off" placeholder="Email Address" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-8 col-sm-12" style="border-right:1px solid black;">
                                <label for="phone">Phone No.:</label>
                                <input class="form-control" type="number" name="phone" id="phone" autocomplete="off" placeholder="Phone number" maxlength="10" required>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-12">
                                <label for="address">Address:</label>
                                <input class="form-control" type="address" name="address" id="address" autocomplete="off" placeholder="Address" required>
                                <input class="form-control" type="hidden" name="password" id="password" autoComplete="off" />

                                <input class="form-control" type="hidden" name="cpassword" id="cpassword" autoComplete="off" />
                                <input type="hidden" name="addEdit" id="addEdit">

                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-8 col-sm-12" style="border-right:1px solid black;">
                                <label for="salary">Salary:</label>
                                <input class="form-control" type="number" name="salary" id="salary" autocomplete="off" placeholder="Enter Salary" required>
                                <!-- <input class="form-control ms-4" type="file" name="image" id="image" autoComplete="off" style="display: none;" /> -->

                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-12 pt-2">
                                <p id="addFirst" style="color: grey;"></p>
                                <p class="bg-danger ms-1 px-2 mt-4" id="errorMsg1" style="color:white; border-radius:8px;float:left;font-size:18px;">Enter valid Details.</p>

                            </div>
                        </div>
                    </div>

                </form>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" id="send" form="addForm">Add</button>

            </div>
        </div>
    </div>


    <?php include('../inc/footer.php'); ?>
    <script>
        $(document).ready(() => {
            $('#error_msg').delay(1500).fadeOut(500);
            var invalidChars = [
                "-", "+", "e", ".",
            ];
            $('#fname,#lname,#address').keydown((e => {
                return /[a-z]/i.test(e.key);

            }));

            $('#phone,#salary').keydown((e) => {
                if (invalidChars.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
                    e.preventDefault();

                }
            });
            $('#phone').on('input', function() {
                if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);
            });
            $('#recipient_name').keydown((e => {
                return /[a-z]/i.test(e.key);

            }));
            $("#addForm").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'ajaxCall/insertEmployee.php',
                    method: 'POST',
                    data: $('#addForm').serialize(),
                    success: (data) => {
                        if (data == 1) {
                            alert('Employee Detalis Inserted.');
                            location.reload(true);
                            // location.replace('customer_list.php');
                        } else if (data == 2) {
                            alert('Employee Details Edited.')
                            location.reload(true);

                        } else {
                            alert(data);
                        }

                    }
                });
            });
            $('#errorMsg').hide();
            $('#errorMsg1').hide();
            $('#branchForm').submit(e => {
                e.preventDefault();
                var branch = $('#recipient_name').val();

                if (branch == '') {
                    $('#errorMsg').show();
                    $('#errorMsg').delay(1500).fadeOut(500);
                } else {
                    $.ajax({
                        url: 'ajaxCall/addBranch.php',
                        method: 'POST',
                        data: {
                            "branch": branch
                        },
                        success: (data) => {
                            alert(data);
                            location.reload(true);

                            // if (data == 1) {
                            //     alert('New Branch Added.');
                            //     $("#mydiv").load(location.href + " #mydiv");

                            // } else {
                            //     alert(data);
                            // }

                        }
                    });
                }
            })

            // $('#send').click(() => {

            // })
            $('#email_msg').delay(2000).fadeOut(700);
            <?php $_SESSION['email_msg'] = '' ?>
            $('#sesMsg').delay(1500).fadeOut(500);
            <?php $_SESSION['message'] = '' ?>

        })
    </script>