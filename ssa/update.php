<?php

// Create connection
$con=mysqli_connect("192.168.1.100","Jeezy","Bliss20106","ssa2");
 
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$meth = $_POST['meth'];
$id = $_POST['id'];
$name = $_POST['name'];
$shipping = $_POST['shipping'];
$invoice = $_POST['invoice'];
$notes = $_POST['notes'];
$add1 = $_POST['add1'];
$add2 = $_POST['add2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$date = $_POST['date'];
$email = $_POST['email'];

if (!empty($meth)) {
  if($meth == "up") {
      $sql = "UPDATE sales SET Email = '$email', OrderDate = '$date', Address1 = '$add1', Address2 = '$add2', City = '$city', State = '$state', Zip = '$zip', NameOnOrder = '$name', InvoicePrice = '$invoice', Shipping = '$shipping', Fee = 0.029 * InvoicePrice + 0.30, Profit = (InvoicePrice - Shipping - Fee) / 2, Expecting = InvoicePrice - Fee, Notes = '$notes' WHERE ID = $id";
  } else if ($meth == "in") {
      $sql = "INSERT INTO sales SET Email = '$email', Address1 = '$add1', Address2 = '$add2', City = '$city', State = '$state', Zip = '$zip', NameOnOrder = '$name', OrderDate = '$date', InvoicePrice = '$invoice', Shipping = '$shipping', Fee = 0.029 * InvoicePrice + 0.30, Profit = (InvoicePrice - Shipping - Fee) / 2, Expecting = InvoicePrice - Fee";
  }
  
  $result = mysqli_query($con, $sql);

  if ($result) {
    echo "Successful.";
  }   
}

// Close connections
mysqli_close($con);