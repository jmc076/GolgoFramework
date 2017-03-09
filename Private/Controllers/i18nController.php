<?php
namespace Controllers;

use Controllers\GFSessions\GFSessionController;

class i18nController {
	public static function localization() {
		static $localeData = NULL;
		if (is_null($localeData)) {
			$localization = GFSessionController::getInstance()->getSessionModel()->getUserLang();
			print_r($localization); die(); //TODO: Diego pre
			$langFile = dirname( __FILE__ ).'/../Localization/' . $localization . '.json';
			$langBase = dirname( __FILE__ ).'/../Localization/'.DEFAULT_LOCALIZATION.'.json';
			if (!file_exists($langFile) || $localization == DEFAULT_LOCALIZATION) {
				$jsonLang = file_get_contents($langBase);
				$langData = json_decode($jsonLang, true);
				$localeData = $langData;
			} else {
				$jsonLang = file_get_contents($langFile);
				$langData = json_decode($jsonLang, true);

				$jsonLangBase = file_get_contents($langBase);
				$localeDataBase = json_decode($jsonLangBase, true);


				$merged = self::array_merge_recursive_distinct($localeDataBase,$langData);
				$localeData = $merged;

			}

		}
		return $localeData;
	}

	public static function array_merge_recursive_distinct(array $array1, array $array2) {
		$merged = array_replace_recursive($array1,$array2);
		return $merged;
	}
}