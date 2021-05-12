<?php
//print_r(gd_info());
// At first must be enable in php.ini like this: extension=gd

$warn = $_GET['warn'];

header("Content-type: image/png");

// << CHANGES WITH STRINGS >>


function addstringtopng($path, $warn) {

$image = @imagecreatefrompng($path);

// Attempt to open
//$image = imagecreatefrompng('Images/university.png');

if ($image) {

// To set a background color or text color ($image, red, green, blue)
$textc = imagecolorallocate($image, 0, 0, 0);

// To set a background with transparent ($image, red, green, blue, transparent(between 0 and 127))
$bgc = imagecolorallocatealpha($image, 255, 0, 0, 10);

// For setting string in the middle of the width of an image
// imagesx() for getting image width
// strlen() for getting string length (how many characters)
$x1 = (imagesx($image) - (22 * strlen($warn))) / 2;
$y1 = (imagesy($image) - 24) / 2;

$width = imagesx($image);
$height = imagesy($image);

// To creat a rectangle with color ($image, x1, y1, x2, y2, $bgc)
imagefilledrectangle($image, 0, ($y1-74), $width, ($y1+50), $bgc);
// If the height of fontsize is 24 [ $y1=$y - 24 +(50) ] and [ $y2=$y + (50) ]

$font = 'D:\xampp\php\extras\fonts\ttf\VeraBd.ttf';
// It is important fontfile needs the path

// To draw a string at an image ($image, fontsize, angle, x(upper left corner), y(upper left corner), $bgc, fontfile, $string)
imagettftext($image, 25, 0, $x1, $y1, $textc, $font, $warn);

}

return $image;
}

$image = addstringtopng("Images/university.png", $warn);

// To output the image
imagepng($image);
//imagepng($image, 'Images/new.png');
// It is important after saving to not output
// It is important If be exist the same filename saves on it

// To destroy an image and to free any memory of it
imagedestroy($image);

// << /CHANGE >>

/*
// << CHANGES WITH IMAGES >>

$mainimg = imagecreatefrompng('Images/edu.png');

$width = imagesx($mainimg);
$height = imagesy($mainimg);

$imgresized = imagecreatefrompng('Images/university.png');
$w = imagesx($imgresized);
$h = imagesy($imgresized);

// To resize and copy an image (newimage, sourceimage, xnew, ynew, xsource, ysource, widthnew, heightnew, Wsource, Hsource)
imagecopyresized($mainimg, $imgresized, $width/2, $height/2, 0, 0, $width/2, $height/2, $w, $h);

imagepng($mainimg, 'Images/new-edu.png');
// WE can save on a path with a new filename
// It is important after saving to not output
// It is important If be exist the same filename saves on it

// We need destroy to free any memory
imagedestroy($imgresized);
imagedestroy($mainimg);
*/
// << /CHANGES WITH IMAGES >>


// << COPY >>

/*
$oldimg = imagecreatefrompng('Images/university.png');

$width = imagesx($oldimg);
$height = imagesy($oldimg);


// Create a new color image
$newcopy = imagecreatetruecolor($width-200, $height-200);

// To copy on a new color image that created already (newimage, sourceimage, xdes, ydes, xsour, ysour, width, height)
// xdestination and ydestination for beginnig of a new image. xsource ysource. width and height from the source image
imagecopy($newcopy, $oldimg, 0, 0, 100, 100, $width-100, $height-100);
// To copy in the middle of the source image

// WE can save on a path with a new filename
imagepng($newcopy, 'Images/copy-university.png');
// It is important after saving to not output
// It is important If be exist the same filename saves on it

// We need destroy to free any memory
imagedestroy($newcopy);
imagedestroy($oldimg);
*/
// << /COPY >>

 ?>
