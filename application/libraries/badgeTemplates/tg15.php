<?php
/*
 * The Gathering 2015
 * Badgetemplate made by Info:Graphics, developed by Simen A. W. Olsen
 *
 */

class TG15_Badge extends BadgeTemplate {

	static $event_id = "5";

	function template($badge){

		$layer = PHPImageWorkshop\ImageWorkshop::initFromPath(path('app')."libraries/badgeTemplates/tg15.jpg");

		// Profile name
		$text = $badge->person()->profile()->name;
		$fontPath = self::fontsDir()."/Droid_Sans/DroidSans-Bold.ttf";
		$fontSize = 45;
		$fontColor = "000000";
		$textRotation = 0;

		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 70, 420, 0);

		// Person
		$text = $badge->person()->firstname." ".$badge->person()->surname;
		$fontPath = self::fontsDir()."Droid_Sans/DroidSans.ttf";
		$fontSize = 65;
		$fontColor = "000000";
		$textRotation = 0;

		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 70, 330, 0);

		// QR
		include(path('app')."libraries/bitly.php");
		include(path('app')."libraries/phpqrcode/qrlib.php");
		$bitly = bitly_v3_shorten(url('accreditation/controll/'.$badge->person()->hash));
		if(!isset($bitly['url'])) $bitly['url'] = url('accreditation/controll/'.$badge->person()->hash);
		QRcode::png('url:'.$bitly['url'], path('storage')."work/".$badge->id.".png", QR_ECLEVEL_L, 7);
    	$qr = PHPImageWorkshop\ImageWorkshop::initFromPath(path('storage')."work/".$badge->id.".png");
    	unlink(path('storage')."work/".$badge->id.".png");
    	$sublayerInfos = $layer->addLayerOnTop($qr, 1150, 320, 0);

		$text = 'GYLDIG TIL: '.date("d.m \k\l. H:i", strtotime($badge->delivery_date));
		$fontPath = self::fontsDir()."Droid_Sans/DroidSans-Bold.ttf";
		$fontSize = 40;
		$fontColor = "000000";
		$textRotation = 0;
		
		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 70, 548, 0);

		$text = strtoupper($badge->id);
		$fontPath = self::fontsDir()."Droid_Sans/DroidSans.ttf";
		$fontSize = 44;
		$fontColor = "FFFFFF";
		$textRotation = 0;
		
		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 1190, 637, 0);

		#$layer->rotate(90);

		return $layer;
	}
}