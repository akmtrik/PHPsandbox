<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8"> 
	<title>購入履歴画面</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

	<?php 
		require "menu.php";
		require "db_connect.php";
    if (isset($_SESSION["customer"])) {
		$sql = "select * from purchase";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(":id",$_SESSION["customer"]["id"],PDO::PARAM_INT);
		$stm->execute();
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);  
        foreach ($result as $row) {
			$sql_detail = "
			select product.id as product_id, name, price, count
			from purchase_detail, product
			where purchase_id = :purchase_id and product_id = product.id ";
			$stm_detail = $pdo->prepare($sql_detail);
			$stm_detail->bindValue(":purchase_id",$row["id"],PDO::PARAM_INT);
			$stm_detail->execute();
			$result_detail = $stm_detail->fetchAll(PDO::FETCH_ASSOC); 
	    ?>
<table>
		<th>商品番号</th>
		<th>商品名</th>
		<th>価格</th>
		<th>個数</th>
		<th>小計</th>
		<?php
		$total = 0;
		foreach ($result_detail as $row_detail) {
		?>
			<tr>
				<td><?= $row_detail["product_id"] ?></td>
				<td><a href="detail.php?id=<?= $row_detail["product_id"] ?>"><?= $row_detail['name'] ?></a></td>
				<td><?= $row_detail["price"] ?></td>
				<td><?= $row_detail["count"] ?></td>
				<?php
				$subtotal = $row_detail["price"] * $row_detail["count"];
				$total += $subtotal;
				?>
				<td><?= $subtotal ?></td>
			</tr>
		<?php
		}
		?>
		<tr>
			<td>合計</td>
			<td></td>
			<td></td>
			<td></td>
			<td><?= $total ?></td>
			<td></td>
		</tr>
	</table>
	<?php
		}
	}else{
		echo "購入履歴を表示するには、ログインしてください。";
	}
	?>

	
</body>
</html>