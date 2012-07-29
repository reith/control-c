<?php
require './lib.php';
header ('Cache-Control: no-cache');

//Start the session so we can store what the security code actually is
session_start();
header('Content-type: image/png');

//Send a generated image to the browser 
create_image(); 
exit(); 

function create_image() 
{ 
    $image_width = 197; 
    $image_height = 40; 
    $image_base = imagecreatetruecolor($image_width,$image_height); 
    $image_background = imagecolorallocate($image_base, 255, 255, 255); 
    imagefill($image_base, 0, 0, $image_background); 

    if(@isset($_SESSION['security_code'])) { 
        $image_text = $_SESSION['security_code']; 
    } 
    else { 
        $image_text = str_rand(6, 'alnum'); 
    } 
	

    //Set the session to store the security code
    $_SESSION["security_code"] = $image_text;
	
    $image_text_array = str_split($image_text, 1); 
    $image_text_color = imagecolorallocate($image_base,0,0,0); 
    $image_font = 'verdana.ttf'; 
    $image_letter = ($image_width/8); 

    for($i=0;$i<=16;$i++) { 
        $image_line_color = imagecolorallocatealpha($image_base,mt_rand('0','200'),mt_rand('0','200'),mt_rand('0','200'),mt_rand('20','115')); 
        imagesetthickness($image_base,mt_rand('1','3')); 
        imageline($image_base,mt_rand('0',$image_width),mt_rand('0',$image_height),mt_rand('0',$image_width),mt_rand('0',$image_height),$image_line_color);
    } 
    $image_dots_spacing = 10; 
    $image_dots_y = $image_height/$image_dots_spacing; 
    for($i=0;$i<=$image_dots_y;$i++) { 
        $image_line_color = imagecolorallocatealpha($image_base,mt_rand('0','200'),mt_rand('0','200'),mt_rand('0','200'),mt_rand('20','115')); 
        imagesetthickness($image_base,2); 
    } 
    for($i=0;$i<6;$i++) { 
        $image_text_direction = mt_rand('1','2'); 
        if($image_text_direction == 1) { 
            imagettftext($image_base, 20,-mt_rand('5','15'),$image_letter,30,$image_text_color,$image_font,$image_text_array[$i]); 
        } 
        else { 
            imagettftext($image_base, 20,mt_rand('5','15'),$image_letter,30,$image_text_color,$image_font,$image_text_array[$i]);
        } 
        $image_letter = $image_letter+($image_width/8); 
    } 
    imagepng($image_base); 
    imagedestroy($image_base); 	
} 
?>