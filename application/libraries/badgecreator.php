<?php
class BadgeCreator {
	/* Requires ImageWorkShop */
	static function save($badge, $saveToFile=true){


		ini_set('memory_limit', '-1');
		$event = Config::get('application.event');
		$layer = PHPImageWorkshop\ImageWorkshop::initFromPath(path('app')."views/badge-{$event->id}.jpg");

		$text = strtoupper($badge->person()->sponsor()->name);
		$fontPath = path('app')."fonts/PlutoSansCondLight-Italic.otf";
		$fontSize = 48;
		$fontColor = "000000";
		$textRotation = 0;

		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 169, 93, 0);

		$text = strtoupper($badge->person()->firstname." ".$badge->person()->surname);
		$fontPath = path('app')."fonts/PlutoSansCondBold.otf";
		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 169, 33, 0);

		$text = strtoupper(date("d.m \k\l. H:i", strtotime($badge->delivery_date)));
		die($text);
		$fontPath = path('app')."fonts/PlutoSansCondBold.otf";
		$fontSize = 44;
		$fontColor = "FFFFFF";
		$textRotation = 0;
		
		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 789, 527, 0);

		$text = strtoupper($badge->id);
		$fontPath = path('app')."fonts/PlutoSansCondRegular.otf";
		$fontSize = 44;
		$fontColor = "000000";
		$textRotation = 0;
		
		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 1080, 643, 0);

		$dirPath = path('storage')."bagde/";
		$filename = "badge-{$badge->id}.png";
		$createFolders = true;
		$backgroundColor = null; // transparent, only for PNG (otherwise it will be white if set null)
		$imageQuality = 95; // useless for GIF, usefull for PNG and JPEG (0 to 100%)
		if($saveToFile)
			$layer->save($dirPath, $filename, $createFolders, $backgroundColor, $imageQuality);
		else {
			$image = $layer->getResult();
			header('Content-type: image/png');
			header('Content-Disposition: filename="'.$badge->id.'.png"');
			imagepng($image, null, 8); // We choose to show a PNG (quality of 8 on a scale of 0 to 9)
			exit;
		}
	}
	static function getBadgeUrl($badge, $full=true){
		return path('storage')."badge/badge-".$badge->id.".png";
	}
	static function printBadge($badge){
		if(!file_exists(self::getBadgeUrl($badge))){
			self::save($badge);
		}
		system("lp ".self::getBadgeUrl($badge));
	}
}
?>