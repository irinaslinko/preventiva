<?php 
require_once(dirname(__FILE__)."/init.php");
es_include("filesys.php");
es_include("localobject.php");

class Branch extends LocalObject
{
	function Branch($data = array())
	{
		parent::LocalObject($data);
	}
	
	function LoadByID($branchID)
	{
		$query = "SELECT BranchID, CityID, BranchName, FullAddress, Address, Map
 			FROM `city_branch`
 		WHERE BranchID=".Connection::GetSQLString($branchID);
		$this->LoadFromSQL($query);
		if ($this->GetProperty("BranchID"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function SaveBranch()
	{
		$result = $this->Validate();
		
		if(!$result)
		{
			return false;
		}
		
		$stmt = GetStatement();
		if ($this->GetIntProperty("BranchID") > 0)
		{
			$query = "UPDATE `city_branch` SET
				CityID=".$this->GetPropertyForSQL("CityID").",
				BranchName=".$this->GetPropertyForSQL("BranchName").",
				FullAddress=".$this->GetPropertyForSQL("FullAddress").",
				Address=".$this->GetPropertyForSQL("Address").",
				Map=".$this->GetPropertyForSQL("Map")."
			WHERE BranchID=".$this->GetIntProperty("BranchID");
		}
		else
		{
			$query = "INSERT INTO `city_branch` (CityID, BranchName, FullAddress, Address, Map)
			VALUES (
			".$this->GetPropertyForSQL("CityID").",
			".$this->GetPropertyForSQL("BranchName").",
			".$this->GetPropertyForSQL("FullAddress").",
			".$this->GetPropertyForSQL("Address").",
			".$this->GetPropertyForSQL("Map").")";
		}
		
		if ($stmt->Execute($query))
		{
			if (!($this->GetIntProperty("BranchID") > 0))
			{
				$this->SetProperty("BranchID", $stmt->GetLastInsertID());
			}
			$this->SavePhoneList();
			$this->SaveHourList();
			return true;
		}
		else
		{
			$this->AddError("sql-error");
			return false;
		}
	}
	
	function SavePhoneList()
	{
		$stmt = GetStatement();
		
		$query = "DELETE FROM `city_branch_phone` WHERE BranchID=".$this->GetIntProperty("BranchID");
		$stmt->Execute($query);
		for($i = 0; $i < count($this->GetProperty("Phone")); $i++)
		{
			$query = "INSERT INTO `city_branch_phone` (BranchID, Office, Phone)
				VALUES(
				".$this->GetPropertyForSQL("BranchID").",
				".Connection::GetSQLString($this->_properties["Office"][$i]).",
				".Connection::GetSQLString($this->_properties["Phone"][$i]).")";
			$stmt->Execute($query);
		}
		return true;
	}
	
	function SaveHourList()
	{
		$stmt = GetStatement();
		
		$query = "DELETE FROM `city_branch_hour` WHERE BranchID=".$this->GetIntProperty("BranchID");
		$stmt->Execute($query);
		for($i = 0; $i < count($this->GetProperty("Hour")); $i++)
		{
			$query = "INSERT INTO `city_branch_hour` (BranchID, Day, Hour)
				VALUES(
				".$this->GetPropertyForSQL("BranchID").",
				".Connection::GetSQLString($this->_properties["Day"][$i]).",
				".Connection::GetSQLString($this->_properties["Hour"][$i]).")";
			$stmt->Execute($query);
		}
		return true;
	}
	
	function GetPhoneListByBranchID($branchID)
	{
		$stmt = GetStatement();
		$phoneList = array();
		$query = "SELECT PhoneID, BranchID, Office, Phone FROM `city_branch_phone` WHERE BranchID=".Connection::GetSQLString($branchID)."ORDER BY PhoneID ASC";
		$phoneList = $stmt->FetchList($query);
		return $phoneList;
	}
	
	function GetHourListByBranchID($branchID)
	{
		$stmt = GetStatement();
		$hourList = array();
		$query = "SELECT HourID, BranchID, Day, Hour FROM `city_branch_hour` WHERE BranchID=".Connection::GetSQLString($branchID)."ORDER BY HourID ASC";
		$hourList = $stmt->FetchList($query);
		return $hourList;
	}
	
	function Validate()
	{
		if (!$this->GetProperty("BranchName"))
			$this->AddError("branch-name-empty");
		
		if (!$this->GetProperty("FullAddress"))
			$this->AddError("branch-full-address-empty");
		
		if (!$this->GetProperty("Address"))
			$this->AddError("branch-address-empty");
		
		if (!$this->GetProperty("Phone"))
			$this->AddError("branch-phone-empty");
		
		if (!$this->GetProperty("Hour"))
			$this->AddError("branch-hour-empty");
		
		return !$this->HasErrors();
	}
}
?>