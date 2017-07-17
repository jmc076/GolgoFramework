<?php
namespace Core\Controllers;

require './Core/Vendors/PHPMailer/PHPMailerAutoload.php';

/**
 * UNDER DEVELOPMENT
 * @author Diego Lopez Rivera <forgin50@gmail.com>
 * @version 0.0.1
 */
class SMTPController {


	public static function sendTemplatedMail($dataArray) {
		$mail = new \PHPMailer;
		$mail->isSMTP();

		$mail->Host = SMTP_HOST;
		$mail->Username = SMTP_USER;
		$mail->Password = SMTP_PASS;

		$mail->SMTPAuth = true;
		$mail->SMTPOptions = array(
				'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
				)
		);
		$mail->SMTPSecure = 'tsl';
		$mail->Port = 25;
		$mail->From = SMTP_FROM;
		$mail->FromName = utf8_decode(SMTP_FROM_NAME);

		foreach ($dataArray["emails"] as $email) {
			$mail->addAddress($email);
		}
		$mail->isHTML(true);
		if(isset($dataArray["files"]) && count($dataArray["files"]) > 0) {
			foreach ($dataArray["files"] as $file) {
				$mail->AddAttachment($file["ruta"], $file["nombre"]);
			}
		}
		if(isset($dataArray["subject"]))
			$mail->Subject = utf8_decode($dataArray["subject"]);
		if(isset($dataArray["body"]))
			$mail->Body = utf8_decode($dataArray["body"]);
		if(isset($dataArray["textBody"]))
			$mail->AltBody = utf8_decode($dataArray["textBody"]);
		return $mail->send();



	}

	public static function sendMail($dataArray) {
		$mail = new \PHPMailer;
		$mail->isSMTP();

		$mail->Host = SMTP_HOST;
		$mail->Username = SMTP_USER;
		$mail->Password = SMTP_PASS;

		$mail->SMTPAuth = true;
		$mail->SMTPOptions = array(
				'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
				)
		);
		$mail->SMTPSecure = 'tsl';
		$mail->Port = 25;
		$mail->From = SMTP_FROM;
		$mail->FromName = utf8_decode(SMTP_FROM_NAME);

		foreach ($dataArray["emails"] as $email) {
			$mail->addAddress($email);
		}
		$mail->isHTML(true);
		if(isset($dataArray["files"]) && count($dataArray["files"]) > 0) {
			foreach ($dataArray["files"] as $file) {
				$mail->AddAttachment($file["ruta"], $file["nombre"]);
			}
		}
		if(isset($dataArray["subject"]))
			$mail->Subject = utf8_decode($dataArray["subject"]);

		if(isset($dataArray["body"]))
			$mail->Body = utf8_decode($dataArray["body"]);

		if(isset($dataArray["textBody"]))
			$mail->AltBody = utf8_decode($dataArray["textBody"]);

		return $mail->send();



	}
}