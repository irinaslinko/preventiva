<?php 
es_include("localobjectlist.php");
es_include("branch.php");

class BranchList extends LocalObjectList
{
	var $module;
	var $params;
	
	function BranchList($data = array())
	{
		parent::LocalObjectList($data);
		
		$this->SetSortOrderFields(array(
			"BranchIDAsc" => "BranchID ASC",
			"BranchIDDesc" => "BranchID DESC",
			"BranchNameDesc" => "c.BranchName DESC",
			"BranchNameAsc" => "c.BranchName ASC",
			"Random" => "RAND()"));
		
		$this->SetDefaultOrderByKey(GetFromConfig("BranchesOrderBy"));
	}
	
	function LoadBranchListByID($cityID)
	{		
		$query = "SELECT BranchID, CityID, BranchName, FullAddress, Address, Map
 			FROM `city_branch`
			WHERE CityID=".Connection::GetSQLString($cityID);

		$this->LoadFromSQL($query);
		$this->_PrepareContentBeforeShow();
	}
	
	function _PrepareContentBeforeShow()
	{
		for($i = 0; $i < $this->GetCountItems(); $i++)
		{	
			$phoneList = $this->GetPhoneListByBranchID($this->_items[$i]["BranchID"]);
			for($j = 0; $j < count($phoneList); $j++)
			{
				if($j == 0)
				{
					$phoneList[$j]["ShowOffice"] = true;
					continue;
				}
				elseif(($phoneList[$j]["Office"] != $phoneList[$j - 1]["Office"]))
					$phoneList[$j]["ShowOffice"] = true;
			}
			$this->_items[$i]["PhoneList"] = $phoneList;
		}
	}
	
	function GetPhoneListByBranchID($branchID)
	{
		$stmt = GetStatement();
		$phoneList = array();
		$query = "SELECT PhoneID, BranchID, Office, Phone FROM `city_branch_phone` WHERE BranchID=".Connection::GetSQLString($branchID);
		$phoneList = $stmt->FetchList($query);
		return $phoneList;
	}
	
	function RemoveByBranchIDs($ids)
	{
		if (!(is_array($ids) && count($ids) > 0)) return;
		$stmt = GetStatement();
		
		$query = "DELETE FROM `city_branch` WHERE BranchID IN (".implode(", ", Connection::GetSQLArray($ids)).")";
		$stmt->Execute($query);
		$this->AddMessage('branches-are-removed', $this->module, array("BranchCount" => $stmt->GetAffectedRows()));
		
		$query = "DELETE FROM `city_branch_phone` WHERE BranchID IN (".implode(", ", Connection::GetSQLArray($ids)).")";
		$stmt->Execute($query);
		
		$query = "DELETE FROM `city_branch_hour` WHERE BranchID IN (".implode(", ", Connection::GetSQLArray($ids)).")";
		$stmt->Execute($query);
	}
}
?>