<?php


class Convert_Task {

	function run(){

		$files = Fil3::where("type", "=", "map")->where("converted", "=", "0")->get();

		foreach($files as $file){
			$event = $file->event()->first();
			$file->childs()->delete();

			$tempPDF = "/tmp/map-{$file->event_id}.pdf";
			file_put_contents($tempPDF, file_get_contents($file->url));
			#system("convert -density 300 {$file->url} /tmp/map-{$file->event_id}.jpg");/tmp/map.jpg
			system("gs -dNumRenderingThreads=4 -dNOPAUSE -sDEVICE=jpeg -dFirstPage=1 -dLastPage=1 -sOutputFile=/tmp/map-{$file->event_id}.jpg -dJPEGQ=100 -r300 -q {$tempPDF} -c quit");
			if(!is_file("/tmp/map-{$file->event_id}.jpg")) continue;

			$filepath = $event->s3_slug."/map.jpg";
			S3::putObject(S3::inputFile("/tmp/map-{$file->event_id}.jpg", false), "s3.obj.no", $filepath, S3::ACL_PUBLIC_READ);

			echo "Uploaded map.jpg.";

			$child = new Fil3;
			$child->type = "jpg-map";
			$child->converted = '1';
			$child->event_id = $event->id;
			$child->filename = "map.jpg";
			$child->s3_path = $filepath;
			$child->parent_id = $file->id;
			$child->url = "http://s3.obj.no/".$filepath;
			$child->save();

			unset($child);

			Bundle::start('imageworkshop');

			$layer = PHPImageWorkshop\ImageWorkshop::initFromPath('/tmp/map.jpg');
			$layer->resizeInPixel(759, null, true, 0, 0, 'MM');			 
			$layer->save("/tmp", "map-{$file->event_id}-759.jpg", false, null, 100);
			if(!is_file("/tmp/map-{$file->event_id}-759.jpg")) continue;

			$filepath = $event->s3_slug."/map-759.jpg";
			S3::putObject(S3::inputFile("/tmp/map-{$file->event_id}-759.jpg", false), "s3.obj.no", $filepath, S3::ACL_PUBLIC_READ);

			echo "Uploaded map-759.";

			$child = new Fil3;
			$child->type = "jpg-759-map";
			$child->converted = '1';
			$child->event_id = $event->id;
			$child->filename = "map-759.jpg";
			$child->s3_path = $filepath;
			$child->parent_id = $file->id;
			$child->url = "http://s3.obj.no/".$filepath;
			$child->save();

			unlink("/tmp/map-{$file->event_id}.jpg");
			unlink("/tmp/map-{$file->event_id}-759.jpg");
			unlink("/tmp/map-{$file->event_id}.pdf");

			echo "Deleted temp-files.";

			$file->converted = '1';
			$file->save();

			echo "File converted.";
		}

		return true;
	}
}