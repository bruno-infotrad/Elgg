//<script>

/**
 * Autocomplete @mentions
 *
 * Fetch and display a list of matching users when writing a @mention and
 * autocomplete the selected user.
 */
define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	var handleResponse = function (json) {
		var existing_tags = '';
		$(json).each(function(index, tag) {
			existing_tags += '<li id="'+tag.content+'">' + tag.content + "</li>";
		});
		$('#elgg-input-tags-autocomplete-results').html(existing_tags);
		$('#elgg-input-tags-autocomplete-results li').bind('click', function (e) {
			replacement = $(this).attr('id');
			$('#elgg-input-tags-autocomplete').val(function(index, value){
				//console.log("index="+index);
				//console.log("value="+value);
				parts = value.split('\n');
				var valeur = parts[parts.length - 1].replace(search_string,replacement);
				parts[parts.length - 1] = valeur;
				var new_valeur = parts.join('\n');
				return new_valeur;
				//return value.replace(new RegExp(/search_string$/m),replacement);
			});
		});
	};

	var autocomplete = function (existing,search_string,name) {
		var options = {success: handleResponse};
		elgg.get(elgg.config.wwwroot + 'tags_autocomplete?q='+search_string+'&name='+name+'&existing='+existing, options);
	};

	var init = function() {
		$(document).click(function(e) {   
			if(e.target.id != 'elgg-input-tags-autocomplete-results') {
				$("#elgg-input-tags-autocomplete-results").hide();   
			} 
		});
		$('#elgg-input-tags-autocomplete').bind('keyup', function(e) {
			$("#elgg-input-tags-autocomplete-results").html('');   
			$("#elgg-input-tags-autocomplete-results").show();   

			// Hide on backspace, enter and escape
			if (e.which == 8 || e.which == 13|| e.which == 27) {
				$("#elgg-input-tags-autocomplete-results").hide();   
			} else {
				content = $(this).val();
				name = $(this).attr('name');
				parts = content.split('\n');
				console.log('PARTS='+parts);
				if (parts.length === 0) {
					search_string = content;
				} else {
					search_string = parts[parts.length - 1];
				}
				dump = parts.pop();
				existing = parts.join(',');
				console.log('existing='+existing);
				console.log('search_string='+search_string);
				autocomplete(existing,search_string,name);
			}
		});

	};

	elgg.register_hook_handler('init', 'system', init, 9999);

	return {
		autocomplete: autocomplete
	};
});
