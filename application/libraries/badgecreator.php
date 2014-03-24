<?php

class BadgeCreator {
	/* Requires ImageWorkShop */
	static function make($badge, $saveToFile=true){
		ini_set('memory_limit', '-1');
		$event = Config::get('application.event');
		
		$layer = badgeTemplate::findTemplate($event->id, $badge);
		if(!$layer) die("There is something wrong with this template. Please consult Objekt-staff. ({$event->id})");

		$dirPath = path('storage')."badge/";
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
	static function getBadgePath($badge, $full=true){
		return path('storage')."badge/badge-".$badge->id.".png";
	}
	static function printBadge($badge){
		if(!file_exists(self::getBadgePath($badge))){
			self::make($badge);
		}

		$event = Config::get('application.event');
		if($event->badgeprinter == "0" || $event->badgeprinter == "")
			return false;


		$cloudprint = new GoogleCloudPrint;
		return $cloudprint->sendPrintToPrinter(
			$event->badgeprinter,
			"Badge #".$badge->id,
			self::getBadgePath($badge),
			"image/png"
			);
	}
}
?>