$(document).ready(function() {

	// sliders
	$('.carousel').carousel({
		interval: 8000,
		pause: false
	});

	// to top
	$('.totop').click(function () {
		$("html, body").animate({
			scrollTop: 0
		}, 600);
	});

	// aside toggle
	$('.aside').click(function () {
		$(this).toggleClass('aside-open');
	});

	// nav tabs animate
	$('.nav-tabs a').click(function (e) {
		e.preventDefault()
		$(this).tab('show');
		$('html, body').animate({
		scrollTop: $(this).parent().parent().offset().top - 10
		}, 700);
	});

	// toggle block_question
	$('.block_question__header').click(function() {
		$(this).parent().toggleClass('open');
		$(this).parent().find('.block_question__content').slideToggle('200','linear');
	});

	 // toggle form_bottom--toggle
	$('.form_bottom--toggle').click(function() {
		$(this).toggleClass('open');
		$(this).find('.form_bottom__wrap').slideToggle('200','linear');
	});

	// delete upload file 
	$(document).on("click", ".fa-close", function(event){
		 var $this = $(this);
		 var file = $this.attr("id");
		 var ajaxURL = $(this).data('url');
		 var result = confirm( "Удалить файл?" );
		 if (result) {
			 $.ajax({
					url: ajaxURL,
					type: 'POST',
					data: {'File': file, 'Action': 'DeleteFile'},
					cache: false,
					dataType: 'JSON',
					success: function(){
				 		$this.parent().remove();
					}
				});
		 } else {
			 return;
		 }
		event.preventDefault();
	});

	// remove class has-error on focus
	$( '.has-error .form-control' ).focus(function() {
		var $this = $(this);
		setTimeout(function(){
			$this.parent().removeClass('has-error');
		},500);
	});

	//forms
	$('input[type=radio][name=radioPayment]').change(function() {
		if (this.value == 'LegalEntity') {
			$('#changefromRadio').show('100','swing');
		}
		else {
			$('#changefromRadio').hide('100','swing');
		}
	});

	$('input.Person').change(function() {
		if (this.value == 'NaturalPerson') {
			$(this).parent().siblings().find('.paymentFaceChange').text('Размер доли в уставном капитале');
		}
		else if (this.value == 'LegalEntity') {
			$(this).parent().siblings().find('.paymentFaceChange').text('Доля');
		}
	});

	$('input[type=radio][name=paymentCapital]').change(function() {
		if (this.value == 'Property') {
			$('#form_bottom-hide').show('100','swing');
		}
		else {
			$('#form_bottom-hide').hide('100','swing');
		}
	});
		
	// tooltip
	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
	});

	// other
	$(".modal").on("show", function () {
		$("body").addClass("modal-open");
	}).on("hidden", function () {
		$("body").removeClass("modal-open")
	});

	var busy = false;
	
	$('.ajax-form').submit(function(e){
		var form = $(this);
		form.find('.form-error').html("");
		form.find('.form-message').html("");
		var formData = form.serialize();
		var urlNext = $(this).find('button#Next').data('url');
		if(busy == false)
		{
			busy = true;
			$.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				dataType: 'JSON',
				data:formData,
				success:function(data){
					busy = false;
					if(data.ErrorList){
						for(var i = 0; i < data.ErrorList.length; i++){
							if(i > 0)
								form.find('.form-error').append('<br />');
							form.find('.form-error').append(data.ErrorList[i]['Message']);
						}
					}else{
						if(data.NextPart){
							window.location.href = urlNext;
						}else{
							for(var i = 0; i < data.MessageList.length; i++){
								if(i > 0)
									form.find('.form-message').append('<br />');
								form.find('.form-message').append(data.MessageList[i]['Message']);
							}
							form[0].reset();
						}
					}
				}			
			});
		}
		e.preventDefault();
	});
	
	$('.custom-select').on('change', function(){
		var costCompany = $('#formTown').val().split(',');
		$(this).siblings().find('span#costEntrepreneur').text($('#formTown').val());
		$(this).siblings().find('span#costCompanyToFive').text(costCompany[0]);
		$(this).siblings().find('span#costCompanyFiveMore').text(costCompany[1]);
	});
	
	var file;
	$('input#uploadFile').on('change', function(){
		var ajaxURL = $(this).data('url'); 
		
		var file = $(this).prop('files')[0];
		if( typeof file == 'undefined' ) return;
		var dataFile = new FormData();
		dataFile.append('File', file);
		dataFile.append('Action', 'UploadFile');
		
		$.ajax({
			url: ajaxURL,
			type: 'POST',
			data: dataFile,
			cache: false,
			dataType: 'JSON',
			processData: false,
			contentType: false,
			success: function(data){
				var clone = $("#new-file").clone();
				clone.removeAttr("id");
				clone.find("span.file-name").text(data.FileName);
				clone.find("input").val($("<div>").html(data.File).text());
				clone.find("span.fa-close").attr("id", data.File);
				clone.appendTo(".form_bottom__upload-text");
			}
		});
	});
	
	$('input#uploadFileHead').on('change', function(){
		var ajaxURL = $(this).data('url'); 
		
		var file = $(this).prop('files')[0];
		if( typeof file == 'undefined' ) return;
		var dataFile = new FormData();
		dataFile.append('File', file);
		dataFile.append('Action', 'UploadFile');
		
		$.ajax({
			url: ajaxURL,
			type: 'POST',
			data: dataFile,
			cache: false,
			dataType: 'JSON',
			processData: false,
			contentType: false,
			success: function(data){
				var clone = $("#new-file-head").clone();
				clone.removeAttr("id");
				clone.find("span.file-name").text(data.FileName);
				clone.find("input.HeadId").val($("<div>").html(data.File).text());
				clone.find("input.HeadName").val($("<div>").html(data.FileName).text());
				clone.find("span.fa-close").attr("id", data.File);
				clone.appendTo(".upload-file-head");
			}
		});
	});
	
	$('input.uploadFileFounder').on('change', function(){
		var ajaxURL = $(this).data('url'); 
		
		var file = $(this).prop('files')[0];
		if( typeof file == 'undefined' ) return;
		var dataFile = new FormData();
		var number = $(this).attr('id');
		dataFile.append('File', file);
		dataFile.append('Action', 'UploadFile');
		
		$.ajax({
			url: ajaxURL,
			type: 'POST',
			data: dataFile,
			cache: false,
			dataType: 'JSON',
			processData: false,
			contentType: false,
			success: function(data){
				var clone = $("#new-file-founder").clone();
				clone.removeAttr("id");
				clone.find("span.file-name").text(data.FileName);
				clone.find("input.FounderID").attr("name", "FounderList[" + number + "][FileListFounderID][]").val($("<div>").html(data.File).text());
				clone.find("input.FounderName").attr("name", "FounderList[" + number + "][FileListFounderName][]").val($("<div>").html(data.FileName).text());
				clone.find("span.fa-close").attr("id", data.File);
				clone.appendTo(".upload-file-founder-" + number);
			}
		});
	});
	
	
	$('.has-child').on('click', function(e){
		e.preventDefault();
		
		if($(this).parent().hasClass('active'))
		{
			$(this).parent().removeClass('active');
			$(this).siblings().addClass('hidden');
		}
		else
		{
			$(this).parent().addClass('active');
			$(this).siblings().removeClass('hidden');
			$(this).parent().siblings().removeClass('active');
			$(this).parent().siblings().find('li').removeClass('active');
			$(this).parent().siblings().find('ul').addClass('hidden');
		}
	});
	
	
});


