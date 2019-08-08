<?php
//Set the Content Type
header('Content-type: image/jpeg');

// Create Image From Existing File
$jpg_image = imagecreatefromjpeg('./uploads/banana.jpg');

// Allocate A Color For The Text
$font_color = imagecolorallocate($jpg_image, 255, 255, 255);
$stroke_color = imagecolorallocate($jpg_image, 0, 0, 0);


// Set Path to Font File
$font_path = './impact.ttf';

// Set Text to Be Printed On Image
$text = "THIS IS MY MEME TEXT!!!";

// Print Text On Image
imagettfstroketext($jpg_image, 25, 0, 30, 30, $font_color, $stroke_color, $font_path, $text, 1);

// Send Image to Browser
//imagejpeg($jpg_image);

// Send Image to Browser
imagejpeg($jpg_image,'./uploads/banana2.jpg');

// Clear Memory
imagedestroy($jpg_image);

// http://www.johnciacia.com/2010/01/04/using-php-and-gd-to-add-border-to-text/
function imagettfstroketext(&$jpg_image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px)
{
    for ($c1 = ($x - abs($px)); $c1 <= ($x + abs($px)); $c1++) {
        for ($c2 = ($y - abs($px)); $c2 <= ($y + abs($px)); $c2++) {
            $bg = imagettftext($jpg_image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
        }
    }

    return imagettftext($jpg_image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
}
