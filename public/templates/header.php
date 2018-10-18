<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>PHP Trial</title>

	<link rel="stylesheet" href="css/style.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
	<script>
	$(document).ready(function() {

	  $('form[id="property_form"]').validate({
		  rules: {
			county: 'required',
			country: 'required',
			town: 'required',
			postcode: 'required',
			description: 'required',
			displayable_address: 'required',
			number_of_bed_rooms: 'required',
			number_of_bath_rooms: 'required',
			price: 'required',
			property_type: 'required',
			property_for: 'required',
		  },
		  messages: {
			county: 'This field is required',
			country: 'This field is required',
			town: 'This field is required',
			postcode: 'This field is required',
			description: 'This field is required',
			displayable_address: 'This field is required',
			number_of_bed_rooms: 'This field is required',
			number_of_bath_rooms: 'This field is required',
			price: 'This field is required',
			property_type: 'This field is required',
			property_for: 'This field is required',
		  },
		  submitHandler: function(form) {
			form.submit();
		  }
		});
	});
	</script>
</head>

<body>
	<h1>PHP Trial</h1>
