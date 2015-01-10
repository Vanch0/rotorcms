// ----------------------------------------------------------------------------
// markItUp bb-code setting!
// ----------------------------------------------------------------------------
mySettings = {
	previewParserPath:	'', // path to your BBCode parser
	markupSet: [
		{name:'Bold', className:"bb-bold", key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name:'Italic', className:"bb-italic", key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name:'Underline', className:"bb-underline", key:'U', openWith:'[u]', closeWith:'[/u]'},
		{name:'Strike', className:"bb-strike", key:'S', openWith:'[s]', closeWith:'[/s]'},
		{separator:'---------------' },
		{name:'Link', className:"bb-link", key:'L', openWith:'[url=[![Ссылка:!:http://]!]]', closeWith:'[/url]', placeHolder:'Текст ссылки...'},
		{name:'Video', className:"bb-youtube", openWith:'[youtube][![Код видео с youtube]!]', closeWith:'[/youtube]'},
		{name:'Colors', className:"bb-colors", openWith:'[color=[![Код цвета]!]]', closeWith:'[/color]',
		dropMenu: [
			{name:'Yellow',	openWith:'[color=#ffd700]', closeWith:'[/color]', className:"col1-1" },
			{name:'Orange',	openWith:'[color=#ffa500]', closeWith:'[/color]', className:"col1-2" },
			{name:'Red', openWith:'[color=#ff0000]', closeWith:'[/color]', className:"col1-3" },

			{name:'Blue', openWith:'[color=#0000ff]', closeWith:'[/color]', className:"col2-1" },
			{name:'Purple', openWith:'[color=#800080]', closeWith:'[/color]', className:"col2-2" },
			{name:'Green', openWith:'[color=#00cc00]', closeWith:'[/color]', className:"col2-3" },

			{name:'Magenta', openWith:'[color=#ff00ff]', closeWith:'[/color]', className:"col3-1" },
			{name:'Gray', openWith:'[color=#808080]', closeWith:'[/color]', className:"col3-2" },
			{name:'Black', openWith:'[color=#00ffff]', closeWith:'[/color]', className:"col3-3" },
		]},
		{separator:'---------------' },

		{name:'Size', className:"bb-size",
		dropMenu :[
			{name:'x-small', openWith:'[size=1]', closeWith:'[/size]' },
			{name:'small', openWith:'[size=2]', closeWith:'[/size]' },
			{name:'medium', openWith:'[size=3]', closeWith:'[/size]' },
			{name:'large', openWith:'[size=4]', closeWith:'[/size]' },
			{name:'x-large', openWith:'[size=5]', closeWith:'[/size]' },
		]},

		{name:'Center', className:"bb-center", openWith:'[center]', closeWith:'[/center]'},
		{name:'Spoiler', className:"bb-spoiler", openWith:'[spoiler=[![Заголовок спойлера]!]]', closeWith:'[/spoiler]'},
		{separator:'---------------' },
		{name:'OrderedList', className:"bb-orderedlist", openWith:'[list=1]', closeWith:'[/list]'},
		{name:'UnorderedList', className:"bb-unorderedlist", openWith:'[list]', closeWith:'[/list]'},
		{separator:'---------------' },
		{name:'Hide', className:"bb-hide", openWith:'[hide]', closeWith:'[/hide]'},
		{name:'Quotes', className:"bb-quotes", openWith:'[quote]', closeWith:'[/quote]'},
		{name:'Code', className:"bb-code", openWith:'[code]', closeWith:'[/code]'},
		{separator:'---------------' },
		{name:'Clean', className:"bb-clean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
		{name:'Smiles', className:"bb-smiles",
		dropMenu: [
			{name:':)', openWith:' :) ', className:"col1-1" },
			{name:':(', openWith:' :( ', className:"col1-2" },
			{name:':E', openWith:' :E ', className:"col1-3" },
			{name:':hello', openWith:' :hello ', className:"col2-1" },
			{name:':cry', openWith:' :cry ', className:"col2-2" },
			{name:':obana', openWith:' :obana ', className:"col2-3" },
			{name:':infat', openWith:' :infat ', className:"col3-1" },
			{name:':klass', openWith:' :klass ', className:"col3-2" },
			{name:':krut', openWith:' :krut ', className:"col3-3" }
		]},
		{name:'Nextpage', className:"nextpage", openWith:'[nextpage]'},
		{name:'Cutpage', className:"cutpage", openWith:'[cut]'},
		{name:'Picture', className:'image-upload', closeWith:function(markItUp){miu.ImageUpload.upload(markItUp,'textile');}},
		//{name:'Preview', className:'preview',  call:'preview'}
	]
}


miu.ImageUpload = function()
{
	var upload =  function(markItUp, language, file_upload) {
		var textareaID = markItUp.textarea.id;
		var textarea = $('#'+textareaID);
		var output = '';
		$('#miu_image_upload').remove();
		$.get(markItUp.root+'image_upload/image_upload.html', function(data){
			$('body').append(data);
			var form =$('#miu_image_upload');

			if (file_upload) {
				var button_class = 'file-upload';
			}else{
				var button_class = 'image-upload';
			}

			form.css({
				'top': textarea.position().top+5,
				'left': textarea.closest('.markItUpContainer').find('li.'+button_class).position().left-322
			});

			form.find('a.cancel').click(function(e){
				e.preventDefault();
				form.fadeOut(function(){
					form.remove();
				});
			});

			form.find('img.spinner').attr('src', markItUp.root+'image_upload/loader.gif');

			// class options
			if (!file_upload) {
				var class_string = textarea.attr('data-classes');
				if (class_string) {
					var arclasses = class_string.split(',');
					var i, l;
					var selectbox = $('#miu_image_upload_class');
					for(i=0, l=arclasses.length; i<l; i++) {
						if (arclasses[i].indexOf('|')) {
							var arval = arclasses[i].split('|');
							var label = arval[0];
							var value = arval[1];
						}else{
							var label = arclasses[i];
							var value = label;
						}
						selectbox.append('<option value="'+value+'">'+label+'</option>');
					}
				}else{
					$('#miu_image_upload_classes').remove();
				}
			}else{
				$('#miu_image_upload_classes').remove();
			}


			form.fadeIn(function(){
				$('#miu_image_upload_image').focus();
			});

			form.attr('action', markItUp.root+'image_upload/image_upload.php');


			form.ajaxForm({
				beforeSubmit: function(){
					form.find('img.spinner').show();
				},
				success: function(r) {
					form.find('img.spinner').hide();
					if (r!='FAIL') {
						form.removeClass('fail');
						var alt = form.find('#miu_image_upload_title').val();
						var classname = form.find('#miu_image_upload_class').val();

						if (file_upload) {
							// FILES
							switch(language.toLowerCase()) {
								case 'textile':
									var s_text = r;
									if (alt) s_text = alt;
									output = '"'+s_text+'":'+r;
									break;

								case 'markdown':
									var s_text = r;
									if (alt) s_text = alt;
									output = '['+s_text+']('+r+')';
									break;

								case 'html':
									var s_text = r;
									if (alt) s_text = alt;
									output = '<a href="'+r+'">'+s_text+'</a>';
									break;
							}
						}else{
							// IMAGES
							switch(language.toLowerCase()) {
								case 'textile':
									var s_alt = '';
									var s_class = '';
									if (alt) s_alt = '('+alt+')';
									if (classname) s_class = '('+classname+')';
									output = '!'+s_class+r+s_alt+'!';
									break;

								case 'markdown':
									var s_alt = '[]';
									var s_class = '';
									if (alt) s_alt = '['+alt+']';
									if (classname) s_class = '.'+classname;
									output = '!'+s_alt+'('+r+')'+s_class;
									break;

								case 'html':
									var s_alt = 'alt=""';
									var s_class = '';
									if (alt) s_alt = ' alt="'+alt+'"';
									if (classname) s_class = ' class="'+classname+'"';
									output = '<img src="'+r+'"'+s_alt+s_class+' />';
									break;
							}
						}

						set_caret_position(textareaID, markItUp.caretPosition, 0);
						$.markItUp({ target:'#'+textareaID, openWith:output, closeWith:' '} );
						form.fadeOut(function(){
							form.remove();
						});
					}else{
						form.addClass('fail');
					}
				},
				data: {
					upload: true,
					image: !file_upload,
					width: textarea.attr('data-width'),
					height: textarea.attr('data-height'),
					crop: textarea.attr('data-crop')
				}
			});

		});


		return true;
	};

	var set_caret_position = function(textareaID, start, len) {
		var textarea = $('#'+textareaID).get(0);
		if (textarea.createTextRange){
			range = textarea.createTextRange();
			range.collapse(true);
			range.moveStart('character', start);
			range.moveEnd('character', len);
			range.select();
		}else if(textarea.setSelectionRange){
			textarea.setSelectionRange(start, start + len);
		}
		textarea.focus();
	};

	return {
		upload: upload
	};
}();
