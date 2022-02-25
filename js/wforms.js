// wForms - a javascript extension to web forms.
// v0.99.23 - July 26 2005
// Copyright (c) 2005 C�dric Savarese <pro@4213miles.com>
// This software is licensed under the CC-GNU LGPL <http://creativecommons.org/licenses/LGPL/2.1/>
// Other Contributors: Michael Duff (fullmoondesigns.net)

// Change Log: see http://formassembly.com/blog/wforms-a-javascript-extension-to-web-forms/

// v0.99.2  Fixed the refreshAllState / refreshState bug with multiple checkbox switches targeting the same element.
//			Fixed the checkOneRequired method. GetAttribute('value') didn't work in Firefox (thx to Bill Rafferty)
//			Added a SPAN element to the generated 'Repeat' and 'Delete' links to allow for CSS Image Replacement Technique
// 			Fixed Repeat behavior's 'find insert node' loop to handle server-side generated text-nodes (when populating a repeated group)
//			Changed default validation behavior in Safari (now always validate, but will break if used in conjunction w/ Switch or Paging behavior)
//			Added support for 'required' on a TABLE, TR or TD
//			Fixed Inline Event Handler in the Repeat behavior (for IE)
// Known Problems:
// 		Safari 1.x:  	Validation will run on invisible fields (switched off or w/ paging behavior. cf. checkVisibility())
// 		IE 5.2 Mac: 	Validation Disabled. currentStyle.display returns an empty string in checkVisibility() causing non-visible fields to get validated. 
//						Buggy Rendering of the Repeat behavior 
//						Counter Field of Repeat Behavior not submitted (probably setting the name attribute didn't work)
// 		IE 5.0 PC:		Repeat behavior doesn't work. All field are created as TEXT input ? To be checked again.


