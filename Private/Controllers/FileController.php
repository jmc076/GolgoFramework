<?php

namespace Controllers;

class FileController {

	public static function putFilesPublic($files,$ruta,$identificador = "") {
		$DIRECTORY_SEPARATOR = "/";
		$upload_dir = ROOT_PATH . '/Public' . $DIRECTORY_SEPARATOR . 'static'.$DIRECTORY_SEPARATOR. $ruta. $DIRECTORY_SEPARATOR;
		if($identificador != "") $upload_dir .= $identificador. $DIRECTORY_SEPARATOR;
		if (!empty($files)) {
			if(!file_exists($upload_dir)) mkdir($upload_dir,0775,true);
			$tempFile = $files['tmp_name'];
			$fileExtension = explode(".",$files['name']);
			$filename = utf8_decode(pathinfo($files['name'], PATHINFO_FILENAME));
			$extension = $fileExtension[count($fileExtension) - 1];

			$mainFile = $upload_dir .$filename."-". FileController::exact_time() . "." . $extension;

			if (move_uploaded_file($tempFile, $mainFile)) {
				$file = explode('static'.$DIRECTORY_SEPARATOR , $mainFile);
				$file[0] = $mainFile;
				return $file;
			} else {
				return null;
			}

		} else {
			return null;
		}
	}
	public static function putFilesPrivate($files,$ruta,$identificador = "") {
		$DIRECTORY_SEPARATOR = "/";
		$upload_dir = ROOT_PATH . "/Private" . $DIRECTORY_SEPARATOR . 'Files' . $DIRECTORY_SEPARATOR . $ruta. $DIRECTORY_SEPARATOR;
		if($identificador != "") $upload_dir .= $identificador. $DIRECTORY_SEPARATOR;
		if (!empty($files)) {
			if(!file_exists($upload_dir)) mkdir($upload_dir,0755,true);
			$tempFile = $files['tmp_name'];
			$fileExtension = explode(".",$files['name']);
			$filename = utf8_decode(pathinfo($files['name'], PATHINFO_FILENAME));
			$extension = $fileExtension[count($fileExtension) - 1];

			$mainFile = $upload_dir .$filename."-". FileController::exact_time() . "." . $extension;

			if (move_uploaded_file($tempFile, $mainFile)) {
				$file = explode('Files'.$DIRECTORY_SEPARATOR , $mainFile);
				$file[0] = $mainFile;
				return $file;
			} else {
				return null;
			}

		} else {
			return null;
		}
	}
	public static function exact_time() {
		$t = explode(' ',microtime());
		return ($t[0] + $t[1]);
	}
}