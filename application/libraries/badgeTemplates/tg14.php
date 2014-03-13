<?php
/*
 * The Gathering 2014
 * Badgetemplate made by Info:Graphics, developed by Simen A. W. Olsen
 *
 */

class TG14_Badge extends BadgeTemplate {

	static $event_id = "2";

	function template($badge){

		$layer = PHPImageWorkshop\ImageWorkshop::initFromPath(path('app')."libraries/badgeTemplates/tg14.jpg");

		// Profile name
		$text = $badge->person()->profile()->name;
		$fontPath = self::fontsDir()."Neuton-Bold.ttf";
		$fontSize = 50;
		$fontColor = "000000";
		$textRotation = 0;

		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 70, 420, 0);

		// Person
		$text = $badge->person()->firstname." ".$badge->person()->surname;
		$fontPath = self::fontsDir()."Neuton-Light.ttf";
		$fontSize = 65;
		$fontColor = "000000";
		$textRotation = 0;

		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 70, 330, 0);

		// QR
		include(path('app')."libraries/bitly.php");
		include(path('app')."libraries/phpqrcode/qrlib.php");
		$bitly = bitly_v3_shorten(url('accreditation/controll/'.$badge->person()->hash));
		QRcode::png('url:'.$bitly['url'], path('storage')."work/".$badge->id.".png", QR_ECLEVEL_L, 6);
    	$qr = PHPImageWorkshop\ImageWorkshop::initFromPath(path('storage')."work/".$badge->id.".png");
    	unlink(path('storage')."work/".$badge->id.".png");
    	$sublayerInfos = $layer->addLayerOnTop($qr, 1193, 30, 0);

		$text = date("d.m \k\l. H:i", strtotime($badge->delivery_date));
		$fontPath = self::fontsDir()."Neuton-Bold.ttf";
		$fontSize = 60;
		$fontColor = "000000";
		$textRotation = 0;
		
		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 900, 548, 0);

		$text = strtoupper($badge->id);
		$fontPath = self::fontsDir()."Neuton-Light.ttf";
		$fontSize = 44;
		$fontColor = "000000";
		$textRotation = 0;
		
		$text = PHPImageWorkshop\ImageWorkshop::initTextLayer($text, $fontPath, $fontSize, $fontColor, $textRotation);
		$sublayerInfos = $layer->addLayerOnTop($text, 900, 637, 0);

		return $layer;
	}
}