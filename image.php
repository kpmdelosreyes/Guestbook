<?php

/**

Command 

src="http://localhost/image.php?w=183&cr=4:3&path=sample.jpg"
src="http://localhost/image.php?h=200&w=183&path=sample.jpg"

*/

if (!isset($_GET['path'])){
	header('HTTP/1.1 400 Bad Request');
	echo 'Error: no image was specified';
	exit();
}

define('MEMORY_TO_ALLOCATE', '100M');
define('DEFAULT_QUALITY', 80);
define('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT']);
define('IMAGE_DIR', DOCUMENT_ROOT . "/guestbook/uploads/");
define('CACHE_DIR_NAME',DOCUMENT_ROOT . '/cache/');
define('CACHE_DIR', CACHE_DIR_NAME);
define('MAX_IMAGE_WIDTH', 2000);
define('MAX_IMAGE_HEIGHT', 1000);

$sImage = IMAGE_DIR . preg_replace('/^(s?f|ht)tps?:\/\/[^\/]+/i', '', (string) $_GET['path']);

if (!$sImage){
	header('HTTP/1.1 400 Bad Request');
	echo 'Error: no image was specified';
	exit();
}

if (!file_exists($sImage)){
	header('HTTP/1.1 404 Not Found');
	echo 'Error: image does not exist: ' . $sImage;
	exit();
}

$aSize = GetImageSize($sImage);
$sMime = $aSize['mime'];

if (substr($sMime, 0, 6) != 'image/'){
	header('HTTP/1.1 400 Bad Request');
	echo 'Error: requested file is not an accepted type: ' . $sImage;
	exit();
}

$iWidth = $aSize[0];
$iHeight = $aSize[1];
$iMaxWidth = (isset($_GET['w'])) ? (int) $_GET['w'] : 0;
$iMaxHeight = (isset($_GET['h'])) ? (int) $_GET['h'] : 0;
$sCropRation = (isset($_GET['cr'])) ? (string) $_GET['cr'] : null;
$sColor = (isset($_GET['color'])) ? preg_replace('/[^0-9a-fA-F]/', '', (string) $_GET['color']) : FALSE;
$iQuality = (isset($_GET['quality'])) ? (int) $_GET['quality'] : DEFAULT_QUALITY;

if (isset($_GET['type'])){
	switch ($_GET['type']){
		case "pt" : 
			$iMaxWidth = 50;
			$iMaxHeight = 50;
			$sCropRation = "4:4";
			break;
		case "ct" :
			$iMaxWidth = 40;
			$iMaxHeight = 40;
			$sCropRation = "4:4";
			break;
		case "sc" :
			$iMaxWidth = 160;
			$iMaxHeight = 0;
			break;
		case "pp" :
			$iMaxWidth = 190;
			$iMaxHeight = 400;
			break;
		case "ba" :
			$iMaxWidth = 125;
			$iMaxHeight = 94;
			$sCropRation = "4:3";
			break;
		case "max" :
			$iMaxWidth = MAX_IMAGE_WIDTH;
			$iMaxHeight = MAX_IMAGE_HEIGHT;
			break;
	}
}

if (!$iMaxWidth && $iMaxHeight) $iMaxWidth = 99999999999999;
else if ($iMaxWidth && !$iMaxHeight) $iMaxHeight = 99999999999999;
else if ($sColor && !$iMaxWidth && !$iMaxHeight){
	$iMaxWidth = $iWidth;
	$iMaxHeight = $iHeight;
}

if ((!$iMaxWidth && !$iMaxHeight) || (!$sColor && $iMaxWidth >= $iWidth && $iMaxHeight >= $iHeight)){
	$sData = file_get_contents($sImage);
	$dLastModifiedString = gmdate('D, d M Y H:i:s', filemtime($sImage)) . ' GMT';
	$sEncryptedTag = md5($sData);

	doConditionalGet($sEncryptedTag, $dLastModifiedString);

	header("Content-type: " . $sMime);
	header("Content-Length: " . strlen($sData));
	
	echo $sData;
	exit();
}

$iOffsetX = 0;
$iOffsetY = 0;

if ($sCropRation != null){
	$aCropRatio = explode(':', (string) $sCropRation);
	if (count($aCropRatio) == 2){
		$iRatioComputed = $iWidth / $iHeight;
		$aCropRatioComputed = (float) $aCropRatio[0] / (float) $aCropRatio[1];

		if ($iRatioComputed < $aCropRatioComputed){
			$iOriginalHeight = $iHeight;
			$iHeight = $iWidth / $aCropRatioComputed;
			$iOffsetY = ($iOriginalHeight - $iHeight) / 2;
		}
		else if ($iRatioComputed > $aCropRatioComputed){
			$iOriginalWidth = $iWidth;
			$iWidth = $iHeight * $aCropRatioComputed;
			$iOffsetX = ($iOriginalWidth - $iWidth) / 2;
		}
	}
}

$xRatio = $iMaxWidth / $iWidth;
$yRatio = $iMaxHeight / $iHeight;

if ($xRatio * $iHeight < $iMaxHeight){
	$sTargetHeight = ceil($xRatio * $iHeight);
	$sTargetWidth = $iMaxWidth;
}
else {
	$sTargetWidth = ceil($yRatio * $iWidth);
	$sTargetHeight = $iMaxHeight;
}

if ($sTargetWidth <= 500 && $sTargetHeight <= 500) $iQuality = 100;

