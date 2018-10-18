<?php

require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

  try {
    $connection = new PDO($dsn, $username, $password, $options);

	if (!empty($_FILES['image_url']['name'])){
    
		$upload_img = uploadImage('image_url', 'photos/', '', TRUE, 'photos/thumbs/', '80', '60');
		
		$thumb_src = 'thumbs/' . $upload_img;
		
	} else {
		
		//if form is not submitted, below variable should be blank
		$thumb_src = '';
		$message = '';
	}

    $property =[
	  "id"        => $_POST['id'],
      "county" => $_POST['county'],
      "country"  => $_POST['country'],
      "town"     => $_POST['town'],
      "postcode"       => $_POST['postcode'],
      "description"  => $_POST['description'],
      "displayable_address"  => $_POST['displayable_address'],
	  "image_url"  => $upload_img,
      "thumbnail_url"  => $thumb_src,
	  "number_of_bed_rooms"  => $_POST['number_of_bed_rooms'],
	  "number_of_bath_rooms"  => $_POST['number_of_bath_rooms'],
	  "price"  => $_POST['price'],
	  "property_type"  => $_POST['property_type'],
	  "property_for"  => $_POST['property_for']
    ];

    $sql = "UPDATE properties 
            SET 
			  county = :county, 
              country = :country, 
              town = :town, 
              postcode = :postcode, 
              description = :description, 
              displayable_address = :displayable_address,
			  image_url = :image_url,
			  thumbnail_url = :thumbnail_url,
			  number_of_bed_rooms = :number_of_bed_rooms,
			  number_of_bath_rooms = :number_of_bath_rooms,
			  price = :price,
			  property_type = :property_type,
			  property_for = :property_for
            WHERE id = :id";
  
  $statement = $connection->prepare($sql);
  $statement->execute($property);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}
  
if (isset($_GET['id'])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);
    $id = $_GET['id'];

    $sql = "SELECT * FROM properties WHERE id = :id";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();
    
    $property = $statement->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
} else {
    echo "Something went wrong!";
    exit;
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) : ?>
	<blockquote>Property successfully updated.</blockquote>
<?php endif; ?>

<h2>Edit a Property</h2>

<form method="post" id="property_form" enctype="multipart/form-data">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
    <input name="id" type="hidden" value="<?php echo escape($property["id"]); ?>">
	<label for="county">County</label>
    <input type="text" name="county" id="county" value="<?php echo escape($property["county"]); ?>">
    <label for="country">Country</label>
    <input type="text" name="country" id="country" value="<?php echo escape($property["country"]); ?>">
    <label for="town">Town</label>
    <input type="text" name="town" id="town" value="<?php echo escape($property["town"]); ?>">
    <label for="postcode">Post code</label>
    <input type="text" name="postcode" id="postcode" value="<?php echo escape($property["postcode"]); ?>">
    <label for="description">Description</label>
    <textarea name="description" rows="5" cols="40" id="description"><?php echo escape($property["description"]); ?></textarea>
	<label for="displayable_address">Displayable Address</label>
    <input type="text" name="displayable_address" id="displayable_address" value="<?php echo escape($property["displayable_address"]); ?>">
	<label for="image_url">Upload Image</label>
    <input type="file" name="image_url" id="image_url">
	<label for="number_of_bed_rooms">Number of bedrooms</label>
    <select name="number_of_bed_rooms" id="number_of_bed_rooms">
	<option value="">Select</option>
	<?php for($i = 1;$i<=10;$i++){?>
	<option value="<?php echo $i;?>" <?php if ($i == $property["number_of_bed_rooms"]) { echo 'selected';}?>><?php echo $i;?></option>
	<?php }?>
	</select>
	<label for="number_of_bath_rooms">Number of bathrooms</label>
    <select name="number_of_bath_rooms" id="number_of_bath_rooms">
	<option value="">Select</option>
	<?php for($i = 1;$i<=10;$i++){?>
	<option value="<?php echo $i;?>" <?php if ($i == $property["number_of_bath_rooms"]) { echo 'selected';}?>><?php echo $i;?></option>
	<?php }?>
	</select>
	<label for="price">Price</label>
    <input type="text" name="price" id="price" value="<?php echo escape($property["price"]); ?>">
	<label for="property_type">Property Type</label>
    <select name="property_type" id="property_type">
	<?php foreach ($property_types as $value) {?>
	<option value="">Select Property Type</option>
	<option value="<?php echo $value;?>" <?php if ($value == $property["property_type"]) { echo 'selected';}?>><?php echo $value;?></option>
	<?php }?>
	</select>
	<label for="property_for">Property For</label>
    <input id="property_for" name="property_for" type="radio" value="sale" <?php if ($property["property_for"] == 'sale') { echo 'checked';}?>> Sale
	<input id="property_for" name="property_for" type="radio" value="rent" <?php if ($property["property_for"] == 'rent') { echo 'checked';}?>> Rent
	<br><br>
    <input type="submit" name="submit" value="Submit">
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
