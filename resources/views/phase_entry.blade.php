@extends('layouts.main')
@section('content') 
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#loader-bg').hide();

        var buttonWidth = "400px";
        var buttonWidth2 = "150px";
        $('#client').multiselect({
            buttonWidth: buttonWidth,
            maxHeight: 700,
            enableFiltering: true,
            includeSelectAllOption: true,
        });

        $('#project').multiselect({
            buttonWidth: buttonWidth,
            maxHeight: 700,
            enableFiltering: true,
            includeSelectAllOption: true,
        });
        $('#vic').multiselect({
            buttonWidth: buttonWidth2,
            enableFiltering: true,
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
            includeSelectAllOption: true,
        });
        $('#sel_staff').multiselect({
            buttonWidth: buttonWidth2,
            enableFiltering: true,
            maxHeight: 400,
            includeSelectAllOption: true,
        });
    });

</script>

<div style="margin-left: 20px">
    <div id="filter_area" style="margin-top: 30px;">
        <div id="filter_left" style="float: left;height: 200px;margin-bottom: 30px">
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2" >
                    <span class="line-height">Client</span>
                </div>
                <div class="col col-md-3">
                    <select id="client" name="client" multiple="multiple" class="form-control select2" data-display="static">                           
                        @foreach ($client as $clients)
                        <option value="{{$clients->id}}">{{$clients->name}}</option>
                        @endforeach
                    </select>
                </div>  
            </div>

            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2">
                    <span class="line-height">Project</span>
                </div>
                <div class="col col-md-1">
                    <select id="project" name="project" multiple="multiple" style="width: 200px">                          
                        @foreach ($project as $projects)
                        <option value="{{$projects->project_name}}">{{$projects->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2">
                    <span class="line-height">PIC</span>
                </div>
                <div class="col col-md-1">                    
                    <select id="pic" name="pic" multiple="multiple" class="form-control">                            
                        @foreach ($pic as $pics)                    
                        <option value="{{$pics->id}}">{{$pics->initial}}</option>
                        @endforeach
                    </select>                                
                </div>
            </div>

            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2">
                    <span class="line-height">Staff</span>
                </div>
                <div class="col col-md-1">
                    <select id="sel_staff" name="sel_staff" multiple="multiple" class="form-control" >                            
                        @foreach ($staff as $staffs)
                        <option value="{{$staffs->id}}">{{$staffs->initial}}</option>
                        @endforeach
                    </select>
                </div>
            </div>              

            <div class="row entry-filter-bottom">                           
                <div class="col col-md-3" >
                    <input type="button" class="btn btn-default" value="Clear" onclick="clearInputFilter()" style="background-color: white;width: 150px;margin-left: 85px">
                </div>
                <div class="col" >
                    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="getProjectAllData()" style="width: 150px;margin-left: 140px">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="loadingText">Search</span>
                    </button>
                </div>
            </div>
        </div>

        <div id="filter_right" style="float: left;margin-left: 80px">
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-4">
                    <span class="line-height">VIC</span>
                </div>
                <div class="col col-md-1">
                    <select id="vic" name="vic" multiple="multiple" class="form-control" >                            
                        <option value="1">VIC</option>
                        <option value="2">IC</option>
                        <option value="3">C</option>
                    </select>
                </div>
            </div>


            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-4">
                    <span class="line-height">Role</span>
                </div>
                <div class="col col-md-1">
                    <select id="sel_role" name="sel_role" multiple="multiple" class="form-control" >                            
                        @foreach ($role as $roles)                    
                        <option value="{{$roles->id}}">{{$roles->role}}</option>
                        @endforeach                    
                    </select>
                </div>
            </div>

            <div class="row entry-filter-bottom">
                <div class="col col-md-4">
                    <span class="line-height">Date From</span>
                </div>
                <div class="col col-md-1">
                    <input type="text" style="width:150px;hight:10px;margin-right: 20px;" class="form-control datepicker1" id="filter_date" name="filter_date" placeholder="mm/dd/yyyy" value="">                            
                </div>                 
            </div>

            <div class="row entry-filter-bottom" style="zoom: 100%;">    
                <div class="col col-md-4">                   
                </div>
                <div class="col col-md-1">

                </div>
            </div>            
        </div>        
    </div>



    <div id='spreadsheet2'></div>

    <input type="hidden" id="phaseCTR">
    <input type="hidden" id="phaseBM">
    <input type="hidden" id="phaseAUD">
    <input type="hidden" id="phaseREV">
    <input type="hidden" id="phaseCOMP">
    <input type="hidden" id="phaseITR">
    <input type="hidden" id="phaseTOPC">
    <input type="hidden" id="phaseOTH">
    <input type="hidden" id="phaseCTRColor">
    <input type="hidden" id="phaseAUDColor">
    <input type="hidden" id="phaseREVColor">
    <input type="hidden" id="phaseCOMPColor">
    <input type="hidden" id="phaseITRColor">
    <input type="hidden" id="phaseBMColor">
    <input type="hidden" id="phaseTOPCColor">
    <input type="hidden" id="phaseOTHColor">
</div>

<script>
    $(document).ready(function () {
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

    var myspreadsheet = jexcel(document.getElementById('spreadsheet2'), {
        //data: data,
        //url: "/webform/test3/input",
        minDimensions: [55, 100],
        tableOverflow: true,
        //lazyLoading: true,
        //pagenation: 10,
        tableWidth: '100%',
        tableHeight: "490px",
        freezeColumns: 3,
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
            if(projectName.match("BM")){
                projectTypeId = 5;
            }else if(projectName.match("CORP TAX")){
                projectTypeId = 9;
            }else if(projectName.match("AUD")){
                projectTypeId = 4;
            }else if(projectName.match("COMP")){
                projectTypeId = 7;
            }else if(projectName.match("OTH")){
                projectTypeId = 22;
            }else if(projectName.match("REV")){
                projectTypeId = 26;
            }else if(projectName.match("INDIV")){
                projectTypeId = 14;
            }
            
            saveCellData(projectId, year, month, day, value,projectTypeId);

            //背景色設定
            var obj = JSON.parse($('#phaseCTRColor').val());
            var obj2 = JSON.parse($('#phaseBMColor').val());
            var obj3 = JSON.parse($('#phaseAUDColor').val());
            var obj4 = JSON.parse($('#phaseCOMPColor').val());
            var obj5 = JSON.parse($('#phaseOTHColor').val());
            var obj6 = JSON.parse($('#phaseREVColor').val());
            var obj7 = JSON.parse($('#phaseITRColor').val());
            
            var color = "white";
            
            if(projectTypeId == 9){
                for (var i = 0; i < obj.length; i++) {
                    if (value == obj[i]["name"]) {
                        color = obj[i]["color"];
                    }
                }
            }else if(projectTypeId == 5){
                for (var i = 0; i < obj2.length; i++) {
                    if (value == obj2[i]["name"]) {
                        color = obj2[i]["color"];
                    }
                }
            }else if(projectTypeId == 4){
                for (var i = 0; i < obj3.length; i++) {
                    if (value == obj3[i]["name"]) {
                        color = obj3[i]["color"];
                    }
                }
            }else if(projectTypeId == 7){
                for (var i = 0; i < obj4.length; i++) {
                    if (value == obj4[i]["name"]) {
                        color = obj4[i]["color"];
                    }
                }
            }else if(projectTypeId == 22){
                for (var i = 0; i < obj5.length; i++) {
                    if (value == obj5[i]["name"]) {
                        color = obj5[i]["color"];
                    }
                }
            }else if(projectTypeId == 26){
                for (var i = 0; i < obj6.length; i++) {
                    if (value == obj6[i]["name"]) {
                        color = obj6[i]["color"];
                    }
                }
            }else if(projectTypeId == 14){
                for (var i = 0; i < obj7.length; i++) {
                    if (value == obj7[i]["name"]) {
                        color = obj7[i]["color"];
                    }
                }
            }

            if (value.match(";")) {
                color = "yellow";
            }

            var ar = columnArray();
            var rowCnt = parseInt(r) + 1;
            myspreadsheet.setStyle(ar[c] + rowCnt, 'background-color', color);

        },
        updateTable: function (el, cell, x, y, source, value, id) {
            if(myspreadsheet !== undefined){
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
            url: "/phase/entry/" + client + "/" + project + "/" + vic + "/" + pic + "/" + staff + "/" + role + "/" + year + "/" + month + "/" + day,
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
                                myspreadsheet.setStyle(ar[x] + rowCnt, 'background-color', "yellow");
                            } else {
                                myspreadsheet.setStyle(ar[x] + rowCnt, 'background-color', data.color[t][x]);
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
            error: () => {
                alert("ajax Error");
                jQuery('#loader-bg').hide();
            }
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
</script>
@endsection