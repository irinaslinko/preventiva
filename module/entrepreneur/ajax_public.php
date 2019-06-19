<?php 
require_once(dirname(__FILE__)."/../../include/init.php");
require_once(dirname(__FILE__)."/init.php");
es_include("citylist.php");
es_include("page.php");
es_include("filesys.php");
es_include("localpage.php");

$module = 'entrepreneur';
$request = new LocalObject(array_merge($_GET, $_POST));
$response = array();
switch ($request->GetProperty("Action"))
{
	case "UploadFile":
 		$fileSys = new FileSys();
 		$newFile = $fileSys->Upload('File', ENTREPRENEUR_FILE_DIR);
 		if($newFile)
 		{
 			$response = array(
 				"Status" => "success",
 				"FileName" => $_FILES['File']['name'],
 				"File" => $newFile["FileName"]
 			);
 		}
		else
		{
			$response = array(
				"Status" => "error",
				"ErrorList" => $fileSys->GetErrorsAsArray()
			);
		}
		break;
		
	case "DeleteFile":
		@unlink(ENTREPRENEUR_FILE_DIR.$request->GetProperty("File"));
		$response = array("Status" => "success");
		break;
		
	case "Save":
		if(!$request->GetProperty('FileList'))
		{
			if(!$request->GetProperty("Name"))
				$request->AddError("name-empty", $module);
			 
			if(!$request->GetProperty("TIN"))
				$request->AddError("tin-empty", $module);
			 
			if(!$request->GetProperty("BirthDate"))
				$request->AddError("birth-date-empty", $module);
			 
			if(!$request->GetProperty("BirthPlace"))
				$request->AddError("birth-place-empty", $module);
			 
			if(!$request->GetProperty("PassportNumber"))
				$request->AddError("passport-number-empty", $module);
			 
			if(!$request->GetProperty("PassportGive"))
				$request->AddError("passport-give-empty", $module);
			 
			if(!$request->GetProperty("DepartmentCode"))
				$request->AddError("department-code-empty", $module);
			 
			if(!$request->GetProperty("DateGive"))
				$request->AddError("data-give-empty", $module);
			 
			if(!$request->GetProperty("Registration"))
				$request->AddError("registration-empty", $module);
		}
			 
		if(!$request->GetProperty("ActivityType"))
		 	$request->AddError("activity-type-empty", $module);
			 	
	 	if(!$request->GetProperty("Taxation"))
	 		$request->AddError("taxation-empty", $module);
		 		
 		if(!$request->GetProperty("ContactPhoneFor"))
 			$request->AddError("contact-phone-empty", $module);
	 			
 		if(!$request->GetProperty("ContactPerson"))
 			$request->AddError("contact-person-empty", $module);
 				
 		if(!preg_match("/^[a-z0-9\._-]+@([a-z0-9_-]+\.)+[a-z0-9_-]+/i", $request->GetProperty("Email")))
 			$request->AddError("email-incorrect", $module);
 					
 		if(!$request->GetProperty("How"))
 			$request->AddError("how-empty", $module);
 						
 		if(!$request->GetProperty("Familiarize"))
 			$request->AddError("familiarize-empty", $module);
 							
 		if(!$request->GetProperty("PostMethod") && !$request->GetProperty("OfficeMethod"))
 			$request->AddError("method-empty", $module);
 								
 		if($request->HasErrors())
 		{
 			$response = array(
				"Status" => "error",
 				"ErrorList" => $request->GetErrorsAsArray()
 			);
		}
 		else 
 		{
			$page = new Page();
 			$page->LoadByID($request->GetProperty("PageID"));
 			
 			$cityList = new CityList();
			$cityList ->LoadCityList();
 			for($i = 0; $i < $cityList->GetCountItems(); $i++)
 			{
 				if($cityList->_items[$i]["CityName"] == $request->GetProperty("City"))
 					$formEmail = $cityList->_items[$i]["FormEmail"];
			}
 				
 			if($page->GetProperty("Template"))
 				$tmplPrefix = $module.'-'.$page->GetProperty("Template").'/';
 			else
 				$tmplPrefix = $module."_";
 				
 			$emailTemplate = new PopupPage($module, false);
 			$email = $emailTemplate->Load($tmplPrefix.'email.html');
 			$email->LoadFromObject($request);
 				
 			if($request->GetProperty("FileList"))
 			{
 				$fileList = $request->GetProperty("FileList");
 				for($i = 0; $i < count($fileList); $i++)
 				{
 					$filePathList[] = array("FilePath" => GetUrlPrefix()."website/".WEBSITE_FOLDER."/var/".$module."/".$fileList[$i]);
 				}
 				$email->SetLoop("FilePathList", $filePathList);
 			}
 			
 				
 			$config = $page->GetConfig();
 			
			$resultMail = SendMailFromAdmin($formEmail, $config['Subject'], $emailTemplate->Grab($email));
				
 			if($resultMail === true)
 			{
 				$request->AddMessage("entrepreneur-success", $module);
				$response = array(
 					"Status" => "success",
 					"MessageList" => $request->GetMessagesAsArray(),
 				);
 			}
 			else
 			{
 				$request->AddError("entrepreneur-error", $module);
 				$response = array(
					"Status" => "error",
 					"ErrorList" => $request->GetErrorsAsArray()
 				);
 			}
 		}
	break;	
}

echo json_encode($response);
?>