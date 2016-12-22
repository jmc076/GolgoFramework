<?php
namespace Controllers;
/**
 * Clase para utilizar la api de MailRealy
 * @author Daniel - Kumografic
 * @version 1.0
 *  Mejoras:
 */

/* Mejoras
 * Iniciar una sesión curl al crear la clase
 * Función para enviar los datos y no tener que repetir la parte final en todos los m�todos
 * Control del proceso: La clase funciona pero el proceso no se va comprobando de una manera "elegante" 
 */

class MailRelayController {
	
	// Para establecer la sesi�n curl 
	private $hostname;// = 'kumografic.ip-zone.com';
	// Para obtener la apiKey
	private $username; // = 'daniel';
	private $password; // = '1234';
	private $apiKey ;
	// private $curl; Cada m�todo crea una funci�n curl. Puede que sea mejor crear una global. Se podr�a realizar desde el constructor. 
	// Para mandar mails
	private $mailboxFromId = 1;
	private $mailboxReplyId = 1;
	private $mailboxReportId = 1;
	private $packageId = 6;
	
	
	//////////////////////////// constructuro simple  ////////////////////////////////////////
	/**
	 * Constructor de clase.
	 * Solo fija el hostname, username y password
	 * Los valores de los buzones de envio, respuesta, informe y paquete se quedan por defecto en 1,1,1,6 
	 * @param String $hostname
	 * @param String $username
	 * @param String $password
	 */
	function initSimple($hostname,$username,$password){
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
	}
	
	/**
	 * Funci�n para establecer loc correos y el paquete
	 * Los id de los buzones se miran en la p�gina web. configuraci�n/administrar remitentes
	 * Los id de los paquetes se saben usando el metodo getPackage;
	 * @param integer $mailboxFromId
	 * @param integer $mailboxReplyId
	 * @param integer $mailboxReportId
	 * @param integer $packageId
	 */
	public function initMailbox($mailboxFromId,$mailboxReplyId,$mailboxReportId,$packageId){
		$this->mailboxFromId = $mailboxFromId;
		$this->mailboxReplyId = $mailboxReplyId;
		$this->mailboxReportId = $mailboxReportId;
		$this->packageId = $packageId;
	}
	
