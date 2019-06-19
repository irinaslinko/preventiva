function AddFounder(){
	var clone = $('#new-founder').clone(true);
	clone.removeAttr("id");
	var count = $('.form_bottom--border').length;
	clone.find('span.count').text(count - 1);
	clone.find('span.count').attr('id', count - 1);
	clone.find('#upload').attr('id', 'upload-' + count).find('a').attr('href', '#tab-' + (count * 2 - 1)).attr('aria-controls', '#tab-' + (count * 2 - 1));
	clone.find('#fill-form').attr('id', 'fill-form-' + count).find('a').attr('href', '#tab-' + (count * 2)).attr('aria-controls', '#tab-' + (count * 2));
	clone.find('#tab-01').attr('id', 'tab-' + (count * 2 - 1));
	clone.find('#tab-02').attr('id', 'tab-' + count * 2);
	
	clone.find('#BirthDateFounder').attr('id', 'BirthDateFounder-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][BirthDateFounder]');
	clone.find('#BirthPlaceFounder').attr('id', 'BirthPlaceFounder-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][BirthPlaceFounder]');
	clone.find('#PassportNumberFounder').attr('id', 'PassportNumberFounder-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][PassportNumberFounder]');
	clone.find('#PassportGiveFounder').attr('id', 'PassportGiveFounder-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][PassportGiveFounder]');
	clone.find('#DepartmentCodeFounder').attr('id', 'DepartmentCodeFounder-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][DepartmentCodeFounder]');
	clone.find('#DateGiveFounder').attr('id', 'DateGiveFounder-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][DateGiveFounder]');
	clone.find('#RegistrationFounder').attr('id', 'RegistrationFounder-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][RegistrationFounder]');
	clone.find('#RegistrationFounder').attr('id', 'RegistrationFounder-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][RegistrationFounder]');	
	clone.find('#NaturalPerson').attr('id', 'NaturalPerson-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][paymentFace]').siblings().attr('for', 'NaturalPerson-' + (count - 2));	
	clone.find('#LegalEntity').attr('id', 'LegalEntity-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][paymentFace]').siblings().attr('for', 'LegalEntity-' + (count - 2));	
	clone.find('#CountMoney').attr('id', 'CountMoney-' + (count - 2)).attr('name', 'FounderList[' + (count - 2) + '][CountMoney]');
	
	clone.find(".upload-file-founder").attr("class", "form_bottom__upload-text upload-file-founder-" + (count - 2));
	clone.find('input.uploadFileFounder').attr('id', (count - 2));
	clone.appendTo(".add");
}

$(document).ready(function() {
	if($('.form_bottom--border').length == 1){
		AddFounder();
	}
	
	$('.add-founder').on('click', function(e){
		e.preventDefault();
		AddFounder();
		
	});
});