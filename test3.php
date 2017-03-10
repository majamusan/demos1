<?php
/**
 * QUESTION 3
 *
 * Using the data stored in the database
 * show a list of top customers per month.
 * For each month that had sales show a list of customers ordered by who spent the most to who spent least.
 * Months with no sales should not show up.
 * If there are no results, then it should just say "There are no results available."
 *
 * See test3.html for desired result.
 */
?>
<?php
//$con holds the connection
require_once('db.php');
$sql = 'SELECT orders.order_date, u.id as uid, u.first_name,u.last_name,devtest.products.product,devtest.products.price FROM devtest.users  as u
LEFT JOIN devtest.orders   ON u.id = devtest.orders.user_id 
LEFT JOIN devtest.order_items  ON devtest.orders.id = devtest.order_items.order_id
Left join devtest.products ON devtest.products.id = devtest.order_items.product_id
WHERE devtest.products.product IS NOT NULL
order by devtest.orders.order_date';

// US national format, using () for negative numbers
// and 10 digits for left precision
setlocale(LC_MONETARY, 'eng');
$output = '';
if($res = mysqli_query($con, $sql)){
	while($row = $res->fetch_object()) {
		$date = date_create($row->order_date);
		$data[date_format($date, 'F Y')][$row->uid]['products'][] = $row->product;
		$data[date_format($date, 'F Y')][$row->uid]['total'] += $row->price;
		$data[date_format($date, 'F Y')][$row->uid]['name'] = $row->first_name.' '.$row->last_name;
	}
	foreach($data as $month => $users){
		$output .= '<tr><th colspan="3"><h2>'.$month.'</h2></th></tr>';
		$output .= '<tr><th>Customer</th><th>Product(s) Bought</th><th style="text-align:right;">Total</th></tr>';
		usort($users, 'totalSort');
		foreach($users as $name => $orders){
			//if($orders['total']<400) continue;//top customer was never defined.
			$output .= '<tr><td>'.$orders['name'].'</td>';
			$i=1;
			foreach($orders['products'] as $k => $product){
				$output .= ($i > 1 ? '<tr><td></td>':'');
				$output .= '<td>'.$product.'</td>';
				$output .= (count($orders['products']) == $i 
						? '<td style="text-align:right;">R '.number_format($orders['total'],2).'</td></tr><tr><td colspan="3"><hr /></td></tr>' 
						: '</tr>');
				$i++;
			}
		}
	}
}else{
	$output .= '<tr></th>There are no results available.</th></tr>';
}

function totalSort($a, $b) {
	if ($a['total'] == $b['total']) return 0;
	return ($a['total'] > $b['total']) ? -1 : 1;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Test3</title>
</head>
<body>
<h1>Top Customers per Month</h1>

<table width="100%"> <?=$output?> </table>



</body>
</html>
