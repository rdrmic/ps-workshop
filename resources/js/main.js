function onLoad() {
    setForms();
    styleOverviewsStoragesTableRows();
}




function setForms() {
    function setElementReadOnly(id) { 
        var elem = document.getElementById(id);
        elem.readOnly = true;
        elem.className += " readonly";
    }
    
    var elemForm;
    
    elemForm = document.getElementById("material-id");
    if (elemForm) {
        var isNewEntry = !elemForm.value ? true : false;
        if (!isNewEntry) {
            setElementReadOnly("label-code");
            setElementReadOnly("input-code");
            
            setElementReadOnly("label-name");
            setElementReadOnly("input-name");
            
            setElementReadOnly("label-packaging");
            setElementReadOnly("input-package_quantity");
            setElementReadOnly("input-package_measure_unit");
            
            setElementReadOnly("label-price");
            setElementReadOnly("input-price");
        }
    }
    
    elemForm = document.getElementById("biscuit-id");
    if (elemForm) {
        var isNewEntry = !elemForm.value ? true : false;
        if (isNewEntry) {
            var workPriceInputElem = document.getElementById("work_price");
            workPriceInputElem.onchange = function () {
                toggleDisableSubmit(false);
            }
        } else {
            setElementReadOnly("label-work_price");
            setElementReadOnly("work_price");
            
            setElementReadOnly("label-materials");
        }
    }
    
    elemForm = document.getElementById("incomplete-id");
    if (elemForm) {
        var isNewEntry = !elemForm.value ? true : false;
        if (isNewEntry) {
            var workPriceInputElem = document.getElementById("work_price");
            workPriceInputElem.onchange = function () {
                toggleDisableSubmit(false);
            }
        } else {
            setElementReadOnly("label-work_price");
            setElementReadOnly("work_price");
            
            setElementReadOnly("label-biscuits");
            
            setElementReadOnly("label-materials");
        }
    }
    
    elemForm = document.getElementById("product-id");
    if (elemForm) {
        var isNewEntry = !elemForm.value ? true : false;
        if (isNewEntry) {
            var workPriceInputElem = document.getElementById("work_price");
            workPriceInputElem.onchange = function () {
                toggleDisableSubmit(false);
            }
        } else {
            setElementReadOnly("label-work_price");
            setElementReadOnly("work_price");
            
            setElementReadOnly("label-incompletes");
            
            setElementReadOnly("label-materials");
        }
    }
}



function toggleDisableSubmit(enable) {
    var submitElem = document.getElementById("submit");
    if (enable) {
        submitElem.removeAttribute("disabled");
        submitElem.classList.remove("disabled");
    } else {
        submitElem.setAttribute("disabled", "disabled");
        submitElem.classList.add("disabled");
    }
}



function InputControl(elemClass, ctrlExpr, msg) {
    this.elemClass = "ctrl-" + elemClass;
    this.ctrlExpr = ctrlExpr;
    this.msg = msg;
}

function checkCodeUniqueness(code) {
	//console.debug("checkCodeUniqueness: " + code);
	
	var codesInUseJson = document.getElementById("codes-in-use").value;
    //console.log("### codesInUseJson ###\n" + codesInUseJson);
    
    var codes = JSON.parse(codesInUseJson);
    //console.log("codes.length = " + codes.length);
    
    for (var i = 0; i < codes.length; i++) {
    	if (codes[i].code == code) {
    		return false;
    	}
    }
	return true;
}

