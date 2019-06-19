<?php 
es_include("modulehandler.php");

class PaymentHandler extends ModuleHandler
{
	function ProcessPublic()
	{
		$this->header["InsideModule"] = $this->module;
		$publicPage = new PublicPage($this->module);
		$content = $publicPage->Load($this->tmplPrefix."main.html", $this->header, $this->pageID);
		$content->SetVar('TitleH1', $this->header['TitleH1']);
		$content->SetLoop('Navigation', $this->header['Navigation']);
		$publicPage->Output($content);
	}
}
?>