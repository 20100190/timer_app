window.onresize = function(){
    myspreadsheet.options.tableHeight = "1000px";    
}

function closeOverrall() {    
    var acWidth = document.getElementById("filter_area").style.height;
    var btnObj = document.getElementById("btn_open_close");
    var size = 100;//window.innerHeight - 350;
    
    if (acWidth == "0px") {
        btnObj.src = imagesUrl + "/close.png"
        document.getElementById("filter_area").style.height = "200px";     
        document.getElementById("filter_area").style.display = "block";
        //document.getElementById("spreadsheet2").style.height = "600px";
        //myspreadsheet.options.maxHeight = "100px";
        
    } else {
        btnObj.src = imagesUrl + "/open.png"
        document.getElementById("filter_area").style.height = "0px";   
        document.getElementById("filter_area").style.display = "none";
    }
}

$(document).ready(function () {
    jQuery('#loader-bg').hide();

    //Date From 初期値
    var dateToday = new Date();
     document.getElementById("filter_date").value = dateToday.toLocaleString("en-US",{
         "year": "numeric",
         "month": "2-digit",
         "day": "2-digit",	
     });    

    var buttonWidth = "400px";
    var buttonWidth2 = "150px";
    $('#client').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });

    $('#project').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });
    $('#vic').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        includeSelectAllOption: true,
    });
    $('#pic').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        maxHeight: 600,
        includeSelectAllOption: true,
    });
    $('#sel_role').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        includeSelectAllOption: true,
    });
    $('#sel_staff').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        maxHeight: 400,
        includeSelectAllOption: true,
    });

    $('.datepicker1').datepicker({
        format: "mm/dd/yyyy",
        language: "en",
        autoclose: true,
        orientation: 'bottom left'
    });
});

var filterOptions = function (o, cell, x, y, value, config) {
    var value = o.getValueFromCoords(2, y);
    var phaseCtrStr = $("#phaseCTR").val();
    var phaseBmStr = $("#phaseBM").val();
    var phaseAudStr = $("#phaseAUD").val();
    var phaseCompStr = $("#phaseCOMP").val();
    var phaseOthStr = $("#phaseOTH").val();
    var phaseRevStr = $("#phaseREV").val();
    var phaseItrStr = $("#phaseITR").val();

    var arrCorp = phaseCtrStr.split(",");//new Array('CORP Phase1', 'CORP Phase2', 'CORP Phase3', 'CORP Phase4', 'CORP Phase5');
    var arrBm = phaseBmStr.split(",");
    var arrAud = phaseAudStr.split(",");
    var arrComp = phaseCompStr.split(",");
    var arrOth = phaseOthStr.split(",");
    var arrRev = phaseRevStr.split(",");
    var arrItr = phaseItrStr.split(",");

    var arrAll = new Array();
    if (value.match("CORP") != null) {
        config.source = arrCorp;
    } else if (value.match("BM") != null) {
        config.source = arrBm;
    } else if (value.match("AUD") != null) {
        config.source = arrAud;
    } else if (value.match("COMP") != null) {
        config.source = arrComp;
    } else if (value.match("OTH") != null) {
        config.source = arrOth;
    } else if (value.match("REV") != null) {
        config.source = arrRev;
    } else if (value.match("INDIV") != null) {
        config.source = arrItr;
    } else {
        config.source = arrAll;
    }
    return config;
}

var maskStr = "#,##0.0";
var spreadsheetWidth = "80";
var size = window.innerHeight - 350;

