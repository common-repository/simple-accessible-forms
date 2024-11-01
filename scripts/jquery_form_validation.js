/*************************************************************************************

Plugin Name: Accessible JQuery Form Validation

******************************************************************************************/
var jquery_form_validation_form_validation_error = 0; // global error variable

jQuery( document ).ready(function() {

    // ADD FORM AND FIELD ATTRIBUTES
  var jquery_form_validation_form_name;
  var jquery_form_validation_form_field_name;

  setTimeout(function(){ 
  if(typeof(jquery_form_validation_form_forms_array) !== "undefined")
   jQuery(jquery_form_validation_form_forms_array).each(function() {
  jquery_form_validation_form_name = jQuery(this)[0];
   
   // add required attributes and markings
  if(jQuery('form[id='+jquery_form_validation_form_name+']').length > 0){


  // add form attributes
  if(jQuery(this)[1] !== 'undefined' && jQuery(this)[1] !== 1 
&& (jQuery('form[id='+jquery_form_validation_form_name+']').attr('aria-label') === '' || jQuery('form[id='+jquery_form_validation_form_name+']').attr('aria-label') === undefined))  jQuery('form[id='+jquery_form_validation_form_name+']').attr('aria-label', jQuery(this)[1]);

  if(jQuery('form[id='+jquery_form_validation_form_name+']').attr('data-addrequiredmarking') === ''){
  jQuery('form[id='+jquery_form_validation_form_name+']').attr('data-addrequiredmarking', '1');
  }
  jQuery('form[id='+jquery_form_validation_form_name+']').addClass('jquery_form_validation_form');
  if(jQuery('form[id='+jquery_form_validation_form_name+']').attr('data-validateonsubmit') === '1'){
  jQuery('form[id='+jquery_form_validation_form_name+'] *[type=submit]').addClass('jquery_form_validation_form_submit');
  jQuery('form[id='+jquery_form_validation_form_name+'] *[type=submit]').attr('data-formname',jQuery('form[id='+jquery_form_validation_form_name+']').attr('name'));
  }
  
  // add form field attributes
  innerArrayLength =  jQuery(this).length;
  
  for (let j = 1; j < innerArrayLength; j++) {
  
  
  if(jQuery(this)[j][0] !== 'undefined')
  jquery_form_validation_form_field_name =jQuery(this)[j][0];
  
  //if(jquery_form_validation_form_field_name.indexOf("[]") > 0) {
    //jquery_form_validation_form_field_name = jquery_form_validation_form_field_name.replace('[]', '');
  //} 
  
  // add data-fieldname
  if(jQuery(this)[j][1] !== 'undefined'){
  jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('data-fieldname', jQuery(this)[j][1]);

  // correct missing field labels only if it is not a checkbox group
  if(jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').length === 1){
  var fieldid = jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('id');
  var arialabel = jQuery('form[id='+jquery_form_validation_form_name+'] [id='+fieldid+']').attr('aria-label');

if(typeof fieldid !== 'undefined'){
 var forattr = jQuery('form[id='+jquery_form_validation_form_name+'] [for='+fieldid+']').attr('for'); 
 
  if(typeof forattr === 'undefined' && typeof arialabel === 'undefined'){
    jQuery('form[id='+jquery_form_validation_form_name+'] [id='+fieldid+']').attr('aria-label',jQuery(this)[j][1]);  
  } 
  
} else{

  var arialabel = jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('aria-label');

  if((typeof arialabel === 'undefined' || arialabel === '') 
  && jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').prop("tagName") !== 'DUET-DATE-PICKER'
  && jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').closest('label') === undefined) 
  {
    jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('aria-label',jQuery(this)[j][1]); 
  }
}
}
}

  
    // add required classes
if(jQuery(this)[j][2] !== 'undefined'){
var newclass = jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('class')+' '+jQuery(this)[j][2];
jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('class', newclass);
  }
  
   // add validation format data-format
  if(jQuery(this)[j][3] !== 'undefined'){
  jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('data-format', jQuery(this)[j][3]);
  }
  
   // add data-requiredif
  if(jQuery(this)[j][4] !== 'undefined'){
  jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('data-requiredif', jQuery(this)[j][4]);
}

// add data-eitheror
if(jQuery(this)[j][5] !== 'undefined'){
jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('data-eitheror', jQuery(this)[j][5]);
}
  
// add data-filetypes
if(jQuery(this)[j][6] !== 'undefined'){
jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('data-filetypes', jQuery(this)[j][6]);
}

// add data-maxuploadsize
if(jQuery(this)[j][7] !== 'undefined'){
jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('data-maxuploadsize', jQuery(this)[j][7]);
}

// add autocomplete
if(jQuery(this)[j][8] !== 'undefined' && jQuery(this)[j][8] !== '' && jQuery(this)[j][8] !== '_')
jQuery('form[id='+jquery_form_validation_form_name+'] [name="'+jquery_form_validation_form_field_name+'"]').attr('autocomplete', jQuery(this)[j][8]);
  
}
}
});


// correct empty option tags
jQuery('form[id='+jquery_form_validation_form_name+'] select option').each(function() { 
  console.log(jQuery(this).text());
  if(jQuery(this).text() === '') jQuery(this).text('Select an Option');
  
});
}, 400);
  
  // END ADD FORM AND FIELD ATTRIBUTES
  /*****************************************************************************************/

  // pause to allow other scripts to run   
 
  setTimeout(function(){  
    // append styles required for displaying form field errors and required fields
      jQuery('head').append('<style>.jquery-form-validation-fieldset{border: 0px; margin-top:10px} .screen-reader-text{border: 0; clip: rect(1px, 1px, 1px, 1px); clip-path: inset(50%); height: 1px; margin: -1px; overflow: hidden; padding: 0; position:absolute; width: 1px; word-wrap: normal !important;} .formerror{color: #ab0707;} .requiredmarkings{color: #ab0707; font-weight: bold; margin-left: 5px;}</style>');

      // add attributes to submit button to enable validation on submit
      jQuery('form[data-validateonsubmit=1] *[type=submit]').addClass('jquery_form_validation_form_submit');
      jQuery('form[data-validateonsubmit=1] *[type=submit]').attr('data-formname',jQuery(this).closest('form').attr('name'));


    // add required field markings and fieldsets
    jQuery("form.jquery_form_validation_form").each(function(form) {
      var formid = '#'+jQuery(this).attr('id');

// hide google recaptcha
if(jQuery(this).attr('data-hiderecaptcha') === '1'){
  if(jQuery(formid+' .grecaptcha-badge').length > 0){
  jQuery(formid+' .grecaptcha-badge').css("visibility", "hidden");

  if(jQuery(formid+' .jquery_validation_recaptcha_message').length === 0){
  jQuery(formid).append('<p class="jquery_validation_recaptcha_message">This site is protected by reCAPTCHA and the <a href="https://policies.google.com/privacy">Google Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</p>');
  }
  };
  }
    
    if(jQuery('form.jquery_form_validation_form').attr('data-addrequiredmarking') === '1'){
    
     var fieldname = '';
     var required;

    // correct fieldsets
    jQuery(formid+" .formfield").each(function() {
      if(jQuery(this).attr('name') != '' && typeof jQuery(this).attr('name') != 'undefined') fieldname = jQuery(this).attr('name');

  // add fieldsets to radio and checkbox groups 
if((jQuery(this).attr("type") === 'radio' || jQuery(this).attr("type") === 'checkbox') && jQuery(this).closest('fieldset').html() === undefined){

// only add if there are more than 1 fields in the group

if(jQuery(formid+' [name="'+fieldname+'"]').length > 1){

  if(jQuery('.fieldset'+fieldname.replace('[]', '')).length > 0){
    
    if(jQuery(this).closest('label').html() !== undefined){
      jQuery('.fieldset'+fieldname.replace('[]', '')).append('<br>');
     jQuery('.fieldset'+fieldname.replace('[]', '')).append(jQuery(this).closest('label'));
    jQuery('.fieldset'+fieldname.replace('[]', '')).append(' ');
    }
    else {
      jQuery('.fieldset'+fieldname.replace('[]', '')).append('<br>');
      jQuery('.fieldset'+fieldname.replace('[]', '')).append(jQuery(this));
      jQuery('.fieldset'+fieldname.replace('[]', '')).append(' ');
      jQuery('.fieldset'+fieldname.replace('[]', '')).append(jQuery('[for='+jQuery(this).attr('id')+']'));
      

    }
  }
  else {
  if(jQuery(this).closest('label').html() !== undefined){
    jQuery(this).closest('label').wrap('<fieldset class="jquery-form-validation-fieldset fieldset'+fieldname.replace('[]', '')+'"></fieldset>');
  }
  else {
    jQuery(this).wrap('<fieldset class="jquery-form-validation-fieldset fieldset'+fieldname.replace('[]', '')+'"></fieldset>');
    jQuery('.fieldset'+fieldname.replace('[]', '')).append(' ');
    jQuery('.fieldset'+fieldname.replace('[]', '')).append(jQuery('[for='+jQuery(this).attr('id')+']'));
    
  }
  jQuery('.fieldset'+fieldname.replace('[]', '')).prepend('<legend class="jquery-form-validation-legend">'+jQuery(this).attr('data-fieldname')+'</legend>');

  }
}
}
});

    // add required markings
    jQuery(formid+" .required").each(function() { 
      if(jQuery(this).attr('name') != '' && typeof jQuery(this).attr('name') != 'undefined') fieldname = jQuery(this).attr('name');

    var id = '';
    if(jQuery(this).prop("tagName") === 'DUET-DATE-PICKER' && jQuery(this).attr('identifier') != '' && typeof jQuery(this).attr('identifier') != 'undefined'){
         id = jQuery(this).attr('identifier');
    }
    else if(jQuery(this).attr('id') != '' && typeof jQuery(this).attr('id') != 'undefined') id = jQuery(this).attr('id');

    // check if marked as required
    required = jQuery(this).attr('aria-required');
    if(required !== 'true' && required !== 'required') required = jQuery(this).attr('required');
    

    // add required markings to fieldsets
    if((jQuery(this).attr("type") === 'radio' || jQuery(this).attr("type") === 'checkbox') && jQuery(this).closest('fieldset').html() !== undefined){
   
      var text = jQuery(this).closest('fieldset').text();


// only replace fieldset text if it is the same the value of data-fieldname
var legend = jQuery(this).closest('fieldset').find('legend').text().replace('*','').trim();

if(legend === jQuery(this).attr('data-fieldname').trim()){

      if(text.slice(-8).toLowerCase() !== 'required' && jQuery(this).closest('fieldset').html().search(/requiredmarkings/i) < 0){
        var requiredmarkings = '<span class="requiredmarkings">*'; 
    
      if(required !== 'true' && required !== 'required'){
        requiredmarkings += '<span class="screen-reader-text">Required</span>'; 
      }

      if(typeof jQuery(this).attr('maxlength') != 'undefined' && jQuery(this).attr('maxlength') !== '') requiredmarkings += ' <span class="screen-reader-text">max length '+jQuery(this).attr('maxlength')+'</span>';

      requiredmarkings += '</span>'; 
    
      jQuery(this).closest('fieldset').find('legend').text(jQuery(this).attr('data-fieldname'));

      if(jQuery(this).closest('fieldset').find('legend').html().indexOf('*') > 0) {
      jQuery(this).closest('fieldset').find('legend').text(jQuery(this).closest('fieldset').find('legend').text().replace('*',''));
  
      }
  
        jQuery(this).closest('fieldset').find('legend').append(requiredmarkings);
        jQuery(this).closest('fieldset').find('legend').show()
        jQuery(formid+' label:contains("'+jQuery(this).attr('data-fieldname')+'")').hide();
    }
  
    } // end check for matching text
      
    }  
      // add required markings to parent label
       else if(jQuery(this).closest('label').html() !== undefined){
      var text = jQuery(this).closest('label').text();
  
      if(text.slice(-8).toLowerCase() !== 'required'){
        var requiredmarkings = '<span class="requiredmarkings">*'; 
    
      if(required !== 'true' && required !== 'required'){
        requiredmarkings += '<span class="screen-reader-text">Required</span>'; 
      }

      if(typeof jQuery(this).attr('maxlength') != 'undefined' && jQuery(this).attr('maxlength') !== '') requiredmarkings += ' <span class="screen-reader-text">max length '+jQuery(this).attr('maxlength')+'</span>';
      
      requiredmarkings += '</span>'; 

      if(text.indexOf('*') > 0) jQuery(this).closest('label').html(jQuery(this).closest('label').html().replace('*','')).append(requiredmarkings);
      else jQuery(this).closest('label').append(requiredmarkings);
      }

      }  

    // if element includes an aria-label add required to it
    else if(fieldname != '' && typeof jQuery(formid+' [name="'+fieldname+'"]').attr('aria-label') !== 'undefined'){
      var text = jQuery(formid+' [name="'+fieldname+'"]').attr('aria-label');
      
      var requiredmarkings = '<span class="requiredmarkings">*</span>'; 

      if(typeof jQuery(this).attr('maxlength') != 'undefined' && jQuery(this).attr('maxlength') !== '') 
      jQuery(formid+' [name="'+fieldname+'"]').attr('aria-label',jQuery('[name="'+fieldname+'"]').attr('aria-label')+' max length '+jQuery(this).attr('maxlength'));

      if(text.slice(-8).toLowerCase() !== 'required'){
        jQuery(formid+' [name="'+fieldname+'"]').after(requiredmarkings);
      jQuery(formid+' [name="'+fieldname+'"]').attr('aria-label',jQuery('[name="'+fieldname+'"]').attr('aria-label')+' Required');
 
    }

    }   
   // else add it to whatever element has the id value as its attribute 
    else if(id != ''){
   
    var text = jQuery(formid+' [for='+id+']').text();
    if(text.indexOf('*') > 0) jQuery(formid+' [for='+id+']').text(text.replace('*',''));
    if(text.slice(-8).toLowerCase() !== 'required'){
      var requiredmarkings = '<span class="requiredmarkings">*'; 
     
    if(required !== 'true' && required !== 'required'){
      requiredmarkings += '<span class="screen-reader-text">Required</span>'; 
    }

    if(typeof jQuery(this).attr('maxlength') != 'undefined' && jQuery(this).attr('maxlength') !== '') requiredmarkings += ' <span class="screen-reader-text">max length '+jQuery(this).attr('maxlength')+'</span>';

    requiredmarkings += '</span>';
  
    jQuery(formid+' [for='+id+']').append(requiredmarkings);
  }
  }
    
    if(jQuery('p[class=requiredmarkings]').length == 0) {
    jQuery("form.jquery_form_validation_form").prepend('<p class="requiredmarkings">Items marked with an asterisk "*" are required.</p>');
    }

    });
  }
        // remove multiple line breaks
        //jQuery(formid).html(jQuery(formid).html().replace(/(<br>\s*<br>)+/gm,''));
  });
  }, 400);
  
    
      // format phone number field
    jQuery(document.body).on('keyup','form.jquery_form_validation_form .phonevalidator', function() {

    var number = jQuery(this).val();
  
    if(number.length < 4) number = number.replace(/(\d{3})/, "($1)");
    else if(number.length > 4) number = number.replace(/(\(\d{3}\))(\d{3})(\d{4})/, "$1 $2-$3");
    jQuery(this).val(number)
    });
    
      // move focus to error field and display error message if defined
    if(typeof validation_error_id !== 'undefined'){
    jQuery('#'+validation_error_id).focus(); 
    
    setTimeout(function(){      
    jQuery('#'+validation_error_id).blur();
    
    jQuery('#'+validation_error_id).focus(); 
    }, 800);
      }
        
    
  
  // validate all fields after submit button is pressed
  jQuery("body").on("click", ".jquery_form_validation_form_submit", function(event) { 
    jquery_form_validation_form_validation_error = 0;
     event.preventDefault();
    var formname = jQuery(this).data('formname');
  
    jQuery('form[name='+formname+'].jquery_form_validation_form .formfield').each(function(index){

     if(jQuery(this).attr('type') === 'file') 
     jquery_form_validation_validate_file_fields(jQuery(this));
  
     else
    jquery_form_validation_validate_form_fields(jQuery(this));
    
    
    });

    // if there are no errors submit
    if(jquery_form_validation_form_validation_error === 0) {
        jQuery("form[name="+formname+"]").submit();
      } else {
        // move focus to the first invalid field
        jQuery("form[name="+formname+"] [aria-invalid=true]").first().focus();
        // hide contact form 7 errors
        jQuery("form[name="+formname+"] span[class=wpcf7-not-valid-tip]").hide();
      }
  
  });
  
  // validate file attachment fields
  jQuery("body").on(".jquery_form_validation_form blur change", "input[type=file]", function() { 
  jquery_form_validation_validate_file_fields(jQuery(this));
  
  });
  
  // validate all other field types
  jQuery("body").on("blur", ".jquery_form_validation_form .formfield", function() { 
    
  // if element is a checkbox inside of a fieldset only validate the last field
  if(jQuery(this).closest('fieldset').html() !== undefined && jQuery(this).attr('type').toLowerCase() === 'checkbox'){
    if(jQuery(this).closest('fieldset').find('input[type=checkbox]:last').is(this) 
    //|| jQuery(this).closest('fieldset').find('input[type=checkbox]:last').is(this)
    )
    jquery_form_validation_validate_form_fields(jQuery(this));
    }
    // validate all others everytime
   else jquery_form_validation_validate_form_fields(jQuery(this)); 
  
  });

    // validate maxlength attributes
    jQuery("body").on("keyup", ".jquery_form_validation_form .formfield", function() { 
  
      jquery_form_validation_validate_form_field_length(jQuery(this));
    
    });

     // validate form field lengths
  function jquery_form_validation_validate_form_field_length(formfield){
  
    var error1 = '';
    var error2 = ''; 
  
     var maxlength= jQuery(formfield).attr('maxlength');

    if(maxlength === '' || maxlength === undefined) return;
    var errorfound = 0;
    var fieldname= jQuery(formfield).attr('data-fieldname');
    var fieldtype = jQuery(formfield).attr('type');
    var inputname= jQuery(formfield).attr('name');
    
  
    // process text fields
    if(fieldtype === 'text' || fieldtype === 'textarea' || fieldtype === 'password'){  
    if(jQuery(formfield).val().length >= maxlength) {
    error1 = 'Max length for "'+fieldname+'" reached.';
    error2 = 'No more characters are allowed in '+fieldname+'.';
    errorfound = 1;
    }

    if(errorfound === 1){
    
      if(jQuery('.'+inputname.replace(".","_")+'Error').length == 0) {
        jquery_form_validation_place_error_notice(formfield, inputname);
      }
      
      setTimeout(() => {
      if(! jQuery('.'+inputname.replace(".","_")+'Error').hasClass('setnext')){
      jQuery('.'+inputname.replace(".","_")+'Error').html(error1);
      jQuery('.'+inputname.replace(".","_")+'Error').addClass('setnext');
      }
      else{
      jQuery('.'+inputname.replace(".","_")+'Error').html(error2);
      jQuery('.'+inputname.replace(".","_")+'Error').removeClass('setnext');
      }
      jQuery(formfield).attr('aria-invalid','true');
      }, 100);
      }
      else {
        jQuery('.'+inputname.replace(".","_")+'Error').html('');
        jQuery(formfield).attr('aria-invalid','false');
      }

    }
    }
  
  
   // validate file attachment fields
      function jquery_form_validation_validate_file_fields(filefield){
  var error1 = '';
  var error2 = ''; 
  
  if(!jQuery(filefield).hasClass('formfield'))  return;
  
  var lg = jQuery(filefield)[0].files.length; // get length
  
  var items = jQuery(filefield)[0].files;
  var errorfound = 0;
  var inputname= jQuery(filefield).attr('name');
  
  var fieldname= jQuery(filefield).attr('data-fieldname');
  
  var validfiletype = 0;
  
  var filetypes = 'application/pdf'; // default pdf
  if(jQuery(filefield).attr('data-filetypes') !== 'undefined')
  filetypes = jQuery(filefield).attr('data-filetypes').split(',');
  
  var maxuploadsize = '2000000'; // default 2MB
  if(jQuery(filefield).attr('data-maxuploadsize') !== 'undefined')
  maxuploadsize= jQuery(filefield).attr('data-maxuploadsize');
  
  
  
  if(jQuery(filefield).hasClass('required') && lg < 1){
  errorfound = 1;
  error1 = 'Before continuing, select a file for "'+fieldname+'".';
  error2 = 'Please select a file for "'+fieldname+'" before continuing.';
  }
  else if (lg > 0) {
  
  for (var i = 0; i < lg; i++) {
  validfiletype = 0;
  
  if(items[i].size > maxuploadsize && errorfound ===0) {
  errorfound = 1;
  error1 = 'Before continuing, please select a file that does not exceed 10MB for "'+fieldname+'".';
  error2 = 'Please select a file that does not exceed 10MB for "'+fieldname+'" before continuing.';
  
  }
  
  jQuery.each(filetypes, function( key, val ) {
  if(items[i].type === val && errorfound ===0){
  validfiletype = 1;
  }
  });
  
  if(validfiletype === 0){
  errorfound = 1;
  
  error1 = 'Before continuing, please select a supported file type for "'+fieldname+'".';
  error2 = 'Please select a supported file type for "'+fieldname+'" before continuing.';
  }
  
  }
  }
  
// check for array
if(inputname.indexOf("[]") > 0) {
inputname = inputname.replace('[]', '');
}

  if(jQuery('.'+inputname.replace(".","_")+'Error').length == 0) {
    jquery_form_validation_place_error_notice(filefield, inputname);
  }
  
  if(errorfound === 0 && lg > 0){
  error1='File attached';
  error2='File attached';
  }
  
  setTimeout(() => {
  if(! jQuery('.'+inputname.replace(".","_")+'Error').hasClass('setnext')){
  jQuery('.'+inputname.replace(".","_")+'Error').html(error1);
  jQuery('.'+inputname.replace(".","_")+'Error').addClass('setnext');
  }
  else{
  jQuery('.'+inputname.replace(".","_")+'Error').html(error2);
  jQuery('.'+inputname.replace(".","_")+'Error').removeClass('setnext');
  }
  if(error1==='File attached')
  jQuery(filefield).attr('aria-invalid','false');
  else  jQuery(filefield).attr('aria-invalid','true');
  }, 100);
  
  // if error found don't submit form
  if(errorfound === 1) jquery_form_validation_form_validation_error = 1;
  }
  
    
  
    
    // validate form fields
  function jquery_form_validation_validate_form_fields(formfield){
  
    var error1 = '';
    var error2 = ''; 
    var formid = '#'+jQuery(formfield).closest('.jquery_form_validation_form').attr('id');

    var format= jQuery(formfield).attr('data-format');
    var tagname = jQuery(formfield).prop("tagName");
    var id= jQuery(formfield).attr('id');
    if(!jQuery(formfield).hasClass('duet-date__input')){
      var inputname= jQuery(formfield).attr('name');
    }else var inputname= jQuery(formfield).attr('name');
    var errorfound = 0;
    var inputarray = '';
  
    if(inputname.indexOf("[]") > 0) {
      inputarray = inputname;
      inputname = inputname.replace('[]', '');
    }
  
  
    var fieldname= jQuery(formfield).attr('data-fieldname');
    var fieldtype = jQuery(formfield).attr('type');
    var regularex = new RegExp("^([0-9a-zA-Z-\\s\/_|=~`+,.:;!\"\\?@#$%^&*()\\u9999'])+$");
    var fieldvalue = '';

    // remove special characters
    fieldname = fieldname.replace(/\*|:/g,'');
    
    // ignore file fields
    if(fieldtype === 'file') return;
    
    var requiredif =  jquery_form_validation_enforce_requiredif(jQuery(formfield).attr('data-requiredif'),formid);
  
    // process text fields
    if(tagname === 'INPUT' || tagname === 'SELECT' || tagname === 'TEXTAREA' || tagname === 'DUET-DATE-PICKER'){ 
      
  
    if(format === 'alpha-puncuation-space') {
    regularex = new RegExp("^([a-zA-Z-\\s\/_|=~`+,.:;!\"\\?@#$%^&*()\\u9999'\\u00AE])+$");
    error1 = 'Before continuing, please enter "'+fieldname+'" using only letters, punctuation or spaces.';
    error2 = 'Please enter "'+fieldname+'" with only letters, punctuation or spaces before continuing.';
    }
    else if(format === 'alnum-puncuation-space') {
    regularex = new RegExp("^([0-9a-zA-Z-\\s\/_|=~`+,.:;!\"\\?@#$%^&*()\\u9999'\\u00AE])+$");
    error1 = 'Before continuing, please enter "'+fieldname+'" using only letters, numbers, punctuation or spaces.';
    error2 = 'Please enter "'+fieldname+'" with only letters, numbers, punctuation or spaces before continuing.';
    }
    else if(format === 'phone') {
    regularex = new RegExp("(^(([0-9]{1,3}\\s)?\\([0-9]{1,3}\\)\\s?[0-9]{3}-[0-9]{4})$|^(\\+?([0-9]{1,3}-?\\s?)?[0-9]{3}-[0-9]{3}-[0-9]{4})$|^([0-9]{10,13})$)");
    error1 = 'Before continuing, please enter a 10 digit phone number for "'+fieldname+'", no spaces, dashes or parenthesis.';
    error2 = 'Please enter a 10 digit phone number for "'+fieldname+'" before continuing, no spaces, dashes or parenthesis.';
    }
    else if(format === 'email') {
    regularex = new RegExp("^([0-9a-zA-Z]|_|\.|-|')+@([0-9a-zA-Z]|\.|-)+(\.)([a-zA-Z]{2,63})$");
    error1 = 'Before continuing, please enter a valid email address for "'+fieldname+'".';
    error2 = 'Please enter a valid email address for "'+fieldname+'" before continuing.';
    }
    else if(format === 'url') {
      regularex = new RegExp("^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$");
      error1 = 'Before continuing, please enter a valid url for "'+fieldname+'".';
      error2 = 'Please enter a valid url for "'+fieldname+'" before continuing.';
      }
    else if(format === 'numeric') {
      regularex = new RegExp("^([0-9,\.])+$");
    error1 = 'Before continuing, please enter a value for "'+fieldname+'" using only numbers. ';
    error2 = 'Please enter a value for "'+fieldname+'" using only numbers, before continuing.';
    }
    else if(format === 'alpha') {
      regularex = new RegExp("^([a-zA-Z])+$");
      error1 = 'Before continuing, please enter a value for "'+fieldname+'" using only letters. ';
      error2 = 'Please enter a value for "'+fieldname+'" using only letters, before continuing.';
      }
    else if(format === 'alnum') {
    regularex = new RegExp("^([a-zA-Z0-9])+$");
    error1 = 'Before continuing, please enter a value for "'+fieldname+'" using only letters and numbers. ';
    error2 = 'Please enter a value for "'+fieldname+'" using only letters and numbers, before continuing.';
    }  
    else if(format === 'zip') {
    regularex = new RegExp("^([0-9]{5})(-([0-9]{4}))?$");
    error1 = 'Before continuing, please enter a five digit zip code for "'+fieldname+'". ';
    error2 = 'Please enter a five digit zip code for "'+fieldname+'", before continuing.';
    }
    else if(format === 'date-YYYY-MM-DD') {
      regularex = new RegExp("^(([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}))$");
    error1 = 'Before continuing, please enter a date in the format four digit year dash two digit month dash two digit day for "'+fieldname+'". ';
    error2 = 'Please enter a date in the format four digit year dash two digit month dash two digit day for "'+fieldname+'", before continuing.';
    }
    else if(format === 'date-MM-DD-YYYY') {
      
      regularex = new RegExp("^(([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4}))|(([0-9]{1,2})-([0-9]{1,2})-([0-9]{4}))$");
      error1 = 'Before continuing, please enter a date in the format two digit month slash two digit day slash four digit year for "'+fieldname+'". ';
      error2 = 'Please enter a date in the format two digit month slash two digit day slash four digit year for "'+fieldname+'", before continuing.';
      }
  
    // change error message for radio buttons, selects and checkboxes
    if(fieldtype === 'checkbox' || fieldtype === 'radio' || tagname === 'SELECT'){
      error1 = 'Before continuing, please select a value for "'+fieldname+'". ';
      error2 = 'Please select a value for "'+fieldname+'", before continuing.'; 
    }
    
    // if not an array
    if(inputarray === ''){
    // get value of checkbox and radio fields
    if(fieldtype === 'checkbox' || fieldtype === 'radio'){
    fieldvalue = jQuery(formid+' input[name="'+inputname+'"]:checked').val();
    }
    // get value of all other fields types
    else{
      fieldvalue = jQuery(formfield).val();
    }

    
  
      // set to empty if undefined
      if(fieldvalue===undefined) fieldvalue = '';
    // check for error
    if((requiredif === 1 && !regularex.test(fieldvalue)) || (jQuery(formfield).hasClass('required') && fieldvalue === '') || (!regularex.test(fieldvalue) && fieldvalue !== '')){
       errorfound = 1;
    }
    
    }
    else{ // process array values
      fieldvalue = '';
      
    // get value of multiselect
    if(tagname === 'SELECT'){
      fieldvalue = jQuery(formfield).val();
      }

      jQuery(formid+' input[name="'+inputarray+'"]').each(function(index){
   
        if(jQuery(this).is(":checked")){

          fieldvalue = jQuery(this).val();

    
          // set to empty if undefined
         if(fieldvalue===undefined) fieldvalue = '';
      
        } 
  
    });

        // check for error
        if(errorfound === 0 && ((requiredif === 1 && !regularex.test(fieldvalue)) || (jQuery(formfield).hasClass('required') && fieldvalue === '') || (!regularex.test(fieldvalue) && fieldvalue !== ''))){
 
          errorfound = 1;
        }
    }
  
    // enforce either or fields
    if(jQuery(formfield).attr('data-eitheror') !== undefined && jQuery(formfield).attr('data-eitheror') !== ''){
   var eitheror =  jquery_form_validation_enforce_eitheror(jQuery(formfield).attr('data-eitheror'),formid);
 
    if(jQuery.isArray(eitheror)){
      errorfound = 1;
      error1 = eitheror[0];
      error2 = eitheror[1];
    }else{
      errorfound = 0;
    }
  }
    
    if(errorfound === 1){
    
    if(jQuery('.'+inputname.replace(".","_")+'Error').length == 0) {
      jquery_form_validation_place_error_notice(formfield, inputname);
    }
    
    setTimeout(() => {
    if(! jQuery('.'+inputname.replace(".","_")+'Error').hasClass('setnext')){
    jQuery('.'+inputname.replace(".","_")+'Error').html(error1);
    jQuery('.'+inputname.replace(".","_")+'Error').addClass('setnext');
    }
    else{
    jQuery('.'+inputname.replace(".","_")+'Error').html(error2);
    jQuery('.'+inputname.replace(".","_")+'Error').removeClass('setnext');
    }
    jQuery(formfield).attr('aria-invalid','true');
    }, 100);
    }
    else {
      jQuery('.'+inputname.replace(".","_")+'Error').html('');
      jQuery(formfield).attr('aria-invalid','false');
    }
    } // end input check
  
    // if error found don't submit form
    
    if(errorfound === 1) jquery_form_validation_form_validation_error = 1;
    }
  
  
      // enforce value for either or
      function jquery_form_validation_enforce_eitheror(eitheror, formid){
        var value = '';
        var returnval = 0;

        var fieldnames = ''; 
        const returnarray = [];
  
        if(eitheror !== undefined){
          var fields = eitheror.split(',');
   
      
      jQuery.each(fields, function( i, inputname ) {
  
          // check for array
   // if(inputname.indexOf("[]") > 0) {
      //inputname = inputname.replace('[]', '');
     // }

      fieldnames = jQuery(formid+' [name="'+inputname+'"]:last').attr('data-fieldname')+','+fieldnames;
  
      if(jQuery(formid+' [name="'+inputname+'"]').attr('type') === 'radio' || jQuery(formid+' [name="'+inputname+'"]').attr('type') === 'checkbox'){ 
      value = jQuery(formid+' [name="'+inputname+'"]:checked').val();
      }
      else 
      value = jQuery(formid+' [name="'+inputname+'"]').val();
    
  
      if(value !== '' && value !== undefined) returnval = 1;
      
  
       });
  
      }
    
      if(returnval === 0){
        returnarray[0] = 'Before continuing, please enter a value for one of the following fields "'+fieldnames.replace(/^,|,$/g,'')+'" .';
        returnarray[1] = 'Please enter a value for one of the following '+fieldnames.replace(/^,|,$/g,'')+' before continuing.';
        return returnarray;
      }
      return returnval;
      }
      
    
    
    // enforce required if
    function jquery_form_validation_enforce_requiredif(requiredif,formid){
      var value = '';
      var returnval = 0;
      var inputarray = '';
      var inputname= '';
      var values;
    
    if(requiredif !== undefined && requiredif != ''){
    
    values = requiredif.split('=');
    
    // check for array
    if(values[0].indexOf("[]") > 0) {
      inputarray = values[0];
      inputname = values[0].replace('[]', '');
      }
      else inputname = values[0];
    
    // if not an array
    if(inputarray === ''){
    if(jQuery(formid+' [name='+inputname+']').attr('type') === 'radio' || jQuery(formid+' [name='+inputname+']').attr('type') === 'checkbox'){ 
    value = jQuery(formid+' [name='+inputname+']:checked').val();
    }
    else 
    value = jQuery(formid+' [name='+inputname+']').val();
    
    if(value === values[1]) returnval = 1;
    
    }
    else{ // handle array fields
    
    jQuery(formid+' input[name="'+inputarray+'"]').each(function(index){
    
    if(jQuery(this).is(":checked")){
    
     value = jQuery(this).val();
    
     if(value === values[1]) returnval = 1;
    
    }
    });
    
    }

    }
    return returnval;
    }

    /************************************************************
     *  append error notice based on field type and parent element
     **************************************************************/
    function jquery_form_validation_place_error_notice(formfield, inputname){
      inputname= inputname.replace(".","_");
      if(jQuery(formfield).closest('fieldset').html() !== undefined && (jQuery(formfield).attr('type') === 'radio' || jQuery(formfield).attr('type') === 'checkbox') ) {  
        jQuery(formfield).closest('fieldset').append('<br><span class="'+inputname+'Error formerror"  aria-live="polite"></span>');
        }
    else if(jQuery(formfield).closest('label').html() !== undefined) {  
      jQuery(formfield).closest('label').after('<br><span class="'+inputname+'Error formerror"  aria-live="polite"></span>');
      }
   else if(jQuery(formfield).parent().prop("tagName") === 'P' || jQuery(formfield).parent().prop("tagName") === 'DIV' || jQuery(formfield).parent().prop("tagName") === 'TD' || jQuery(formfield).parent().prop("tagName") === 'SPAN') {  
      jQuery(formfield).parent().append('<br><span class="'+inputname+'Error formerror"  aria-live="polite"></span>');
      }
    else if(jQuery(formfield).parent().prop("tagName") === 'TD') {  
      jQuery(formfield).parent().prepend('<br><span class="'+inputname+'Error formerror"  aria-live="polite"></span>');
      }
    else {
    jQuery(formfield).after('<br><span class="'+inputname+'Error formerror"  aria-live="polite"></span>');
    }
    }
    
    });