<?php 
es_include("localobjectlist.php");

class CityList extends LocalObjectList
{
	var $module;
	var $params;
	
	function CityList($data = array())
	{
		parent::LocalObjectList($data);
		
		$this->SetSortOrderFields(array(
			"CityIDAsc" => "CityID ASC",
			"CityIDDesc" => "CityID DESC",
			"CityNameDesc" => "c.CityName DESC",
			"CityNameAsc" => "c.CityName ASC",
			"Random" => "RAND()"));
		
		$this->SetDefaultOrderByKey(GetFromConfig("CitiesOrderBy"));
	}
	
	function LoadCityList()
	{		
		$query = "SELECT CityID, CityName, Domain, Director, Phone, Email, FormEmail, CostEntrepreneur, CostCompanyToFive, CostCompanyFive FROM `city`";

		$this->LoadFromSQL($query);
		$this->_PrepareContentBeforeShow();
	}
	
	function _PrepareContentBeforeShow()
	{
		$currentCityID = $this->GetCurrentCityID();
		for($i = 0; $i < $this->GetCountItems(); $i++)
		{
			if($this->_items[$i]["CityID"] == $currentCityID)
				$this->_items[$i]["Selected"] = true;
			else
				$this->_items[$i]["Selected"] = false;
			$urlParser = GetURLParser();
			$fullPath = $urlParser->GetFullPathAsString();
			$language = GetLanguage();
			$dataLng = $language->GetDataLanguageByCode(DATA_LANGCODE);
			$baseDomain = preg_replace("/^www\./", "", $dataLng["HostName"]);
			if($this->_items[$i]["Domain"])
				$this->_items[$i]["URL"] = "http://".$this->_items[$i]["Domain"].".".$baseDomain.$fullPath;
			else
				$this->_items[$i]["URL"] = "http://".$baseDomain.$fullPath;
		}
	}
	
	function RemoveByCityIDs($ids)
	{
		if (!(is_array($ids) && count($ids) > 0)) return;
		$stmt = GetStatement();
		
		$query = "DELETE FROM `city` WHERE CityID IN (".implode(", ", Connection::GetSQLArray($ids)).")";
		$stmt->Execute($query);
		$this->AddMessage('сities-are-removed', $this->module, array("CityCount" => $stmt->GetAffectedRows()));
		
		$query = "DELETE p, h, b FROM `city_branch` AS b 
				LEFT JOIN `city_branch_phone` AS p ON b.BranchID=p.BranchID
				LEFT JOIN `city_branch_hour` AS h ON b.BranchID=h.BranchID
				WHERE b.CityID IN (".implode(", ", Connection::GetSQLArray($ids)).")";
		$stmt->Execute($query);
	}
	
	function GetCurrentCityID()
	{
		$currentSubdomain = GetCurrentSubdomain();
		$stmt = GetStatement();
		$query = "SELECT CityID FROM `city` WHERE Domain=".Connection::GetSQLString($currentSubdomain);
		$cityID = $stmt->FetchField($query);
		return $cityID;
	}
}
?>