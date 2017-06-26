<?php
namespace Helpers;

/**
 * UNDER DEVELOPMENT
 * @author Diego Lopez Rivera <forgin50@gmail.com>
 * @version 0.0.2
 */
class HelperUtils {


	/**
	 * Replace language-specific characters by ASCII-equivalents.
	 * @param string $s
	 * @return string
	 */
	public static  function normalizeChars($s) {
		$replace = array(
				'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
				'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
				'Þ'=>'B',
				'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
				'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
				'Ğ'=>'G',
				'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
				'Ł'=>'L',
				'Ñ'=>'N', 'Ń'=>'N',
				'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
				'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
				'Ț'=>'T',
				'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
				'Ý'=>'Y',
				'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
				'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
				'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
				'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
				'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'D', 'ð'=>'d',
				'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
				'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
				'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
				'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
				'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
				'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
				'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
				'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
				'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
				'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
				'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
				'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
				'ק'=>'q',
				'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
				'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
				'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
				'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
				'в'=>'v', 'ו'=>'v', 'В'=>'v',
				'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
				'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
				'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
		);
		return strtr($s, $replace);
	}

	public static function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		foreach ($arr as $key=> $row) {
			$sort_col[$key] = $row[$col];
		}

		array_multisort($sort_col, $dir, $arr);
	}

	/**
	 * Returns a random string of a specified length
	 * @param int $length
	 * @return string $key
	 */
	public static function getRandomKey($length = 20)
	{
		$chars = "A1B2C3D4E5F6G7H8I9J0K1L2M3N4O5P6Q7R8S9T0U1V2W3X4Y5Z6a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6";
		$key = "";

		for ($i = 0; $i < $length; $i++) {
			$key .= $chars{mt_rand(0, strlen($chars) - 1)};
		}

		return $key;
	}

	public static function getFileExtension($path) {
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		return $ext;
	}

	public static function getFileMimeType($file) {
		$ftype = 'unknown';
		$finfo = @finfo_open(FILEINFO_MIME);
		if ($finfo !== FALSE) {
   			$fres = @finfo_file($finfo, $file);
   			if ( ($fres !== FALSE)  && is_string($fres)  && (strlen($fres)>0)) {
            	$ftype = $fres;
        	}
   			@finfo_close($finfo);
		}
		return $ftype;
	}

	public static function array_utf8_decoder($array)
	{
		array_walk_recursive($array, function(&$item, $key){
			if(is_string($item)){
				$item = utf8_decode($item);
			}
		});

			return $array;
	}
	public static function array_utf8_encoder($array)
	{
	    array_walk_recursive($array, function(&$item, $key){
	        if(is_string($item)){
	            $item = utf8_encode($item);
	        }
	    });

	        return $array;
	}


	public static function stringToUTF8 ($str) {
		$decoded = utf8_decode($str);
			return $decoded;
	}


	public static function convertArrayKeysToUtf8(array $array) {
		$convertedArray = array();
		foreach($array as $key => $value) {
			if(!mb_check_encoding($key, 'UTF-8')) $key = utf8_encode($key);
			if(is_array($value)) $value = self::convertArrayKeysToUtf8($value);

			$convertedArray[$key] = $value;
		}
		return $convertedArray;
	}

	public static function formatDateTime($fecha = null){
		if($fecha == null) {
			$fecha = date('d/m/Y H:i:s');
		}
		$date = str_replace('/', '-', $fecha);
		$date = date('Y-m-d H:i:s', strtotime($date));
		$time = strtotime($date);
		$date = new \DateTime();
		if($fecha != null) {
			$date->setTimestamp($time);
		}
		return $date;
	}

	public static function xssafe($data,$encoding='UTF-8') {
		if(is_array($data)){
			foreach ($data as &$value) {
				if (!is_array($value)) { $value = self::xssafe($value); }
				else { self::xssafe($value); }
			}
		} else {
			$data = htmlspecialchars($data,ENT_QUOTES | ENT_HTML401);
			return self::stringToUTF8($data);
		}

	}



	/**
	 * This is the list of currently registered HTTP status codes.
	 *
	 * @var array
	 */
	public static $statusCodes = [
			100 => 'Continue',
			101 => 'Switching Protocols',
			102 => 'Processing',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authorative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status', // RFC 4918
			208 => 'Already Reported', // RFC 5842
			226 => 'IM Used', // RFC 3229
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',
			308 => 'Permanent Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			418 => 'I\'m a teapot', // RFC 2324
			421 => 'Misdirected Request', // RFC7540 (HTTP/2)
			422 => 'Unprocessable Entity', // RFC 4918
			423 => 'Locked', // RFC 4918
			424 => 'Failed Dependency', // RFC 4918
			426 => 'Upgrade Required',
			428 => 'Precondition Required', // RFC 6585
			429 => 'Too Many Requests', // RFC 6585
			431 => 'Request Header Fields Too Large', // RFC 6585
			451 => 'Unavailable For Legal Reasons', // draft-tbray-http-legally-restricted-status
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version not supported',
			506 => 'Variant Also Negotiates',
			507 => 'Insufficient Storage', // RFC 4918
			508 => 'Loop Detected', // RFC 5842
			509 => 'Bandwidth Limit Exceeded', // non-standard
			510 => 'Not extended',
			511 => 'Network Authentication Required', // RFC 6585
	];
	public static function getIp()
	{
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	public static function addTrailingSlash(&$url) {
		if (substr($url, -1) !== '/') {
			$url .= '/';
		}
	}

	public static function addLeadingSlash(&$url) {
		if (strpos($url, "\/")) {
			$url = '/' . $url;
		}
	}
}