/*
	jQuery Masked Input Plugin
	Copyright (c) 2007 - 2015 Josh Bush (digitalbush.com)
	Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license)
	Version: 1.4.1
*/
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a){var b,c=navigator.userAgent,d=/iphone/i.test(c),e=/chrome/i.test(c),f=/android/i.test(c);a.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},autoclear:!0,dataName:"rawMaskFn",placeholder:"_"},a.fn.extend({caret:function(a,b){var c;if(0!==this.length&&!this.is(":hidden"))return"number"==typeof a?(b="number"==typeof b?b:a,this.each(function(){this.setSelectionRange?this.setSelectionRange(a,b):this.createTextRange&&(c=this.createTextRange(),c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",a),c.select())})):(this[0].setSelectionRange?(a=this[0].selectionStart,b=this[0].selectionEnd):document.selection&&document.selection.createRange&&(c=document.selection.createRange(),a=0-c.duplicate().moveStart("character",-1e5),b=a+c.text.length),{begin:a,end:b})},unmask:function(){return this.trigger("unmask")},mask:function(c,g){var h,i,j,k,l,m,n,o;if(!c&&this.length>0){h=a(this[0]);var p=h.data(a.mask.dataName);return p?p():void 0}return g=a.extend({autoclear:a.mask.autoclear,placeholder:a.mask.placeholder,completed:null},g),i=a.mask.definitions,j=[],k=n=c.length,l=null,a.each(c.split(""),function(a,b){"?"==b?(n--,k=a):i[b]?(j.push(new RegExp(i[b])),null===l&&(l=j.length-1),k>a&&(m=j.length-1)):j.push(null)}),this.trigger("unmask").each(function(){function h(){if(g.completed){for(var a=l;m>=a;a++)if(j[a]&&C[a]===p(a))return;g.completed.call(B)}}function p(a){return g.placeholder.charAt(a<g.placeholder.length?a:0)}function q(a){for(;++a<n&&!j[a];);return a}function r(a){for(;--a>=0&&!j[a];);return a}function s(a,b){var c,d;if(!(0>a)){for(c=a,d=q(b);n>c;c++)if(j[c]){if(!(n>d&&j[c].test(C[d])))break;C[c]=C[d],C[d]=p(d),d=q(d)}z(),B.caret(Math.max(l,a))}}function t(a){var b,c,d,e;for(b=a,c=p(a);n>b;b++)if(j[b]){if(d=q(b),e=C[b],C[b]=c,!(n>d&&j[d].test(e)))break;c=e}}function u(){var a=B.val(),b=B.caret();if(o&&o.length&&o.length>a.length){for(A(!0);b.begin>0&&!j[b.begin-1];)b.begin--;if(0===b.begin)for(;b.begin<l&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}else{for(A(!0);b.begin<n&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}h()}function v(){A(),B.val()!=E&&B.change()}function w(a){if(!B.prop("readonly")){var b,c,e,f=a.which||a.keyCode;o=B.val(),8===f||46===f||d&&127===f?(b=B.caret(),c=b.begin,e=b.end,e-c===0&&(c=46!==f?r(c):e=q(c-1),e=46===f?q(e):e),y(c,e),s(c,e-1),a.preventDefault()):13===f?v.call(this,a):27===f&&(B.val(E),B.caret(0,A()),a.preventDefault())}}function x(b){if(!B.prop("readonly")){var c,d,e,g=b.which||b.keyCode,i=B.caret();if(!(b.ctrlKey||b.altKey||b.metaKey||32>g)&&g&&13!==g){if(i.end-i.begin!==0&&(y(i.begin,i.end),s(i.begin,i.end-1)),c=q(i.begin-1),n>c&&(d=String.fromCharCode(g),j[c].test(d))){if(t(c),C[c]=d,z(),e=q(c),f){var k=function(){a.proxy(a.fn.caret,B,e)()};setTimeout(k,0)}else B.caret(e);i.begin<=m&&h()}b.preventDefault()}}}function y(a,b){var c;for(c=a;b>c&&n>c;c++)j[c]&&(C[c]=p(c))}function z(){B.val(C.join(""))}function A(a){var b,c,d,e=B.val(),f=-1;for(b=0,d=0;n>b;b++)if(j[b]){for(C[b]=p(b);d++<e.length;)if(c=e.charAt(d-1),j[b].test(c)){C[b]=c,f=b;break}if(d>e.length){y(b+1,n);break}}else C[b]===e.charAt(d)&&d++,k>b&&(f=b);return a?z():k>f+1?g.autoclear||C.join("")===D?(B.val()&&B.val(""),y(0,n)):z():(z(),B.val(B.val().substring(0,f+1))),k?b:l}var B=a(this),C=a.map(c.split(""),function(a,b){return"?"!=a?i[a]?p(b):a:void 0}),D=C.join(""),E=B.val();B.data(a.mask.dataName,function(){return a.map(C,function(a,b){return j[b]&&a!=p(b)?a:null}).join("")}),B.one("unmask",function(){B.off(".mask").removeData(a.mask.dataName)}).on("focus.mask",function(){if(!B.prop("readonly")){clearTimeout(b);var a;E=B.val(),a=A(),b=setTimeout(function(){B.get(0)===document.activeElement&&(z(),a==c.replace("?","").length?B.caret(0,a):B.caret(a))},10)}}).on("blur.mask",v).on("keydown.mask",w).on("keypress.mask",x).on("input.mask paste.mask",function(){B.prop("readonly")||setTimeout(function(){var a=A(!0);B.caret(a),h()},0)}),e&&f&&B.off("input.mask").on("input.mask",u),A()})}})});

$(document).ready(function() {
	$(".form_phone").mask("+7 (999) - 999 - 99 - 99");
});
