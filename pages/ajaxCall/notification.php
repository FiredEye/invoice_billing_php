<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
$result = $invoice->getMessage();
$msg_count = mysqli_num_rows($result);
$output = "";
if ($msg_count > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        date_default_timezone_set('Asia/Kathmandu');
        $date = date("M d, Y", strtotime($row["time"]));
        $time = date("H:i a", strtotime($row["time"]));
        $output .= ' <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle">
                            <img src="' . $row['image'] . '" alt="" width="30px" height="30px" style="border-radius:50px;" />
                        </div>
                        <div class="small text-gray-500">' . $row['username'] . '</div>
                    </div>
                    <div class="row">
                        <div class="small text-gray-500">
                            <p class="float-left">' . $date . '</p>
                            <p class="float-right">' . $time . '</p>
                        </div>
                        <span class="font-weight-bold">' . $row['message'] . '</span>
                    </div>
                </a>';
    }
} else {
    $output .= '<p class="mt-3 text-center">No new messages.</p>';
}
echo $output;
