<?php


class Convert_Task {

	function run(){

		$files = Fil3::where("type", "=", "map")->where("converted", "=", "0")->get();

		foreach($files as $file){
			$fp_pdf = fopen($file->url, 'r');

			$img = new imagick(); // [0] can be used to set page number
			$img->readImageFile($fp_pdf);
			$img->setImageFormat( "jpg" );
			$img->setImageCompression(imagick::COMPRESSION_JPEG); 
			$img->setImageCompressionQuality(90);
			$img->setResolution(1200,1200);
			$img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
			$img->writeImage('/tmp/map.jpg');

			$event = Config::get('application.event');
			$filepath = $event->s3_slug."/map.jpg";

			S3::putObject(S3::inputFile('/tmp/map.jpg', false), "s3.obj.no", $filepath, S3::ACL_PUBLIC_READ);

			unlink('/tmp/map.jpg');
		}

		return true;
	}
}