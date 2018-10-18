<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (empty($_SESSION['csrf'])) {
	if (function_exists('random_bytes')) {
		$_SESSION['csrf'] = bin2hex(random_bytes(32));
	} else if (function_exists('mcrypt_create_iv')) {
		$_SESSION['csrf'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
	} else {
		$_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
	}
}

/**
 * Escapes HTML for output
 *
 */

function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

$property_types = ["Residential","Residential House","Villa","Studio Apartment","Commercial Office Space","Farm House"];

$zoopla_api_key = 'wxg8jtyuybwq2ydqu4xuejw5';

function uploadImage($field_name = '', $target_folder = '', $file_name = '', $thumb = FALSE, $thumb_folder = '', $thumb_width = '', $thumb_height = ''){

    //folder path setup
    $target_path = $target_folder;
    $thumb_path = $thumb_folder;
    
    //file name setup
    $filename_err = explode(".",$_FILES[$field_name]['name']);
    $filename_err_count = count($filename_err);
    $file_ext = $filename_err[$filename_err_count-1];
    if($file_name != ''){
        $fileName = $file_name.'.'.$file_ext;
    }else{
        $fileName = $_FILES[$field_name]['name'];
    }
    
    //upload image path
    $upload_image = $target_path . basename($fileName);
    
    //upload image
    if(move_uploaded_file($_FILES[$field_name]['tmp_name'],$upload_image))
    {
        //thumbnail creation
        if($thumb == TRUE)
        {
            $thumbnail = $thumb_path . $fileName;
            list($width, $height) = getimagesize($upload_image);
            $thumb_create = imagecreatetruecolor($thumb_width, $thumb_height);
            switch($file_ext){
                case 'jpg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;
                case 'jpeg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;

                case 'png':
                    $source = imagecreatefrompng($upload_image);
                    break;
                case 'gif':
                    $source = imagecreatefromgif($upload_image);
                    break;
                default:
                    $source = imagecreatefromjpeg($upload_image);
            }

            imagecopyresized($thumb_create,$source,0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
            switch($file_ext){
                case 'jpg' || 'jpeg':
                    imagejpeg($thumb_create, $thumbnail, 100);
                    break;
                case 'png':
                    imagepng($thumb_create, $thumbnail, 100);
                    break;

                case 'gif':
                    imagegif($thumb_create, $thumbnail, 100);
                    break;
                default:
                    imagejpeg($thumb_create, $thumbnail, 100);
            }

        }

        return $fileName;
    }
    else
    {
        return false;
    }
}