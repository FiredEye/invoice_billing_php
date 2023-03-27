<?php
class Customer
{
    private $host  = 'localhost';
    private $user  = 'root';
    private $password   = "";
    private $database  = "invoice_system";
    private $customerTable = 'customer';
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
    public function checkLoggedIn()
    {
        if (!$_SESSION['userid']) {
            header("Location:login.php");
        }
    }
    public function saveCustomer($POST)
    {
        for ($i = 0; $i < count($POST['customerFName']); $i++) {
            $sqlInsertCustomer = "
                INSERT INTO " . $this->customerTable . "(recorded_by,first_name, last_name, address, phone_no, email) 
                VALUES ('" . $_SESSION['userid'] . "','" . $POST['customerFName'][$i] . "', '" . $POST['customerLName'][$i] . "', '" . $POST['customerAddress'][$i] . "', '" . $POST['customerPhone'][$i] . "', '" . $POST['customerEmail'][$i] . "')";
            mysqli_query($this->dbConnect, $sqlInsertCustomer);
        }
    }
    public function updateCustomer($POST)
    {

        $sqlUpdateCustomer  = "
            UPDATE " . $this->customerTable . " 
            SET first_name = '" . $POST['customerFName'] . "', last_name = '" . $POST['customerLName'] . "', address= '" . $POST['customerAddress'] . "', phone_no = '" . $POST['customerPhone'] . "', email = '" . $POST['customerEmail'] . "' WHERE id = '" . $POST['id'] . "'";

        return mysqli_query($this->dbConnect, $sqlUpdateCustomer);
    }
    public function getNumRows_Customer()
    {
        $sqlQuery = "
                SELECT * FROM " . $this->customerTable;
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        $numRows = mysqli_num_rows($result);
        return $numRows;
    }
    //for create_invoice.php
    public function getCustomerslist()
    {
        $sqlQuery = "
                SELECT * FROM " . $this->customerTable . " WHERE recorded_by='" . $_SESSION['userid'] . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        return  $result;
    }
    //for customer_list.php
    public function getCustomerlist()
    {
        $sqlQuery = "
                SELECT * FROM " . $this->customerTable . " WHERE recorded_by='" . $_SESSION['userid'] . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        return $this->getData($sqlQuery);
    }
    public function getCustomerlocation($POST)
    {
        $sqlQuery = "
                SELECT address FROM " . $this->customerTable . " WHERE first_name='" . $_POST['fname'] . "' AND last_name='" . $_POST['lname'] . "'";

        return $this->getData($sqlQuery);
    }
    public function getCustomer($customerId)
    {
        $sqlQuery = "
                SELECT * FROM " . $this->customerTable . " 
                WHERE id = '$customerId'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
    public function deleteCustomer($customerId)
    {
        $sqlQuery = "
                DELETE FROM " . $this->customerTable . " 
                WHERE id = '" . $customerId . "'";
        mysqli_query($this->dbConnect, $sqlQuery);
        return 1;
    }
    //for email
    public function getCustomerDetail($customerName)
    {
        $sqlQuery = "
                SELECT * FROM " . $this->customerTable . "  
                WHERE first_name='" . $customerName[0] . "' AND last_name='" . $customerName[1] . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
    public function getCustomerbyEmail($search)
    {

        $sqlQuery = "
        SELECT * FROM " . $this->customerTable . " WHERE email='$search'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        return $result;
    }
    public function getCustomerbyNumber($search)
    {

        $sqlQuery = "
        SELECT * FROM " . $this->customerTable . " WHERE phone_no='$search'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        return $result;
    }
    public function getCustomerbyId($search)
    {
        $sqlQuery = "
        SELECT * FROM " . $this->customerTable . " WHERE id='$search'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        return $result;
    }
    public function getCustomersbyId($search)
    {
        $sqlQuery = "
        SELECT * FROM " . $this->customerTable . " WHERE id='$search'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        return $this->getDatas($sqlQuery);
    }
}