function wFORMS() { // wFORMS Class Constructor

	var wu = new wUTILITY();
	var self = this;

	// CSS class name definitions. 
	this.classNamePrefix_switch			= "switch";
	this.className_switchIsOn 			= "swtchIsOn"; // used to keep track of the switch state on buttons and links (where the checked attribute is not available) (added in v0.97)
	this.classNamePrefix_offState		= "offstate";
	this.classNamePrefix_onState		= "onstate";
	this.className_repeat 				= "repeat";
	this.className_delete 				= "removeable";
	this.className_required 			= "required";
	this.className_validationError_msg 	= "errMsg";		 
	this.className_validationError_fld	= "errFld";  
	this.classNamePrefix_validation 	= "validate";
	this.className_duplicateLink 		= "duplicateLink";
	this.className_removeLink 			= "removeLink";
	this.className_activeFieldHint 		= "field-hint";
	this.className_inactiveFieldHint 	= "field-hint-inactive";
	this.className_paging				= "wfPage";
	this.className_pagingCurrent		= "wfCurrentPage";
	this.className_pagingButtons		= "wfPageButton";
	this.className_hideSubmit			= "wfHideSubmit";
	// id attribute suffixes
	this.idSuffix_fieldHint 			= "-H";
	this.idSuffix_fieldLabel			= "-L";
	this.idSuffix_fieldError			= "-E";
	this.idSuffix_repeatCounter			= "-RC";
	this.idSuffix_duplicateLink			= "-wfDL";				// not fully implemented yet
	this.idPrefix_pageIndex				= "wfPgIndex-";

	// Behavior configuration options
	this.preserveRadioName				= true; 				// if true, Repeat behavior will preserve name attributes for radio input. 
	this.switchScopeRootTag				= "FORM";				// limit the scope of the switch behavior. You may use 'BODY'.	
	this.functionName_formValidation = "this.formValidation";	// Form validation function name. May be overidden if you need to run your own validation routine (but make sure to run formValidation() in it).	
	this.showAlertOnError = true; 								// sets to false to not show the alert when a validation error occurs.
	this.preventSubmissionOnEnter = false; 						// prevents submission when pressing the 'enter' key. Set to true if pagination behavior is used.
	
	// Error messages. This array may be overwritten in a separate js file for localization or customization purpose.
	this.arrErrorMsg = new Array(); 
	this.arrErrorMsg[0] = "Este campo es requerido. "; // required
	this.arrErrorMsg[1] = "The text must use alphabetic characters only (a-z, A-Z). Numbers are not allowed. "; 	// validate_alpha
	this.arrErrorMsg[2] = "This does not appear to be a valid email address.";									// validate_email
	this.arrErrorMsg[3] = "Please enter an integer.";															// validate_integer
	this.arrErrorMsg[4] = "Please enter a float (ex. 1.9).";
	this.arrErrorMsg[5] = "Unsafe password. Your password should be between 4 and 12 characters long and use a combinaison of upper-case and lower-case letters.";
	this.arrErrorMsg[6] = "Please use alpha-numeric characters only [a-z 0-9].";
	this.arrErrorMsg[7] = "This does not appear to be a valid date.";
	this.arrErrorMsg[8] = "%% error(s) detected. Your form has not been submitted yet.\nPlease check the information you provided."; // %% will be replaced by the actual number of errors.
	
	// Other Messages
	this.arrMsg = new Array();
	this.arrMsg[0] = "Add a row"; 	// repeat link
	this.arrMsg[1] = "Repeats the preceding field or field group." // title attribute on the repeat link 
	this.arrMsg[2] = "Remove"; 		// remove link
	this.arrMsg[3] = "Removes the preceding field or field group." // title attribute on the remove link
	this.arrMsg[4] = "Next Page";
	this.arrMsg[5] = "Previous Page";	
	this.utilities = wu;	
	

	// =======================================================================================
	// Switch Behavior Methods
	// =======================================================================================

	// Privileged Instance Methods
	//----------------------------
	this.refreshAllStates = function(fId) {
		wu.debug('refreshAll:'+ fId);
		var f=document.getElementById(fId);
		if(!f) return;
		// loop through the fields
		var x = wu.getElements(f);
		for (var i=0;i<x.length;i++) {
			// add switch/state behavior
			if (x[i].tagName.toUpperCase() == "SELECT" && wu.isEventHandled(x[i],'change') ) {				
				this.refreshState(null,x[i]);
			}
			if (x[i].className && x[i].className.indexOf(this.classNamePrefix_switch) != -1) {			
				switch(x[i].tagName.toUpperCase()) {
					case "OPTION":
						break;
					default:
						this.refreshState(null,x[i], true); // true= We only set the target on (not off)
						break;
				}
			}
		}
	}
	
	this.refreshState = function(e) {
			
		var onStateOnly = false; // this applies when the refreshState runs as part of the refreshAllStates function.

		if(!e && arguments.length>1) {
			var srcE = arguments[1];
			if(arguments.length>2) 
				onStateOnly = arguments[2];
		}			
		else
			var srcE = wu.getSrcElement(e);

		
		switch(srcE.tagName.toUpperCase()) {
			case "SELECT":
				
				var selectedStateClass="";
				var localScope = switchScope(srcE);
				for(var i=0;i<srcE.options.length;i++) {
					if(srcE.options[i].className.indexOf(self.classNamePrefix_switch) != -1 ) {
						var s = srcE.options[i].className;
						s = s.substr(s.indexOf(self.classNamePrefix_switch)).split(" ")[0].substr(self.classNamePrefix_switch.length);
						var offStateClass = self.classNamePrefix_offState + s;
						var onStateClass = self.classNamePrefix_onState + s;				
						if(i==srcE.selectedIndex) {					
							switchState(localScope, offStateClass, onStateClass);
							selectedStateClass = onStateClass; // prevents further switching off 
						}
						else if(onStateClass != selectedStateClass) {
							switchState(localScope, onStateClass, offStateClass);
						}
					}			
				}
				break;
			case "INPUT":	
				if(srcE.type.toLowerCase() == 'radio') {
					// Go through the radio group.
					for(var i=0;i <srcE.form[srcE.name].length;i++) { 
						var r = srcE.form[srcE.name][i];
						if(r.className && r.className.indexOf(self.classNamePrefix_switch)!=-1) {
							var s = r.className;
							s = s.substr(s.indexOf(self.classNamePrefix_switch)).split(" ")[0].substr(self.classNamePrefix_switch.length);
							var offStateClass = self.classNamePrefix_offState + s;
							var onStateClass = self.classNamePrefix_onState + s;	

							if(r.checked) 
								switchState(switchScope(r), offStateClass, onStateClass);
							else {								
								switchState(switchScope(r), onStateClass, offStateClass); 
							}						
						}
					}
				} else {
					var s = srcE.className;
					s = s.substr(s.indexOf(self.classNamePrefix_switch)).split(" ")[0].substr(self.classNamePrefix_switch.length);
					var offStateClass = self.classNamePrefix_offState + s;
					var onStateClass = self.classNamePrefix_onState + s;	

					if(srcE.checked || 
					  (srcE.type.toLowerCase() == 'button' && srcE != arguments[1] && srcE.className.indexOf(self.className_switchIsOn) == -1 )) { // && !srcE.defaultChecked
						switchState(switchScope(srcE), offStateClass, onStateClass);
						if(srcE.type.toLowerCase() == 'button') {
							srcE.className += " " + self.className_switchIsOn;
						}
					}
					else if(!onStateOnly) {							
						switchState(switchScope(srcE), onStateClass, offStateClass); 
						if(srcE.type.toLowerCase() == 'button' && srcE != arguments[1]) {
							srcE.className = srcE.className.replace(self.className_switchIsOn,"");
						}
					}
				}
				break;
			default: 
				var s = srcE.className;
				s = s.substr(s.indexOf(self.classNamePrefix_switch)).split(" ")[0].substr(self.classNamePrefix_switch.length);
				var offStateClass = self.classNamePrefix_offState + s;
				var onStateClass = self.classNamePrefix_onState + s;	
				if(srcE != arguments[1] && srcE.className.indexOf(self.className_switchIsOn) == -1) { 
					switchState(switchScope(srcE), offStateClass, onStateClass);
					srcE.className += " " + self.className_switchIsOn;
				}
				else if(srcE != arguments[1]) {
					switchState(switchScope(srcE), onStateClass, offStateClass); 
					srcE.className = srcE.className.replace(self.className_switchIsOn,"");
				}
				break;
		}	
	}	
	
	// Switch Behavior Private Methods
	// -------------------------------

	// The switch scope limits the element tree on which the switch can operate.
	// Because of interference issue, a SWITCH contained in a REPEATed block
	// should not be allowed to operate outside of it.
	function switchScope(n) {
		while(n) {
			 if (n.className && ( (' '+n.className+' ').indexOf(' '+self.className_repeat+' ') != -1 || (' '+n.className+' ').indexOf(' '+self.className_delete+' ') != -1)) 
				return n;
			 if (n.tagName.toUpperCase() == self.switchScopeRootTag)
				return n;
			 n = n.parentNode;
		}
		return null; // should not happen. A form should exists.
	}
	
	// Recursive loop within the scope to switch classes
	function switchState(n, oldStateClass, newStateClass) {		
		if(n.nodeType != 1) return;
		if(n.className && n.className.indexOf(oldStateClass) != -1) {  		
			n.className = n.className.replace(oldStateClass, newStateClass);
			// FAT support
			// n.id not set.
			// if(Fat && n.id) Fat.fade_element(n.id, 10, 600, "#FFFFCC", "#FFFFFF");	
		}
		for (var i=0;i<n.childNodes.length;i++) 
			switchState(n.childNodes[i], oldStateClass, newStateClass);
	}

	// =======================================================================================
	// Repeat/Remove Behavior Methods
	// =======================================================================================

	// -------------------
	// Priviledged Methods
	// -------------------
	this.duplicateFieldGroup = function(e) {
		var srcE = wu.getSrcElement(e);
		// Get Element to duplicate.
		var sourceNode = srcE.parentNode;
		while (sourceNode && (!sourceNode.className || (' '+sourceNode.className+' ').indexOf(' '+self.className_repeat+' ') == -1)) {
			sourceNode = sourceNode.parentNode;
		}	
		if (sourceNode && sourceNode.className.indexOf(self.className_repeat) != -1) {
			// Extract row counter information
			counterField = document.getElementById(sourceNode.id + self.idSuffix_repeatCounter);
			if(!counterField) return; // should not happen.
			var rowCount = parseInt(counterField.value) + 1;
			// Prepare id suffix
			var suffix = "-" + rowCount.toString()
			// duplicate node tree 
			var dupTree = replicateTree(sourceNode, null, suffix);  //  sourceNode.cloneNode(true); 
			// find insert point in DOM tree (after existing repeated element)
			var insertNode = sourceNode.nextSibling;
			while(insertNode && 
				  (insertNode.nodeType==3 ||       // skip text-node that can be generated server-side when populating a previously repeated group 
				   ( insertNode.nodeType==1 &&     
					 insertNode.className && 
					 insertNode.className.indexOf(self.className_delete) != -1))) {
				insertNode = insertNode.nextSibling;
			}
					
			sourceNode.parentNode.insertBefore(dupTree,insertNode);	 // Buggy rendering in IE5/Mac
			// if(navigator.appVersion.indexOf("MSIE") != -1 && navigator.appVersion.indexOf("Windows") == -1)			
			//
			
			// the copy is not duplicable, it's removeable
			dupTree.className = sourceNode.className.replace(self.className_repeat,self.className_delete);
			// Save new row count 			
			document.getElementById(sourceNode.id + self.idSuffix_repeatCounter).value = rowCount;
			// re-add behaviors
			if(!dupTree.id) dupTree.id = wu.randomId() + suffix;  //  createAttribute()
			wu.debug('Duplicated tree id : '+dupTree.id);
			self.addBehaviors(dupTree.id);
			
			// FAT support
			if(typeof Fat != 'undefined' && Fat && dupTree.id) Fat.fade_element(dupTree.id, 10, 600, "#FFFFCC", "#FFFFFF");	

		}
		return wu.XBrowserPreventEventDefault(e);
	}
	this.removeFieldGroup = function(e) {
		var srcE = wu.getSrcElement(e);	// Get Element to remove.
		var delNode = srcE.parentNode;

		while (delNode && (' '+delNode.className+' ').indexOf(' '+self.className_delete+' ') == -1) {
			delNode = delNode.parentNode;
		}
		delNode.parentNode.removeChild(delNode);
		return wu.XBrowserPreventEventDefault(e);
	}

	// Repeat Behavior Private Methods
	// -------------------------------
	function removeRepeatCountSuffix(str) {
		return str.replace(/-\d$/,'');
	}
	
	function replicateTree(srcNode,dupParentNode, idSuffix) {
		switch(srcNode.nodeType) {
			case 1:	// ELEMENT-NODE
				if(srcNode.className.indexOf(self.className_duplicateLink) != -1 ||
					srcNode.className.indexOf(self.className_removeLink) != -1  ) 							
					return null; // Exclude the 'duplicate/remove' links
				if(srcNode.className.indexOf(self.className_delete) != -1) { 							
					return null; // Exclude duplicated elements of a nested repeat group
				}				 
				if(srcNode.className.indexOf(self.className_repeat) != -1 && dupParentNode!=null) { 
					// Match nested repeat group only
					idSuffix = idSuffix.replace('-','__');
				}
				   
				if(document.all && !window.opera) { 
					// IE Specific : see http://msdn.microsoft.com/workshop/author/dhtml/reference/properties/name_2.asp
					var tagHtml = srcNode.tagName;
					
					if(srcNode.name) 					
						if (srcNode.tagName.toUpperCase()=="INPUT" && srcNode.type.toLowerCase()=="radio" && self.preserveRadioName)
							tagHtml += " NAME='" + srcNode.name + "' ";
						else
							tagHtml += " NAME='" + removeRepeatCountSuffix(srcNode.name) + idSuffix + "' ";
					if(srcNode.type) {
						tagHtml += " TYPE='" + srcNode.type + "' ";
					}
					if(srcNode.selected) 
						tagHtml += " SELECTED='SELECTED' ";
					if(srcNode.checked)
						tagHtml += " CHECKED='CHECKED' ";

					if(navigator.appVersion.indexOf("MSIE") != -1 && navigator.appVersion.indexOf("Windows") == -1) // IE5 Mac
						var newNode = document.createElement(tagHtml);
					else
						var newNode = document.createElement("<" + tagHtml + "></"+ srcNode.tagName + ">"); 
					try { newNode.type = srcNode.type; } catch(e) {}; // nail it down for IE5 ?, breaks in IE6
 					
				}
				else
					var newNode = document.createElement(srcNode.tagName); 
	
				// get attributes										
				for(var i=0; i< srcNode.attributes.length; i++) {
					// Get Attribute Value. Adjust it if necessary.
					if(	srcNode.attributes[i].specified || // in IE, the attributes array contains all attributes in the DTD
						srcNode.attributes[i].nodeName.toLowerCase() == 'value' ) { // attr.specified buggy in IE?  
	
						if(	srcNode.attributes[i].nodeName.toLowerCase() == "id" || 
							srcNode.attributes[i].nodeName.toLowerCase() == "name" ||
							srcNode.attributes[i].nodeName.toLowerCase() == "for") {
														
							if(srcNode.attributes[i].nodeValue.indexOf(self.idSuffix_fieldHint) != -1)  {
								//leave the field hint suffix at the end of the id.
								var value = srcNode.attributes[i].nodeValue;
								value= removeRepeatCountSuffix(value.substr(0,value.indexOf(self.idSuffix_fieldHint))) + idSuffix + self.idSuffix_fieldHint;
							}
							else {
								if (srcNode.tagName.toUpperCase()=="INPUT" && srcNode.getAttribute('type',false).toLowerCase()=="radio" &&
									srcNode.attributes[i].nodeName.toLowerCase() == "name" && self.preserveRadioName) {
									var value = srcNode.attributes[i].nodeValue;						
								}
								else {
									// var value = removeRepeatCountSuffix(srcNode.attributes[i].nodeValue) + idSuffix;
									var value = srcNode.attributes[i].nodeValue + idSuffix;
								}
							}
						} else {
							// Do not copy the value attribute for text/password/file input
							if(srcNode.attributes[i].nodeName.toLowerCase() == "value" &&
							 	srcNode.tagName.toUpperCase()=='INPUT' &&  
								  (srcNode.type.toLowerCase() == 'text' || srcNode.type.toLowerCase() == 'password' || srcNode.type.toLowerCase() == 'file')) 
								var value='';   
							else
								var value = srcNode.attributes[i].nodeValue;
						}
						// Create attribute and assign value
						switch(srcNode.attributes[i].nodeName.toLowerCase()) {
							case "class":
								newNode.className = value; 
								break;
							case "style": // inline style attribute (fix for IE)
								if(srcNode.style && srcNode.style.cssText) newNode.style.cssText = srcNode.style.cssText; 
								break;								
							case "onclick": // inline event handler (fix for IE)
								newNode.onclick = srcNode.onclick;							
								break;							
							case "onchange":							
								newNode.onchange = srcNode.onchange;							
								break;							
							case "onsubmit":
								newNode.onsubmit = srcNode.onsubmit;							
								break;							
							case "onmouseover":							
								newNode.onmouseover = srcNode.onmouseover;							
								break;							
							case "onmouseout":							
								newNode.onmouseout = srcNode.onmouseout;							
								break;							
							case "onmousedown":
								newNode.onmousedown = srcNode.onmousedown;							
								break;							
							case "onmouseup":
								newNode.onmouseup = srcNode.onmouseup;							
								break;							
							case "ondblclick":
								newNode.ondblclick = srcNode.ondblclick;							
								break;							
							case "onkeydown":
								newNode.onkeydown = srcNode.onkeydown;							
								break;							
							case "onkeyup":
								newNode.onkeyup = srcNode.onkeyup;							
								break;							
							case "onblur":
								newNode.onblur = srcNode.onblur;							
								break;							
							case "onfocus":
								newNode.onfocus = srcNode.onfocus;							
								break;
							default:
								newNode.setAttribute(srcNode.attributes[i].name, value, 0);//setAttribute(newNode, srcNode.attributes[i].name, value);
						}
					}
				}				
				break;
			case 3: // TEXT-NODE (do not copy value of textareas)
				if(srcNode.parentNode.tagName.toUpperCase() != 'TEXTAREA')
					var newNode = document.createTextNode(srcNode.data); 
				break;
		}
		if(dupParentNode && newNode) dupParentNode.appendChild(newNode);
		for(var i=0; i<srcNode.childNodes.length;i++) {
			replicateTree(srcNode.childNodes[i],newNode,idSuffix);
		}
		return newNode;
	}
		
	// =======================================================================================
	// Field Hint Behavior Methods
	// =======================================================================================

	this.activateFieldHint = function(e) {
		var srcE = wu.getSrcElement(e);
		var fh = document.getElementById(srcE.id +  self.idSuffix_fieldHint);
		if(!fh && srcE.tagName.toUpperCase()=='INPUT' && srcE.type.toLowerCase() == 'radio') {
			fh = document.getElementById(srcE.name + self.idSuffix_fieldHint);
		}		
		if(fh) fh.className = fh.className.replace(self.className_inactiveFieldHint, self.className_activeFieldHint);
	}
	this.desactivateFieldHint = function(e) {
		var srcE = wu.getSrcElement(e);
		var fh = document.getElementById(srcE.id +  self.idSuffix_fieldHint);
		if(!fh && srcE.tagName.toUpperCase()=='INPUT' && srcE.type.toLowerCase() == 'radio') {
			fh = document.getElementById(srcE.name + self.idSuffix_fieldHint);
		}		
		if(fh) fh.className = fh.className.replace(self.className_activeFieldHint,self.className_inactiveFieldHint);
	}
	
	
	// =======================================================================================
	// Multi-page Forms Management
	// =======================================================================================
	this.pagingNext= function(e) {
		var srcE = wu.getSrcElement(e);
		var curPageDiv = srcE.parentNode;
		var pageIndex = parseInt(curPageDiv.id.replace(/[\D]*/,"")) + 1;
		var pageDiv = document.getElementById(self.idPrefix_pageIndex+pageIndex.toString());
		if(pageDiv) {
			if(eval(self.functionName_formValidation.replace("this","self"))(e)) {
				curPageDiv.className = curPageDiv.className.replace(self.className_pagingCurrent,"");
				pageDiv.className += ' ' + self.className_pagingCurrent;
				// show submit button if the last page of the form is reached
				pageIndex++;
				var anotherpage = document.getElementById(self.idPrefix_pageIndex+pageIndex.toString());			
				if(!anotherpage) {				
					var submitButton = document.getElementById("submit-"+srcE.form.id);
					if(submitButton) submitButton.className = submitButton.className.replace(self.className_hideSubmit,"");
				}				
			}
		}
	}

	this.pagingPrevious = function(e) {
		var srcE = wu.getSrcElement(e);
		var curPageDiv = srcE.parentNode;
		var pageIndex = parseInt(curPageDiv.id.replace(/[\D]*/,"")) - 1;
		var pageDiv = document.getElementById(self.idPrefix_pageIndex+pageIndex.toString());
		if(pageDiv) {
			curPageDiv.className = curPageDiv.className.replace(self.className_pagingCurrent,"");
			pageDiv.className += ' ' + self.className_pagingCurrent;
			// hide submit button if necessary
			var submitButton = document.getElementById("submit-"+srcE.form.id);
			if(submitButton && submitButton.className.indexOf(self.className_hideSubmit)==-1) submitButton.className += ' ' + self.className_hideSubmit;
		}
	}
	
	// =======================================================================================
	// FORM VALIDATION 
	// =======================================================================================
	this.formValidation = function(e) {		

		var srcE = wu.getSrcElement(e);
		
		if(self.preventSubmissionOnEnter) { 
			// prevent form submission if 'enter' key is pressed
			// (doesn't work in Opera. Further tests needed in IE and Safari)
			if(!e) e = window.event;			
			if(srcE.type && srcE.type.toLowerCase()=='text')
				return wu.XBrowserPreventEventDefault(e); // works w/ Firefox.
		}
		
		while (srcE && srcE.tagName.toUpperCase() != 'FORM') {
			srcE = srcE.parentNode;
		}				
		var x = wu.getElements(srcE); //srcE.elements;  
		var nbTotalErrors = 0;
		var isVisible = false;
		
		for (var i=0;i<x.length;i++) {
			var nbErrors = 0;			
			
			if ((' '+x[i].className+' ').indexOf(' '+self.className_required+' ') != -1) {				
				if(wu.checkVisibility(x[i])) {
					isVisible = true;
					var v = true; // is Valid				
					switch(x[i].tagName.toUpperCase()) {
						case "INPUT":
							switch(x[i].getAttribute("type").toUpperCase()) {
								case "CHECKBOX":
									v = x[i].checked; 
									break;
								case "RADIO":
									v = x[i].checked; 
									break;
								default:
									v = !self.isEmpty(x[i].value);
							}
							break;
						case "SELECT":
							v = !self.isEmpty(x[i].options[x[i].selectedIndex].value);
							break;
						case "TEXTAREA":
							v = !self.isEmpty(x[i].value);
							break;
						case "FIELDSET":
							v = checkOneRequired(x[i]);
							break;
						case "DIV":
							v = checkOneRequired(x[i]);
							break;
						case "SPAN":
							v = checkOneRequired(x[i]);
							break;
						case "TABLE":
							v = checkOneRequired(x[i]);
							break;							
						case "TR":
							v = checkOneRequired(x[i]);
							break;
						case "TD":
							v = checkOneRequired(x[i]);							
							break;
					} // end switch
					if(!v) {
						// flag error
						self.showError(x[i],self.arrErrorMsg[0]);
						nbErrors++;
					}  else { // remove required error flag if any.
						var rErrClass = new RegExp(self.className_validationError_fld,"gi");
						x[i].className = x[i].className.replace(rErrClass,"");
						var fe = document.getElementById(x[i].id +  self.idSuffix_fieldError);
						if(fe) fe.parentNode.removeChild(fe);
					} 
				} else { // not visible
				}
			} // end test=required

			// input validation
			if (x[i].className.indexOf(self.classNamePrefix_validation) != -1) {
				if(!isVisible) isVisible = wu.checkVisibility(x[i]);
				if(isVisible) {
					var arrClasses = x[i].className.split(" ");
					for (j=0;j<arrClasses.length;j++) {
						switch(arrClasses[j]) {
							case "validate-alpha":
								if(!self.isAlpha(x[i].value)) {
									self.showError(x[i],self.arrErrorMsg[1]);
									nbErrors++;
								}
								break;
							case "validate-alphanum":
								if(!self.isAlphaNum(x[i].value)) {
									self.showError(x[i],self.arrErrorMsg[6]);
									nbErrors++;
								}
								break;
							case "validate-date":
								if(!self.isDate(x[i].value)) {
									self.showError(x[i],self.arrErrorMsg[7]);
									nbErrors++;
								}
								break;
							case "validate-time":
								/* NOT IMPLEMENTED */
								break;
							case "validate-email":
								if(!self.isEmail(x[i].value)) {
									self.showError(x[i],self.arrErrorMsg[2]);
									nbErrors++;
								}
								break;
							case "validate-integer":
								if(!self.isInteger(x[i].value)) {
									self.showError(x[i],self.arrErrorMsg[3]);
									nbErrors++;
								}					
								break;
							case "validate-float":
								if(!self.isFloat(x[i].value)) {
									self.showError(x[i],self.arrErrorMsg[4]);
									nbErrors++;
								}
								break;
							case "validate-strongpassword": // NOT IMPLEMENTED
								if(!self.isPassword(x[i].value)) {
									self.showError(x[i],self.arrErrorMsg[5]);
									nbErrors++;
								}
								break;
						} // end switch
					} // end for
				}  else { // not visible
				}
			} // end validation check
				
			if(nbErrors>0) {
				nbTotalErrors+= nbErrors;
			} else {
				var rErrClass = new RegExp(self.className_validationError_fld,"gi");
				x[i].className = x[i].className.replace(rErrClass,"");
				var fe = document.getElementById(x[i].id +  self.idSuffix_fieldError);
				if(fe) fe.parentNode.removeChild(fe);
			} 
		}
		if (nbTotalErrors > 0) {
			if(self.showAlertOnError){  self.showAlert(nbTotalErrors); }
			return wu.XBrowserPreventEventDefault(e);
		}
		return true;
	}
	this.isEmpty = function(s) {
		var regexpWhitespace = /^\s+$/;
		return  ((s == null) || (s.length == 0) || regexpWhitespace.test(s));
	}
	this.isAlpha = function(s) {
		var regexpAlphabetic = /^[a-zA-Z]+$/; // Add ' and - ?
		return self.isEmpty(s) || regexpAlphabetic.test(s);
	}
	this.isAlphaNum = function(s) {
		var illegalChars = /\W/;
		return self.isEmpty(s) || !illegalChars.test(s);
	}
	this.isDate = function(s) {
		var testDate = new Date(s);
		return self.isEmpty(s) || !isNaN(testDate);
	}
	this.isEmail = function(s) {
		var regexpEmail = /\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/;
		return self.isEmpty(s) || regexpEmail.test(s);
	}
	this.isInteger =function(s) {
		var regexp = /^[+]?\d+$/;
		return self.isEmpty(s) || regexp.test(s);
	}
	this.isFloat = function(s) {		
		return self.isEmpty(s) || !isNaN(parseFloat(s));
	}
	// NOT IMPLEMENTED
	this.isPassword = function(s) {
		// Matches strong password : at least 1 upper case latter, one lower case letter. 4 characters minimum. 12 max.
		//var regexp = /^(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,12}$/;  // <= breaks in IE5/Mac
		return self.isEmpty(s);
	}
	this.showError = function (n,errorMsg) {		
		if(n.className.indexOf(self.className_validationError_fld)!= -1) {
			return;
		}
		if (!n.id) n.id = wu.randomId(); // we'll need an id here.		
		// Add error flag to the field
		n.className += " " + self.className_validationError_fld;
		// Prepare error message
		var msgNode = document.createTextNode(" " + errorMsg);
		// Find error message placeholder.
		var fe = document.getElementById(n.id +  self.idSuffix_fieldError);
		if(!fe) { // create placeholder.
			fe = document.createElement("div"); 
			fe.setAttribute('id', n.id +  self.idSuffix_fieldError);			
			// attach the error message after the field label if possible
			var fl = document.getElementById(n.id +  self.idSuffix_fieldLabel);
			if(fl)
				fl.parentNode.insertBefore(fe,fl.nextSibling);
			else
				// otherwise, attach it after the field tag.
				n.parentNode.insertBefore(fe,n.nextSibling);
		}
		// Finish the error message.
		fe.appendChild(msgNode);  	
		fe.className += " " + self.className_validationError_msg;
	}
	this.showAlert = function (nbTotalErrors) {
 	   alert(self.arrErrorMsg[8].replace('%%',nbTotalErrors));
	}
	// Validation Private Method
	// -------------------------
	function checkOneRequired(n) {	
		var v=null;
		if(n.nodeType != 1) return false;
		if(n.tagName.toUpperCase() == "INPUT") {
			switch(n.type.toLowerCase()) {
				case "checkbox":
					v = n.checked; 
					break;
				case "radio":
					v = n.checked; 
					break;
				default:
					v = n.value;
			}
		} else v = n.value;
		if(v && !self.isEmpty(v)) {
			return true;
		}
		for(var i=0; i<n.childNodes.length;i++) {
			if(checkOneRequired(n.childNodes[i])) return true;
		}
		return false;
	}

}

