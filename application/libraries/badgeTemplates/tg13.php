<?php
/*
 * The Gathering 2013
 * Badgetemplate made by Info:Graphics, developed by Simen A. W. Olsen
 *
 */

class TG13_Badge extends BadgeTemplate {

	static $event_id = "1";

	function template($badge){

		$layer = PHPImageWorkshop\ImageWorkshop::initFromPath(path('app')."libraries/badgeTemplates/tg13.jpg");

		$text = strtoupper($badge->person()->profile()->name);
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

		return $layer;
	}
}