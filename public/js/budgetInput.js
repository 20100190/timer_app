window.addEventListener("resize", function (event) {

    //document.getElementById("spreadsheet").style.cssText = "width: 100%;height:" + (window.innerHeight - 280) + "px";
    //document.body.style.zoom = "80%";
});

$(window).on('load', function () {
    document.getElementById("spreadsheet").style.cssText = "width: 100%;height:" + (window.innerHeight - 280) + "px";
    jQuery('#loader-bg').hide();
});

$(document).ready(function () {
    //alert(window.innerHeight + "filt:" + $("#client").height());
  
    var buttonWidth = "400px";
    var buttonWidth2 = "150px";
    $('#client').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });
    $('#project').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: 700,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
    });
    $('#fye').multiselect({
        buttonWidth: buttonWidth2,
        enableFiltering: true,
        onDropdownShown: function(even) {
            this.$filter.find('.multiselect-search').focus();
        },
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
});

$('.datepicker1').datepicker({
    format: "mm/dd/yyyy",
    language: "en",
    autoclose: true,
    orientation: 'bottom left'
});

//Fromに当日日付設定
     var dateToday = new Date();
     document.getElementById("filter_date").value = dateToday.toLocaleString("en-US",{
         "year": "numeric",
         "month": "2-digit",
         "day": "2-digit",	
     });     

var maskStr = "#,##0.0";
var spreadsheetWidth = "80";
var size = window.innerHeight - 350;

var myspreadsheet = jexcel(document.getElementById('spreadsheet'), {
    //data: data,
    //url: "/webform/test3/input",
    minDimensions: [62, 100],
    tableOverflow: true,
    //lazyLoading: true,
    //pagenation: 10,
    tableWidth: '100%',
    tableHeight: size + "px",
    freezeColumns: 10,
    contextMenu:function() { return false; },
    columns: [
        {
            title: 'Client',
            width: '250'
        },
        {
            title: 'Project',
            width: '300'
        },
        {
            title: 'FYE',
            width: '50'
        },
        {
            title: 'VIC',
            width: '50'
        },
        {
            title: 'PIC',
            width: '50'
        },
        {
            //type: "dropdown",
            title: 'Role',
            width: '150',
            //autocomplete: true,
            //url: "/webform/test3/role"
            //source:["Role1","Role2","Admin"]
        },
        {
            title: 'Staff',
            width: '80',
        },
        {
            type: 'number',
            title: 'Budget',
            width: '100',
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: 'Assigned',
            width: '100',
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: 'Diff',
            width: '100',
            mask: "[-]" + maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
        {
            type: 'number',
            title: ' ',
            width: spreadsheetWidth,
            mask: maskStr,
            align: "center"
        },
    ],    
    onload: function (instance) {

    },    
    onchange: function (instance, cell, c, r, value) {
        var client = myspreadsheet.getValueFromCoords(0, r);
        var project = myspreadsheet.getValueFromCoords(1, r);
        var staff = myspreadsheet.getValueFromCoords(6, r);

        //ブランク行、集計セルのonchange回避
        if (client !== "" && project !== "" && value.charAt(0) !== "=") {           
            //var year = myspreadsheet.getHeader(c).split("\n")[0];
            //var month = myspreadsheet.getHeader(c).split("\n")[1].split("/")[0];
            //var day = myspreadsheet.getHeader(c).split("/")[1];
            var headerDate = myspreadsheet.getHeader(c).split("/");
            var year = headerDate[2];
            var month = headerDate[0];
            var day = headerDate[1];
            saveCellData(client, project, staff, year, month, day, value);
        }
    },
    updateTable: function (el, cell, x, y, source, value, id) {

        //１行目ReadOnly
        for (var i = 0; i < 60; i++) {
            if ((x == i) && y == 0) {
                cell.classList.add('readonly');
            }
        }

        //Budget列ReadOnly
        for (var j = 0; j < 10; j++) {
            for (var i = 1; i < 100; i++) {
                if ((x == j) && y == i) {
                    cell.classList.add('readonly');
                }
            }
        }

        //Total行ReadOnly
        if (myspreadsheet != undefined) {
            var project = myspreadsheet.getValueFromCoords(1, y);
            if (project.slice(-5) == "Total") {
                for (var i = 0; i < 60; i++) {
                    if ((x == i) && y == y) {
                        cell.classList.add('readonly');
                    }
                }
            }
        }
    }
});

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
    array[56] = "BE";
    array[57] = "BF";
    array[58] = "BG";
    array[59] = "BH";
    array[60] = "BI";
    array[61] = "BJ";

    return array;
}