var INPUT_CONTROLS = {
    uniqueCode: new InputControl(
        "unique-code",
        "if (#value#) {!checkCodeUniqueness(#value#)} else {false}",
        "#item-type# sa tom šifrom već postoji!"
    ),
    required: new InputControl(
        "required",
        "#value#.length == 0",
        "Obavezan unos!"
    ),
    alphabetic_required: new InputControl(
        "alphabetic-required",
        "!/^[a-z]+$/i.test(#value#)",
        "Unos mora biti slovna oznaka!"
    ),
    integerGreaterThanZero_required: new InputControl(
        "int-gt-zero-required",
        "!/^[1-9][0-9]*$/.test(#value#)",
        "Unos mora biti cijeli broj veći od 0!"
    ),
    floatW3DecimalsGreaterThanZero_required: new InputControl(
        "float-3decimals-gt-zero-required",
        "!/^\\d+(,\\d{1,3})?$/.test(#value#) || #value# == '0,000' || #value# == '0,00' || #value# == '0,0' || #value# == '0'",
        "Unos mora biti decimalni broj veći od 0,000!"
    ),
    currency: new InputControl(
        "currency",
        "!/^(([1-9]\\d{0,2}(\\.\\d{3})*)|0)?(,\\d{1,2})?$/.test(#value#)",
        "Unos mora biti u formatu \"x.xxx,xx\"!"
    )
    /*currency_required: new InputControl(
        "currency-required",
        "!/(?=.*\\d)^(([1-9]\\d{0,2}(\\.\\d{3})*)|0)?(,\\d{1,2})?$/.test(#value#)",
        "Unos mora biti u formatu \"x.xxx,xx\"!"
    )*/
};

