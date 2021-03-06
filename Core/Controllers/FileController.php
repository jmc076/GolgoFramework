<?php

namespace Core\Controllers;


use Core\Helpers\Utils;
use Core\Controllers\Http\Psr\Response;
use Core\Controllers\Http\Psr\Stream;

/**
 * UNDER DEVELOPMENT
 * @author Diego Lopez Rivera <forgin50@gmail.com>
 * @version 0.0.1
 */
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
		$upload_dir = ROOT_PATH . "/Core" . $DIRECTORY_SEPARATOR . 'Files' . $DIRECTORY_SEPARATOR . $ruta. $DIRECTORY_SEPARATOR;
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


	public function deletePublicFile(string $ruta) {

		Utils::addLeadingSlash($ruta);

		if(file_exists(ROOT_PATH . '/Public' . $ruta)) {
			return unlink(ROOT_PATH . '/Public' . $ruta);
		}
		return false;
	}

	public function deletePrivateFile($ruta) {

		Utils::addLeadingSlash($ruta);

		if(file_exists( ROOT_PATH . '/Core/Files' . $ruta)) {
			return unlink( ROOT_PATH . '/Core/Files' . $ruta);
		}
		return false;
	}

//NEW FILES CONTROLLER
	public function sendFile($path) {
		$file = $path;
		if (is_file($file)) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);

			$fileResponse = new Response();
			$this->sendFileHeaders($fileResponse,$file, finfo_file($finfo, $file), basename($file));
			$chunkSize = 1024 * 1024;
			$fileStream = new Stream(fopen($file, 'rb'));
			while (!$fileStream->eof()) {
				$buffer = $fileStream->read($chunkSize);
				$fileResponse->getBody()->writeSized($buffer, $chunkSize);
				$fileResponse->getBody()->flush();
			}
			$fileStream->close();
		} else {
			ExceptionController::customError("File not found", 400);
		}



	}

	function sendFileHeaders(Response $fileResponse,$file, $type, $name=NULL) {
		if (empty($name))
		{
			$name = basename($file);
		}
		$fileResponse->putHeaderValue("Pragma", "public");
		$fileResponse->putHeaderValue("Expires", "0");
		$fileResponse->putHeaderValue("Cache-Control", "must-revalidate, post-check=0, pre-check=0");
		$fileResponse->putHeaderValue("Cache-Control", "");
		$fileResponse->putHeaderValue("Content-Transfer-Encoding", "binary");
		$fileResponse->putHeaderValue("Content-Disposition", 'attachment; filename="'.$name.'";');
		$fileResponse->putHeaderValue("Content-Type", $type);
		$fileResponse->putHeaderValue("Content-Length", filesize($file));
		$fileResponse->sendHeaders();
	}


}