function saveData() {
    var xxx = myspreadsheet.getData(false);
    var ddd = JSON.stringify(xxx);
    document.getElementById("postArray").value = ddd;
    //document.s.submit();

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
    $.ajax({
        url: "/budget/test3/save",
        type: "post",
        contentType: "application/json",
        data: JSON.stringify(xxx),
        dataType: "json",
    }).done(function (data1, textStatus, jqXHR) {
        //$("#p1").text(jqXHR.status); //例：200
        //console.log(data1.code); //1
        //console.log(data1.name); //eigyou
        //$("#p2").text(JSON.stringify(data1));
    }).fail(function (jqXHR, textStatus, errorThrown) {
        //$("#p1").text("err:" + jqXHR.status); //例：404
        //$("#p2").text(textStatus); //例：error
        //$("#p3").text(errorThrown); //例：NOT FOUND
    }).always(function () {
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

function testData() {
    var defaultColor = "gainsboro";
    var client = "blank";
    var clientObj = $("#client").val();
    var projectObj = $("#project").val();
    var project = "blank";
    var fyeObj = $("#fye").val();
    var fye = "blank";
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
    var clientAS = document.getElementById("archive_client").checked;
    var projectAS = document.getElementById("archive_project").checked;
    var picAS = document.getElementById("archive_pic").checked;
    var staffAS = document.getElementById("archive_staff").checked;

    //検索文字列
    client = setDelimiter(clientObj);
    project = setDelimiter(projectObj);
    fye = setDelimiter(fyeObj);
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
        url: "/budget/test3/input/" + client + "/" + project + "/" + fye + "/" + vic + "/" + pic + "/" + staff + "/" + role + "/" + year + "/" + month + "/" + day + "/" + clientAS + "/" + projectAS + "/" + picAS + "/" + staffAS,
        dataType: "json",
        success: data => {
            $('#budget_info').val(JSON.stringify(data.budget));

            myspreadsheet.setData(data.budget);
            var ar = columnArray();

            //background color
            for (var cnt = 0; cnt < data.budget.length; cnt++) {
                myspreadsheet.setStyle('A' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('A' + (cnt + 1), 'color', 'black');
                myspreadsheet.setStyle('B' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('B' + (cnt + 1), 'color', 'black');
                myspreadsheet.setStyle('C' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('C' + (cnt + 1), 'color', 'black');
                myspreadsheet.setStyle('D' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('D' + (cnt + 1), 'color', 'black');
                myspreadsheet.setStyle('E' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('E' + (cnt + 1), 'color', 'black');
                myspreadsheet.setStyle('F' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('F' + (cnt + 1), 'color', 'black');
                myspreadsheet.setStyle('G' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('G' + (cnt + 1), 'color', 'black');
                myspreadsheet.setStyle('H' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('H' + (cnt + 1), 'color', 'black');
                myspreadsheet.setStyle('I' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('I' + (cnt + 1), 'color', 'black');
                myspreadsheet.setStyle('J' + (cnt + 1), 'background-color', '#f5f5f5');
                myspreadsheet.setStyle('J' + (cnt + 1), 'color', 'black');

                if (data.budget[cnt][1].slice(-5) == "Total" || cnt == 0) {

                    //Total行初期色
                    for (var x = 0; x < ar.length; x++) {
                        myspreadsheet.setStyle(ar[x] + (cnt + 1), 'background-color', defaultColor);
                        //
                    }

                    //Phase背景色
                    //if (data.budget[cnt][0] == "Yamaha") {
                    //    myspreadsheet.setStyle('K' + (cnt + 1), 'background-color', 'lightgreen');
                    //    myspreadsheet.setStyle('L' + (cnt + 1), 'background-color', 'lightblue');
                    //} else if (data.budget[cnt][0] == "Zensho") {
                    //    myspreadsheet.setStyle('K' + (cnt + 1), 'background-color', 'aliceblue');
                    //    myspreadsheet.setStyle('L' + (cnt + 1), 'background-color', 'lavender');
                    // }
                } 

            }

            //header
            //undoのバグ回避のため2回読み込み----------------------------------------
            var columnCnt = 10;
            for (var s = 0; s < data.week.length; s++) {
                myspreadsheet.setHeader(columnCnt, data.week[s]);
                //myspreadsheet.setHeader(columnCnt, data.week[s].replace("/", "\n"));
                columnCnt += 1;
            }
            
            var columnCnt = 10;
            for (var s = 0; s < data.week.length; s++) {
                myspreadsheet.setHeader(columnCnt, data.week[s]);
                //myspreadsheet.setHeader(columnCnt, data.week[s].replace("/", "\n"));
                columnCnt += 1;
            }
            //--------------------------------------------------------------------------
            
            //Style設定
            for (var t = 1; t <= myspreadsheet.rows.length; t++) {
                for (var x = 0; x < ar.length; x++) {
                    if (x < 7) {
                        myspreadsheet.setStyle(ar[x] + t, 'text-align', 'left');
                    } else {
                        myspreadsheet.setStyle(ar[x] + t, 'text-align', 'right');
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

function getProjectData() {
    var clientId = document.getElementById("client").value;
    $.ajax({
        url: "/budget/test3/project/" + clientId,
        dataType: "json",
        success: data => {
            $('#project').children().remove();

            for (var i = 0; i < data.length; i++) {
                var op = document.createElement("option");
                var val = data[i]["id"];
                if (typeof val === "undefined") {
                    val = i;
                }
                op.value = val;
                op.text = data[i]["name"];   //テキスト値
                document.getElementById("project").appendChild(op);
            }

            $('#project').multiselect('rebuild');

        },
        error: () => {
            alert("ajax Error");
        }
    });
}

function saveCellData(client, project, staff, year, month, day, value) {
    if (value == "") {
        value = 0;
    }

    $.ajax({
        url: "/budget/test3/save/" + client + "/" + project + "/" + staff + "/" + year + "/" + month + "/" + day + "/" + value,
    }).success(function (data) {
        //alert('success!!');
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    });

}

function sss() {
    var budgetArray = JSON.parse(document.getElementById("budget_info").value);
    myspreadsheet.setData(budgetArray);
}

function clearInputFilter() {
    $('#client').multiselect('deselectAll', false);
    $('#client').multiselect('updateButtonText');

    $('#project').multiselect('deselectAll', false);
    $('#project').multiselect('updateButtonText');

    $('#fye').multiselect('deselectAll', false);
    $('#fye').multiselect('updateButtonText');

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

function getInputAllData() {
    var clientObj = $("#client").val();
    var projectObj = $("#project").val();
    var fyeObj = $("#fye").val();
    var vicObj = $("#vic").val();
    var picObj = $("#pic").val();
    var staffObj = $("#sel_staff").val();
    var roleObj = $("#sel_role").val();
    var dateFromObj = document.getElementById("filter_date").value;

    if (clientObj == null && projectObj == null && fyeObj == null && vicObj == null && picObj == null && staffObj == null && roleObj == null && dateFromObj == "") {
        Swal.fire({
            title: 'Are you sure?',
            text: "Your search returned a large number of results. Continue with the search?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                testData();
            }
        })
    } else {
        testData();
    }

}

/* ------------------------------
 Loading イメージ表示関数
 引数： msg 画面に表示する文言
 ------------------------------ */
function dispLoading(msg){
  // 引数なし（メッセージなし）を許容
  if( msg == undefined ){
    msg = "";
  }
  // 画面表示メッセージ
  //var dispMsg = "<div class='loadingMsg'>" + msg + "</div>";
  // ローディング画像が表示されていない場合のみ出力
  //if($("#loading").length == 0){
    $("body").append("<div id='loading'><div class='loadingMsg'>" + "" + "</div></div>");
  //}
}
 
/* ------------------------------
 Loading イメージ削除関数
 ------------------------------ */
function removeLoading(){
  $("#loading").remove();
}
        