//------------------------------------------------------------------------------------------------------------------------
// WFORMS Public Methods
//------------------------------------------------------------------------------------------------------------------------
wFORMS.prototype.onLoadHandler = function() {		
	for (var i=0;i<document.forms.length;i++) {
		if(!document.forms[i].id) document.forms[i].id = this.utilities.randomId();
		this.addBehaviors(document.forms[i].id);
	}
}
wFORMS.prototype.addBehaviors = function (fId) {
	var f=document.getElementById(fId);
	if(!f) return;

	var thisForm; 				// Pointer to keep track of the current form being processed.
	var wu = this.utilities;	// Utiltiy class instance
	wu.resetEventList();
	
	// loop through the fields
	var x = wu.getElements(f);
	
	for (var i=0;i<x.length;i++) {

		// add form validation behavior
		if(x[i].tagName.toUpperCase()=="FORM") {			
			wu.XBrowserAddHandler(x[i],'submit',eval(this.functionName_formValidation));
			thisForm = x[i];	// Pointer to keep track of the current form being processed.
		}
		// add fieldhint behavior
		var fh = document.getElementById(x[i].id + this.idSuffix_fieldHint);			
		if(!fh && x[i].tagName.toUpperCase()=='INPUT' && x[i].type.toLowerCase() == 'radio') {
			fh = document.getElementById(x[i].name + this.idSuffix_fieldHint);
		}		
		if(fh) {		
			wu.XBrowserAddHandler(x[i],'focus',this.activateFieldHint);
			wu.XBrowserAddHandler(x[i],'blur',this.desactivateFieldHint);			
		}
		// add switch/state behavior
		if (x[i].className && x[i].className.indexOf(this.classNamePrefix_switch) != -1) {
			
			switch(x[i].tagName.toUpperCase()) {
				case "OPTION":
					var sel = x[i].parentNode;	// Get to the SELECT
					if(sel.tagName.toUpperCase() == "OPTGROUP") sel = sel.parentNode; // try again.
					if(!wu.isEventHandled(sel,'change')) {
						wu.XBrowserAddHandler(sel,'change',this.refreshState);						
				   	}
					break;
				case "INPUT":
					if(x[i].type && x[i].type.toLowerCase() == 'radio') {
						// Add the onclick event on radio inputs of the same group
						if(!thisForm) thisForm = x[i].form;	
						for (var j=0;j<thisForm[x[i].name].length;j++) {
							if(thisForm[x[i].name][j].type == 'radio') // prevents conflicts with elements with an id = name of radio group
								wu.XBrowserAddHandler(thisForm[x[i].name][j],'click',this.refreshState);
						}
					} else {
						wu.XBrowserAddHandler(x[i],'click',this.refreshState);
					}
					break;
				default:					
					wu.XBrowserAddHandler(x[i],'click',this.refreshState);
					break;
			}
		}

		// add paging behavior
		if (x[i].className && (' '+x[i].className+' ').indexOf(' '+ this.className_paging+' ') != -1) {
			var currentPageIndex = parseInt(x[i].id.replace(/[\D]*/,""));
			if(currentPageIndex > 1) {
				// add previous page button			
				var actionNode = document.createElement("input"); 
				actionNode.setAttribute('value',this.arrMsg[5]);	
				actionNode.setAttribute('type',"button");	
				actionNode.className = this.className_pagingButtons;
				x[i].appendChild(actionNode);
				// Add event handler			
				wu.XBrowserAddHandler(actionNode,'click',this.pagingPrevious);			
			} else {
				// set current page class
				x[i].className += ' ' + this.className_pagingCurrent;
				// hide submit button until the last page of the form is reached
				if(!thisForm) {
					thisForm = x[i].parentNode;
					while(thisForm && thisForm.tagName.toUpperCase() != "FORM")
						thisForm = thisForm.parentNode;
				}
				var submitButton = document.getElementById("submit-"+thisForm.id);
				if(submitButton) submitButton.className = this.className_hideSubmit; 
				// prevent submission of form with enter key.
				this.preventSubmissionOnEnter = true;
			}
			if(document.getElementById(this.idPrefix_pageIndex+(currentPageIndex+1).toString())) {
				// add next page button			
				var actionNode = document.createElement("input"); 
				actionNode.setAttribute('value',this.arrMsg[4]);	
				actionNode.setAttribute('type',"button");	
				actionNode.className = this.className_pagingButtons;
				x[i].appendChild(actionNode);
				// Add event handler			
				wu.XBrowserAddHandler(actionNode,'click',this.pagingNext);			
			}
		}
		
		// add repeat behavior
		if (x[i].className && (' '+x[i].className+' ').indexOf(' '+this.className_repeat+' ') != -1) {
			// this element to be duplicated.
			wu.debug('adding repeat on ' + x[i].id);
			var actionNode = null;
			if(x[i].id) actionNode = document.getElementById(x[i].id + this.idSuffix_duplicateLink);
			if (!actionNode) {				
				// add duplicate action
				actionNode = document.createElement("a"); 
				var spanNode = document.createElement("span");  // For CSS image replacement 
				var textNode = document.createTextNode(this.arrMsg[0]);
				actionNode.setAttribute('href',"#");	
				actionNode.className = this.className_duplicateLink;			
				actionNode.setAttribute('title', this.arrMsg[1]);	
				if(x[i].tagName.toUpperCase()=="TR") {
					// find the last TD
					var n = x[i].lastChild;	
					while(n && n.nodeType != 1)  
						n = n.previousSibling;
					if(n && n.nodeType == 1) 
						n.appendChild(actionNode);
					// Else Couldn't find the TD. Table row malformed ?
				} else
					x[i].appendChild(actionNode);
					
				spanNode.appendChild(textNode); 
				actionNode.appendChild(spanNode); 
			}
			// Add hidden counter field if necessary
			var counterField = document.getElementById(x[i].id + this.idSuffix_repeatCounter);
			if (!counterField) {
				if(document.all && !window.opera) { // IE Specific :-(
					// see http://msdn.microsoft.com/workshop/author/dhtml/reference/properties/name_2.asp
					var counterFieldId = x[i].id + this.idSuffix_repeatCounter;
					if(navigator.appVersion.indexOf("MSIE") != -1 && navigator.appVersion.indexOf("Windows") == -1) // IE5 Mac
						counterField = document.createElement("INPUT NAME=\"" + counterFieldId + "\"");
					else
						counterField = document.createElement("<INPUT NAME=\"" + counterFieldId + "\"></INPUT>"); 					
					counterField.type='hidden';
					counterField.id = counterFieldId; 
					counterField.value = "1";
				}
				else {
					counterField = document.createElement("INPUT"); 
					counterField.setAttribute('type','hidden'); // hidden
					counterField.setAttribute('value','1');
					counterField.setAttribute('name', x[i].id + this.idSuffix_repeatCounter);
					counterField.setAttribute('id', x[i].id + this.idSuffix_repeatCounter); 
				}
				
				if(!thisForm) {
					thisForm = x[i].parentNode;
					while(thisForm && thisForm.tagName.toUpperCase() != "FORM")
						thisForm = thisForm.parentNode;
				}
				
				thisForm.appendChild(counterField);
			}
			// Add event handler			
			wu.XBrowserAddHandler(actionNode,'click',this.duplicateFieldGroup);			
		}	
		// add remove behavior
		if (x[i].className && (' '+x[i].className+' ').indexOf(' '+this.className_delete+' ') != -1) {
			wu.debug('adding remove on ' + x[i].id);
			// this element can be removed
			// add remove action
			var actionNode = document.createElement("a");
			var spanNode = document.createElement("span");  // For CSS image replacement 
			var textNode = document.createTextNode(this.arrMsg[2]);
			actionNode.setAttribute('href',"#");	
			actionNode.className = this.className_removeLink;
			actionNode.setAttribute('title',this.arrMsg[3]);	
			if(x[i].tagName.toUpperCase()=="TR") {
				// find the last TD
				var n = x[i].lastChild;	
				while(n && n.nodeType != 1)  
					n = n.previousSibling;
				if(n && n.nodeType == 1) 
					n.appendChild(actionNode);
				// Else Couldn't find the TD. Table row malformed ?
			} else
				x[i].appendChild(actionNode);
			spanNode.appendChild(textNode); 
			actionNode.appendChild(spanNode); 	
			wu.XBrowserAddHandler(actionNode,'click',this.removeFieldGroup);			
		}	
	 }
	 this.refreshAllStates(fId);
}


