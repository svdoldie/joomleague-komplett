
function move(fbox, tbox) {
	var arrFbox = new Array();
	var arrTbox = new Array();
	var arrLookup = new Array();
	var i;
	for(i=0; i<tbox.options.length; i++) {
		arrLookup[tbox.options[i].text] = tbox.options[i].value;
		arrTbox[i] = tbox.options[i].text;
	}
	var fLength = 0;
	var tLength = arrTbox.length
	for(i=0; i<fbox.options.length; i++) {
		arrLookup[fbox.options[i].text] = fbox.options[i].value;
		if(fbox.options[i].selected && fbox.options[i].value != "") {
			 arrTbox[tLength] = fbox.options[i].text;
			 tLength++;
		} else {
			 arrFbox[fLength] = fbox.options[i].text;
			 fLength++;
		}
	}
	fbox.length = 0;
	tbox.length = 0;
	var c;
	for(c=0; c<arrFbox.length; c++) {
		var no = new Option();
		no.value = arrLookup[arrFbox[c]];
		no.text = arrFbox[c];
		fbox[c] = no;
	}
	for(c=0; c<arrTbox.length; c++) {
		var no = new Option();
		no.value = arrLookup[arrTbox[c]];
		no.text = arrTbox[c];
		tbox[c] = no;
	}
	}
	function selectAll(box) {
	 for(var i=0; i<box.length; i++) {
	 box[i].selected = true;
		}
	}


	function moveOptionUp(selectId)
	{
		var selectList = document.getElementById(selectId);
		var selectOptions = selectList.getElementsByTagName('option');
		for (var i = 1; i < selectOptions.length; i++) {
			var opt = selectOptions[i];
			if (opt.selected) {
			selectList.removeChild(opt);
			selectList.insertBefore(opt, selectOptions[i - 1]);
			return true;
			}
		}
	}

	function moveOptionDown(selectId)
	{
		var selectList = document.getElementById(selectId);
		var selectOptions = selectList.getElementsByTagName('option');
		for (var i = 0; i < selectOptions.length-1; i++) {
			var opt = selectOptions[i];
			if (opt.selected) {
				var next = selectOptions[i + 1];
			selectList.removeChild(next);
			selectList.insertBefore(next, selectOptions[i]);
			return true;
			}
		}
	}