var myspreadsheet = jexcel(document.getElementById('spreadsheet2'), {
    //data: data,
    //url: "/webform/test3/input",
    minDimensions: [55, 100],
    tableOverflow: true,
    //lazyLoading: true,
    //pagenation: 10,
    tableWidth: '100%',
    tableHeight: size + "px",//"490px",
    freezeColumns: 4,
    contextMenu: function () {
        return false;
    },
    columns: [
        {
            title: 'id',
            width: '0'
        },
        {
            title: 'Client',
            width: '250'
        },
        {
            title: 'Project',
            width: '250'
        },
        {
            title: 'PIC',
            width: '50'
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
        {
            type: 'dropdown',
            title: ' ',
            width: spreadsheetWidth,
            filterOptions: filterOptions,
            multiple: true,
        },
    ],
    onload: function (instance) {
        
    },
    onchange: function (instance, cell, c, r, value) {
        var projectId = myspreadsheet.getValueFromCoords(0, r);
        var projectName = myspreadsheet.getValueFromCoords(2, r);
        var headerDate = myspreadsheet.getHeader(c).split("/");
        var year = headerDate[2];
        var month = headerDate[0];
        var day = headerDate[1];

        var projectTypeId = 0;
        if (projectName.match("BM")) {
            projectTypeId = 5;
        } else if (projectName.match("CORP TAX")) {
            projectTypeId = 9;
        } else if (projectName.match("AUD")) {
            projectTypeId = 4;
        } else if (projectName.match("COMP")) {
            projectTypeId = 7;
        } else if (projectName.match("OTH")) {
            projectTypeId = 22;
        } else if (projectName.match("REV")) {
            projectTypeId = 26;
        } else if (projectName.match("INDIV")) {
            projectTypeId = 14;
        }

        saveCellData(projectId, year, month, day, value, projectTypeId);

        //背景色設定
        var obj = JSON.parse($('#phaseCTRColor').val());
        var obj2 = JSON.parse($('#phaseBMColor').val());
        var obj3 = JSON.parse($('#phaseAUDColor').val());
        var obj4 = JSON.parse($('#phaseCOMPColor').val());
        var obj5 = JSON.parse($('#phaseOTHColor').val());
        var obj6 = JSON.parse($('#phaseREVColor').val());
        var obj7 = JSON.parse($('#phaseITRColor').val());

        var color = "white";
        var phaseCnt = 0;
        var phaseObj = "";

        if (projectTypeId == 9) {
            phaseObj = obj;
        } else if (projectTypeId == 5) {
            phaseObj = obj2;
        } else if (projectTypeId == 4) {
            phaseObj = obj3;
        } else if (projectTypeId == 7) {
            phaseObj = obj4;
        } else if (projectTypeId == 22) {
            phaseObj = obj5;
        } else if (projectTypeId == 26) {
            phaseObj = obj6;
        } else if (projectTypeId == 14) {
            phaseObj = obj7;
        }
        
        if (phaseObj != ""){
            var {retCnt, retColor} = getPhaseColorAndIndex(phaseObj, value);
            color = retColor;
            phaseCnt = retCnt;
        }

        if (value.match(";")) {
            var splitValue = value.split(";");
            var colorString = splitValue[splitValue.length -1];      
            if(value.match("EXT")){
                colorString = "EXT";
            }
            var {retCnt, retColor} = getPhaseColorAndIndex(phaseObj, colorString);            
            color = retColor;
            phaseCnt = retCnt;
        }

        var ar = columnArray();
        var rowCnt = parseInt(r) + 1;
        myspreadsheet.setStyle(ar[c] + rowCnt, 'background-color', color);
        
        //font color
        myspreadsheet.setStyle(ar[c] + rowCnt, 'color', "black");
        if(phaseCnt >= 3){
            myspreadsheet.setStyle(ar[c] + rowCnt, 'color', "white");
        }

    },
    updateTable: function (el, cell, x, y, source, value, id) {
        if (myspreadsheet !== undefined) {
            for (var j = 0; j < 4; j++) {
                for (var i = 0; i < myspreadsheet.rows.length; i++) {
                    if ((x == j) && y == i) {
                        cell.classList.add('readonly');
                    }
                }
            }
        }
    }

});

function getPhaseColorAndIndex(obj,value) {
    var phaseCnt = 0;    
    var color = "white";
    
    for (var i = 0; i < obj.length; i++) {
        if (value == obj[i]["name"]) {
            color = obj[i]["color"];
            break;
        }
        phaseCnt += 1;
    }
    
    return {
        retCnt: phaseCnt,
        retColor: color
    };
    
}

function getProjectAllData() {

    var client = "blank";
    var clientObj = $("#client").val();
    var projectObj = $("#project").val();
    var project = "blank";
    var vicObj = $("#vic").val();
    var vic = "blank";
    var picObj = $("#pic").val();
    var pic = "blank";
    var staffObj = $("#sel_staff").val();
    var staff = "blank";
    var roleObj = $("#sel_role").val();
    var role = "blank";
    var year = "2020";
    var month = "1";
    var day = "6";
    var archived = 0;
    if ($("#is_archive").prop("checked") == false) {
        archived = 1;
    }

    client = setDelimiter(clientObj);
    project = setDelimiter(projectObj);
    vic = setDelimiter(vicObj);
    pic = setDelimiter(picObj);
    staff = setDelimiter(staffObj);
    role = setDelimiter(roleObj);

    var dateObj = document.getElementById("filter_date");
    if (dateObj.value != "") {
        year = parseInt(dateObj.value.split("/")[2]);
        month = parseInt(dateObj.value.split("/")[0]);
        day = parseInt(dateObj.value.split("/")[1]);
    }

    $.ajax({
        url: "/phase/entry/" + client + "/" + project + "/" + vic + "/" + pic + "/" + staff + "/" + role + "/" + year + "/" + month + "/" + day + "/" + archived,
        dataType: "json",
        success: data => {
            $('#budget_info').val(JSON.stringify(data.budget));
            $('#phaseCTR').val(JSON.stringify(data.phaseCTR).replace(/"/g, ""));
            $('#phaseCTRColor').val(JSON.stringify(data.phaseCTRColor));
            $('#phaseBM').val(JSON.stringify(data.phaseBM).replace(/"/g, ""));
            $('#phaseBMColor').val(JSON.stringify(data.phaseBMColor));
            $('#phaseAUD').val(JSON.stringify(data.phaseAUD).replace(/"/g, ""));
            $('#phaseAUDColor').val(JSON.stringify(data.phaseAUDColor));
            $('#phaseCOMP').val(JSON.stringify(data.phaseCOMP).replace(/"/g, ""));
            $('#phaseCOMPColor').val(JSON.stringify(data.phaseCOMPColor));
            $('#phaseOTH').val(JSON.stringify(data.phaseOTH).replace(/"/g, ""));
            $('#phaseOTHColor').val(JSON.stringify(data.phaseOTHColor));
            $('#phaseREV').val(JSON.stringify(data.phaseREV).replace(/"/g, ""));
            $('#phaseREVColor').val(JSON.stringify(data.phaseREVColor));
            $('#phaseITR').val(JSON.stringify(data.phaseITR).replace(/"/g, ""));
            $('#phaseITRColor').val(JSON.stringify(data.phaseITRColor));

            myspreadsheet.setData(data.budget);
            var ar = columnArray();

            //myspreadsheet.setStyle("E1", "background-color", "green");
            var jexcelEl = document.getElementsByClassName("jexcel_dropdown");

            for (var i = 0; i < jexcelEl.length; i++) {
                var elX = jexcelEl[i].dataset.x;
                var elY = jexcelEl[i].dataset.y;

                //x4 y0 bud[0][4]
                //x5 y0 bud[0][5]
                //x4 y1 bud[1][4]
                document.getElementsByClassName("jexcel_dropdown")[i].innerText = data.budget[elY][elX];
            }

            //header
            //undoのバグ回避のため2回読み込み----------------------------------------
            var columnCnt = 4;
            for (var s = 0; s < data.week.length; s++) {
                myspreadsheet.setHeader(columnCnt, data.week[s]);
                //myspreadsheet.setHeader(columnCnt, data.week[s].replace("/", "\n"));
                columnCnt += 1;
            }

            var columnCnt = 4;
            for (var s = 0; s < data.week.length; s++) {
                myspreadsheet.setHeader(columnCnt, data.week[s]);
                //myspreadsheet.setHeader(columnCnt, data.week[s].replace("/", "\n"));
                columnCnt += 1;
            }
            //--------------------------------------------------------------------------

            //Style設定
            for (var t = 1; t <= myspreadsheet.rows.length; t++) {
                //for (var x = 0; x < ar.length; x++) {
                for (var x = 0; x < 4; x++) {
                    myspreadsheet.setStyle(ar[x] + t, 'text-align', 'left');
                    myspreadsheet.setStyle(ar[x] + t, 'color', 'black');
                }
            }

            //for (var t = 0; t <= myspreadsheet.rows.length - 1; t++) {                   
            for (var t = 0; t <= data.color.length - 1; t++) {
                for (var x = 4; x < ar.length; x++) {
                    if (data.color[t][x] != "") {
                        var rowCnt = parseInt(t) + 1;
                        if (data.color[t][x].match(";")) {
                            var splitValue = data.color[t][x].split(";");
                            var colorString = splitValue[splitValue.length -1];     
                            if (data.color[t][x].match("134f5c")) {
                                colorString = "#134f5c";
                            }
                            myspreadsheet.setStyle(ar[x] + rowCnt, 'background-color', colorString);
                            var targetColorArray = ["#a64d79","#3c78d8","#3d85c6","#e69138","#6aa84f","#f1c232","#741b47","#1155cc","#0b5394","#b45f06","#38761d","#bf9000","#134f5c","#45818e"];
                            if(targetColorArray.includes(colorString)){
                                myspreadsheet.setStyle(ar[x] + rowCnt, 'color', "white");
                            }
                        } else {
                            myspreadsheet.setStyle(ar[x] + rowCnt, 'background-color', data.color[t][x]);
                            
                            //該当カラーの場合、文字色白
                            myspreadsheet.setStyle(ar[x] + rowCnt, 'color', "black");
                            var targetColorArray = ["#a64d79","#3c78d8","#3d85c6","#e69138","#6aa84f","#f1c232","#741b47","#1155cc","#0b5394","#b45f06","#38761d","#bf9000","#134f5c","#45818e"];
                            if(targetColorArray.includes(data.color[t][x])){
                                myspreadsheet.setStyle(ar[x] + rowCnt, 'color', "white");
                            }
                        }
                    }
                }
            }

        },
        beforeSend: function (xhr, settings) {
            //処理中
            //$("#loadingSpinner").css("visibility", "visible");
            //$("#loadingText").html("保存中");
            //$("#s").find(':select').attr('readonly', true);
            //$("#btn_load").attr('disabled', true);
            jQuery('#loader-bg').show();

        },
        complete: function (xhr, textStatus) {
            //sss();

            //$("#loadingSpinner").css("visibility", "hidden");
            //$("#loadingText").html("保存");
            //$("#s").find(':select').attr('readonly', false);
            //$("#s").find(':select').removeAttr('readonly');
            //$("#btn_load").attr('disabled', false);
            //$("#btn_load").removeAttr('disabled');            
            jQuery('#loader-bg').hide();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {            
            if (XMLHttpRequest.status === 401) {
                location.href = location.protocol + "//" + location.hostname + "/login";
            }
        }
        /*error: () => {
            alert("ajax Error");
            jQuery('#loader-bg').hide();
        }*/
    });
}

function columnArray() {
    var array = [];
    array[0] = "A";
    array[1] = "B";
    array[2] = "C";
    array[3] = "D";
    array[4] = "E";
    array[5] = "F";
    array[6] = "G";
    array[7] = "H";
    array[8] = "I";
    array[9] = "J";
    array[10] = "K";
    array[11] = "L";
    array[12] = "M";
    array[13] = "N";
    array[14] = "O";
    array[15] = "P";
    array[16] = "Q";
    array[17] = "R";
    array[18] = "S";
    array[19] = "T";
    array[20] = "U";
    array[21] = "V";
    array[22] = "W";
    array[23] = "X";
    array[24] = "Y";
    array[25] = "Z";
    array[26] = "AA";
    array[27] = "AB";
    array[28] = "AC";
    array[29] = "AD";
    array[30] = "AE";
    array[31] = "AF";
    array[32] = "AG";
    array[33] = "AH";
    array[34] = "AI";
    array[35] = "AJ";
    array[36] = "AK";
    array[37] = "AL";
    array[38] = "AM";
    array[39] = "AN";
    array[40] = "AO";
    array[41] = "AP";
    array[42] = "AQ";
    array[43] = "AR";
    array[44] = "AS";
    array[45] = "AT";
    array[46] = "AU";
    array[47] = "AV";
    array[48] = "AW";
    array[49] = "AX";
    array[50] = "AY";
    array[51] = "AZ";
    array[52] = "BA";
    array[53] = "BB";
    array[54] = "BC";
    array[55] = "BD";

    return array;
}

function saveCellData(projectId, year, month, day, value, projectTypeId) {

    if (value == "") {
        value = "blank";
    }

    $.ajax({
        url: "/phase/entry/save/" + projectId + "/" + year + "/" + month + "/" + day + "/" + value + "/" + projectTypeId,
    }).success(function (data) {
        //alert('success!!');
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    });

}

function setDelimiter(obj) {
    var str = "";
    if (obj == null) {
        str = "blank";
    } else {
        for (var s = 0; s < obj.length; s++) {
            str += obj[s];
            if (s != obj.length - 1) {
                str += ",";
            }
        }
    }
    return str;
}

function clearInputFilter() {
    $('#client').multiselect('deselectAll', false);
    $('#client').multiselect('updateButtonText');

    $('#project').multiselect('deselectAll', false);
    $('#project').multiselect('updateButtonText');

    $('#vic').multiselect('deselectAll', false);
    $('#vic').multiselect('updateButtonText');

    $('#pic').multiselect('deselectAll', false);
    $('#pic').multiselect('updateButtonText');

    $('#sel_role').multiselect('deselectAll', false);
    $('#sel_role').multiselect('updateButtonText');

    $('#sel_staff').multiselect('deselectAll', false);
    $('#sel_staff').multiselect('updateButtonText');

    document.getElementById("filter_date").value = "";
}

function exportPhaseData(){
    var client = "blank";
    var clientObj = $("#client").val();
    var projectObj = $("#project").val();
    var project = "blank";
    var vicObj = $("#vic").val();
    var vic = "blank";
    var picObj = $("#pic").val();
    var pic = "blank";
    var staffObj = $("#sel_staff").val();
    var staff = "blank";
    var roleObj = $("#sel_role").val();
    var role = "blank";
    var year = "2020";
    var month = "1";
    var day = "6";
    var archived = 0;
    if ($("#is_archive").prop("checked") == false) {
        archived = 1;
    }

    client = setDelimiter(clientObj);
    project = setDelimiter(projectObj);
    vic = setDelimiter(vicObj);
    pic = setDelimiter(picObj);
    staff = setDelimiter(staffObj);
    role = setDelimiter(roleObj);

    var dateObj = document.getElementById("filter_date");
    if (dateObj.value != "") {
        year = parseInt(dateObj.value.split("/")[2]);
        month = parseInt(dateObj.value.split("/")[0]);
        day = parseInt(dateObj.value.split("/")[1]);
    }

    $.ajax({
        url: "/phase/entry/" + client + "/" + project + "/" + vic + "/" + pic + "/" + staff + "/" + role + "/" + year + "/" + month + "/" + day + "/" + archived,
        dataType: "json",
        success: data => {
            createExportPhaseData(data);
        }
    });

}

function createExportPhaseData(data){
    var daysArray = ["Client","Project","PIC"];
    var reportArray = [];
    //header
    for (var i = 0; i < data.week.length; i++) {
        daysArray.push(data.week[i]);
    }

    reportArray.push(daysArray);

    //detail
    for(var x=0; x<data.budget.length; x++){
        data.budget[x].shift();
        reportArray.push(data.budget[x]);
    }

    exportBudgetDataFunc(reportArray);
}

function exportBudgetDataFunc(header1) {
    // 書き込み時のオプションは以下を参照
    // https://github.com/SheetJS/js-xlsx/blob/master/README.md#writing-options
    var write_opts = {
        type: 'binary'
    };

    /*var array1 =
      [
        ["apple", "banana", "cherry"],
        [1, 2, 3]
      ];*/

    // ArrayをWorkbookに変換する
    var wb = aoa_to_workbook(header1);
    var wb_out = XLSX.write(wb, write_opts);

    // WorkbookからBlobオブジェクトを生成
    // 参照：https://developer.mozilla.org/ja/docs/Web/API/Blob
    var blob = new Blob([s2ab(wb_out)], { type: 'application/octet-stream' });

    // FileSaverのsaveAs関数で、xlsxファイルとしてダウンロード
    // 参照：https://github.com/eligrey/FileSaver.js/
    saveAs(blob, 'myExcelFile.xlsx');
}

// SheetをWorkbookに追加する
// 参照：https://github.com/SheetJS/js-xlsx/issues/163
function sheet_to_workbook(sheet/*:Worksheet*/, opts)/*:Workbook*/ {
    var n = opts && opts.sheet ? opts.sheet : "Sheet1";
    var sheets = {}; sheets[n] = sheet;
    return { SheetNames: [n], Sheets: sheets };
}

// ArrayをWorkbookに変換する
// 参照：https://github.com/SheetJS/js-xlsx/issues/163
function aoa_to_workbook(data/*:Array<Array<any> >*/, opts)/*:Workbook*/ {
    return sheet_to_workbook(XLSX.utils.aoa_to_sheet(data, opts), opts);
}

// stringをArrayBufferに変換する
// 参照：https://stackoverflow.com/questions/34993292/how-to-save-xlsx-data-to-file-as-a-blob
function s2ab(s) {
    var buf = new ArrayBuffer(s.length);
    var view = new Uint8Array(buf);
    for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
    return buf;
}



