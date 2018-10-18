<?php

require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

  try  {
    $connection = new PDO($dsn, $username, $password, $options);

	if (!empty($_FILES['image_url']['name'])){
    
		$upload_img = uploadImage('image_url', 'photos/', '', TRUE, 'photos/thumbs/', '80', '60');
		
		$thumb_src = 'thumbs/' . $upload_img;
		
	} else {
		
		//if form is not submitted, below variable should be blank
		$thumb_src = '';
		$message = '';
	}
    
    $new_property = array(
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
    );

    $sql = sprintf(
      "INSERT INTO %s (%s) values (%s)",
      "properties",
      implode(", ", array_keys($new_property)),
      ":" . implode(", :", array_keys($new_property))
    );
    
    $statement = $connection->prepare($sql);
    $statement->execute($new_property);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}
?>
<?php require "templates/header.php"; ?>


  <?php if (isset($_POST['submit']) && $statement) : ?>
    <blockquote>Property successfully added.</blockquote>
  <?php endif; ?>

  <h2>Add a Property</h2>

  <form method="post" id="property_form" enctype="multipart/form-data">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
    <label for="county">County</label>
    <input type="text" name="county" id="county">
    <label for="country">Country</label>
    <input type="text" name="country" id="country">
    <label for="town">Town</label>
    <input type="text" name="town" id="town">
    <label for="postcode">Post code</label>
    <input type="text" name="postcode" id="postcode">
    <label for="description">Description</label>
    <textarea name="description" rows="5" cols="40" id="description"></textarea>
	<label for="displayable_address">Displayable Address</label>
    <input type="text" name="displayable_address" id="displayable_address">
	<label for="image_url">Upload Image</label>
    <input type="file" name="image_url" id="image_url">
	<label for="number_of_bed_rooms">Number of bedrooms</label>
    <select name="number_of_bed_rooms" id="number_of_bed_rooms">
	<option value="">Select</option>
	<?php for($i = 1;$i<=10;$i++){?>
	<option value="<?php echo $i;?>"><?php echo $i;?></option>
	<?php }?>
	</select>
	<label for="number_of_bath_rooms">Number of bathrooms</label>
    <select name="number_of_bath_rooms" id="number_of_bath_rooms">
	<option value="">Select</option>
	<?php for($i = 1;$i<=10;$i++){?>
	<option value="<?php echo $i;?>"><?php echo $i;?></option>
	<?php }?>
	</select>
	<label for="price">Price</label>
    <input type="text" name="price" id="price">
	<label for="property_type">Property Type</label>
    <select name="property_type" id="property_type">
	<?php foreach ($property_types as $value) {?>
	<option value="">Select Property Type</option>
	<option value="<?php echo $value;?>"><?php echo $value;?></option>
	<?php }?>
	</select>
	<label for="property_for">Property For</label>
    <input id="property_for" name="property_for" type="radio" value="sale"> Sale
	<input id="property_for" name="property_for" type="radio" value="rent"> Rent
	<br><br>
    <input type="submit" name="submit" value="Submit">
  </form>

  <a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
