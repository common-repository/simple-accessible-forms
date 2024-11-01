=== Simple Accessible Forms ===
Contributors: seshelby
Donate link: 
Tags: forms, accessibility
Requires at least: 3.9.1
Tested up to: 6.6
Requires PHP: 5.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Stable tag: 1.0.11

== Description ==
Easily make any html form accessible using the Simple Accessible Forms Plugin. Forms must be carefully constructed to ensure accessibility.

1. The form element must include an aria-label describing the purpose of the form.
1. In the event of error or during a multiple step entry process, information previously entered must be auto populated or available for the user to select.
1. Each form field must be labeled using the label tag <label> or by using an aria-label attribute.
1. Required fields should be annotated within the field label. This may be done using an asterisk for sited users but the word required should be used for screen reader users. Screen reader text may be hidden off screen using CSS. WordPress has a built in class for this purpose: screen-reader-text.
1. An error notice should be displayed next to fields with invalid entries when the user navigates away from the field.
1. If the form submission results in an error the values previously entered must be repopulated, an error displayed to clearly identify the issue and focus should be taken to the first field resulting in error.

The Simple Accessible Forms Plugin allows you to define form field validation information which will be used to add the necessary features to make the form accessible. It will add form field labels, required field markings, add form field validation and display screen reader friendly error message when a user navigates away from a field, moves focus to the first field in error in the event of a form submission failure.

Upgrade to the Pro version to add:

**[PRO]**  unlimited forms (free version is limited to 2 forms).
**[PRO]**  unlimited form fields (free version is limited to 4 form fields).
**[PRO]**  automatically adds id attributes to all form elements.
**[PRO]**  activates the form field import option to allow you to quickly import and configure the plugin to work with your website forms.
**[PRO]**  adds option to remove "Form accessibility enhanced by Simple Accessible Forms" from forms

[Learn More about the Simple Accessible Forms Plugin](https://www.alumnionlineservices.com/free-plugins/simple-accessible-forms/)

== Installation ==

1. Install via WordPress Dashboard or upload `simple-accessible-forms.zip`;
1. Activate the plugin through the 'Plugins' menu in WordPress;

== Frequently Asked Questions ==

= How do I use Simple Accessible Forms? =

1. Go to the WordPress Plugins tab and choose Add Plugin.
1. Search for Simple Accessible Forms, install and activate the plugin. The plugin will add a new Dashboard tab labeled Simple Accessible Form Builder.
1. Simple Accessible Forms is not a form builder in the traditional sense. Instead it allows you to define your form fields and select the correct validation information. Simple Accessible Forms will work with almost any HTML form.
1. Go to the Simple Accessible Form Builder tab and select Create New Form. 
1. Enter a name, url  and id and Click Save.
1. If you are using the Pro version, click Import form fields and verify or edit the imported form fields as required.
1. If you are using the free version choose Add a form field, enter the required information to define your form fields and choose Save after each form field has been added.
1. Once all your fields have been added, activate the form and go try it out.

= Additional FAQ =

[View additional FAQ](https://www.alumnionlineservices.com/free-plugins/simple-accessible-forms/)

== Changelog ==
= 1.0.11 =
1. added automatic correction of empty option tags
1. corrected undefined array key error when loading form builder

= 1.0.10 =
1. corrected validation issue on multiselect dropdowns

= 1.0.9 =
1. corrected form field names that include a period failing to display error notice
1. corrected forms list not updating after a new form is added

= 1.0.8 =
1. corrected csrf vulnerability

= 1.0.7 =
1. updated numeric validation to allow floating point number
1. stripped * before comparing to legend to allow replacement of legends with an astrisk at the end
1. added check for attachment arrays and replaced brackets [] in inputname to resolve javascript error
1. added support for registered sign in regular expressions
1. corrected unescaped output variables

= 1.0.6 =
1. added maxlength notice to form labels when maxlength attribute is present
1. updated feature to add required markings to fieldsets to ensure that it does not update fieldsets unless the text is the same as that in data-fieldname 
1. limited changing fieldset legends for required markings to radio button and check box inputs
1. changed check for existing required markings to only check the end of the string
1. trim blank space from legend text befor comparing to data-fieldname values

= 1.0.5 =
1. added support for form submit buttons using the button element
1. made changes to allow form names and urls to be changed
1. added autocomplete values for state and city
1. added validation for max length
1. added option to hide Google ReCaptcha version 3 from screen reader users
1. added auto correction of radio and checkbox groups that are missing fieldsets
1. removed existing * before placing required markings
1. corrected form markings being applied on forms that are not active
1. added responseid field to allow post messages to be made accessible by applying an alert role attribute
1. moved focus to first field in error state on submit
1. set to validate only the last element in a checkbox group
1. improved phone validation regular expression 
1. improved accuracy of field name targeting by removing wildcard search

= 1.0.4 =
1. added support for autocomplete attributes on form fields

= 1.0.3 =
1. added check for forms marked with the correct class before validating
1. added aria-label attribute to form element

= 1.0.2 =
1. added aria-invalid attribute when error exists

= 1.0.1 =
1. corrected import option not returning message when pro version is not installed
1. corrected array field names not accepted in form builder

= 1.0 =
1. initial release