// *************************************************************************************************************
// UTILITY CLASS
// *************************************************************************************************************
function wUTILITY() {
	// Event Handler utility list
	this.handlerList = new Array(); 
}

// Cross-Browser event handler management.
// adapted from Andy Smith's (http://weblogs.asp.net/asmith/archive/2003/10/06/30744.aspx)
wUTILITY.prototype.XBrowserAddHandler = function (target,eventName,handlerName) {
	if(!target) return;
	if (target.addEventListener) { 
		target.addEventListener(eventName, function(e){eval(handlerName)(e);}, false);
	} else if (target.attachEvent) { 
		target.attachEvent("on" + eventName, function(e){eval(handlerName)(e);});
		} else { 
		// THIS CODE NOT TESTED 
		var originalHandler = target["on" + eventName]; 
		if (originalHandler) { 
		  target["on" + eventName] = function(e){originalHandler(e);eval(handlerName)(e);}; 
		} else { 
		  target["on" + eventName] = eval(handlerName); 
		} 
	} 
	// Keep track of added handlers.
	var l = this.handlerList.length;
	this.handlerList[l] = new Array(2);
	this.handlerList[l][0] = target.id;  
	this.handlerList[l][1] = eventName;  	
	this.debug("Handler added :" + target.id + ' ' + eventName);
}
// 
wUTILITY.prototype.isEventHandled = function(n, type) {	
	for(var i=0; i < this.handlerList.length; i++) {
		if(this.handlerList[i][0]==n.id && this.handlerList[i][1]==type)
			return true;
	}
	return false;
}
wUTILITY.prototype.resetEventList = function() {
	this.handlerList = new Array(); 
}

