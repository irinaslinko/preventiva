<?php 
es_include("localobject.php");

class City extends LocalObject
{
	function City($data = array())
	{
		parent::LocalObject($data);
	}
	
	function LoadByID($cityID)
	{
		$query = "SELECT CityID, CityName, Domain, Director, Phone, Email, FormEmail, CostEntrepreneur, CostCompanyToFive, CostCompanyFive
 			FROM `city`
 		WHERE CityID=".Connection::GetSQLString($cityID);
		$this->LoadFromSQL($query);
		
		if ($this->GetProperty("CityID"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function SaveCity()
	{
		$result = $this->Validate();
		
		if(!$result)
		{
			return false;
		}
		
		$stmt = GetStatement();
		if ($this->GetIntProperty("CityID") > 0)
		{
			$query = "UPDATE `city` SET
				CityName=".$this->GetPropertyForSQL("CityName").",
				Domain=".$this->GetPropertyForSQL("Domain").",
				Director=".$this->GetPropertyForSQL("Director").",
				Phone=".$this->GetPropertyForSQL("Phone").",
				Email=".$this->GetPropertyForSQL("Email").",
				FormEmail=".$this->GetPropertyForSQL("FormEmail").",
				CostEntrepreneur=".$this->GetPropertyForSQL("CostEntrepreneur").",
				CostCompanyToFive=".$this->GetPropertyForSQL("CostCompanyToFive").",
				CostCompanyFive=".$this->GetPropertyForSQL("CostCompanyFive")."
			WHERE CityID=".$this->GetIntProperty("CityID");
		}
		else
		{
			$query = "INSERT INTO `city` (CityName, Domain, Director, Phone, Email, FormEmail, CostEntrepreneur, CostCompanyToFive, CostCompanyFive)
			VALUES (
			".$this->GetPropertyForSQL("CityName").",
			".$this->GetPropertyForSQL("Domain").",
			".$this->GetPropertyForSQL("Director").",
			".$this->GetPropertyForSQL("Phone").",
			".$this->GetPropertyForSQL("Email").",
			".$this->GetPropertyForSQL("FormEmail").",
			".$this->GetPropertyForSQL("CostEntrepreneur").",
			".$this->GetPropertyForSQL("CostCompanyToFive").",
			".$this->GetPropertyForSQL("CostCompanyFive").")";
		}
		
		if ($stmt->Execute($query))
		{
			if (!($this->GetIntProperty("CityID") > 0))
			{
				$this->SetProperty("CityID", $stmt->GetLastInsertID());
			}
			return true;
		}
		else
		{
			$this->AddError("sql-error");
			return false;
		}
	}
	
	function Validate()
	{		
		if (!$this->GetProperty("CityName"))
			$this->AddError("city-name-empty");
			
		return !$this->HasErrors();
	}
}
?>