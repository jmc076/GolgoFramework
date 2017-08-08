<?php
namespace Modules\GFFileManager\ViewsLogic;


use Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard\PAGPrivateAdministracionBase;

class PAGPrivateAdministracionFileManager extends PAGPrivateAdministracionBase {


	protected function preLoad() {
		$this->setActive("/GolgoFramework/dashboard/filemanager");
		parent::preLoad();
	}

	protected function assignTplVars() {
		parent::assignTplVars();
		$this->smarty->assign("headerTitle", "Administración Inicio");

		$dir = "Files";
		// Run the recursive function
		$filesArray = $this->fileScan(FILES_FOLDER);
		$fileResult = json_encode(array(
				"name" => "Files",
				"type" => "folder",
				"path" => $dir,
				"items" => $filesArray
		));
		$this->smarty->assign("fileResult", $fileResult);
	}


	protected function setTplFile() {
		$this->tpl = "Modules/GFFileManager/Views/tpls" . '/private/dashboard/file-manager.tpl';

	}

	// This function scans the files folder recursively, and builds a large array
	public function fileScan($dir) {
			$files = array();

			if(file_exists($dir)){

				foreach(scandir($dir) as $f) {

					if(!$f || $f[0] == '.') {
						continue; // Ignore hidden files
					}

					if(is_dir($dir . '/' . $f)) {

						// The path is a folder

						$files[] = array(
								"name" => $f,
								"type" => "folder",
								"path" => "Files".explode("Files",$dir)[1] . '/' . $f,
								"items" => $this->fileScan($dir . '/' . $f) // Recursively get the contents of the folder
						);
					}

					else {
						// It is a file
						$files[] = array(
								"name" => $f,
								"type" => "file",
								"path" => "/".DOMAIN_PATH. "/Files".explode("Files",$dir)[1] . '/' . $f,
								"size" => filesize($dir . '/' . $f) // Gets the size of this file
						);
					}
				}

			} else {
				print_r("no existe"); die(); //TODO: Diego pre
			}

			return $files;
	}


}
