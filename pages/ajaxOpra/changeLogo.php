<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
$ext = strtolower(pathinfo('upload/' . $_FILES['logo']['name'], PATHINFO_EXTENSION));
$arr = array('jpeg', 'jpg', 'png');
if (!in_array($ext, $arr)) {
    echo "<script> alert('file extension not accepted.'); </script>"; ?> <script>
        location.replace("../company.php")
    </script><?php
            } else {


                $company = $invoice->companyInfo();
                unlink('../' . $company[0]['logo']);
                $logo = $_FILES['logo']['name'];
                $destination = "../../companyImg/" . $logo;
                move_uploaded_file($_FILES['logo']['tmp_name'], $destination);
                $result = $invoice->changeLogo($_FILES['logo']);
                if ($result) {
                    $_SESSION['company'] = 'Company Logo updated.';
                    header('location:../company.php');
                }
            }