// Activating an Alternate Stylesheet (thx to: http://www.howtocreate.co.uk/tutorials/index.php?tut=0&part=27)
// Use this to activate a CSS Stylesheet that shouldn't be used if javascript is turned off.
// The stylesheet rel attribute should be 'alternate stylesheet'. The title attribute should be set.
wUTILITY.prototype.activateStylesheet = function(sheetref) {
	if(document.getElementsByTagName) {
		var ss=document.getElementsByTagName('link');
	} else if (document.styleSheets) {
		var ss = document.styleSheets;
	}
	for(var i=0;ss[i];i++ ) {
		if(ss[i].href.indexOf(sheetref) != -1) {
			ss[i].disabled = true;
			ss[i].disabled = false;			
		}
	}
}
// Generates a random ID
wUTILITY.prototype.randomId = function () {
	var rId = "";
	for (var i=0; i<6;i++)
		rId += String.fromCharCode(97 + Math.floor((Math.random()*24)))
	return rId;
}
// returns all child elements of a node.
wUTILITY.prototype.getElements = function(n, list) {
	if(!list) list = new Array();
	if(n.nodeType==1) {
		list[list.length]= n;
		for(var i=0; i<n.childNodes.length;i++) 
			this.getElements(n.childNodes[i], list);
		return list;
	}
}
// Returns the event's source element 
wUTILITY.prototype.getSrcElement = function(e) {	
	if(!e) 
		e = window.event;	
	if(e.target)
		var srcE = e.target;
	else
		var srcE = e.srcElement;
	if(srcE.nodeType == 3) srcE = srcE.parentNode; // safari weirdness		
	if(srcE.tagName.toUpperCase()=='LABEL') { 
		// when clicking a label, firefox fires the input onclick event
		// but the label remains the source of the event. In Opera and IE 
		// the source of the event is the input element. Which is the 
		// expected behavior, I suppose.		
		if(srcE.getAttribute('for')) {
			srcE = document.getElementById(srcE.getAttribute('for'));
		}
	}
	return srcE;
}
// Cancel the default execution of an event.
wUTILITY.prototype.XBrowserPreventEventDefault = function(e) {
	if(!e) e = window.event;
	if (e.preventDefault) e.preventDefault();
	else e.returnValue = false;
	return false;
}
wUTILITY.prototype.checkVisibility = function(n) {
	// check if any of the element's ancestors is not visible.
	if(window.getComputedStyle) {
		var isVisible = window.getComputedStyle(n,"").getPropertyValue("display").toLowerCase()!="none";
		isVisible = isVisible && window.getComputedStyle(n,"").getPropertyValue("visibility").toLowerCase()!="hidden";
		// add visiblity!=collapse ?
	}
	else if(n.currentStyle) {		
		if(n.currentStyle.display=='') return false; // effectively disable validation on IE5/Mac.
		var isVisible = n.currentStyle.display.toLowerCase() != "none";
		isVisible = isVisible && n.currentStyle.visibility.toLowerCase() !="hidden";
	}
	else {
		return true; 
		// use return false to disable validation if a switch or paging behavior is used.
	}
	if(!n.parentNode) { return false; } ; // should not happen, unless we're checking some removed elements.
	if (n.parentNode.tagName.toUpperCase()=="BODY" || !isVisible)
		return isVisible;
	return this.checkVisibility(n.parentNode);
}
	
wUTILITY.prototype.debug = function(text) { 
	var debugOutput = document.getElementById('debugOutput');  
	if(debugOutput) debugOutput.innerHTML = debugOutput.innerHTML+"<br />"+text; 
}

// =======================================================================================================================
// LET's GO
var wf = new wFORMS();
// Attach JS only stylesheet.
wf.utilities.activateStylesheet('wforms-jsonly.css'); 
// onLoad event handler
wf.utilities.XBrowserAddHandler(window,'load',function() { wf.onLoadHandler();}  );