	///////////////////////////////////// Constructor con todos los parametros  ///////////////////////////////////////////////////////////////
	/**
	 * Constructor de clase fija todos los parametros de envio. 
	 * Los id de los buzones se miran en la p�gina web. configuraci�n/administrar remitentes
	 * Los id de los paquetes se saben usando el metodo getPackage;
	 * @param String $hostname Nombre del hostname. ejemplo: 'kumografic.ip-zone.com'
	 * @param String $username Nombre de usuario
	 * @param String $password Password
	 * @param Integer $mailboxFromId
	 * @param Integer $mailboxReplyId
	 * @param integer $mailboxReportId
	 * @param integer $padageId
	 */
	function init($hostname,$username,$password,$mailboxFromId,$mailboxReplyId,$mailboxReportId,$packageId){
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->mailboxFromId = $mailboxFromId;
		$this->mailboxReplyId = $mailboxReplyId;
		$this->mailboxReportId = $mailboxReportId;
		$this->$packageId = $packageId;
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Inicia una sesi�n curl para usar con la api
	 * Fija la direcci�n donde est� disponible la api 
	 * Fija la opci�n de envio como Post
	 * Se usa en todas las funciones para iniciar sesi�n curl
	 * @return devuelve la funci�n curl
	 */
	
	private function iniciarCurl(){
		// crear sesiion curl
		// $hostname = 'kumografic.ip-zone.com';
		$curl = curl_init('https://' . $this->hostname .'/ccm/admin/api/version/2/&type=json');
		//fijar opcioines para mandar por post
		curl_setopt($curl, CURLOPT_POST, true); // Mandar un post normal
		// para que devuelva un string
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// introduzco los campos que voy a mandar para identificar
		return $curl;		
	}
	
	///////////////////////////////////  Obtener apiKey  //////////////////////////////////////////////
	
	/**
	 * Funci�n para obtener la apiKey y se establece como variable de clase. 
	 * Se usa en todas las funciones como un paso para obtener la clave para usar la API 
	 * Mail relay usa un String como clave para conectar con la api.
	 * Esta clave se puede ver y modificar en la pagina de MailRelay en configuraci�n/Acceso a API
	 * Esta funci�n asegura que siempre se use la clave que est� actualmente en uso. 
	 * Para autentificar se manda un array con el nombre de usuario y la contrase�a.
	 * @throws Exception en caso de fallo de validaci�n.
	 */
	private function establecerApiKey() {
		// crear sesiion curl
		$curl= $this->iniciarCurl();
		$postData = array(
				'function' => 'doAuthentication',
				'username' => $this->username,
				'password' => $this->password,
		);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData)); // establecer los campos a mandar en formato httppost		
		// ejecuto el curl
		$result = curl_exec($curl);
		// decodifico el json
		$jsonResult = json_decode($result);
		$apikey = $jsonResult->data;
		// Por si hay un error en la conexi�n con curl
		if(curl_errno($curl))
		{
			echo 'Curl error: ' . curl_error($curl);
		}
		// Es posible que se produzca un error de seguridad SLL.
		// Para solucionarlo se baja el fichero cerca.pem de http://curl.haxx.se/docs/caextract.html
		// Se modifica el fichero php.ini para establecer la ubicaci�n del certificado.
		// Al final del fichero se escribe la siguiente linea: curl.cainfo=C:\xampp\php\cert\cacert.pem
		if (!$jsonResult->status) {
			throw new Exception('Fallo en la validaci�n. Verifique su hostname, username o password.');
		} else {
			$this->apiKey = $jsonResult->data;
		}
		// cerramos la sesi�n curl
		curl_close($curl);
	}// establecerApiKey
	
	////////////////////////////////////////        Mandar mail de manera directa  /////////////////////////////////////
	
	/**
	 * Funci�n b�sica para mandar un mail a varias direcci�nes.
	 * Crea un grupo de suscriptores llamado API Fecha Hora
	 * Crea los nuevos suscriptores en el caso de que no existan
	 * Crea un nuevo boletin dentro de la carpeta API
	 * Los valores mailboxId y packages estan puestos por defecto. Es muy posible que si cambias de cuenta tengas que revisar estos valores. 
	 * @param String $subject Asunto que aparecer� en el correo
	 * @param Array $arrayMail array con los nombres y email ha de tener la siguiente estructura array(array('name'=> 'nombre destinatario01', 'email'= 'correo del destinatario01'), array('name'=> 'nombre destinatario02', 'email'= 'correo del destinatario02'))
	 * 				El par�metro 'name' es opcional. El 'email' es obligatorio
	 * @param String $html Archivo html. Hay que tener cuidado de que sea un String v�lido. Puede ser que tenga algun signo de comillas que de problemas. 
	 * @throws Exception 
	 * @return boolean Devuelve true si los emails se establecen correctamente en la cola de envio. 
	 */
	
	public function mandarMail($subject,$arrayMail,$html){
		// Obtener la apiKey
		//$apiKey = KumoMailRelay::establecerApiKey();
		$this->establecerApiKey();
		// abrir sesi�n curl
		$curl = $this->iniciarCurl();			
		// datos para mandar un mail usando la funcion sendMail
		// packageID se obtiene usando la funci�n getpakage de esta misma clase
		// mailbox Id. Se mira en la web, configuraci�n/administrar remitentes
		
		$postData = array(
				'function' => 'sendMail',
				'apiKey' => $this->apiKey,
				'subject' => $subject,
				'html' => $html,
				'mailboxFromId' => $this->mailboxFromId,
				'mailboxReplyId' => $this->mailboxReplyId,
				'mailboxReportId' => $this->mailboxReportId,
				'packageId' => $this->packageId,
				'emails' => $arrayMail
		);
		

		$post = http_build_query($postData);		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);		
		$json = curl_exec($curl);
		$result = json_decode($json);		
		if(curl_errno($curl))		
		{
			echo 'Curl error: ' . curl_error($curl);
		}
		curl_close($curl);		
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Something went wrong. manda mail sencillo');
		}	
		return $result->data;	
	}// mandarMail
	
	//////////////////////////////////////////// crear grupo de suscriptores  /////////////////////////////////////////////////////////
	
	/**
	 * funci�n para crear un grupo de suscriptores.
	 * Por defecto fijo visible false.
	 * @param String $grupo Nombre del grupo.
	 * @param String $descripcion descripci�n del grupo. 
	 * @param integer $posicion posici�n del grupo
	 * @throws Exception
	 * @return Un Integer con el Id del grupo creado.
	 */
	public function crearGrupo($grupo, $descripcion, $posicion){
		// establezco la apikey y creo una sesi�n curl
		$this->establecerApiKey();
		$curl = $this->iniciarCurl();
		
		// datos a enviar.
		$postData = array(
				'function' => 'addGroup',
				'apiKey' => $this->apiKey,
				'name' => $grupo,
				'description' => $descripcion,
				'position' => $posicion,
				'enable' => true,
				'visible' => false,
		);
		// establecer datos
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		// ejecutar
		$json = curl_exec($curl);
		// decodificar json
		$result = json_decode($json);
		// cierro la sesi�n
		curl_close($curl);
		// compruebo que todo fue bien.
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Algo fue mal al crear el grupo.');
		}
		// devuelve el id del grupo creado. 
		$idGrupo=$result->data;
		return $idGrupo;
		
	} // crearGrupos
	
	//////////////////////////////////////////////  asignar suscriptores a grupo  /////////////////////////////////////////////////////
	
	/**
	 * Funci�n para asignar suscriptores a los grupos. 
	 * Lo que se pasa es un array con los email. Si los emails no estan como suscriptores los crea. Pero sin nombre.
	 * @param Array Integer $grupos Array con los grupos en los se incluiran los coreos. han de ser integer
	 * @param Array $suscriptores Array con los emails a incluir en los grupos. Cada mail es un String.
	 * @return Devuelve un objeto stdClass con un array con el n�mero de suscriptores inscritos, actualizados y fallidos. 
	 * @throws Exception
	 */
	public function asignarGrupo($grupos, $suscriptores){
		// Establezco la ApiKey
		$this->establecerApiKey();
		// inicio la sesi�n curl
		$curl = $this->iniciarCurl();
		// Array
		$postData = array(
				
				'function' => 'assignSubscribersToGroups',
				'apiKey' => $this->apiKey,
				'groups' => $grupos,
				'subscribers' => $suscriptores,				
		);
		// establecer los datos
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		// ejecutar curl
		$json = curl_exec($curl);
		// decodificar json
		$result = json_decode($json);
		// cierro la sesi�n
		curl_close($curl);
		// comprobar status
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Algo fu� mal al asignar algrupo.');
		}

		return $result->data;
		
	}//asignarGrupo
	
	//////////////////////////////////  Crear boletin  ////////////////////////////////////////////////////////
	
	/**
	 * Funci�n para crear la bolet�n
	 * @param String $subject Asunto que aparecer� en el mail
	 * @param Array Integer $grupos Array con los id de los grupos a incluir en la bolet�n
	 * @param String $html correo que se enviar�
	 * @throws Exception
	 * @return Integer devuelve el Id de la bolet�n generada
	 */
	public function crearBoletin($subject, $grupos, $html){
		$this->establecerApiKey();
		$curl = $this->iniciarCurl();
		// Array de datos
		$postData = array(
				'function' => 'addCampaign',
				'apiKey' => $this->apiKey,
				'subject' => $subject,
				'mailboxFromId' => $this->mailboxFromId,
				'mailboxReplyId' => $this->mailboxReplyId,
				'mailboxReportId' => $this->mailboxReportId,
				'emailReport' => true,
				'groups' => $grupos,
				'text' => null,
				'html' => $html,
				'packageId' => $this->packageId,
				//'campaignFolderId' => 1,// Parametro opcional. Si no lo pones se crea en la carpeta API
			// En principio la camapa�a se guarda en el directorio API con add addDirectorioBoletin y cogerdirectorioBoletin se puede crear un directorio o ver el id de uno ya existente para configurar este par�metro. 
		);
		
		// establecer los datos
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		// ejecutar curl
		$json = curl_exec($curl);
		// decodificar json
		$result = json_decode($json);
		// cierro la sesi�n
		curl_close($curl);
		// comprobar status
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Algo fu� mal al crear el bolet�n.');
		}	
		return $result->data;
	} // crear boletin
	
	
	////////////////////////////////////////////  Enviar boletin  ///////////////////////////////////////////////////////////////
	
	/**
	 * Funci�n para enviar el bolet�n
	 * @param Integer $idBoletin
	 * @throws Exception
	 * @return devuelve el Id de la lista de envio creada.
	 */
	public function enviarBoletin($idBoletin){
		$this->establecerApiKey();
		$curl = $this->iniciarCurl();
		$postData = array(
				'function' => 'sendCampaign',
				'apiKey' => $this->apiKey,
				'id' => $idBoletin,
		);
		// establecer los datos
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		// ejecutar curl
		$json = curl_exec($curl);
		// decodificar json
		$result = json_decode($json);
		// cierro la sesi�n
		curl_close($curl);
		// comprobar status
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Algo fu� mal al enviar el bolet�n.');
		}
		return $result->data;
	}// enviar boletin
	
	////////////////////////////////////// Borrar grupo //////////////////////////////////////////
	
	/**
	 * Funci�n para borrar un grupo 
	 * @param unknown $id id del grupo a borrar.
	 * @throws Exception
	 */
	public function borrarGrupo($id){
		$this->establecerApiKey();
		$curl = $this->iniciarCurl();
		$postData = array(
				'function' => 'deleteGroup',
				'apiKey' => $this->apiKey,
				'id' => $id,
		);
		// establecer los datos
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		// ejecutar curl
		$json = curl_exec($curl);
		// decodificar json
		$result = json_decode($json);
		// cierro la sesi�n
		curl_close($curl);
		// comprobar status
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Algo fu� mal al eliminar el grupo.');
		}
		return $result->data;
	} // borrarGrupo
	
	///////////// Proceso completo  ///////////////////////////////////////////////////////////////////////////////
	/**
	 * Funci�n que realiza el proceso completo. Mantiene creados el grupo y el boletin
	 * @param String $grupo nombre del grupo
	 * @param String $descripcion descripci�n del grupo
	 * @param integer $posicion posici�n del grupo.
	 * @param Array String $suscriptores Array con las direcciones de correo electr�nico
	 * @param String $subject Asunto que aparecer� en el correo
	 * @param String $html html del correo. 
	 * @return boolean true si todo fue bien false si se lanza alguna Exception
	 */
	public function procesarEnvioCompleto($grupo,$descripcion,$posicion,$suscriptores,$subject,$html,$borrarGrupo){
		$idGrupo;
		$idBoletin;
		try {
			// crear grupo
			$idGrupo = $this->crearGrupo($grupo, $descripcion, $posicion);
			// asignar los correos al grupo creado
			$this->asignarGrupo(array($idGrupo), $suscriptores);
			// crear boletin y signarle el grupo creado
			$idBoletin = $this->crearBoletin($subject,$idGrupo, $html);
			// enviar boletin
			$this->enviarBoletin($idBoletin);		
			// devuelvo true para saber que todo ha ido bien
			if ($borrarGrupo){$this->borrarGrupo($idGrupo);}
		} catch (Exception $e) {
			// echo 'Se producjo un Error en el envio: ',  $e->getMessage(), "\n";
			return false;
		}
		return true;
	} // proceso completo 
	
	
	/////////////////////// a�adir 1 suscriptor a un grupo ////////////////////////////////////////////////////////
	
	/**
	 * A�ade un supcriptor a la lista de suscriptores y a un grupo. A�ade un solo suscriptor a uno o varios grupos
	 * @param String $eMail
	 * @param String $nombr
	 * @param Array Integer $grupos
	 * @throws Exception
	 * @return Integer con el id del suscriptor.
	 */
	public function anadirSuscriptor($eMail,$nombre,$grupos){
		// Establezco la ApiKey
		$this->establecerApiKey();
		// inicio la sesi�n curl
		$curl = $this->iniciarCurl();
		// Array de datos
		$postData = array(
				'function' => 'addSubscriber',
				'apiKey' => $this->apiKey,
				'email' => $eMail,
				'name' => $nombre,
				'groups' => $grupos
		);
		// establecer los datos
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		// ejecutar curl
		$json = curl_exec($curl);
		// decodificar json
		$result = json_decode($json);
		// cierro la sesi�n
		curl_close($curl);
		// comprobar status
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Algo fu� mal al a�adir suscriptor');
		}			
		return $result->data;
	}//anadirSuscriptor
	
	
	
	////////////////////////////////////////////////////////////////   utilidades ///////////////////////////////////////////////////
	/**
	 * Funci�n para obtener el id de los packages. 
	 * @throws Exception
	 */
	function getPackages(){
		// muestra la lista de paquetes
		// establecer apikey
		$this->establecerApiKey();
		// Abro la conexti�n con curl
		$curl = $this->iniciarCurl();
		// Array de datos
		$postData = array(
				'function' => 'getPackages',
				'apiKey' => $apiKey,
		);
		// acciiones curl	
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		$json = curl_exec($curl);
		$result = json_decode($json);
		curl_close($curl);
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Something went wrong. Ver paquetes');
		}
		// ver resultado
		echo '<pre>';
		var_dump($result->data);
		echo '</pre>';
	}// get pakages
	
	/**
	 * Funci�n para objeter los directorios de los boletines.
	 * Muestra por pantalla el array recibido. 
	 * Necesarios para establecer en que carpeta se guardan los boletines 
	 *@throws Exception
	 */
	function cogerDirectorioBoletin(){
		// muetra la lista de directoris de campoa�a
		$this->establecerApiKey();
		$curl = $this->iniciarCurl();
		// Array de datos
		$postData = array(
				'function' => 'getCampaignFolders',
				'apiKey' => $this->apiKey
				//'offset' => 0,
				//'count' => 2,
				
		);
		// acciiones curl
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		$json = curl_exec($curl);
		$result = json_decode($json);
		curl_close($curl);
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Something went wrong. Ver capa�as ');
		}
		// ver resultado
		echo '<pre>';
		var_dump($result->data);
		echo '</pre>';
		
	}// getCapaign
	
	
	/**
	 * Funci�n para crear una carpeta dentro del listado de campa�as
	 * Se crea a partir de la carpeta raiz. Hay una opci�n para indicar la carpeta padre. Pero no se ha implementado. (Ver documentaci�n oficial)
	 * @param unknown $name
	 * @throws Exception
	 */
	
	function addDirectorioBoletin($name){
		// muetra la lista de directoris de campoa�a
		$this->establecerApiKey();
		$curl = $this->iniciarCurl();
		// Array de datos
		$postData = array(
				'function' => 'addCampaignFolder',
				'apiKey' => $this->apiKey,
				'name' => $name
		);
		// acciiones curl
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
		$json = curl_exec($curl);
		$result = json_decode($json);
		curl_close($curl);
		if ($result->status == 0) {
			throw new Exception('Bad status returned. Something went wrong. A�adir directorio');
		}
		// ver resultado
		echo '<pre>';
		var_dump($result->data);
		echo '</pre>';
		return $result->data;	
	}
	
} // class



