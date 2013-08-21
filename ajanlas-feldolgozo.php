<?php
/**
* Továbbajánló űrlapot feldolgozó szkript. Az input adatokat _POST metódussal, utf-8 kódolással kell átadni.
*/

/** Beállítások START **/
define('MM_API_USERNAME',''); // A MailMaster fiókhoz tartozó API felhasználónév
define('MM_API_PASSWORD',''); // A MailMaster fiókhoz tartozó API jelszó
define('NOMINATOR_LISTID',0); // Az ajánlókat gyűjtő lista azonosítója
define('NOMINATOR_FORMID',0); // Az ajánlókat gyűjtő lista egy feliratkozó űrlapjának azonosítója
define('NOMINEE_LISTID',0); // Az ajánlottakat gyűjtő lista azonosítója
define('NOMINEE_FORMID',0); // Az ajánlottakat gyűjtő lista egy feliratkozó űrlapjának azonosítója
/** Beállítások END **/

/**
* Először elmentésre kerül az ajánló egy az ajánlókat gyűjtő email listába
*/
if ($_POST['email'] != '') {
	$mmAPI = new mailmasterapi(NOMINATOR_LISTID,NOMINATOR_FORMID,MM_API_USERNAME,MM_API_PASSWORD);
	unset($param);
	$param = array(
	'email' => $_POST['email'],
	'mssys_firstname' => mb_convert_encoding($_POST['mssys_firstname'],'utf-8','iso-8859-2'),
	'mssys_referer_page' => $_SERVER['HTTP_REFERER'] // Elmentésre kerül melyik oldalról iratkozott fel
	);
	$data = json_encode($param);
	$headers = array(
		'Accept: application/json',
		'Content-Type: application/json'
	);
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, 'http://'.MM_API_USERNAME.':'.MM_API_PASSWORD.'@restapi.emesz.com/subscribe/'.NOMINATOR_LISTID.'/form/'.NOMINATOR_FORMID);
	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_USERAGENT, 'MailMaster API');
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	$nominatorID = curl_exec($handle);

	if ($nominatorID > 0) {
		/* Az ajánlott emberek adatai elmentésre kerülnek az ajánlottakat gyűjtő email listába. Feltételezzük, hogy az űrlapon maximum négy ajánlást lehet egyszerre leadni.
		* Az az ajánlottak keresztneve és email címét lehet megadni az űrlapon illetve a szöveget, amelyet email-ben megkapnak.
		* Az ajánlottak email címei az nominee_email_[i], keresztnevük az nominee_firstname_[i] konvenció alapján elnevezett mezőkben kell legyen megadva.
		* A továbbajánló szöveg az email_content nevű mezőben szerepeljen.
		*/
		//$mmAPI = new mailmasterapi(NOMINEE_LISTID,NOMINEE_FORMID,MM_API_USERNAME,MM_API_PASSWORD);
		//var $validNominees = 0;
		for ($i = 1;$i <= 4;$i++) {
			if ($_POST['nominee_email_'.$i] != '') {
				// Ha az i. ajánlott email címe ki van töltve, akkor elmentésre kerül az ajánlást
				$emailContent = preg_replace('/\[mssys_firstname\]/',$_POST['nominee_firstname_'.$i],$_POST['email_content']);
				$emailContent = preg_replace('/\[nominator_firstname\]/',$_POST['mssys_firstname'],$emailContent);
				
				unset($param);
				$param = array(
				'email' => $_POST['nominee_email_'.$i],
				'mssys_firstname' => mb_convert_encoding($_POST['nominee_firstname_'.$i],'utf-8','iso-8859-2'),
				'email_content' => mb_convert_encoding($emailContent,'utf-8','iso-8859-2'),
				'nominator_email' => $_POST['email'], // Ajánló email címe
				'nominator_firstname' => mb_convert_encoding($_POST['mssys_firstname'],'utf-8','iso-8859-2'), // Ajánló keresztneve
				'mssys_referer_page' => $_SERVER['HTTP_REFERER'] // Elmentésre kerül melyik oldalról iratkozott fel
				);
				$data = json_encode($param);
				$headers = array(
					'Accept: application/json',
					'Content-Type: application/json'
				);
				$handle = curl_init();
				curl_setopt($handle, CURLOPT_URL, 'http://'.MM_API_USERNAME.':'.MM_API_PASSWORD.'@restapi.emesz.com/subscribe/'.NOMINEE_LISTID.'/form/'.NOMINEE_FORMID);
				curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($handle, CURLOPT_USERAGENT, 'MailMaster API');
				curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($handle, CURLOPT_POST, true);
				curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
				$response = curl_exec($handle);

				$validNominees++;
			}

			// Visszarögzítésre kerül az ajánlókat gyűjtő listába, hogy az illető hány ajánlást adott
			if ($validNominees > 0) {
				$mmAPI = new mailmasterapi(NOMINATOR_LISTID,NOMINATOR_FORMID,MM_API_USERNAME,MM_API_PASSWORD);
				$mmAPI->update($nominatorID, array('nominee_num' => $validNominees));
			}
		}
	}
}
?>
<script>
document.location.href = 'ajanlas-koszono.html';
</script>