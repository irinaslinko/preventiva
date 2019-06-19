<?php 
require_once(dirname(__FILE__)."/../../include/init.php");
require_once(dirname(__FILE__)."/init.php");
es_include("filesys.php");
es_include("citylist.php");
es_include("page.php");
es_include("localpage.php");

$module = 'company';
$request = new LocalObject(array_merge($_GET, $_POST));
$response = array();
$session = GetSession();
$companyInfo = $session->GetProperty(COMPANY_INFO);
switch ($request->GetProperty("Action"))
{
	case "UploadFile":
		$fileSys = new FileSys();
		$newFile = $fileSys->Upload('File', COMPANY_FILE_DIR);
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
		@unlink(COMPANY_FILE_DIR.$request->GetProperty("File"));
		$response = array("Status" => "success");
		break;
}

switch($request->GetProperty("Part"))
{
	case "First":
		if(!$request->GetProperty("FullTitle"))
			$request->AddError("full-title-empty", $module);
		
		if(!$request->GetProperty("ShortTitle"))
			$request->AddError("short-title-empty", $module);
		
		if(!$request->GetProperty("City"))
			$request->AddError("city-empty", $module);
		
		if(!$request->GetProperty("Street"))
			$request->AddError("street-empty", $module);
		
		if(!$request->GetProperty("House"))
			$request->AddError("house-empty", $module);
		
		if($request->HasErrors())
		{
			$response = array(
				"Status" => "error",
				"ErrorList" => $request->GetErrorsAsArray()
			);
		}
		else
		{
			$companyInfo["PartOne"] = $request->GetProperties();
			$session->SetProperty(COMPANY_INFO, $companyInfo);
			$session->SaveToDB();
			$response = array(
				"Status" => "success",
				"NextPart" => "Second",
			);
		}
		break;
	case "Second":
		if(!$request->GetProperty("Money"))
			$request->AddError("money-empty", $module);
			
		if($request->GetProperty("paymentCapital") == "Property")
		{
			if(!$request->GetProperty("NameProperty"))
				$request->AddError("property-name-empty", $module);
		}
		
		$founderList = $request->GetProperty("FounderList");
		for($i = 0; $i < count($request->GetProperty("FounderList")); $i++)
		{
		/* 	if() */
		}
/* 		if(!$request->GetProperty('FileListFounderID'))
		{
			if(!$request->GetProperty("BirthDateFounder"))
				$request->AddError("birth-date-founder-empty", $module);
				
			if(!$request->GetProperty("BirthPlaceFounder"))
				$request->AddError("birth-place-founder-empty", $module);
				
			if(!$request->GetProperty("PassportNumberFounder"))
				$request->AddError("passport-number-founder-empty", $module);
				
			if(!$request->GetProperty("PassportGiveFounder"))
				$request->AddError("passport-give-founder-empty", $module);
				
			if(!$request->GetProperty("DepartmentCodeFounder"))
				$request->AddError("department-code-founder-empty", $module);
				
			if(!$request->GetProperty("DateGiveFounder"))
				$request->AddError("data-give-founder-empty", $module);
				
			if(!$request->GetProperty("RegistrationFounder"))
				$request->AddError("registration-founder-empty", $module);

		} */
		
		
		/* if(!$request->GetProperty("CountMoney"))
		{
			if($request->GetProperty("paymentFace") == "NaturalPerson")
				$request->AddError("natural-person-money-empty", $module);
			else
				$request->AddError("legal-entity-money-empty", $module);
		} */
		
		print_r($request);
		
		if(!$request->GetProperty('FileListHeadID'))
		{
			if(!$request->GetProperty("BirthDateHead"))
				$request->AddError("birth-date-head-empty", $module);
				
			if(!$request->GetProperty("BirthPlaceHead"))
				$request->AddError("birth-place-head-empty", $module);
				
			if(!$request->GetProperty("PassportNumberHead"))
				$request->AddError("passport-number-head-empty", $module);
				
			if(!$request->GetProperty("PassportGiveHead"))
				$request->AddError("passport-give-head-empty", $module);
				
			if(!$request->GetProperty("DepartmentCodeHead"))
				$request->AddError("department-code-head-empty", $module);
				
			if(!$request->GetProperty("DateGiveHead"))
				$request->AddError("data-give-head-empty", $module);
				
			if(!$request->GetProperty("RegistrationHead"))
				$request->AddError("registration-head-empty", $module);
		}
		
		if(!$request->GetProperty("Position"))
			$request->AddError("position-empty", $module);
		
		if(!$request->GetProperty("OfficeTerm"))
			$request->AddError("office-term-empty", $module);
		
		if(!$request->GetProperty("ContactPhoneFor"))
			$request->AddError("contact-phone-empty", $module);
			
		if(!$request->GetProperty("ContactPerson"))
			$request->AddError("contact-person-empty", $module);
			
		if(!preg_match("/^[a-z0-9\._-]+@([a-z0-9_-]+\.)+[a-z0-9_-]+/i", $request->GetProperty("Email")))
			$request->AddError("email-incorrect", $module);
		
		if($request->HasErrors())
		{
			$response = array(
				"Status" => "error",
				"ErrorList" => $request->GetErrorsAsArray()
			);
		}
		else
		{
			$companyInfo["PartTwo"] = $request->GetProperties();
			$session->SetProperty(COMPANY_INFO, $companyInfo);
			$session->SaveToDB();
			$response = array(
				"Status" => "success",
				"NextPart" => "Third",
			);
		}
		break;
	
	case "Third":
		if(!$request->GetProperty("Type"))
			$request->AddError("type-empty", $module);
			
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
				if($cityList->_items[$i]["CityName"] == $companyInfo["PartOne"]["City"])
					$formEmail = $cityList->_items[$i]["FormEmail"];
			}
			
			if($page->GetProperty("Template"))
				$tmplPrefix = $module.'-'.$page->GetProperty("Template").'/';
			else
				$tmplPrefix = $module."_";
				
			$emailTemplate = new PopupPage($module, false);
			$email = $emailTemplate->Load($tmplPrefix.'email.html');
			$email->LoadFromObject($request);
			
			$partOne = $companyInfo["PartOne"];
			$email->LoadFromArray($partOne);
			$partTwo = $companyInfo["PartTwo"];
			$email->LoadFromArray($partTwo);
			if($partTwo["FileListFounderID"])
			{
				$fileListFounder = $partTwo["FileListFounderID"];
				for($i = 0; $i < count($fileListFounder); $i++)
				{
					$filePathListFounder[] = array("FilePath" => GetUrlPrefix()."website/".WEBSITE_FOLDER."/var/".$module."/".$fileListFounder[$i]);
				}
				$email->SetLoop("FilePathListFounder", $filePathListFounder);
			}
			
			if($partTwo["FileListHeadID"])
			{
				$fileListHead = $partTwo["FileListHeadID"];
				for($i = 0; $i < count($fileListHead); $i++)
				{
					$filePathListHead[] = array("FilePath" => GetUrlPrefix()."website/".WEBSITE_FOLDER."/var/".$module."/".$fileListHead[$i]);
				}
				$email->SetLoop("FilePathListHead", $filePathListHead);
			}
			
			$config = $page->GetConfig();
			
			$resultMail = SendMailFromAdmin($formEmail, $config['Subject'], $emailTemplate->Grab($email));
			
			if($resultMail === true)
			{
				$request->AddMessage("company-success", $module);
				$response = array(
					"Status" => "success",
					"MessageList" => $request->GetMessagesAsArray(),
				);
			}
			else
			{
				$request->AddError("company-error", $module);
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