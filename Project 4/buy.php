<?php
	// Name: Shagun Paul
	// UTA ID: 1001557958
	// CSE 5335: Web Data Management Project 4: E-Commerce Web Application
?>

<?php
session_start(); 
if (isset($_GET['clear'])) 
{
	session_destroy();
	header("location:"."buy.php");
} 
?>
<html>
<head>
	<title> Buy Products </title>
</head>
<body style="background-color: #D7D2CF;">
	<h2 align="left" style="color: #6E464A; font-family: 'Comic Sans MS';"> Web Data Management: An E-Commerce Web Application </h2>
<p><b> Shopping Basket: </b></p>

<?php
// Creates a session to stores ID of product to be put into shopping cart
if(!isset($_SESSION['id']))
{
	$_SESSION['id'] = array();
}
if(isset($_GET['buy']))
{
	array_push($_SESSION['id'], $_GET['buy']);
}			
?>

<?php
if(!isset($_SESSION['basket']))
	$_SESSION['basket'] = array();
if(isset($_GET['buy']))
{
	foreach ($_SESSION['id'] as $i) 
	{
		foreach ($_SESSION['data'] as $key => $value) 
		{
			if($i===$value[0])
			{
				array_push($_SESSION['basket'], $value);
			}
		}
	}
}
?>

<?php
// Used to delete a selected product
if(isset($_GET['delete']))
{
	foreach ($_SESSION['basket'] as $key => $value) 
	{
			if (in_array($_GET['delete'], $value))
			{
				unset($_SESSION['basket'][$key]);
			}	
	}
} 
?>

<?php 
// Used to delete a duplicate product
$shopping = $_SESSION['basket'];
$out_arr = array();
$key = array(); 
foreach ($shopping as $arr_in) 
{ 
    if (!in_array($arr_in[0], $key)) 
    { 
        $key[] = $arr_in[0]; 
        $out_arr[] = $arr_in; 
    }
}

$_SESSION['basket'] = $out_arr;
?>

<?php 
// displays shopping cart and evaluates total amount
$amount = 0;
if(isset($_SESSION['basket']))
{
	echo "<table border='2'>";
	
	foreach ($_SESSION['basket'] as $x) 
	{
		echo "<tr>";
		echo "<td>".$x[3]."</td>";
		echo "<td>"."$".$x[2]."</td>";
		echo "<td><a style='color: #a83838;' href=buy.php?delete=".$x[0].">Delete</a></td>";
		$amount += $x[2];
		echo "</tr>";
	}
	echo "<table border='2'>";

}
?>

<?php
if(isset($GET['Search']))
{
	$search_key = $_GET['Search'];

}
if(isset($_GET['category']))
{
	$category_id = $_GET['category'];
}
?>

<?php
// 
error_reporting(E_ALL);
ini_set('display_errors','Off');
$xmlstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&showAllDescendants=true');
$xml_cat = new SimpleXMLElement($xmlstr);
header('Content-Type: text/html');
?>

<p/> <b>Total: </b><?php echo "$".$amount ?><p/> 
<form action="buy.php" method="GET">
<input type="hidden" name="clear" value="1"/> 
<input type="submit" value="Empty Basket"/> 
</form> 
<form action="buy.php" method="GET">
<fieldset>
	<legend>Find products: </legend> 
	<label> Categories: 
<select id="category" name="category">
    <option value="72">Computers</option>
    <?php
        foreach ($xml_cat->category->categories->category as $item) {
     ?>
        <option value="<?php echo $item['id']; ?>"><?php echo $item->name; ?></option>
           <optgroup label="<?php echo $item->name; ?>">
                <?php
                if (isset($item->categories->category)) {
                    foreach ($item->categories->category as $itemvalue) 
                    {
                ?>
                    		<option value="<?php echo $itemvalue['id']; ?>"><?php echo $itemvalue->name; ?></option>
                      		<?php
                         }
                       }
                            ?>
           </optgroup>
           <?php
                }
           ?>
</select>
	</label>
	<label> Search Keywords: 
	<input id = "searchkey" type="text" name="Search"/>
	</label>
	<input type="submit" name="Submit" value="Search"/> 
</fieldset> 
</form>
<br><br>

<?php
// Displays products on the basis of search keywords entered.
if (isset($_GET['Submit'])) 
{
	$xml_find = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&keyword=' . $search_key . '&categoryId=' . $category_id . '&numItems=20'); 
    $prod_xml = new SimpleXMLElement($xml_find);

		$_SESSION['data'] = array();

		if($prod_xml)
		{
			global $product_arr;
			foreach($prod_xml->categories->category->items->offer as $prod)
			{
				$product_arr = array();
				$name = (string)$prod->name;
				$prod_id = (string)$prod->attributes()->id;
				$prod_desc = (string)$prod->description;
				$price = (string)$prod->basePrice;
				array_push($product_arr,$prod_id,$name,$price,$prod_desc);
				array_push($_SESSION['data'], $product_arr); 

			}
		}
		echo "<table border='2' style='background-color: #BAAFAC'>";
		//echo "<td> &nbsp&nbsp </td>";
		echo "<tr style='height: 40px; background-color: #8C7A76;'>
		<th> Product Name:</th>
		<th> Product Price:</th>
		<th> Product Description: </th></tr>";
			foreach ($_SESSION['data'] as $a) 
			{
					
					echo "<tr>";
					echo "<td><a style='color: #a83838;' href=buy.php?buy=".$a[0].">".$a[1]."</a></td>";
					echo "<td>"."$".$a[2]."</td>";
					echo "<td>".$a[3]."</td>";
					echo "</tr>";
			}
		echo "<table border='2'>";
	}
?>
</body>
</html>