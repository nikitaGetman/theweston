<?
/*
Принимает в виде
md5 sessid
int id
string action
array parametrs
// важно помнить что иногда необходимо подключить какой-нибудь модуль
*/
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$arErrors = array();
$id = $_REQUEST["id"];
$result = array();
$status = "Failed";
$arParams = $_REQUEST["parametrs"];

$arPossibleErrors = Array(
	"-666" => array("Code" => "-666", "Message" => "А шут его знает что не так!"),
	"-1" => array("Code" => "-1", "Message" => "Нет разрешения на подобную операцию у вас"),
	"-2" => array("Code" => "-2", "Message" => "Действие такое не существует"),
	"-6" => array("Code" => "-6", "Message" => "Модуль инфоблоков не подключен"),
	"-3" => array("Code" => "-3", "Message" => "Элемент не найден"),
	"-4" => array("Code" => "-4", "Message" => "Не удалось отправить эвент"),
	"-7" => array("Code" => "-7", "Message" => "Не удалось проверить подпись"),
);

function mk_error($error='-666') {
	global $arErrors, $arPossibleErrors;
	$arErrors[] = $arPossibleErrors[(String)$error];
}

function mk_custom_error($text) {
	global $arErrors;
	$arErrors[] = array("Code" => "-666", "Message" => $text);
}

function no_errors() {
	global $arErrors;
	return count($arErrors) === 0;
}

if (check_bitrix_sessid()){
	// $arParams["USER_ID"] = $USER->GetID();
	switch ($_REQUEST["action"]) {

		case 'callback':
			$signer = new \Bitrix\Main\Security\Sign\Signer;
			try {
				$cParams = $signer->unsign($_REQUEST['signedParamsString'], 'mkmatrix_callback');
				$cParams = unserialize(base64_decode($cParams));
			} catch (\Bitrix\Main\Security\Sign\BadSignatureException $e) {
				mk_error(-7);
			}

			if ($cParams["USE_BITRIX24"] == "Y") {
				include "../../bitrix24.php";
			}

			if (no_errors()) {
				$message = "";
				foreach ($cParams["FIELDS"] as $key => $arField) {
					$message .= $arField["RU_NAME"] . ": " . $arParams[$arField["CODE"]]. "<br/>";
				}

				CEvent::Send(
					$cParams["TEMPLATE_NAME"],
					SITE_ID,
					array(
						"THEME" => $cParams["THEME"],
						"MESSAGE" => $message,
					)
				);
			}
			break;

		default:
			mk_error("-2");
			break;
	}
} else {
	mk_error("-1");
}

if (no_errors() && $status=="Failed") $status="Possible ok"; // такой предположительный Ок

$return = Array(
	"id"     => $id,
	"Status" => $status,
	"Errors" => $arErrors,
	"Result" => $result,
	// "parametrs" => $arParams,
);
die(json_encode($return));
?>