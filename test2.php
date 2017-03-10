<?php
/**
 * QUESTION 2
 *
 * Using the data stored in the database
 * show a list of products with their prices
 * grouped by category.
 * The categories should be listed in alphabetical order.
 * The products within those categories should also be listed in alphabetical order.
 * Products with no category will be categorized as "Uncategorized".
 * If there are no results, then it should just say "There are no results available."
 *
 * See test2.html for desired result.
 */
?>
<?php
	require_once('db.php');
	$output = '';
	$sql = 'SELECT p.product,p.price, IFNULL(devtest.categories.category,\'Uncategorized\') as category FROM devtest.products as p '.
					'LEFT JOIN devtest.categories  ON p.category_id = devtest.categories.id order by devtest.categories.category';
				
	if($res = mysqli_query($con, $sql)){
		while($row = $res->fetch_object()) {
			$categorys[$row->category][] = '<tr><td>'.$row->product.'</td><td>R '.number_format($row->price,2).'</td></tr>';
		}
		ksort($categorys);
		foreach($categorys as $cats => $products){
			$output .= '<tr><th colspan="2"><h2>'.$cats.'</h2></th></tr>';
			$output .= '<tr><th>Product</th><th>Price</th></tr>';
			$output .= implode('',$products);
		}
	}else{
		$output .= '<tr></th>There are no results available.</th></tr>';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Test2</title>
	</head>
	<body>
		<h1>Products</h1>
	<table width="50%"> <?=$output?> </table>
	</body>
</html>