function checkInputedValues(form, control, itemType) {
    var elementsToCheck = form.getElementsByClassName(control.elemClass);
    if (elementsToCheck.length == 0) {
        return true;
    }
    
    control.ctrlExpr = control.ctrlExpr.replace(/#value#/g, "elem.value");
    if (control.elemClass == "ctrl-unique-code") {
    	control.msg = control.msg.replace(/#item-type#/g, itemType);
    }
    
    var errCount = 0;
    for (var i = 0; i < elementsToCheck.length; i++) {
        var elem = elementsToCheck[i];
        if (eval(control.ctrlExpr)) {
            errCount++;
            elem.classList.add("err_mark");
        } else {
            elem.classList.remove("err_mark");
        }
    }
    
    var elemErrorMsg = form.getElementsByClassName("err_msg_to_user")[0];
    if (errCount > 0) {
        elemErrorMsg.innerHTML = control.msg;
        return false;
    }
    elemErrorMsg.innerHTML = null;
    return true;
}

function checkBuildingItemsAreSelected(form, buildingTypes) {
    var errCount = 0;
    for (var i = 0; i < buildingTypes.length; i++) {
        var elemSelectsDiv = document.getElementById(buildingTypes[i] + "-selections");
        //console.log("elemSelectsDiv.id = " + elemSelectsDiv.id);
        
        var selectElements = elemSelectsDiv.getElementsByTagName("select");
        //console.log("selectElements.length = " + selectElements.length);
        
        var elemSelectsLabel = document.getElementById("label-" + buildingTypes[i]);
        if (selectElements.length == 0) {
            errCount++;
            elemSelectsLabel.classList.add("err_mark");
        } else {
            var isAllSelectsEmpty = true;
            for (var j = 0; j < selectElements.length; j++) {
                var elemSelect = selectElements[j];
                //console.log("-> elemSelect = " + elemSelect.value);
                if (elemSelect.value != -1) {
                    isAllSelectsEmpty = false;
                    break;
                }
            }
            if (isAllSelectsEmpty) {
                errCount++;
                elemSelectsLabel.classList.add("err_mark");
            }
        }
    }
    
    var elemErrorMsg = form.getElementsByClassName("err_msg_to_user")[0];
    if (errCount > 0) {
        elemErrorMsg.innerHTML = "Nije odabran građevni artikal!";
        return false;
    }
    elemErrorMsg.innerHTML = null;
    return true;
}

function resetErrors(form, elemClasses, selectLabelsToReset) {
    for (var i = 0; i < elemClasses.length; i++) {
        var className = elemClasses[i];
        var elementsToReset = form.getElementsByClassName(className);
        for (var j = 0; j < elementsToReset.length; j++) {
            var elem = elementsToReset[j];
            elem.classList.remove("err_mark");
        }
    }
    
    for (var i = 0; i < selectLabelsToReset.length; i++) {
        var elemLabel = selectLabelsToReset[i];
        elemLabel.classList.remove("err_mark");
    }
    
    var elemErrorMsg = form.getElementsByClassName("err_msg_to_user")[0];
    elemErrorMsg.innerHTML = null;
}


function checkItemDefinitionInputValues(type) {
    var controls = [	// FIXME only controls required for the given type?
    	INPUT_CONTROLS.uniqueCode,
        INPUT_CONTROLS.required,
        INPUT_CONTROLS.integerGreaterThanZero_required,
        INPUT_CONTROLS.floatW3DecimalsGreaterThanZero_required,
        INPUT_CONTROLS.alphabetic_required,
        INPUT_CONTROLS.currency
    ];
    
    var elemClassesToReset = [];
    for (var i = 0; i < controls.length; i++) {
        elemClassesToReset[i] = controls[i].elemClass;
    }
    
    
    var itemType;
    var buildingTypes = [];
    if (type == "biscuits") {
    	itemType  = "Biskvit";
        buildingTypes[0] = "materials";
    } else if (type == "incompletes") {
    	itemType  = "Poluproizvod";
        buildingTypes[0] = "materials";
        buildingTypes[1] = "biscuits";
    } else if (type == "products") {
    	itemType  = "Gotovi proizvod";
        buildingTypes[0] = "materials";
        buildingTypes[1] = "incompletes";
    } else {
    	itemType = "Materijal";
    }
    
    var selectLabelsToReset = [];
    for (var i = 0; i < buildingTypes.length; i++) {
        var elemSelectsDiv = document.getElementById(buildingTypes[i] + "-selections");
        var selectElements = elemSelectsDiv.getElementsByTagName("select");
        var elemSelectsLabel = document.getElementById("label-" + buildingTypes[i]);
        selectLabelsToReset[i] = elemSelectsLabel;
    }
    
    
    var form = document.forms[0];
    
    resetErrors(form, elemClassesToReset, selectLabelsToReset);
    
    for (var i = 0; i < controls.length; i++) {
        if (!checkInputedValues(form, controls[i], itemType)) {
            return false;
        };
    }
    
    return checkBuildingItemsAreSelected(form, buildingTypes);
}

function checkIncreaseQuantityInput() {
    var form = document.forms[0];
    if (!checkInputedValues(form, INPUT_CONTROLS.integerGreaterThanZero_required, null)) {
        return false
    };
    return true;
}




function styleOverviewsStoragesTableRows() {
    var elemTbody = document.getElementById("tbody-overviews-added-items");
    if (elemTbody) {
        var count = 0;
        for (var i = 0; i < elemTbody.childNodes.length; i++) {
            var childNode = elemTbody.childNodes[i];
            if (childNode.nodeType == Node.ELEMENT_NODE) {
                if (childNode.classList && childNode.classList[0] == "countable-rows-js") {
                    childNode.classList.add(++count % 2 == 0 ? "countable-rows-js-even" : "countable-rows-js-odd");
                } else {
                    count = 0;
                }
            }
        }
    }
}




function addBuildingItemSelection(form, type) {
    //console.info("addBuildingItemSelection() --->");
    
    // GET BUILDING ITEMS FROM HIDDEN INPUT
    var itemsHiddenInputId = "selection-" + type;
    var itemsJson = form[itemsHiddenInputId].value;
    //console.log("### itemsJson ###\n" + itemsJson);
    var items = JSON.parse(itemsJson);
    //console.log("buiding items = " + items.length);

    // GET PARENT DIV-ELEMENT
    var parentDivElem = document.getElementById(type + "-selections");
    //console.log("fetched parent div element = " + parentDivElem.id);
    
    // GET ALREADY CREATED SELECT-ELEMENTS
    var selectElements = parentDivElem.getElementsByTagName("select");
    //console.log("already created select elements = " + selectElements.length);
    for (var i = 0; i < selectElements.length; i++) {
        if (selectElements[i].value == "-1") {
            //console.warn("An empty option found, returning...");
            return;
        }
    }
    
    // ASSEMBLE NAME FOR THE SELECT-ELEMENT (e.g. mat-0, inc-3, ...), CREATE IT, AND APPEND IT TO THE PARENT DIV-ELEMENT
    var selectElemName = type.substring(0, 3) + "-" + selectElements.length;
    //console.log("select element name = " + selectElemName);

    var selectElem = document.createElement("select");
    selectElem.name = selectElemName;
    selectElem.className += " field-select";
    parentDivElem.appendChild(selectElem);

    // CREATE INPUT AND LABEL FOR QUANTITY, BUT DON'T APPEND IT TO PARENT DIV-ELEMENT YET (IT IS DONE ON ACTUAL ITEM'S SELECTING)
    var inputUnitQuantity = document.createElement("input");
    inputUnitQuantity.id = selectElemName + "_quantityUsed";
    inputUnitQuantity.name = selectElemName + "_quantityUsed";
    inputUnitQuantity.setAttribute("type", "text");
    inputUnitQuantity.style.marginLeft = "6px";
    inputUnitQuantity.className += " field-middle right-align";
    if (type == "materials") {
    	inputUnitQuantity.className += " ctrl-float-3decimals-gt-zero-required";
        inputUnitQuantity.setAttribute("placeholder", "0,000");
    } else {
    	inputUnitQuantity.className += " ctrl-int-gt-zero-required";
        inputUnitQuantity.setAttribute("placeholder", "0");
    }
    
    var labelUnit = document.createElement("label");
    labelUnit.style.display = "inline";
    labelUnit.style.fontWeight = "normal";

    // CREATE ELEMENTS FOR PRICE, BUT DON'T APPEND IT TO PARENT DIV-ELEMENT YET (IT IS DONE ON ACTUAL ITEM'S SELECTING)
    var price = document.createElement("input");
    price.id = selectElemName + "_calculatedPrice";
    price.name = selectElemName + "_calculatedPrice";
    //price.style.marginLeft = "0px";
    price.className += " field-middle right-align";
    price.setAttribute("type", "text");
    price.setAttribute("placeholder", "0,00");
    price.readOnly = true;
    price.className += " readonly";
    //price.style.backgroundColor = "orange";
    price.style.width = "75%";
    price.style.display = "inline";
    
    var labelKn = document.createElement("label");
    labelKn.textContent = " Kn";
    labelKn.style.display = "inline";
    labelKn.style.fontWeight = "normal";
    /*labelKn.style.verticalAlign = "bottom";*/
    //labelKn.style.width = "50%";
    
    var divPrice = document.createElement("div");
    divPrice.appendChild(price);
    divPrice.appendChild(labelKn);
    divPrice.style.display = "inline";
    divPrice.style.cssFloat = "right";
    divPrice.style.marginLeft = "20px";
    //divPrice.style.backgroundColor = "yellow";
    divPrice.style.width = "130px";
    
    // ADD EMPTY OPTION ("-1") TO SELECT ELEMENT
    var option = document.createElement("option");
    option.value = "-1";
    option.text = "";
    selectElem.appendChild(option);
    
    // ADD BUILDING ITEMS TO SELECT ELEMENT
    for (var i = 0; i < items.length; i++) {
        var item = items[i];
        option = document.createElement("option");
        option.value = item.id;
        option.text = item.code + ", " + item.name;
        if (type == "materials") {
            option.text += " (" + item.price + " Kn / " + item.package_quantity + " " + item.package_measure_unit + ")";
        } else {
            option.text += " (" + item.price + " Kn)";
        }
        selectElem.appendChild(option);
    }
    
    // APPEND LINE-BREAK ELEMENT UNDER THE CREATED SELECT ELEMENT
    var brElement = document.createElement("br");
    parentDivElem.appendChild(brElement);
    
    //console.log("### INIT HTML ###\n" + parentDivElem.innerHTML);
    
    // SET ONCHANGE BEHAVIOUR TO THE CREATED SELECT ELEMENT
    selectElem.onchange = function () {
        /*console.info("#### select-ed value =", this.value);
        console.info("#### -->");
        for (var i = 0; i < this.parentNode.childNodes.length; i++) {
            var materialSelect = selectElements[i];
            console.info("    ", this.parentNode.childNodes[i]);
        }*/
        
        if (this.value == -1) {
            parentDivElem.removeChild(inputUnitQuantity);
            parentDivElem.removeChild(labelUnit);
            parentDivElem.removeChild(divPrice);
            
            toggleDisableSubmit(false);
            return;
        }
        
        var selectedBuildingItem = null;
        for (i in items) {
            if (items[i].id == this.value) {
                selectedBuildingItem = items[i];
                break;
            }
        }
        if (selectedBuildingItem == null) {
            return;
        }

        //console.info("this.nextSibling.nodeName =", this.nextSibling.nodeName);
        var labelUnitText = type == "materials" ? selectedBuildingItem.package_measure_unit : "Kom";
        labelUnit.textContent = " " + labelUnitText;
        if (this.nextSibling.nodeName == "BR") {
            parentDivElem.insertBefore(divPrice, this.nextSibling);
            parentDivElem.insertBefore(labelUnit, divPrice);
            parentDivElem.insertBefore(inputUnitQuantity, labelUnit);
        }/* else if (this.nextSibling.nodeName == "INPUT") {
        } else {
            //console.warn("UNEXPECTED NEXT SIBLING =", this.nextSibling.nodeName);
        }*/
        
        toggleDisableSubmit(false);
        
        //console.log("### HTML ###\n" + parentDivElem.innerHTML);
    };

    inputUnitQuantity.onchange = function () {
        toggleDisableSubmit(false);
    }

    //console.info("addBuildingItemSelection() DONE");
}


function calculateDefinition(form, type) {
    var checkInputValuesOk = checkItemDefinitionInputValues(type);
    if (!checkInputValuesOk) {
    	return;
    }
    
    // WORK PRICE
    if (form["work_price"].value.length == 0) {
    	form["work_price"].value = "0,00";
    }
    var workPrice = currency2Float(form["work_price"].value);
    
    // PRICES OF USED BUILDING ITEMS
    var totalBuildingItemsPrice = 0;
    if (type == "biscuits") {
        totalBuildingItemsPrice += calculateBuildingItemsPrice(form, "materials");
    } else if (type == "incompletes") {
        totalBuildingItemsPrice += calculateBuildingItemsPrice(form, "biscuits");
        totalBuildingItemsPrice += calculateBuildingItemsPrice(form, "materials");
    } else if (type == "products") {
        totalBuildingItemsPrice += calculateBuildingItemsPrice(form, "incompletes");
        totalBuildingItemsPrice += calculateBuildingItemsPrice(form, "materials");
    }
    
    // TOTAL PRICE
    var price = parseFloat(workPrice) + parseFloat(totalBuildingItemsPrice);
    price = price.toFixed(2);

    form["price"].value = float2Currency(price);
    
    // ENABLE SUBMIT BUTTON AFTER THE CALCULATION
    toggleDisableSubmit(checkInputValuesOk);
}

function calculateBuildingItemsPrice(form, type) {
    // GET BUILDING ITEMS FROM HIDDEN INPUT
    var itemsHiddenInputId = "selection-" + type;
    var itemsJson = form[itemsHiddenInputId].value;
    var items = JSON.parse(itemsJson);

    // GET PARENT DIV-ELEMENT
    var parentDivElem = document.getElementById(type + "-selections");
    
    // GET BUILDING ITEMS' SELECT-ELEMENTS
    var selectElements = parentDivElem.getElementsByTagName("select");
    
    // CALCULATE USED BUILDING ITEMS PRICES
    var allUsedItemsTotalPrice = 0.0;
    for (var i = 0; i < selectElements.length;i++) {
    	
        // GET SELECT-ELEMENT
        var itemSelectElem = selectElements[i];
        
        // SKIP IF EMPTY OPTION IS SELECTED
        if (itemSelectElem.value == "-1") {
            continue;
        }
        
        // GET NAME OF THE SELECT-ELEMENT (e.g. mat-0, inc-3, ...)
        var selectElemName = itemSelectElem.name;
        
        // GET QUANTITY USED
        var quantityInputElem = document.getElementById(selectElemName + "_quantityUsed");
        
        // FETCH THE ACTUAL ITEM (FROM JSON ARRAY SET IN HIDDEN INPUT)
        var item = getItem(itemSelectElem.value, items);
        
        // CALCULATE THE PRICE OF USED BUILDING ITEM
        var usedBuildingItemPrice = 0.0;
        if (type == "materials") {
        	var quantityTaken = currency2Float(quantityInputElem.value);
        	//console.debug("quantityTaken: " + quantityTaken);
            var pricePerUnit = item.price / item.package_quantity;
            //console.debug("pricePerUnit: " + pricePerUnit);
            
            usedBuildingItemPrice = quantityTaken * pricePerUnit;
            //console.debug("usedBuildingItemPrice: " + usedBuildingItemPrice);
        } else {
            var quantityTaken = str2Int(quantityInputElem.value);
            
            usedBuildingItemPrice = quantityTaken * item.price;
        }

        // SET THE PRICE OF USED BUILDING ITEM TO IT'S READONLY INPUT-ELEMENT
        var priceReadonlyElem = document.getElementById(selectElemName + "_calculatedPrice");
        priceReadonlyElem.value = float2Currency(usedBuildingItemPrice.toFixed(2));
        //console.debug("### priceReadonlyElem.value: " + priceReadonlyElem.value);
        
        // ADD THE PRICE TO THE TOTAL PRICE OF BUILDING ITEMS OF THIS TYPE
        allUsedItemsTotalPrice += usedBuildingItemPrice
    }
    
    // SET THE PRICE OF ALL THE USED BUILDING ITEMS OF THIS TYPE TO THIS TYPE'S READONLY PRICE INPUT-ELEMENT
    var allUsedItemsTotalPriceElemName = type + "_price";
    form[allUsedItemsTotalPriceElemName].value = float2Currency(allUsedItemsTotalPrice.toFixed(2));
    
    return allUsedItemsTotalPrice;
}


function getItem(id, items) {
    for (var i = 0; i < items.length; i++) {
        if (items[i].id == id) {
            return items[i];
        }
    }
    console.warn("Not found", id, "in", items);
    return null;
}


function str2Int(str) {     // FIXME alert() -> err_msg_to_user
    if (!str) {
        return 0;
    }
    var intValue = parseInt(str);
    if (isNaN(intValue)) {
        alert("Unos '" + str + "' nije valjan broj!");  // FIXME
        return 0;
    }
    return intValue;
}

// FIXME find usages..
function str2Float(str) {
    if (!str) {
        return 0;
    }
    var floatValue = parseFloat(str);
    if (isNaN(floatValue)) {
        alert("Unos '" + str + "' nije valjan broj!");  // FIXME
        
        //var floatValue = 0;
        //alert("NaN -> " + +(floatValue.toFixed(3)));
        //return +(floatValue.toFixed(3));
        return 0;
    }
    //return +(floatValue.toFixed(3));
    return floatValue;
}

function currency2Float(str) {
	var float = 0;
    if (str) {
    	float = str.replace(/\./g, "");
    	float = float.replace(/,/g, ".");
    }
    return float;
}

/*function float2Str(float) {
	var str = "0,00";
    if (float) {
    	str = float.toString().replace(/\./g, ",");
    }
    return str;
}*/

function float2Currency(float) {
	var str = "0,00";
    if (float) {
    	str = float.toString().replace(/\./g, ",");
    	
    	var floatAsStringParts = str.split(",");
    	var numbersToGroup = floatAsStringParts[0];
    	var decimalPart = floatAsStringParts[1];
    	
    	if (numbersToGroup.length > 3) {
    		var numbers = numbersToGroup.split("");
    		var count = 0;
    		var assembledReversedArray = [];
    		for (var i = numbers.length - 1; i >= 0; i--)  {
    			count++;
    			if (count == 4) {
    				count = 1;
    				assembledReversedArray.push(".");
    			}
    			assembledReversedArray.push(numbers[i]);
    		}
    		assembledReversedArray.reverse();
    		assembledReversedArray.push(",");
    		assembledReversedArray.push(decimalPart);
    		str = assembledReversedArray.join("");
    	}
    }
    return str;
}
