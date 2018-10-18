<?php

require "../config.php";
require "../common.php";

$url = 'https://api.zoopla.co.uk/api/v1/property_listings';
$responderUrl = "?area=Oxford&api_key=" . $zoopla_api_key;

$request = curl_init();
curl_setopt($request, CURLOPT_URL, $url . $responderUrl);
curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($request, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($request, CURLOPT_HEADER, FALSE);
$result = curl_exec($request);
curl_close($request);

$properties = simplexml_load_string($result) or die("Error: Cannot create object");

try {
  $connection = new PDO($dsn, $username, $password, $options);
	foreach ($properties->listing as $property) {
		$listing_id = $property->listing_id;

		$sql = "SELECT * FROM properties WHERE listing_id = :listing_id";
		$statement = $connection->prepare($sql);
		$statement->bindValue(':listing_id', $listing_id);
		$statement->execute();
		
		$zoopla_property = $statement->fetch(PDO::FETCH_ASSOC);
		if ($property->status == 'for_sale') {
			$property_status = 'sale';
		} else {
			$property_status = 'rent';
		}

		$new_property = array(
			  "county" => $property->county,
			  "country"  => $property->country,
			  "town"     => $property->post_town,
			  "description"  => $property->description,
			  "displayable_address"  => $property->displayable_address,
			  "image_url"  => $property->image_url,
			  "thumbnail_url"  => $property->thumbnail_url,
			  "latitude" => $property->latitude,
			  "longitude" => $property->longitude,
			  "number_of_bed_rooms"  => $property->num_bedrooms,
			  "number_of_bath_rooms"  => $property->num_bathrooms,
			  "price"  => $property->price,
			  "property_type"  => $property->category,
			  "property_for"  => $property_status,
			  "listing_id" => $listing_id
			);

		if ($zoopla_property) {
			$sql = "UPDATE properties 
				SET 
				  county = :county, 
				  country = :country, 
				  town = :town, 
				  description = :description, 
				  displayable_address = :displayable_address,
				  image_url = :image_url,
				  thumbnail_url = :thumbnail_url,
				  latitude = :latitude,
				  longitude = :longitude,
				  number_of_bed_rooms = :number_of_bed_rooms,
				  number_of_bath_rooms = :number_of_bath_rooms,
				  price = :price,
				  property_type = :property_type,
				  property_for = :property_for,
				  listing_id = :listing_id
				WHERE id = " . $zoopla_property["id"];
	  
			$statement = $connection->prepare($sql);
			$statement->execute($new_property);
		} else {
			echo $sql = sprintf(
			  "INSERT INTO %s (%s) values (%s)",
			  "properties",
			  implode(", ", array_keys($new_property)),
			  ":" . implode(", :", array_keys($new_property))
			);
			$statement = $connection->prepare($sql);
			$statement->execute($new_property);
		}
	}
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<blockquote>Properties loaded from Zoopla...</blockquote>
  <a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
