<?php 
define("IS_ADMIN", true);
require_once(dirname(__FILE__)."/../include/init.php");
es_include("localpage.php");
es_include("urlfilter.php");
es_include("citylist.php");
es_include("city.php");
es_include("branchlist.php");
es_include("branch.php");

$request = new LocalObject(array_merge($_GET, $_POST));

$adminPage = new AdminPage();

if($request->IsPropertySet("BranchID"))
{
	if($request->GetProperty('BranchID') == 0)
		$title = GetTranslation("add-branch-title");
	else
		$title = GetTranslation("edit-branch-title");
	
	$navigation = array(
		array("Title" => GetTranslation("title-city-list"), "Link" => "city.php"),
		array("Title" => $title, "Link" => "city.php?CityID=".$request->GetProperty("CityID")."BranchID=".$request->GetProperty("BranchID"))
	);
	
	$header = array(
		"Title" => $title,
		"Navigation" => $navigation
	);
	
	$phoneList = array();
	$hourList = array();
	
	$content = $adminPage->Load("branch_edit.html", $header);
	
	$branch = new Branch();
	if ($request->GetProperty('Save'))
	{
		$branch->LoadFromObject($request);
		if($branch->SaveBranch())
		{
			header("location: city.php?CityID=".$branch->GetProperty("CityID"));
			exit();
		}
		else 
		{
			$content->LoadErrorsFromObject($branch);
			
			$day = $request->GetProperty('Day');
			$hour = $request->GetProperty('Hour');
			for($i = 0; $i < count($hour); $i++)
			{
				$hourList[] = array(
					"HourID" => $i,
					"Day" => $day[$i],
					"Hour" => $hour[$i]
				);
			}
			
			$phone = $request->GetProperty('Phone');
			$office = $request->GetProperty('Office');
			for($i = 0; $i < count($phone); $i++)
			{
				$phoneList[] = array(
					"PhoneID" => $i,
					"Phone" => $phone[$i],
					"Office" => $office[$i]
				);
			}
		}
	}
	else
	{
		$branch->LoadByID($request->GetProperty('BranchID'));
		$phoneList = $branch->GetPhoneListByBranchID($request->GetProperty('BranchID'));
		$hourList = $branch->GetHourListByBranchID($request->GetProperty('BranchID'));
	}
	$content->SetLoop("PhoneList", $phoneList);
	$content->SetLoop("HourList", $hourList);
	$content->LoadFromObject($branch);
	$content->SetVar("CityID", $request->GetProperty('CityID'));
}
else if($request->IsPropertySet("CityID"))
{
	if($request->GetProperty('CityID') == 0)
		$title = GetTranslation("add-city-title");
	else
		$title = GetTranslation("edit-city-title");
	
	$navigation = array(
		array("Title" => GetTranslation("title-city-list"), "Link" => "city.php"),
		array("Title" => $title, "Link" => "city.php?CityID=".$request->GetProperty("CityID"))
	);
	
	$header = array(
		"Title" => $title,
		"Navigation" => $navigation
	);
	$content = $adminPage->Load("city_edit.html", $header);
	
	$city = new City();
	if ($request->GetProperty('Save'))
	{
		$city->LoadFromObject($request);
		if($city->SaveCity())
		{
			header("location: city.php");
			exit();
		}
		else 
		{
			$content->LoadErrorsFromObject($city);
		}
	}
	else
	{
		$city->LoadByID($request->GetProperty('CityID'));
	}
	
	$branchList = new BranchList();
	
	if ($request->GetProperty('Do') == 'Remove')
		$branchList->RemoveByBranchIDs($request->GetProperty("ListIDs"));
	
	$branchList->LoadBranchListByID($request->GetProperty("CityID"));
	$content->LoadFromObjectList("BranchList", $branchList);
	$content->LoadMessagesFromObject($branchList);
	$content->SetVar("ListInfo", GetTranslation('list-info1', array('Page' => $branchList->GetItemsRange(), 'Total' => $branchList->GetCountTotalItems())));
	$content->LoadFromObject($city);
}
else 
{
	$title = GetTranslation("title-city-list");
	
	$navigation = array(
		array("Title" => $title, "Link" => "city.php")
	);
	$header = array(
		"Title" => $title,
		"Navigation" => $navigation
	);
	$content = $adminPage->Load("city_list.html", $header);
	
	$cityList = new CityList();
	if ($request->GetProperty('Do') == 'Remove')
		$cityList->RemoveByCityIDs($request->GetProperty("ListIDs"));
	
	$cityList->LoadCityList($request);
	$content->LoadFromObjectList("CityList", $cityList);
	$content->LoadMessagesFromObject($cityList);
	$content->SetVar("ListInfo", GetTranslation('list-info1', array('Page' => $cityList->GetItemsRange(), 'Total' => $cityList->GetCountTotalItems())));
}

$content->SetLoop("Navigation", $navigation);
$adminPage->Output($content);
?>	