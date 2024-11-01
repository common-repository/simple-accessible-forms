
/****************************************
scripts
***************************************/
jQuery(document).ready(function($){


    // show add field form
    jQuery("body").on("click",".simple_accessible_forms_new_field_btn", function(event) {
        event.preventDefault();  
        jQuery("#simple_accessible_forms_add_field").toggle();
    });

        // show add new form
        jQuery("body").on("click",".simple_accessible_forms_new_form_btn", function(event) {
            event.preventDefault();  
            jQuery("#simple_accessible_forms_form_to_correct_form_name_add").val('');
            jQuery("#simple_accessible_forms_form_to_correct_form_element_id_add").val('');
            jQuery("#simple_accessible_forms_form_to_correct_form_response_id").val(''); 
            jQuery("#simple_accessible_forms_form_update_formid").val('');
            jQuery('#simple_accessible_forms_form_to_correct_form_url_add').val('');
            jQuery("#simple_accessible_forms_dynamic_fields").html('');
            jQuery("#simple_accessible_forms_form_to_correct_form_apply_required_markings").prop('checked',true);
            jQuery("#simple_accessible_forms_validate_on_submit").prop('checked',true);
            jQuery("#simple_accessible_forms_active").prop('checked',false);
            jQuery("#simple_accessible_forms_recaptcha").prop('checked',false);
            jQuery('.simple_accessible_forms_savemessage').html(simpleaccessibleformsVariables['formcleared']);
        });
    

 // remove form or field
jQuery("body").on("click",".simple_accessible_forms_remove_btn", function(event) {
    event.preventDefault();

    var formid = jQuery(this).data('formid');
    var fieldid = jQuery(this).data('fieldid');

    if(fieldid != '' && fieldid != '0') {
        var type = 'fieldid';
        var id = fieldid;
    }
    else {
        var type = 'formid';
        var id = formid;
    }

    var seperator='&';
    var resturl = simpleaccessibleformsVariables['resturl'];
    if(resturl.search('/wp-json/')>0) seperator='?';
    
    $.ajax({
    url: resturl+'simple_accessible_forms_remove/v1/remove/'+seperator+'_wpnonce='+simpleaccessibleformsVariables['nonce']+'&id='+id+'&type='+type,
    dataType: "html",
    error: function(){ return true; },
    success: function(data){ 
        if(type ==='fieldid' ) var action = 'fieldremoved';
        else var action = 'formremoved';
        simple_accessible_forms_refresh(type, formid,action);
        
    }
    });

    });
    
 

// show fiedl details
jQuery("body").on("click keypress",".simple_accessible_show_fields", function(event) {
    event.preventDefault();

    if(event.keyCode !== 13 && event.keyCode !== 32 && event.keyCode !== undefined) {
       return;
    }

if(jQuery(this).attr('aria-expanded') === 'false'){
jQuery("#simple_accessible_forms_field_details"+jQuery(this).data('fieldid')).show();
jQuery(this).attr('aria-expanded','true');
}else{
    jQuery("#simple_accessible_forms_field_details"+jQuery(this).data('fieldid')).hide();
   jQuery(this).attr('aria-expanded','false');  
}
});

// load form 
jQuery("body").on("click",".simple_accessible_forms_loadform", function(event) {
event.preventDefault();
var formid = jQuery(this).data('formid');
var fieldid = jQuery(this).data('fieldid');

// save form values
var seperator='&';
var resturl = simpleaccessibleformsVariables['resturl'];
if(resturl.search('/wp-json/')>0) seperator='?';

$.ajax({
url: resturl+'simple_accessible_forms_load/v1/load/'+seperator+'_wpnonce='+simpleaccessibleformsVariables['nonce']+'&formid='+formid+'&fieldid='+fieldid,
dataType: "json",
error: function(){ return true; },
success: function(data){ 

    if(data['fieldlist'] === '')  jQuery('.simple_accessible_forms_savemessage').html(simpleaccessibleformsVariables['nofields']);
    else jQuery('.simple_accessible_forms_savemessage').html(simpleaccessibleformsVariables['formloaded']);
    jQuery('#simple_accessible_forms_dynamic_fields').html(data['fieldlist']);
    jQuery('#simple_accessible_forms_form_to_correct_form_name_add').val(data['formnicename']);
    jQuery('#simple_accessible_forms_form_to_correct_form_element_id_add').val(data['formelementid']);
    jQuery("#simple_accessible_forms_form_to_correct_form_response_id").val(data['responseid'])
    jQuery('#simple_accessible_forms_form_to_correct_form_url_add').val(data['formurl']);
    jQuery('#simple_accessible_forms_form_update_formid').val(data['formid']);
    if(data['addrequiredmarking'] === '1')
    jQuery("#simple_accessible_forms_form_to_correct_form_apply_required_markings").prop('checked',true);
    else jQuery("#simple_accessible_forms_form_to_correct_form_apply_required_markings").prop('checked',false);

    if(data['validate_on_submit'] === '1')
    jQuery("#simple_accessible_forms_validate_on_submit").prop('checked',true);
    else jQuery("#simple_accessible_forms_validate_on_submit").prop('checked',false);

    if(data['active'] === '1')
    jQuery("#simple_accessible_forms_active").prop('checked',true);
    else jQuery("#simple_accessible_forms_active").prop('checked',false);

    if(data['recaptcha'] === '1')
    jQuery("#simple_accessible_forms_recaptcha").prop('checked',true);
    else jQuery("#simple_accessible_forms_recaptcha").prop('checked',false);
    
}
});


});



// import form fields

jQuery("body").on("click",".simple_accessible_forms_import", function(event) {
    event.preventDefault();
var formid = jQuery(this).data('formid');

// save form values
var seperator='&';
var resturl = simpleaccessibleformsVariables['resturl'];
if(resturl.search('/wp-json/')>0) seperator='?';

$.ajax({
url: resturl+'simple_accessible_forms_fieldimport/v1/import/'+seperator+'_wpnonce='+simpleaccessibleformsVariables['nonce']+'&formid='+formid,
dataType: "html",
error: function(){ 
var action = 'invalidlicense';
simple_accessible_forms_refresh('formid',formid,action);      
},
success: function(data){ 
if(data === 'invalid license') var action = 'invalidlicense';    
else if(data > '0') var action = 'fieldsimported';
else var action = 'fieldsimportfailed';
simple_accessible_forms_refresh('formid',formid,action);
}
});

});




// add or update form
jQuery("body").on("click",".simple_accessible_forms_form_save_btn", function() {

var basicregularex = new RegExp("^([0-9a-zA-Z-\\[\\]\\s\/_|=~`+,.:;!@\?$%^*()\\u9999'])+$");
var urlregularex = new RegExp("^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$");

var formid = jQuery("#simple_accessible_forms_form_update_formid").val();
if(!basicregularex.test(formid) && formid !=''){
    alert(simpleaccessibleformsVariables['invalidformvalue']);
    return;
}
var formname = jQuery("#simple_accessible_forms_form_to_correct_form_name_add").val();
if(!basicregularex.test(formname)){
alert(simpleaccessibleformsVariables['invalidformvalue']);
jQuery("#simple_accessible_forms_form_to_correct_form_name_add").focus();
return;
}

var formurl = jQuery("#simple_accessible_forms_form_to_correct_form_url_add").val();
if(!urlregularex.test(formurl)){
alert(simpleaccessibleformsVariables['invalidformurl']);
jQuery("#simple_accessible_forms_form_to_correct_form_url_add").focus();
return;
}

var formelementid = jQuery("#simple_accessible_forms_form_to_correct_form_element_id_add").val();
if(!basicregularex.test(formelementid)){
    alert(simpleaccessibleformsVariables['invalidformvalue']);
    jQuery("#simple_accessible_forms_form_to_correct_form_element_id_add").focus();
    return;
}

var responseid = jQuery("#simple_accessible_forms_form_to_correct_form_response_id").val();


if(jQuery("#simple_accessible_forms_form_to_correct_form_apply_required_markings").is(':checked')){
    var apply_required_markings = 1;
}else var apply_required_markings = 0;

if(jQuery("#simple_accessible_forms_validate_on_submit").is(':checked')){
    var validate_on_submit = 1;
}else var validate_on_submit = 2;

if(jQuery("#simple_accessible_forms_active").is(':checked')){
    var active = 1;
}else var active = 0;

if(jQuery("#simple_accessible_forms_recaptcha").is(':checked')){
    var recaptcha = 1;
}else var recaptcha = 0;


var formvalues = formname+':|:'+formelementid+':|:'+formid+':|:'+formurl+':|:'+apply_required_markings+':|:'+validate_on_submit+':|:'+active+':|:'+recaptcha+':|:'+responseid;

if(formvalues != ''){

// save form values
var seperator='&';
var resturl = simpleaccessibleformsVariables['resturl'];
 if(resturl.search('/wp-json/')>0) seperator='?';

$.ajax({
url: resturl+'simple_accessible_forms_update/v1/update/'+seperator+'_wpnonce='+simpleaccessibleformsVariables['nonce']+'&variables='+formvalues,
dataType: "html",
error: function(){ return true; },
success: function(formid){ 
if(formid === 'erroroccured') {
    simple_accessible_forms_refresh('fieldid', formid,'unknownerror');
}
else if(formid === 'invalidurl'){
    simple_accessible_forms_refresh('formid',formid,'invalidurl');
    }
 else if(formid === '0'){
    simple_accessible_forms_refresh('formid',formid,'formlimitexceeded');
 }
  else{ 
    simple_accessible_forms_refresh('formid',formid,'formadded');
}
}
});

}
});

// add or update form fields
jQuery("body").on("click",".simple_accessible_forms_form_field_save_btn", function() {

    var basicregularex = new RegExp("^([0-9a-zA-Z-\\[\\]\\s\/_|=~`+,.:;!@\?$%^*()\\u9999'])+$");
    numericregularex = new RegExp("^([0-9])+$");
    memtypesregularex = new RegExp("^([a-zA-Z0-9]+\/[a-zA-Z0-9.\\-\+]+)(,[a-zA-Z0-9]+\/[a-zA-Z0-9.\\-\+]+)*$");

    var parentid = jQuery(this).parent().attr('id');
    if(!basicregularex.test(parentid)){
        alert(simpleaccessibleformsVariables['invalidformvalue']);
        return;
    }

var formid = jQuery("#"+parentid+ " .simple_accessible_forms_form_to_correct_formid").val();
if(!basicregularex.test(formid)){
alert(simpleaccessibleformsVariables['invalidformvalue']);
return;
}

var fieldid = jQuery("#"+parentid+ " .simple_accessible_forms_form_to_correct_fieldid").val();

var fieldname = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_field_name_add_"+fieldid).val();
if(!basicregularex.test(fieldname) ){
alert(simpleaccessibleformsVariables['invalidformvalue']);
jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_field_name_add_"+fieldid).focus();
return;
}

var fieldlabel = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_field_label_add_"+fieldid).val();
if(!basicregularex.test(fieldlabel)){
alert(simpleaccessibleformsVariables['invalidformvalue']);
jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_field_label_add_"+fieldid).focus();
return;
}

var fieldtype = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_fieldtype_add_"+fieldid).val();
if(!basicregularex.test(fieldtype)){
alert(simpleaccessibleformsVariables['invalidformvalue']);
jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_fieldtype_add_"+fieldid).focus();
return;
}

if(jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_required_field_add_"+fieldid).is(':checked')){
var requiredvalue = 'required';
}
else var requiredvalue = '';

var requiredifvalue = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_required_if_field_add_"+fieldid).val();
if(!basicregularex.test(requiredifvalue) && requiredifvalue !== ''){
alert(simpleaccessibleformsVariables['invalidformvalue']);
jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_required_if_field_add_"+fieldid).focus();
return;
}

var eitherorvalue = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_either_or_field_add_"+fieldid).val();
if(!basicregularex.test(eitherorvalue) && eitherorvalue !== ''){
alert(simpleaccessibleformsVariables['invalidformvalue']);
jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_either_or_field_add_"+fieldid).focus();
return;
}
if(fieldtype === 'file'){
    var filetypesvalue = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_file_types_field_add_"+fieldid).val();
    if(!memtypesregularex.test(filetypesvalue)){
    alert(simpleaccessibleformsVariables['filetypeformvalue']);
    jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_file_types_field_add_"+fieldid).focus();
    return;
    }
 
    var filemaxuploadsize = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_file_maxuploadsize_field_add_"+fieldid).val();
    if(!numericregularex.test(filemaxuploadsize)){
    alert(simpleaccessibleformsVariables['numericformvalue']);
    jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_file_maxuploadsize_field_add_"+fieldid).focus();
    return;
    }

    var fieldformat = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_format_add_"+fieldid).val();
    if(!basicregularex.test(fieldformat) && fieldformat !== ''){
    alert(simpleaccessibleformsVariables['invalidformvalue']);
    jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_format_add_"+fieldid).focus();
    return;
    }
    var autocompletvalue = jQuery("#"+parentid+ " #simple_accessible_forms_field_autocomplete_attr_"+fieldid).val();
    if(!basicregularex.test(fieldformat) && fieldformat !== ''){
    alert(simpleaccessibleformsVariables['invalidformvalue']);
    jQuery("#"+parentid+ " #simple_accessible_forms_field_autocomplete_attr_"+fieldid).focus();
    return;
    }
    
    
}else{
var filetypesvalue = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_file_types_field_add_"+fieldid).val();
if(!memtypesregularex.test(filetypesvalue) && filetypesvalue !== ''){
alert(simpleaccessibleformsVariables['fileypeformvalue']);
jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_file_types_field_add_"+fieldid).focus();
return;
}

var filemaxuploadsize = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_file_maxuploadsize_field_add_"+fieldid).val();
if(!numericregularex.test(filemaxuploadsize) && filemaxuploadsize !== ''){
alert(simpleaccessibleformsVariables['numericformvalue']);
jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_file_maxuploadsize_field_add_"+fieldid).focus();
return;
}
var fieldformat = jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_format_add_"+fieldid).val();
if(!basicregularex.test(fieldformat) ){
alert(simpleaccessibleformsVariables['invalidformvalue']);
jQuery("#"+parentid+ " #simple_accessible_forms_form_to_correct_format_add_"+fieldid).focus();
return;
}

var autocompletvalue = jQuery("#"+parentid+ " #simple_accessible_forms_field_autocomplete_attr_"+fieldid).val();
if(!basicregularex.test(fieldformat) && fieldformat !== ''){
alert(simpleaccessibleformsVariables['invalidformvalue']);
jQuery("#"+parentid+ " #simple_accessible_forms_field_autocomplete_attr_"+fieldid).focus();
return;
}
}

var formfieldvalue = fieldname+':|:'+fieldlabel+':|:'+fieldtype+':|:'+requiredvalue+':|:'+requiredifvalue+':|:'+eitherorvalue+':|:'+filetypesvalue+':|:'+filemaxuploadsize+':|:'+fieldformat+':|:'+formid+':|:'+fieldid+':|:'+autocompletvalue;

if(formfieldvalue != ''){

// save form values
var seperator='&';
var resturl = simpleaccessibleformsVariables['resturl'];
if(resturl.search('/wp-json/')>0) seperator='?';

$.ajax({
url: resturl+'simple_accessible_forms_field_update/v1/update/'+seperator+'_wpnonce='+simpleaccessibleformsVariables['nonce']+'&variables='+formfieldvalue,
dataType: "html",
error: function(){ return true; },
success: function(returnformid){
if(returnformid === 'erroroccured') {
    simple_accessible_forms_refresh('fieldid', formid,'unknownerror');
}
else if(returnformid != formid) {
simple_accessible_forms_refresh('fieldid', formid,'fieldlimitexceeded');
}
else{
simple_accessible_forms_refresh('fieldid', formid,'fieldupdated');
}
}
});

}
});

// reload content
    function simple_accessible_forms_refresh(type, formid,action){
        var seperator='&';
        var resturl = simpleaccessibleformsVariables['resturl'];
        if(resturl.search('/wp-json/')>0) seperator='?';
        
        $.ajax({
        url: resturl+'simple_accessible_forms_refresh/v1/refresh/'+seperator+'_wpnonce='+simpleaccessibleformsVariables['nonce'],
        dataType: "html",
        error: function(){ return true; },
        success: function(data){ 
      
            if(data.indexOf('null') === -1) jQuery('#simple_accessible_forms_page_wrapper').html(data);
            jQuery('.simple_accessible_forms_savemessage').html(simpleaccessibleformsVariables[action]);
            if((type === 'fieldid' || action === 'formadded' || action === 'fieldsimported') && action !== 'unknownerror' && action !== 'fieldlimitexceeded' && action !== 'formlimitexceeded'){
                setTimeout(() => {
                jQuery('#simple_accessible_forms_loadform'+formid).trigger('click');
            }, "2000");
            }
            
        }
        });
    }

    // allow key commands on click events		
simple_accessible_forms_setbutton_eventListener();						 
function simple_accessible_forms_setbutton_eventListener(){

    if(document.querySelectorAll('[role="button"]') !== undefined){
    document.querySelectorAll('[role="button"]').forEach(function(button) {
    
        button.addEventListener('keydown', function(evt) {
    
           if(evt.keyCode == 13 || evt.keyCode == 32) {
               button.click();
           }
    
        });
    
    });
    }								 
    }   


});