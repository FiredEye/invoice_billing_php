<?php

class Invoice
{
	private $host  = 'localhost';
	private $user  = 'root';
	private $password   = "";
	private $database  = "invoice_system";
	private $invoiceUserTable = 'invoice_users';
	private $invoiceCompanyTable = 'company';

	private $invoiceOrderTable = 'invoice_order';
	private $invoiceOrderItemTable = 'invoice_order_item';
	private $noticeTable = 'notification';
	private $dbConnect = false;
	public function __construct()
	{
		if (!$this->dbConnect) {
			$conn = new mysqli($this->host, $this->user, $this->password, $this->database);
			if ($conn->connect_error) {
				die("Error failed to connect to MySQL: " . $conn->connect_error);
			} else {
				$this->dbConnect = $conn;
			}
		}
	}
	private function getData($sqlQuery)
	{
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if (!$result) {
			die('Error in query: ' . mysqli_error());
		}
		$data = array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[] = $row;
		}
		return $data;
	}
	private function getDatas($sqlQuery)
	{
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if (!$result) {
			die('Error in query: ' . mysqli_error());
		}

		while ($row = mysqli_fetch_assoc($result)) {
			$data = $row;
		}
		return $data;
	}
	public function getNumRows_orderItem($invoiceId)
	{
		$sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderItemTable . " 
			WHERE order_id = '$invoiceId'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);

		if (!$result) {
			die('Error in query: ' . mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function signinUser($POST)
	{
		$pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$cpass = password_hash($_POST['cpassword'], PASSWORD_BCRYPT);
		$token = bin2hex(random_bytes(15));
		$image = $_FILES['image']['name'];
		$destination = "../img/" . $image;
		$sqlInsert = "
		INSERT INTO " . $this->invoiceUserTable . "(username, email, mobile, address, image, password, cpassword, token, status) VALUES('" . $_POST['name'] . "', '" . $_POST['email'] . "', '" . $_POST['phone'] . "', '" . $_POST['address'] . "','$destination', '$pass', '$cpass', '$token', 'inactive')";
		$result = mysqli_query($this->dbConnect, $sqlInsert);
		move_uploaded_file($_FILES['image']['tmp_name'], $destination);
		return [$result, $token];
	}
	public function getTotalEmpSal()
	{
		$sqlQuery = "
		SELECT SUM(salary) as totalSalary, COUNT(id) as EmpNo FROM " . $this->invoiceUserTable . " WHERE email!='' AND role!='Admin'";
		return  $this->getData($sqlQuery);
	}
	public function signinUID($POST)
	{
		$pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$cpass = password_hash($_POST['cpassword'], PASSWORD_BCRYPT);
		$token = bin2hex(random_bytes(15));

		// $sqlInsert  = "
		// UPDATE " . $this->invoiceUserTable . " 
		// SET username = '" . $_POST['name'] . "', mobile = '" . $_POST['phone'] . "', password = '" . $pass . "', cpassword = '" . $cpass . "', token= '" . $token . "', status = 'inactive', address= '" . $_POST['address'] . "', salary = '" . $_POST['salary'] . "', email = '" . $_POST['email'] . "' WHERE id =" . $_POST['uId'];
		$sqlInsert = "UPDATE " . $this->invoiceUserTable . " SET username='" . $_POST['name'] . "', mobile = '" . $_POST['phone'] . "',email = '" . $_POST['email'] . "', address= '" . $_POST['address'] . "', salary = '" . $_POST['salary'] . "', password = '$pass', cpassword = '$cpass', token= '$token', status = 'active' WHERE id= " . $_POST['uId'];
		$result = mysqli_query($this->dbConnect, $sqlInsert);
		return [$result, $token];
	}

	public function addBranch($POST)
	{

		$sqlInsert = "
		INSERT INTO " . $this->invoiceUserTable . "(branch,role) VALUES('" . $_POST['branch'] . "','SalesPerson')";
		$result = mysqli_query($this->dbConnect, $sqlInsert);
		return $result;
	}
	public function changeImage($id, $POST)
	{

		$image = $_FILES['image']['name'];
		$destination = "../img/" . $image;
		$sqlInsert = "
				UPDATE " . $this->invoiceUserTable . " 
				SET image = '" . $destination . "' WHERE id = '" . $id . "'";


		return mysqli_query($this->dbConnect, $sqlInsert);
	}
	public function changeLogo($POST)
	{

		$logo = $_FILES['logo']['name'];
		$destination = "../companyImg/" . $logo;
		$sqlInsert = "
				UPDATE " . $this->invoiceCompanyTable . " 
				SET logo = '" . $destination . "' WHERE id = '1'";


		return mysqli_query($this->dbConnect, $sqlInsert);
	}
	public function checkEmail($POST)
	{
		$sqlQuery = "
			SELECT * FROM " . $this->invoiceUserTable . " 
			WHERE email = '" . $_POST['email'] . "'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);

		if (!$result) {
			die('Error in query: ' . mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function checkPhone($POST)
	{
		$sqlQuery = "
			SELECT * FROM " . $this->invoiceUserTable . " 
			WHERE mobile = '" . $_POST['phone'] . "'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);

		if (!$result) {
			die('Error in query: ' . mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function emailDetail($POST)
	{
		$sqlQuery = "
			SELECT * FROM " . $this->invoiceUserTable . " 
			WHERE email = '" . $_POST['email'] . "'";
		return  $this->getData($sqlQuery);
	}
	public function loginUsers($email)
	{
		$sqlQuery = "
			SELECT * 
			FROM " . $this->invoiceUserTable . " 
			WHERE email='" . $email . "' AND status='active'";
		return  $this->getData($sqlQuery);
	}
	public function companyInfo()
	{
		$sqlQuery = "
			SELECT * 
			FROM " . $this->invoiceCompanyTable . " 
			WHERE id='1'";
		return  $this->getData($sqlQuery);
	}

	public function resetPassword($POST, $GET)
	{
		$pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$cpass = password_hash($_POST['cpassword'], PASSWORD_BCRYPT);
		$sqlQuery = "
			UPDATE " . $this->invoiceUserTable . " SET password='$pass', cpassword='$cpass' WHERE token= '" . $_GET['token'] . "'";
		return mysqli_query($this->dbConnect, $sqlQuery);
	}
	public function changeStatus($token)
	{
		$sqlQuery = "
			UPDATE " . $this->invoiceUserTable . " SET status='active' WHERE token= '$token'";
		return mysqli_query($this->dbConnect, $sqlQuery);
	}
	public function checkLoggedIn()
	{
		if (!$_SESSION['userid']) {
			header("Location:login.php");
		}
	}
	public function saveInvoice($POST)
	{
		$sqlInsert = "
			INSERT INTO " . $this->invoiceOrderTable . "(recorded_by, due_date, order_receiver_id, order_total_before_tax, order_total_tax, order_tax_per, order_total_after_tax, order_amount_paid, order_total_amount_due) 
			VALUES ('" . $POST['recordedBy'] . "', '" . $POST['due_date'] . "', '" . $POST['customerId'] . "','" . $POST['subTotal'] . "', '" . $POST['taxAmount'] . "', '" . $POST['taxRate'] . "', '" . $POST['totalAftertax'] . "', '" . $POST['amountPaid'] . "', '" . $POST['amountDue'] . "')";
		mysqli_query($this->dbConnect, $sqlInsert);
		$lastInsertId = mysqli_insert_id($this->dbConnect);
		for ($i = 0; $i < count($POST['productCode']); $i++) {
			$sqlInsertItem = "
			INSERT INTO " . $this->invoiceOrderItemTable . "(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount) 
			VALUES ('" . $lastInsertId . "', '" . $POST['productCode'][$i] . "', '" . $POST['productName'][$i] . "', '" . $POST['quantity'][$i] . "', '" . $POST['price'][$i] . "', '" . $POST['total'][$i] . "')";
			mysqli_query($this->dbConnect, $sqlInsertItem);
		}
		return $lastInsertId;
	}
	public function sendMessage($message)
	{
		$sqlQuery1 = "
			SELECT id FROM " . $this->invoiceUserTable . " WHERE role='Admin' OR id='" . $_SESSION['userid'] . "'";
		$res = mysqli_query($this->dbConnect, $sqlQuery1);
		while ($row = mysqli_fetch_assoc($res)) {
			$sqlQuery2 = "
				INSERT INTO " . $this->noticeTable . "(from_id,to_id,message) values('" . $_SESSION['userid'] . "','" . $row['id'] . "','$message')";
			mysqli_query($this->dbConnect, $sqlQuery2);
		}
	}
	public function getUnreadMessage()
	{
		$sqlQuery = "
			SELECT * from " . $this->noticeTable . " WHERE status=0 and to_id='" . $_SESSION['userid'] . "' order by id desc";

		return mysqli_query($this->dbConnect, $sqlQuery);
	}
	public function getMessage()
	{
		$sqlQuery = "
			SELECT a.message,a.time,b.username,b.image from " . $this->noticeTable . " as a JOIN " . $this->invoiceUserTable . " as b WHERE a.from_id=b.id AND a.to_id='" . $_SESSION['userid'] . "' order by a.id desc";

		return mysqli_query($this->dbConnect, $sqlQuery);
	}
	public function readMessage()
	{
		$sqlQuery = "
			UPDATE " . $this->noticeTable . " SET status=1 where to_id='" . $_SESSION['userid'] . "'";

		mysqli_query($this->dbConnect, $sqlQuery);
	}
	public function updateInvoice($POST)
	{
		if ($POST['invoiceId']) {

			$sqlInsert = "
				UPDATE " . $this->invoiceOrderTable . " 
				SET order_receiver_id = '" . $POST['customerId'] . "', due_date = '" . $POST['due_date'] . "', order_total_before_tax = '" . $POST['subTotal'] . "', order_total_tax = '" . $POST['taxAmount'] . "', order_tax_per = '" . $POST['taxRate'] . "', order_total_after_tax = '" . $POST['totalAftertax'] . "', order_amount_paid = '" . $POST['amountPaid'] . "', order_total_amount_due = '" . $POST['amountDue'] . "'	WHERE recorded_by = '" . $POST['recordedBy'] . "' AND order_id = '" . $POST['invoiceId'] . "'";
			mysqli_query($this->dbConnect, $sqlInsert);
		}
		$this->deleteInvoiceItems($POST['invoiceId']);
		for ($i = 0; $i < count($POST['productCode']); $i++) {
			$sqlInsertItem = "
				INSERT INTO " . $this->invoiceOrderItemTable . "(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount) 
				VALUES ('" . $POST['invoiceId'] . "', '" . $POST['productCode'][$i] . "', '" . $POST['productName'][$i] . "', '" . $POST['quantity'][$i] . "', '" . $POST['price'][$i] . "', '" . $POST['total'][$i] . "')";
			mysqli_query($this->dbConnect, $sqlInsertItem);
		}
	}
	public function changeCompanyDetail($POST)
	{
		$sqlInsert = "
				UPDATE " . $this->invoiceCompanyTable . " 
				SET name = '" . $_POST['name'] . "', email= '" . $_POST['email'] . "', number = '" . $_POST['number'] . "', address = '" . $_POST['address'] . "', password = '" . $_POST['password'] . "' WHERE id = '1'";
		return mysqli_query($this->dbConnect, $sqlInsert);
	}
	public function changeCompanyDetail1($POST)
	{
		$signature = $_FILES['signature']['name'];
		$destination = "../companyImg/" . $signature;

		$sqlInsert = "
				UPDATE " . $this->invoiceCompanyTable . " 
				SET name = '" . $_POST['name'] . "', email= '" . $_POST['email'] . "', number = '" . $_POST['number'] . "', address = '" . $_POST['address'] . "', password = '" . $_POST['password'] . "',signature='" . $destination . "' WHERE id = '1'";

		return mysqli_query($this->dbConnect, $sqlInsert);
	}



	public function changePassword($id, $pass)
	{
		$pass1 = password_hash($pass, PASSWORD_BCRYPT);
		$sqlInsert = "
				UPDATE " . $this->invoiceUserTable . " 
				SET password = '" . $pass1 . "' WHERE id = '" . $id . "'";
		return mysqli_query($this->dbConnect, $sqlInsert);
	}
	public function changeNumber($id, $num)
	{

		$sqlInsert = "
				UPDATE " . $this->invoiceUserTable . " 
				SET mobile= '" . $num . "' WHERE id = '" . $id . "'";
		return mysqli_query($this->dbConnect, $sqlInsert);
	}
	public function changeAddress($id, $add)
	{

		$sqlInsert = "
				UPDATE " . $this->invoiceUserTable . " 
				SET address= '" . $add . "' WHERE id = '" . $id . "'";
		return mysqli_query($this->dbConnect, $sqlInsert);
	}
	public function getInvoiceList()
	{
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "
		SELECT * FROM " . $this->invoiceOrderTable . " ORDER BY order_id DESC";
		} else {
			$sqlQuery = "
		SELECT * FROM " . $this->invoiceOrderTable . " WHERE recorded_by='" . $_SESSION['userid'] . "' ORDER BY order_id DESC";
		}
		return  $this->getData($sqlQuery);
	}
	public function getUnpaidAmtRow()
	{
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "
		SELECT COUNT(order_id) as row, SUM(order_total_amount_due) as due, SUM(order_total_after_tax) as total FROM " . $this->invoiceOrderTable . " WHERE order_total_amount_due>0";
		} else {
			$sqlQuery = "
		SELECT COUNT(order_id) as row, SUM(order_total_amount_due) as due, SUM(order_total_after_tax) as total FROM " . $this->invoiceOrderTable . " WHERE order_total_amount_due>0 AND recorded_by='" . $_SESSION['userid'] . "'";
		}
		return  $this->getData($sqlQuery);
	}
	public function getInvoice($invoiceId)
	{
		$sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderTable . " 
			WHERE order_id = '$invoiceId'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		return $row;
	}
	public function getInvoiceItems($invoiceId)
	{
		$sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderItemTable . " 
			WHERE order_id = '$invoiceId'";
		return  $this->getData($sqlQuery);
	}
	public function getInvoice_fromDate($POST)
	{
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderTable . " WHERE CAST(order_date as DATE)  BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "' ORDER BY order_id DESC ";
		} else {
			$sqlQuery = "
		SELECT * FROM " . $this->invoiceOrderTable . " 
		WHERE recorded_by='" . $_SESSION['userid'] . "' AND CAST(order_date as DATE)  BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "' ORDER BY order_id DESC ";
		}
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}
	public function getCustomerInvoice($search)
	{
		$sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderTable . " 
			WHERE order_receiver_id = '$search'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}
	public function getCustomerInvoicePay($search)
	{
		$sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderTable . " 
			WHERE order_receiver_id = '$search' ORDER BY order_total_amount_due DESC";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}
	public function getDailyTransaction()
	{
		date_default_timezone_set('Asia/Kathmandu');
		$date = date('Y-m-d');
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderTable . " 
			WHERE  CAST(order_date as DATE)= '" . $date . "'";
		} else {
			$sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderTable . " 
			WHERE  CAST(order_date as DATE)= '" . $date . "' AND recorded_by='" . $_SESSION['userid'] . "'";
		}
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}
	public function getCustomerSales_fromDate($POST)
	{
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "select recorded_by,order_receiver_id,SUM(order_total_after_tax) as total,SUM(order_amount_paid) as paid FROM " . $this->invoiceOrderTable . " WHERE CAST(order_date as DATE) BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "' GROUP BY order_receiver_id ORDER BY sum(order_total_after_tax) DESC";
		} else {
			$sqlQuery = "select order_receiver_id,SUM(order_total_after_tax) as total,SUM(order_amount_paid) as paid FROM " . $this->invoiceOrderTable . " WHERE recorded_by='" . $_SESSION['userid'] . "' AND CAST(order_date as DATE) BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "' GROUP BY order_receiver_id ORDER BY sum(order_total_after_tax) DESC";
		}
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}
	public function getSalesPersonsales_fromDate($POST)
	{
		$sqlQuery = "select recorded_by,SUM(order_total_after_tax) as total,SUM(order_amount_paid) as paid,COUNT(order_id) as sn FROM " . $this->invoiceOrderTable . " WHERE CAST(order_date as DATE) BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "' GROUP BY recorded_by ORDER BY sum(order_total_after_tax) DESC";

		$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}
	public function getItemSales_fromDate($POST)
	{
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "select invoice_order_item.item_code,invoice_order_item.item_name,sum(invoice_order_item.order_item_quantity) as quantity,invoice_order_item.order_item_price,sum(invoice_order_item.order_item_final_amount) as total from invoice_order_item join invoice_order on invoice_order_item.order_id=invoice_order.order_id where CAST(invoice_order.order_date as DATE) BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "' GROUP BY invoice_order_item.item_name ORDER BY sum(invoice_order_item.order_item_final_amount) DESC";
		} else {
			$sqlQuery = "select invoice_order_item.item_code,invoice_order_item.item_name,sum(invoice_order_item.order_item_quantity) as quantity,invoice_order_item.order_item_price,sum(invoice_order_item.order_item_final_amount) as total from invoice_order_item join invoice_order on invoice_order_item.order_id=invoice_order.order_id where invoice_order.recorded_by='" . $_SESSION['userid'] . "' AND CAST(invoice_order.order_date as DATE) BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "' GROUP BY invoice_order_item.item_name ORDER BY sum(invoice_order_item.order_item_final_amount) DESC";
		}

		$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}
	public function getSPItemSales_fromDate($POST)
	{
		$sqlQuery = "select invoice_order_item.item_code,invoice_order_item.item_name,sum(invoice_order_item.order_item_quantity) as quantity,invoice_order_item.order_item_price,sum(invoice_order_item.order_item_final_amount) as total from invoice_order_item join invoice_order on invoice_order_item.order_id=invoice_order.order_id where recorded_by='" . $POST['id'] . "' AND CAST(invoice_order.order_date as DATE) BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "' GROUP BY invoice_order_item.item_name ORDER BY sum(invoice_order_item.order_item_final_amount) DESC";

		$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}

	public function getSalesPersonlist()
	{
		$sqlQuery = "
                SELECT * FROM " . $this->invoiceUserTable . " WHERE role='SalesPerson'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if (!$result) {
			die('Error in query: ' . mysqli_error());
		}
		return  $result;
	}
	public function getSalesPersonById($id)
	{
		$sqlQuery = "
                SELECT * FROM " . $this->invoiceUserTable . " WHERE id=$id";
		return $this->getDatas($sqlQuery);
	}

	public function deleteInvoiceItems($invoiceId)
	{
		$sqlQuery = "
			DELETE FROM " . $this->invoiceOrderItemTable . " 
			WHERE order_id = '" . $invoiceId . "'";
		mysqli_query($this->dbConnect, $sqlQuery);
	}
	public function deleteInvoice($invoiceId)
	{
		$sqlQuery = "
			DELETE FROM " . $this->invoiceOrderTable . " 
			WHERE order_id = '" . $invoiceId . "'";
		mysqli_query($this->dbConnect, $sqlQuery);
		$this->deleteInvoiceItems($invoiceId);
		return 1;
	}
	public function totalAmt_invoiceCount($POST)
	{
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "
			SELECT SUM(order_total_after_tax) AS total, COUNT(order_id) AS row FROM " . $this->invoiceOrderTable . " 
			WHERE  CAST(order_date as DATE)  BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "'";
		} else {
			$sqlQuery = "
		SELECT SUM(order_total_after_tax) AS total, COUNT(order_id) AS row FROM " . $this->invoiceOrderTable . " 
		WHERE recorded_by='" . $_SESSION['userid'] . "' AND CAST(order_date as DATE)  BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "'";
		}
		return $this->getDatas($sqlQuery);
	}
	public function totalAmtPaid($POST)
	{
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "
		SELECT SUM(order_amount_paid) AS total FROM " . $this->invoiceOrderTable . " 
		WHERE  CAST(order_date as DATE)  BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "'";
		} else {
			$sqlQuery = "
		SELECT SUM(order_amount_paid) AS total FROM " . $this->invoiceOrderTable . " 
		WHERE recorded_by='" . $_SESSION['userid'] . "' AND CAST(order_date as DATE)  BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "'";
		}
		return $this->getDatas($sqlQuery);
	}
	public function totalItemSales($POST)
	{
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "
		SELECT SUM(invoice_order_item.order_item_final_amount) AS total FROM invoice_order_item join invoice_order on invoice_order_item.order_id=invoice_order.order_id WHERE CAST(order_date as DATE) BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "'";
		} else {
			$sqlQuery = "
		SELECT SUM(invoice_order_item.order_item_final_amount) AS total FROM invoice_order_item join invoice_order on invoice_order_item.order_id=invoice_order.order_id WHERE invoice_order.recorded_by='" . $_SESSION['userid'] . "' AND CAST(order_date as DATE) BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "'";
		}
		return $this->getDatas($sqlQuery);
	}
	public function totalItemSalesSP($POST)
	{
		$sqlQuery = "
			SELECT SUM(invoice_order_item.order_item_final_amount) AS total FROM invoice_order_item join invoice_order on invoice_order_item.order_id=invoice_order.order_id WHERE recorded_by='" . $POST['id'] . "' AND CAST(order_date as DATE) BETWEEN '" . $POST['from_date'] . "' AND '" . $POST['to_date'] . "'";

		return $this->getDatas($sqlQuery);
	}
	public function customerTotalSum($search)
	{
		$sqlQuery = "
			SELECT SUM(order_total_after_tax) AS total,SUM(order_total_amount_due) AS due FROM invoice_order WHERE order_receiver_id=" . $search;
		return $this->getDatas($sqlQuery);
	}
	public function customerTotalPayment($search)
	{
		$sqlQuery = "
			SELECT SUM(paid_amount) AS pay FROM payment WHERE order_receiver_id=" . $search;
		return $this->getDatas($sqlQuery);
	}
	public function payInvoiceDue($id, $amount)
	{
		$paid = $amount;
		$sqlQuery = "
			SELECT * from invoice_order WHERE order_receiver_id='" . $id . "' AND order_total_amount_due>0 ORDER BY order_total_amount_due ASC";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		//$output = '<table>';
		while ($data = mysqli_fetch_assoc($result)) {
			//$output .= "<tr><td>" . $data['order_total_amount_due'] . "</td></tr>";
			if ($paid > $data['order_total_amount_due'] && $paid > 0) {
				$sqlQuery1 = "
					UPDATE invoice_order SET order_total_amount_due=0.00,order_amount_paid='" . $data['order_total_after_tax'] . "' WHERE order_id='" . $data['order_id'] . "'";
				mysqli_query($this->dbConnect, $sqlQuery1);
				$paid = $paid - $data['order_total_amount_due'];
			} elseif ($paid == $data['order_total_amount_due'] && $paid > 0) {
				$sqlQuery1 = "
					UPDATE invoice_order SET order_total_amount_due=0.00,order_amount_paid='" . $data['order_total_after_tax'] . "' WHERE order_id='" . $data['order_id'] . "'";
				mysqli_query($this->dbConnect, $sqlQuery1);
				$paid = 0;
			} elseif ($paid < $data['order_total_amount_due'] && $paid > 0) {
				$sqlQuery1 = "
					UPDATE invoice_order SET order_total_amount_due=order_total_amount_due-$paid,order_amount_paid=order_amount_paid+$paid WHERE order_id='" . $data['order_id'] . "'";

				mysqli_query($this->dbConnect, $sqlQuery1);
				$paid = 0;
			}
		}
		// $output .= "</>";
		// return $output;
	}
	public function getDueInvoices()
	{
		if ($_SESSION['role'] == 'Admin') {
			$sqlQuery = "
		SELECT * from invoice_order WHERE order_total_amount_due>0 ORDER BY due_date ASC";
		} else {
			$sqlQuery = "
			SELECT * from invoice_order WHERE recorded_by='" . $_SESSION['userid'] . "' AND order_total_amount_due>0 ORDER BY due_date ASC";
		}
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		return $result;
	}
}
