<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Task");

$APPLICATION->IncludeComponent(
	"pixel:task.client", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"ELEMENT_COUNT" => "10"
	),
	$component
);

?>