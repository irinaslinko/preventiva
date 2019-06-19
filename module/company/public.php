<?php 
require_once(dirname(__FILE__)."/../../include/init.php");
require_once(dirname(__FILE__)."/init.php");
es_include("modulehandler.php");

class CompanyHandler extends ModuleHandler
{
	function ProcessPublic()
	{
		$this->header["InsideModule"] = $this->module;
		$request = $this->ParseRequest();
		/* $request = new LocalObject(array_merge($_GET, $_POST));
		if(!$request->IsPropertySet("Part"))
			$request->SetProperty('Part', 'First'); */
		$session = GetSession();
		switch($request->GetProperty("Part"))
		{
			case "First":
				$publicPage = new PublicPage($this->module);
				$content = $publicPage->Load($this->tmplPrefix."main.html", $this->header, $this->pageID);
				$content->SetLoop('Navigation', $this->header['Navigation']);
				$content->SetVar('TitleH1', $this->header['TitleH1']);
				$content->SetVar('Content', $this->header['Content']);
				$content->SetVar("CompanySecondURL", $this->baseURL."/second/");
				$companyInfo = $session->GetProperty(COMPANY_INFO);
				
				if(count($companyInfo) > 0)
				{
					$partOne = $companyInfo["PartOne"];
					$content->LoadFromArray($partOne);
				}
				
				$publicPage->Output($content);
				break;
			case "Second":
				$publicPage = new PublicPage($this->module);
				$content = $publicPage->Load($this->tmplPrefix."second.html", $this->header, $this->pageID);
				$content->SetLoop('Navigation', $this->header['Navigation']);
				$content->SetVar('TitleH1', $this->header['TitleH1']);
				$content->SetVar("CompanyFirstURL", $this->baseURL.".html");
				$content->SetVar("CompanyThirdURL", $this->baseURL."/third/");
				$companyInfo = $session->GetProperty(COMPANY_INFO);
				
				if(count($companyInfo) > 1)
				{
					$partTwo = $companyInfo["PartTwo"];

					$fileListHead = array();
					for($i = 0; $i < count($partTwo["FileListHeadID"]); $i++)
					{
						$fileListHead[$i]['File'] = $partTwo["FileListHeadID"][$i];
						$fileListHead[$i]['FileName'] = $partTwo["FileListHeadName"][$i];
					}
					$content->SetLoop("FileListHead", $fileListHead);
					
					$founderList = array();
					for($i = 0; $i < count($partTwo["FounderList"]); $i++)
					{	
						for($j = 0; $j < count($partTwo["FounderList"][$i]["FileListFounderID"]); $j++)
						{
							$founderList[$i]["FileList"][$j]['File'] = $partTwo["FounderList"][$i]["FileListFounderID"][$j];
							$founderList[$i]["FileList"][$j]['FileName'] = $partTwo["FounderList"][$i]["FileListFounderName"][$j];
							$founderList[$i]["FileList"][$j]['Founder'] = $i;
						}
						$founderList[$i]['BirthDateFounder'] = $partTwo["FounderList"][$i]["BirthDateFounder"];
						$founderList[$i]['BirthPlaceFounder'] = $partTwo["FounderList"][$i]["BirthPlaceFounder"];
						$founderList[$i]['PassportNumberFounder'] = $partTwo["FounderList"][$i]["PassportNumberFounder"];
						$founderList[$i]['PassportGiveFounder'] = $partTwo["FounderList"][$i]["PassportGiveFounder"];
						$founderList[$i]['DepartmentCodeFounder'] = $partTwo["FounderList"][$i]["DepartmentCodeFounder"];
						$founderList[$i]['DateGiveFounder'] = $partTwo["FounderList"][$i]["DateGiveFounder"];
						$founderList[$i]['RegistrationFounder'] = $partTwo["FounderList"][$i]["RegistrationFounder"];
						$founderList[$i]['paymentFace'] = $partTwo["FounderList"][$i]["paymentFace"];
						$founderList[$i]['CountMoney'] = $partTwo["FounderList"][$i]["CountMoney"];
						$founderList[$i]['FounderNumber'] = $i;
					}
					$content->SetLoop("FounderList", $founderList);
				}
				$content->SetVar("Money", $partTwo["Money"]);
				$content->SetVar("paymentCapital", $partTwo["paymentCapital"]);
				$content->SetVar("NameProperty", $partTwo["NameProperty"]);
				$content->SetVar("Position", $partTwo["Position"]);
				$content->SetVar("OfficeTerm", $partTwo["OfficeTerm"]);
				$content->SetVar("ContactPhoneFor", $partTwo["ContactPhoneFor"]);
				$content->SetVar("ContactPerson", $partTwo["ContactPerson"]);
				$content->SetVar("Email", $partTwo["Email"]);
				$publicPage->Output($content);
				break;
			case "Third":	
				$publicPage = new PublicPage($this->module);
				$content = $publicPage->Load($this->tmplPrefix."third.html", $this->header, $this->pageID);
				$content->SetLoop('Navigation', $this->header['Navigation']);
				$content->SetVar('TitleH1', $this->header['TitleH1']);
				$content->SetVar('PageID', $this->pageID);
				$content->SetVar("CompanySecondURL", $this->baseURL."/second/");
				
				$publicPage->Output($content);
				break;
		}
		
	}
	
	function ParseRequest()
	{
		$request = new LocalObject(array_merge($_GET, $_POST));
		$chunks = count($this->pathInsideModule);
		
		if ($chunks == 1 && $this->pathInsideModule[0] == "")
		{
			Send404();
		}
		else if($chunks == 0 ||($chunks == 1 && $this->pathInsideModule[0] == INDEX_PAGE.'.html'))
		{
			$request->SetProperty('Part', 'First');
		}
		else if($chunks == 2 && $this->pathInsideModule[0] == 'second')
		{
			$request->SetProperty('Part', 'Second');
		}
		else if($chunks == 2 && $this->pathInsideModule[0] == 'third')
		{
			$request->SetProperty('Part', 'Third');
		}
		return $request;
	}
}
?>
