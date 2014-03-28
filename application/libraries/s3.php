<?php

/**
 * A LaravelPHP package for working w/ Amazon S3.
 *
 * @package    S3
 * @author     Scott Travis <scott.w.travis@gmail.com>
 * @link       http://github.com/swt83/laravel-s3
 * @license    MIT License
 */

class S3
{
	const ACL_PRIVATE = 'private';
	const ACL_PUBLIC_READ = 'public-read';
	const ACL_PUBLIC_READ_WRITE = 'public-read-write';
	const ACL_AUTHENTICATED_READ = 'authenticated-read';
	const STORAGE_CLASS_STANDARD = 'STANDARD';
	const STORAGE_CLASS_RRS = 'REDUCED_REDUNDANCY';

	public static function __callStatic($method, $args)
	{	
		// include
		require_once(__DIR__.'/s3/s3.php');
	
		// load config
		$config = Config::get('s3'); // from application, not bundle
		
		// build object
		$s3 = new Amazon\S3($config['access_key'], $config['secret_key']);
		
		// return
		return call_user_func_array(array($s3, self::camelize($method)), $args);
	}
	
	private static function camelize($word)
	{
		return lcfirst(preg_replace('/(^|_)(.)/e', "strtoupper('\\2')", strval($word)));
	}

	static function uploadS3($filepath){
		global $s3;

	    $upload = Input::file('files') ? Input::file('files') : null;
	    $info = array();
	    if ($upload && is_array($upload['tmp_name'])) {

	        foreach($upload['tmp_name'] as $index => $value) {

	            $fileTempName = $upload['tmp_name'][$index];
	            $fileName = Request::server('HTTP_X_FILE_NAME') ? Request::server('HTTP_X_FILE_NAME') : $upload['name'][$index];
	            $ext = substr(strrchr($fileName,'.'),1);
	            $hash = Str::random(32, 'alpha');
	            $fileName = $filepath."/".$hash.".".$ext;
	            $response = S3::putObject(S3::inputFile($fileTempName, false), "s3.obj.no", $fileName, S3::ACL_PUBLIC_READ);
	            if ($response){
	            	$exif = exif_read_data($fileTempName, 0, true);
	            	$xmp = XMP::read($fileTempName);
	            	$files[] = array('path' => $fileName, 'exif' => $exif, 'xmp' => $xmp, 'filename' => $fileName, 'hash' => $hash
	            		);
	            }
	        }

	    } else {
	        if ($upload || Request::server('HTTP_X_FILE_NAME')) {
	            $fileTempName = $upload['tmp_name'];
	            $fileName = Request::server('HTTP_X_FILE_NAME') ? Request::server('HTTP_X_FILE_NAME') : $upload['name'];
	            $ext = substr(strrchr($fileName,'.'),1);
	           	$hash = Str::random(32, 'alpha');
	            $fileName = $filepath."/".$hash.".".$ext;
	            $response = S3::putObject(S3::inputFile($fileTempName, false), "s3.obj.no", $fileName, S3::ACL_PUBLIC_READ);
	            if ($response){
	            	$exif = exif_read_data($fileTempName, 0, true);
	            	$xmp = XMP::read($fileTempName);
	            	$files[] = array('path' => $fileName, 'exif' => $exif, 'xmp' => $xmp, 'filename' => $fileName, 'hash' => $hash
	            		);
	            }
	        }
	    }
	    header('Vary: Accept');
	    if (Request::server('HTTP_ACCEPT') && (strpos(Request::server('HTTP_ACCEPT'), 'application/json') !== false)) {
	        header('Content-type: application/json');
	    } else {
	        header('Content-type: text/plain');
	    }
	    return $files;
	}
}