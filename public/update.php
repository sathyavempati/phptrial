<?php

require "../config.php";
require "../common.php";

try {
  $connection = new PDO($dsn, $username, $password, $options);

  if (isset($_GET["id"]) && $_GET['action'] == 'del') {
	  try {
	  
		$id = $_GET["id"];

		$sql = "DELETE FROM properties WHERE id = :id";

		$statement = $connection->prepare($sql);
		$statement->bindValue(':id', $id);
		$statement->execute();

		$success = "Property successfully deleted";
	  } catch(PDOException $error) {
		echo $sql . "<br>" . $error->getMessage();
	  }
  }

  $sql = "SELECT * FROM properties";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>
        
<h2>List of Properties</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>County</th>
            <th>Country</th>
            <th>Town</th>
            <th>Postcode</th>
            <th>Number of bedrooms</th>
            <th>Number of bathrooms</th>
			<th>Price</th>
			<th>Property Type</th>
			<th>Property For</th>
			<th>Picture</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($result as $row) : ?>
        <tr>
            <td><?php echo escape($row["id"]); ?></td>
            <td><?php echo escape($row["county"]); ?></td>
            <td><?php echo escape($row["country"]); ?></td>
            <td><?php echo escape($row["town"]); ?></td>
            <td><?php echo escape($row["postcode"]); ?></td>
            <td><?php echo escape($row["number_of_bed_rooms"]); ?></td>
            <td><?php echo escape($row["number_of_bath_rooms"]); ?> </td>
			<td><?php echo escape($row["price"]); ?> </td>
			<td><?php echo escape($row["property_type"]); ?> </td>
			<td><?php echo escape($row["property_for"]); ?> </td>
			<td>
			<?php 
			if ($row["thumbnail_url"] != '' && file_exists('photos/' . $row["thumbnail_url"])) {
				echo '<img src="' . 'photos/' . $row["thumbnail_url"] . '">';
			} elseif ($row["thumbnail_url"] != '') {
				echo '<img src="' . $row["thumbnail_url"] . '">';
			}
			?>
			 </td>
            <td><a href="update-single.php?id=<?php echo escape($row["id"]); ?>">Edit</a></td>
			<td><a href="update.php?action=del&id=<?php echo escape($row["id"]); ?>">Delete</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>