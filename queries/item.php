<?php
class Item
{
    private $host  = 'localhost';
    private $user  = 'root';
    private $password   = "";
    private $database  = "invoice_system";
    private $itemTable = 'items';
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
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    private function getDatas($sqlQuery)
    {
        $data = '';
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
    public function saveItem($POST)
    {
        for ($i = 0; $i < count($POST['itemName']); $i++) {
            $sqlInsertItem = "
                INSERT INTO " . $this->itemTable . "(code, name, price, quantity, min_quantity) 
                VALUES ('" . $POST['itemCode'][$i] . "', '" . $POST['itemName'][$i] . "', '" . $POST['itemPrice'][$i] . "', '" . $POST['itemQuantity'][$i] . "', '" . $_POST['itemMinQuantity'][$i] . "')";
            mysqli_query($this->dbConnect, $sqlInsertItem);
        }
    }

    public function getNumRows_Item()
    {
        $sqlQuery = "
                SELECT * FROM " . $this->itemTable;
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        $numRows = mysqli_num_rows($result);
        return $numRows;
    }
    //for create_invoice.php
    public function getItemslist()
    {
        $sqlQuery = "
                SELECT * FROM " . $this->itemTable;
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        return  $result;
    }
    //for item_list.php
    public function getItemlist()
    {
        $sqlQuery = "
                SELECT * FROM " . $this->itemTable;
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        return $this->getData($sqlQuery);
    }
    public function addItem($code, $quantity)
    {
        $sqlUpdateItem  = "
    UPDATE " . $this->itemTable . " SET quantity = quantity+$quantity WHERE code =$code";

        return mysqli_query($this->dbConnect, $sqlUpdateItem);
    }
    public function deleteItem($itemId)
    {
        $sqlQuery = "
                DELETE FROM " . $this->itemTable . " 
                WHERE code = '" . $itemId . "'";
        mysqli_query($this->dbConnect, $sqlQuery);
        return 1;
    }
    public function getNamePrice($POST)
    {
        $sqlQuery = "
            SELECT * FROM " . $this->itemTable . " WHERE code='" . $_POST['code'] . "' ";
        return $this->getDatas($sqlQuery);
    }
    public function getItem($itemCode)
    {
        $sqlQuery = " 
                SELECT * FROM " . $this->itemTable . " 
                WHERE code = '$itemCode'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
    public function updateItem($POST)
    {

        $sqlUpdateItem  = "
            UPDATE " . $this->itemTable . " SET name = '" . $POST['itemName'] . "', price= '" . $POST['itemPrice'] . "', quantity = '" . $POST['itemQuantity'] . "' WHERE code = '" . $POST['itemCode'] . "'";

        return mysqli_query($this->dbConnect, $sqlUpdateItem);
    }
    public function getItembyNC($search)
    {
        $search_value = $search;
        $sqlQuery = "
        SELECT * FROM " . $this->itemTable . " WHERE code LIKE '%{$search_value}%' OR name LIKE '%{$search_value}%'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        return $result;
    }
    public function getItemAmtQty()
    {
        $sqlQuery = "
			SELECT SUM(price*quantity) as total, SUM(quantity) as quantity FROM " . $this->itemTable;
        return  $this->getData($sqlQuery);
    }
    public function getItembyId($search)
    {
        $sqlQuery = "
        SELECT * FROM " . $this->itemTable . " WHERE code=$search";
        return $this->getDatas($sqlQuery);
    }
    public function getLowStock()
    {
        $sqlQuery = "
        SELECT * FROM " . $this->itemTable . " WHERE quantity<=min_quantity ORDER BY quantity ASC";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        return $result;
    }
}
