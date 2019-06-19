<?php 
require_once(dirname(__FILE__)."/../../include/init.php");
es_include("page.php");
es_include("citylist.php");
es_include("localpage.php");

require_once(dirname(__FILE__)."/init.php");
require_once(dirname(__FILE__)."/include/category.php");
require_once(dirname(__FILE__)."/include/category_list.php");
require_once(dirname(__FILE__)."/include/item.php");
require_once(dirname(__FILE__)."/include/item_list.php");
$module = "infoblock";
$request = new LocalObject(array_merge($_GET, $_POST));
$page = new Page();
if ($page->LoadByID($request->GetProperty("PageID")))
{
	$item = new InfoblockItem($module, $page->GetProperty("PageID"), $page->GetConfig());
	$item->LoadFromObject($request);
	
	if($request->GetProperty("Action") == "Review")
	{
		if (!$item->ValidateNotEmpty("Title"))
			$item->AddError("title-name-empty", $module);
			
		if (!$item->ValidateEmail("Email"))
			$item->AddError("email-incorrect", $module);
			
		if (!$item->ValidateNotEmpty("Description"))
			$item->AddError("description-empty", $module);
			
		if($item->GetProperty('PrivacyPolicyAgreement') != "Y")
			$item->AddError("privacy-policy-agreement-requiredy", $module);
			
		$item->SetProperty("ItemDate", date("Y-m-d H:i:s"));
		$item->SetProperty("TitleH1", $request->GetProperty("Title"));
		$item->SetProperty("MetaTitle", $request->GetProperty("Title"));
		$item->SetProperty("MetaKeywords", "");
		$item->SetProperty("MetaDescription", "");
		$item->SetProperty("StaticPath", $module.rand());
		$item->SetProperty("Content", "");
		$item->SetProperty("Active", "Y");
		
		if($item->HasErrors())
			$result = false;
		else
			$result = $item->Save();
			
		if($result == true)
		{
			$cityList = new CityList();
			$cityList->LoadCityList();
			for($i = 0; $i < $cityList->GetCountItems(); $i++)
			{
				if($cityList->_items[$i]["CityName"] == $item->GetProperty("City"))
					$formEmail = $cityList->_items[$i]["FormEmail"];
			}
			
			if($page->GetProperty("Template"))
				$tmplPrefix = $module.'-'.$page->GetProperty("Template").'/';
			else
				$tmplPrefix = $module."_";
				
			$emailTemplate = new PopupPage($module, false);
			$email = $emailTemplate->Load($tmplPrefix.'email.html');
			$email->LoadFromObject($request);
			
			$resultMail = SendMailFromAdmin($formEmail, $item->config['Subject'], $emailTemplate->Grab($email));
			if($resultMail === true)
			{
				$item->AddMessage("review-success", $module);
				echo json_encode(array(
					"Status" => "success",
					"MessageList" => $item->GetMessagesAsArray(),
				));
				die();
			}
			else
			{
				$item->AddError("review-error", $module);
				echo json_encode(array(
					"Status" => "error",
					"ErrorList" => $item->GetErrorsAsArray(),
				));
				die();
			}
		}
		else
		{
			$error = $item->GetErrorsAsArray();
			echo json_encode(array(
				"Status" => "error",
				"ErrorList" => $error,
			));
			die();
		}
	}
	elseif($request->GetProperty("Action") == "Question")
	{
		if (!$item->ValidateNotEmpty("Name"))
			$item->AddError("title-name-empty", $module);
			
		if (!$item->ValidateEmail("Email"))
			$item->AddError("email-incorrect", $module);
			
		if (strlen($item->GetProperty("Topic")) == 0)
			$item->AddError("topic-empty", $module);
			
		if (!$item->ValidateNotEmpty("Question"))
			$item->AddError("question-empty", $module);
			
		if($item->GetProperty('PrivacyPolicyAgreement') != "Y")
			$item->AddError("privacy-policy-agreement-requiredy", $module);
		
		if($item->HasErrors())
		{
			$error = $item->GetErrorsAsArray();
			echo json_encode(array(
				"Status" => "error",
				"ErrorList" => $error,
			));
			die();
		}
		else
		{
			$cityList = new CityList();
			$cityList->LoadCityList();
			for($i = 0; $i < $cityList->GetCountItems(); $i++)
			{
				if($cityList->_items[$i]["CityName"] == $item->GetProperty("City"))
					$formEmail = $cityList->_items[$i]["FormEmail"];
			}
			
			if($page->GetProperty("Template"))
				$tmplPrefix = $module.'-'.$page->GetProperty("Template").'/';
			else
				$tmplPrefix = $module."_";
				
			$emailTemplate = new PopupPage($module, false);
			$email = $emailTemplate->Load($tmplPrefix.'email.html');
			$email->LoadFromObject($request);
			
			$resultMail = SendMailFromAdmin($formEmail, $item->config['Subject'], $emailTemplate->Grab($email));
			if($resultMail === true)
			{
				$item->AddMessage("question-success", $module);
				echo json_encode(array(
					"Status" => "success",
					"MessageList" => $item->GetMessagesAsArray(),
				));
				die();
			}
			else
			{
				$item->AddError("question-error", $module);
				echo json_encode(array(
					"Status" => "error",
					"ErrorList" => $item->GetErrorsAsArray(),
				));
				die();
			}
		}
	}	
}
?>