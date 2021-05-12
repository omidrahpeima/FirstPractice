<?php
$tcache = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: " . $tcache);
header("Last-Modified: " . $tcache);
//header("Pragma: no-cache");
//header("Cache-Control: no-cache, must-revalidate");
$fi = "DownloadingFile/HTMLlearning.pdf";
//$fi = "images/university.png";

$header = apache_request_headers();

if (isset($header['If-Modified-Since']) && (strtotime($header['If-Modified-Since']) == filemtime($fi)))
{
  // The following header (200) is like block for client
  // We can use (304 Not Modified) for block any process but image and pdf and all text stay
  // So after openning new window all things show again and pdf will download again

  header("Last-Modified: " . gmdate('D, d M Y H:i:s', filemtime($fi)) . " GMT", true, 304);

  // We can use 403 Forbidden as well
  //$protocol = $_SERVER['SERVER_PROTOCOL'];
  //$protocol = $protocol . " 403 Forbidden";
  //header("$protocol");

  } else{
  header("Last-Modified: " . gmdate('D, d M Y H:i:s', filemtime($fi)) . " GMT", true, 200);
  header("Content-type: application/pdf");
  header("Content-Disposition: attachment; filename='images/HTMLlearning.pdf'");
  //readfile('DownloadingFile/HTMLlearning.pdf');

  //header('content-length: ' . filesize($fi));
  //header("Content-type: image/png");
  //print file_get_contents($fi);
}
 ?>
