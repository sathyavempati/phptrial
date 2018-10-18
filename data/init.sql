CREATE DATABASE phptrial;

use phptrial;

CREATE TABLE properties (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	county VARCHAR(255) NOT NULL,
	country VARCHAR(255) NOT NULL,
	town VARCHAR(255) NOT NULL,
	postcode VARCHAR(255) NOT NULL,
	description TEXT NOT NULL,
	displayable_address VARCHAR(255) NOT NULL,
	image_url VARCHAR(255),
	thumbnail_url VARCHAR(255),
	latitude DECIMAL(11,8),
	longitude DECIMAL(11,8),
	number_of_bed_rooms SMALLINT(5),
	number_of_bath_rooms SMALLINT(5),
	price DECIMAL(12,4),
	property_type VARCHAR(255) NOT NULL,
	property_for ENUM('sale','rent'),
	listing_id VARCHAR(255),
	date TIMESTAMP
);