$sResizedImageSource = $sTargetWidth . 'x' . $sTargetHeight . 'x' . $iQuality;

if ($sColor) $sResizedImageSource .= 'x' . $sColor;
if (isset($_GET['cropratio'])) $sResizedImageSource .= 'x' . (string) $_GET['cropratio'];

$sResizedImageSource .= '-' . $sImage;
$sResizedImage = md5($sResizedImageSource);
$sResized = CACHE_DIR . $sResizedImage;

if (!isset($_GET['nocache']) && file_exists($sResized)){
	$sImageModified = filemtime($sImage);
	$sThumbModified = filemtime($sResized);

	if($sImageModified < $sThumbModified){
		$sData = file_get_contents($sResized);

		$dLastModifiedString = gmdate('D, d M Y H:i:s', $sThumbModified) . ' GMT';
		$sEncryptedTag = md5($sData);

		doConditionalGet($sEncryptedTag, $dLastModifiedString);

		header("Content-type: " . $sMime);
		header("Content-Length: " . strlen($sData));
		echo $sData;
		exit();
	}
}

ini_set('memory_limit', MEMORY_TO_ALLOCATE);

$oNewImage = imagecreatetruecolor($sTargetWidth, $sTargetHeight);

switch ($aSize['mime']){
	case 'image/gif':
		$sCreationFunction = 'ImageCreateFromGif';
		$sOutputFunction = 'ImagePng';
		$sMime = 'image/png';
		$bDoSharpen = FALSE;
		$iQuality = round(10 - ($iQuality / 10));
	break;

	case 'image/x-png':
	case 'image/png':
		$sCreationFunction = 'ImageCreateFromPng';
		$sOutputFunction = 'ImagePng';
		$bDoSharpen = FALSE;
		$iQuality = round(10 - ($iQuality / 10));
	break;

	default:
		$sCreationFunction = 'ImageCreateFromJpeg';
		$sOutputFunction = 'ImageJpeg';
		$bDoSharpen = TRUE;
	break;
}

$sLocation = $sCreationFunction($sImage);

if (in_array($aSize['mime'], array('image/gif', 'image/png'))){
	if (!$sColor){
		imagealphablending($oNewImage, false);
		imagesavealpha($oNewImage, true);
	}
	else {
		if ($sColor[0] == '#')
		$sColor = substr($sColor, 1);

		$bBackground = FALSE;

		if (strlen($sColor) == 6) $bBackground = imagecolorallocate($oNewImage, hexdec($sColor[0].$sColor[1]), hexdec($sColor[2].$sColor[3]), hexdec($sColor[4].$sColor[5]));
		else if (strlen($sColor) == 3) $bBackground = imagecolorallocate($oNewImage, hexdec($sColor[0].$sColor[0]), hexdec($sColor[1].$sColor[1]), hexdec($sColor[2].$sColor[2]));
		if ($bBackground) imagefill($oNewImage, 0, 0, $bBackground);
	}
}

ImageCopyResampled($oNewImage, $sLocation, 0, 0, $iOffsetX, $iOffsetY, $sTargetWidth, $sTargetHeight, $iWidth, $iHeight);

if ($bDoSharpen){
	$sharpness = findSharp($iWidth, $sTargetWidth);

	$sharpenMatrix = array(
						array(-1, -2, -1),
						array(-2, $sharpness + 12, -2),
						array(-1, -2, -1)
					);
	
	$divisor = $sharpness;
	$offset= 0;
	
	imageconvolution($oNewImage, $sharpenMatrix, $divisor, $offset);
}

if (!file_exists(CACHE_DIR)) mkdir(CACHE_DIR, 0755);

if (!is_readable(CACHE_DIR)){
	header('HTTP/1.1 500 Internal Server Error');
	echo 'Error: the cache directory is not readable';
	exit();
}
else if (!is_writable(CACHE_DIR)){
	header('HTTP/1.1 500 Internal Server Error');
	echo 'Error: the cache directory is not writable';
	exit();
}

$sOutputFunction($oNewImage, $sResized, $iQuality);

ob_start();
$sOutputFunction($oNewImage, null, $iQuality);
$sData = ob_get_contents();
ob_end_clean();

ImageDestroy($sLocation);
ImageDestroy($oNewImage);

$dLastModifiedString = gmdate('D, d M Y H:i:s', filemtime($sResized)) . ' GMT';
$sEncryptedTag = md5($sData);

doConditionalGet($sEncryptedTag, $dLastModifiedString);

header("Content-type: " . $sMime);
header("Content-Length: " . strlen($sData));

echo $sData;

function findSharp($iOriginal, $iFinal){
	$iFinal = $iFinal * (750.0 / $iOriginal);
	$a = 52;
	$b = -0.27810650887573124;
	$c = .00047337278106508946;

	$iResult = $a + $b * $iFinal + $c * $iFinal * $iFinal;

	return max(round($iResult), 0);
}

function doConditionalGet($sEncryptedTag, $lastModified){
	header("Last-Modified: " . $lastModified);
	header("ETag: \"" . $sEncryptedTag . "\"");

	$bMatch = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) :  false;

	$bModified = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) : false;

	if (!$bModified && !$bMatch) return;
	if ($bMatch && $bMatch != $sEncryptedTag && $bMatch != '"' . $sEncryptedTag . '"') return;
	if ($bModified && $bModified != $lastModified) return;
	
	header("HTTP/1.1 304 Not Modified");
	exit();
}

?>