<?php 
es_include("modulehandler.php");

class EntrepreneurHandler extends ModuleHandler
{
	function ProcessPublic()
	{
		$this->header["InsideModule"] = $this->module;
		$publicPage = new PublicPage($this->module);
		$content = $publicPage->Load($this->tmplPrefix."main.html", $this->header, $this->pageID);
		$content->SetLoop('Navigation', $this->header['Navigation']);
		
		$content->SetVar('TitleH1', $this->header['TitleH1']);
		$content->SetVar('Content', $this->header['Content']);
		$content->SetVar('PageID', $this->pageID);
		$publicPage->Output($content);
	}
}
?>