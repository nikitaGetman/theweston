<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();


if(!CModule::IncludeModule("iblock")) {
	ShowError( "Модуль Инфоблоков не установлен!");
	return;
}

$rsElements = CIBlockElement::GetList(
	Array("SORT"=>"ASC"), // order
	Array(  // filter
		"ACTIVE" => "Y",
		"IBLOCK_ID" => COURSES_IBLOCK_ID,
	),
	false, // group
	false, // array("nTopCount" => 1), // pagination
	Array( // select
		"ID",
		"IBLOCK_ID",
		"NAME",
		// "DETAIL_PAGE_URL",
		// "CODE",
		// "PREVIEW_TEXT",
		"PREVIEW_PICTURE",
		// "DETAIL_TEXT",
		// "DETAIL_PICTURE",
		// "SECTION_ID",
		// "PROPERTY_*",
	)
);
$arResult["ITEMS"] = array();
while ($arElement = $rsElements->GetNext()) {
	$arElement["PREVIEW_PICTURE"] = CFile::ResizeImageGet(
		$arElement["PREVIEW_PICTURE"],
		array("width" => 30, "height" => 30),
		BX_RESIZE_IMAGE_PROPORTIONAL, // BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);

	$arResult["ITEMS"][] = $arElement;
}


?>
