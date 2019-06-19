<?php 
require_once(dirname(__FILE__)."/../../include/init.php");
es_include("page.php");

$module = 'payment';
$request = new LocalObject(array_merge($_GET, $_POST));

if(!$request->GetProperty("Branch"))
	$request->AddError("branch-empty", $module);

if($request->GetProperty("radioPayment") == "LegalEntity")
{
	if(!$request->GetProperty("NameLegalEntity"))
		$request->AddError("legal-entity-name-empty", $module);
}

if(!$request->GetProperty("PayerName"))
	$request->AddError("payer-name-empty", $module);

if(!$request->GetProperty("Phone"))
	$request->AddError("phone-empty", $module);

if(!$request->GetProperty("Sum"))
	$request->AddError("sum-empty", $module);
	
if($request->HasErrors())
{
	$error = $request->GetErrorsAsArray();
	echo json_encode(array(
		"Status" => "error",
		"ErrorList" => $error,
	));
	die();
}

?>