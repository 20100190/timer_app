$(document).ready(function () {
    
     jQuery('#loader-bg').hide();
    
    //読込イベント処理を書く
    var div1 = $('#div1');
    var div2 = $('#div2');
    div2.scroll(function () {
        div1.scrollLeft(div2.scrollLeft())
    });
    div1.scroll(function () {
        div2.scrollLeft(div1.scrollLeft())
    });

    var buttonWidth = "300px";
    $('#client').multiselect({
        buttonWidth: buttonWidth,
        maxHeight: "500",
        enableFiltering: true,
        includeSelectAllOption: true,                
    });
    $('#project').multiselect({
        buttonWidth: buttonWidth, 
        maxHeight: "500",
        enableFiltering: true,
        includeSelectAllOption: true,                
    });
    $('#fye').multiselect({
        buttonWidth:"150",
        enableFiltering: true,
        includeSelectAllOption: true,
    });
    $('#vic').multiselect({
        buttonWidth: "150",
        enableFiltering: true,
        includeSelectAllOption: true,
    });
    $('#pic').multiselect({
        buttonWidth: 150,
        enableFiltering: true,
        includeSelectAllOption: true,
    });
    $('#sel_staff').multiselect({
        buttonWidth: 150,
        enableFiltering: true,
        includeSelectAllOption: true,
    });
    $('#sel_role').multiselect({
        buttonWidth: 150,
        enableFiltering: true,
        includeSelectAllOption: true,
    });
    
    $('.datepicker1').datepicker({
            format: "mm/dd/yyyy",
            language: "en",
            autoclose: true,
            orientation: 'bottom left'
     });

});

function closeOverrall() {
    var acWidth = document.getElementById("div1").style.height;
    var btnObj = document.getElementById("btn_open_close");
    if (acWidth == "0%") {
        btnObj.src = imagesUrl + "/close.png"
        document.getElementById("div1").style.height = "40%";
        document.getElementById("div1").style.minHeight = "350px";
        document.getElementById("div2").style.height = "40%";
        document.getElementById("div3").style.height = "300px";
        
        document.getElementById("div3").style.zIndex = "10";        
        
    } else {
        btnObj.src = imagesUrl + "/open.png"
        document.getElementById("div1").style.height = "0%";
        document.getElementById("div1").style.minHeight = "0px";
        document.getElementById("div2").style.height = "80%";
        document.getElementById("div3").style.height = "0px";
        
        document.getElementById("div3").style.zIndex = "0";
        
    }
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

function getData() {
    var clientObj = $("#client").val();
    var client = "blank";
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
    var dateFromObj = document.getElementById("filter_date_from");
    var dateToObj = document.getElementById("filter_date_to");

    client = setDelimiter(clientObj);
    project = setDelimiter(projectObj);
    fye = setDelimiter(fyeObj);
    vic = setDelimiter(vicObj);
    pic = setDelimiter(picObj);
    staff = setDelimiter(staffObj);
    role = setDelimiter(roleObj);

    dateFrom = "01-06-2020"
    dateTo = "12-31-2020"
    if (dateFromObj.value != "") {
        dateFrom = dateFromObj.value.split("/").join("-");
    }
    if (dateToObj.value != "") {
        dateTo = dateToObj.value.split("/").join("-");
    }
    
    $.ajax({
        url: "/budget/test3/data/" + client + "/" + project + "/" + fye + "/" + vic + "/" + pic + "/" + staff + "/" + role + "/" + dateFrom + "/" + dateTo,
        dataType: "json",
        success: data => {
            //初期化
            var table = document.getElementById("budget_list");
            while (table.rows.length > 2)
                table.deleteRow(2);                       
          
            //header
            var mnt = "";
            for (var i = 0; i < data.week.length; i++) {
                document.getElementById("month" + (i + 1)).innerHTML = "";
                document.getElementById("d_month" + (i + 1)).innerHTML = "";
                document.getElementById("h_month" + (i + 1)).innerHTML = "";
                document.getElementById("h2_month" + (i + 1)).innerHTML = "";

                document.getElementById("month" + (i + 1)).innerHTML = data.week[i].day;
                document.getElementById("d_month" + (i + 1)).innerHTML = data.week[i].day;

                if (mnt != data.week[i].month) {
                    var monthEng = getMonthEng(data.week[i].month);
                    document.getElementById("h_month" + (i + 1)).innerHTML = monthEng;//data.week[i].month;
                    document.getElementById("h2_month" + (i + 1)).innerHTML = monthEng;//data.week[i].month;
                }
                
                var bkColor = "#e2efda";
                var rowWidth = "40";
                //overall total style       
                document.getElementById("td_h2_month" + (i + 1)).style.cssText = "width: "+ rowWidth +"px;z-index: 0;text-align: center;background-color: "+ bkColor +";";
                document.getElementById("td_month" + (i + 1)).style.cssText = "width: "+ rowWidth +"px;z-index: 0;text-align: center;background-color: "+ bkColor +";";
                if (document.getElementById("h2_month" + (i + 1)).innerHTML != "") {
                    document.getElementById("td_h2_month" + (i + 1)).style.cssText = "width: "+ rowWidth +"px;z-index: 0;text-align: center;background-color: "+ bkColor +";border-left: solid 1px lightgray";
                    document.getElementById("td_month" + (i + 1)).style.cssText = "width: "+ rowWidth +"px;z-index: 0;text-align: center;background-color: "+ bkColor +";border-left: solid 1px lightgray";
                }
                
                //client list style
                document.getElementById("td_h_month" + (i + 1)).style.cssText = "width: "+ rowWidth +"px;z-index: 0;text-align: center;background-color: "+ bkColor +";";
                document.getElementById("td_d_month" + (i + 1)).style.cssText = "width: "+ rowWidth +"px;z-index: 0;text-align: center;background-color: "+ bkColor +";";
                if (document.getElementById("h_month" + (i + 1)).innerHTML != "") {
                    document.getElementById("td_h_month" + (i + 1)).style.cssText = "width: "+ rowWidth +"px;z-index: 0;text-align: center;background-color: "+ bkColor +";border-left: solid 1px lightgray";
                    document.getElementById("td_d_month" + (i + 1)).style.cssText = "width: "+ rowWidth +"px;z-index: 0;text-align: center;background-color: "+ bkColor +";border-left: solid 1px lightgray";
                }

                mnt = data.week[i].month;

            }

            //overall detail 初期化
            /*for (var a = 1; a <= 20; a++) {
                document.getElementById("ot_initial" + a).innerHTML = "";
                document.getElementById("ot_ptotal" + a).innerHTML = "";
                for (var b = 1; b <= 52; b++) {
                    document.getElementById("ot" + a + b).innerHTML = "";
                }
            }*/            
            var staffCount = 30
            for (var a = 1; a <= staffCount; a++) {
                document.getElementById("ot_initial" + a).innerHTML = "";
                document.getElementById("ot_ptotal" + a).innerHTML = "";
                for (var b = 1; b <= 52; b++) {
                    document.getElementById("ot" + ("00" + a).slice(-2) + b).innerHTML = "";
                }
            }
            
            //overall total 初期化
            for (var c = 1; c <= 52; c++) {
                document.getElementById("otTotal" + c).innerHTML = "";
            }
            
            for (var a = 1; a <= staffCount; a++) {
                for (var x = 0; x < data.week.length; x++) {
                    document.getElementById("ot" + ("00" + a).slice(-2) + (x + 1)).innerHTML = "";
                    document.getElementById("ot_uh" + a).innerHTML = "";
                    document.getElementById("ot" + ("00" + a).slice(-2) + (x + 1)).style.cssText = "";
                }
            }
                

            //overall detail
            for (var s = 0; s < data.total.length; s++) {
                if (data.total[s].staff_id !== null) {                                      
                    var colNo = getOverallTotalColNo(data.total[s].year, data.total[s].month, data.total[s].day, data.week);                                        
                    var operatingTime = data.week[colNo - 1].operating_time;
                    var staffId = ("00" + data.total[s].staff_id).slice(-2);                   
                    //document.getElementById("ot" + data.total[s].staff_id + colNo).style = "background-color: white";
                    document.getElementById("ot" + staffId + colNo).style = "background-color: white";
                    if (operatingTime < data.total[s].working_days) {                        
                        document.getElementById("ot" + staffId + colNo).style = "display:inline-block;height: 100%;width: 100%;background-color: #FFDBC9";
                    }
                    //document.getElementById("ot" + data.total[s].staff_id + colNo).innerHTML = Math.ceil(data.total[s].working_days);
                    document.getElementById("ot" + staffId + colNo).innerHTML = Math.ceil(data.total[s].working_days);
                    document.getElementById("ot_initial" + data.total[s].staff_id).innerHTML = data.total[s].initial;
                }
            }
            
            //罫線適用
            var baseStyle = "width: 50px;z-index: 0;text-align: right;background-color: white;";
            var borderStyle = "border-left: solid 1px lightgray";
            for (var x = 0; x < data.week.length; x++) {               
               //初期化
                document.getElementById("td_otTotal" + (x + 1)).style.cssText = baseStyle;
                for (y = 1; y <= staffCount; y++) {
                    document.getElementById("td_ot" + ("00" + y).slice(-2) + (x + 1)).style.cssText = baseStyle;
                }
                
                if (document.getElementById("h2_month" + (x + 1)).innerHTML != ""){
                    var str = baseStyle + borderStyle;
                    for(y = 1; y <= staffCount; y++){
                        document.getElementById("td_ot" + ("00" + y).slice(-2) + (x + 1)).style.cssText = str;                        
                    }     
                    
                    document.getElementById("td_otTotal" + (x + 1)).style.cssText += ";" + borderStyle;
                }
             
                //背景色
                var rowStyle = "display:inline-block;height: 100%;width: 100%;";
                var rowColorOdd = "background-color: aliceblue";
                var rowColorWhite = "background-color: white";
                var currentColor = rowColorOdd;
                for (var a = 1; a <= staffCount; a++) {
                    if (document.getElementById("ot_initial" + a).innerHTML != "") {
                        if (document.getElementById("ot" + ("00" + a).slice(-2) + (x + 1)).innerHTML == "") {
                            document.getElementById("ot" + ("00" + a).slice(-2) + (x + 1)).innerHTML = "&nbsp";
                        }
                        
                        if (document.getElementById("ot_uh" + a).innerHTML == "") {
                            document.getElementById("ot_uh" + a).innerHTML = "&nbsp";
                        }
                        
                        document.getElementById("ot_initial" + a).style = rowStyle + currentColor;
                        document.getElementById("ot_ptotal" + a).style = rowStyle + currentColor;
                        document.getElementById("ot_uh" + a).style = rowStyle + currentColor;
                        
                        if(document.getElementById("ot" + ("00" + a).slice(-2) + (x + 1)).style.cssText != "display: inline-block; height: 100%; width: 100%; background-color: rgb(255, 219, 201);"){
                            document.getElementById("ot" + ("00" + a).slice(-2) + (x + 1)).style = rowStyle + currentColor;//"display:inline-block;height: 100%;width: 100%;background-color: lightblue";
                        }
                        
                        if(currentColor == rowColorOdd){
                            currentColor = rowColorWhite;
                        }else{
                            currentColor = rowColorOdd;
                        }                    
                    }
                }
                
            }                        

            //overall personal total
            for (var s = 0; s < data.overallPTotal.length; s++) {
                if (data.overallPTotal[s].staff_id !== null) {
                    document.getElementById("ot_ptotal" + data.overallPTotal[s].staff_id).innerHTML = Number(data.overallPTotal[s].working_days).toLocaleString();
                }
            }

            //overall total            
            for (var s = 0; s < data.overallTotal.length; s++) {
                var colNo = getOverallTotalColNo(data.overallTotal[s].year, data.overallTotal[s].month, data.overallTotal[s].day, data.week);
                document.getElementById("otTotal" + colNo).innerHTML = Math.ceil(data.overallTotal[s].working_days);
            }
            
            //overall total week
            document.getElementById("otAll").innerHTML = "0";
            if (data.overallWeekTotal[0].working_days !== null) {
                document.getElementById("otAll").innerHTML =  Number(data.overallWeekTotal[0].working_days).toLocaleString();
            }
            
            //client list
            var table = document.getElementById("budget_list");
            var oldClient = "";
            var oldProject = "";
            var newClient = "";
            var newProject = "";

            var daysArray = new Array(52);
            for (var a = 0; a < daysArray.length; a++) {
                daysArray[a] = 0;                
            }
          

            var detailArray = [];
            var detailRowArray = new Array(62);
            
            var sumBudget = 0;
            var sumAssigned = 0;
            var sumDiff = 0;

            for (var s = 0; s < data.clientList.length; s++) {
                var tr = document.createElement('tr');
                var client = data.clientList[s][0];
                var project = data.clientList[s][1];
                var fye = data.clientList[s][2];
                var vic = data.clientList[s][3];
                var pic = data.clientList[s][4];
                var role = data.clientList[s][5];
                var assign = data.clientList[s][6];
                var budget = Math.ceil(data.clientList[s][7]);
                var assigned = Math.ceil(data.clientList[s][8]);
                var diff = Math.ceil(data.clientList[s][9]);               
                
                var row = data.clientList[s];

                if (assigned == 0) {
                    continue;
                }
                
                if (client == null) {
                    continue;
                }

                if (oldClient == "") {
                    oldClient = client;
                    oldProject = project;
                }
                
                sumBudget += budget;
                sumAssigned += assigned;
                sumDiff += diff;

                detailRowArray[0] = client;
                detailRowArray[1] = project;
                detailRowArray[2] = fye;
                detailRowArray[3] = vic;
                detailRowArray[4] = pic;
                detailRowArray[5] = role;
                detailRowArray[6] = assign;
                detailRowArray[7] = budget;
                detailRowArray[8] = assigned.toLocaleString();
                detailRowArray[9] = " ";
                if (diff != 0) {
                    detailRowArray[9] = diff.toLocaleString();
                }
                
                for (var w = 10; w < 62; w++) {
                    detailRowArray[w] = " ";
                    if (row[w] != 0) {
                        detailRowArray[w] = Math.ceil(row[w]);
                    }
                }
                detailArray.push(detailRowArray);
                detailRowArray = new Array(62);
              
                for (b = 0; b < daysArray.length; b++) {
                    if (row[10 + b] != "") {
                        //daysArray[b] += row[10 + b];
                        daysArray[b] += parseFloat(row[10 + b]);
                    }
                }
                

                if (s < data.clientList.length - 1) {
                    newClient = data.clientList[s + 1][0];
                    newProject = data.clientList[s + 1][1];
                }

                var backgroundColor = "e5e5e5";

                if (oldClient != newClient || oldProject != newProject) {
                    var tr = document.createElement('tr');

                    for (var x = 0; x < 62; x++) {
                        var td = document.createElement('td');
                        if (x == 0) {
                            td.innerHTML = client;
                            td.style.backgroundColor = backgroundColor;                            
                            td.classList.add("column_row_block");
                        }
                        if (x == 1) {
                            td.innerHTML = project + " Total";
                            td.style.backgroundColor = backgroundColor;
                            td.classList.add("column_row_block");
                            td.classList.add("col2");
                        }
                        if (x == 2) {
                            //td.innerHTML = fye;
                            td.style.backgroundColor = backgroundColor;
                            td.classList.add("column_row_block");
                            td.classList.add("col3");
                        }
                        if (x == 3) {
                            //td.innerHTML = vic;
                            td.style.backgroundColor = backgroundColor;
                            td.classList.add("column_row_block");
                            td.classList.add("col4");
                        }
                        if (x == 4) {
                            //td.innerHTML = pic;
                            td.style.backgroundColor = backgroundColor;
                            td.classList.add("column_row_block");
                            td.classList.add("col5");
                        }
                        if (x == 5) {
                            //td.innerHTML = role;
                            td.style.backgroundColor = backgroundColor;
                            td.classList.add("column_row_block");
                            td.classList.add("col6");
                        }
                        if (x == 6) {
                            //td.innerHTML = assign;
                            td.style.backgroundColor = backgroundColor;
                            td.classList.add("column_row_block");
                            td.classList.add("col7");
                        }
                        if (x == 7) {
                            td.innerHTML = "&nbsp";
                            if (sumBudget != 0) {
                                td.innerHTML = sumBudget;
                            }
                            td.style.cssText = "text-align:right; background-color: " + backgroundColor;
                            td.classList.add("column_row_block");
                            td.classList.add("col8");
                            sumBudget = 0;
                        }
                        if (x == 8) {
                            td.innerHTML = sumAssigned;
                            td.style.cssText = "text-align:right; background-color: " + backgroundColor;
                            td.classList.add("column_row_block");
                            td.classList.add("col9");
                            sumAssigned = 0;
                        }
                        if (x == 9) {
                            td.innerHTML = "&nbsp";
                            if (sumDiff != 0) {
                                td.innerHTML = sumDiff;
                            }
                            td.style.cssText = "text-align:right; background-color: " + backgroundColor;
                            td.classList.add("column_row_block");
                            td.classList.add("col10");
                            sumDiff = 0;
                        }
                        for (c = 0; c < daysArray.length; c++) {
                            if (x == c + 10) {
                                if (daysArray[c] != 0) {
                                    td.innerHTML = Math.ceil(daysArray[c]);
                                }
                                td.style.backgroundColor = backgroundColor;
                                td.align = "right";
                                
                                if(document.getElementById("h_month" + (c+1)).innerHTML != ""){
                                    td.style.borderLeft = "solid 1px lightgray";
                                }
                                
                            }
                        }

                        tr.appendChild(td);
                    }

                    table.appendChild(tr);

                    for (var a = 0; a < daysArray.length; a++) {
                        daysArray[a] = 0;                        
                    }

                    //Detail                    
                    for (var x = 0; x < detailArray.length; x++) {
                        var xtr = document.createElement('tr');

                        for (var f = 0; f < 62; f++) {
                            var xtd = document.createElement('td');
                            xtd.innerHTML = detailArray[x][f];

                            if (f == 0) {
                                xtd.style.backgroundColor = "white";
                                xtd.classList.add("column_row_block");
                            }
                            if (f == 1) {
                                xtd.style.backgroundColor = "white";
                                xtd.classList.add("column_row_block");
                                xtd.classList.add("col2");
                            }
                            if (f == 2) {
                                xtd.style.backgroundColor = "white";
                                xtd.classList.add("column_row_block");
                                xtd.classList.add("col3");
                            }
                            if (f == 3) {
                                xtd.style.backgroundColor = "white";
                                xtd.classList.add("column_row_block");
                                xtd.classList.add("col4");
                            }
                            if (f == 4) {
                                xtd.style.backgroundColor = "white";
                                xtd.classList.add("column_row_block");
                                xtd.classList.add("col5");
                            }
                            if (f == 5) {
                                xtd.style.backgroundColor = "white";
                                xtd.classList.add("column_row_block");
                                xtd.classList.add("col6");
                            }
                            if (f == 6) {
                                xtd.style.backgroundColor = "white";                                
                                xtd.classList.add("column_row_block");
                                xtd.classList.add("col7");
                            }
                            if (f == 7) {                                
                                xtd.style.cssText = "text-align:right; background-color: white";
                                xtd.classList.add("column_row_block");
                                xtd.classList.add("col8");
                            }
                            if (f == 8) {
                                xtd.style.cssText = "text-align:right; background-color: white";
                                xtd.classList.add("column_row_block");
                                xtd.classList.add("col9");
                            }
                            if (f == 9) {
                                xtd.style.cssText = "text-align:right; background-color: white";
                                xtd.classList.add("column_row_block");
                                xtd.classList.add("col10");
                            }
                            if (f >= 10) {
                                xtd.style.textAlign = "right";                                
                                if (document.getElementById("h_month" + (f-9)).innerHTML != "") {
                                    xtd.style.borderLeft = "solid 1px lightgray";
                                }
                            }

                            xtr.appendChild(xtd);
                        }

                        table.appendChild(xtr);
                    }
                    detailArray = [];



                }

                oldClient = newClient;
                oldProject = newProject;

            }

            if (detailArray.length > 0) {

                var tr = document.createElement('tr');

                for (var x = 0; x < 62; x++) {
                    var td = document.createElement('td');
                    if (x == 0) {
                        td.innerHTML = client;
                        td.style.backgroundColor = backgroundColor;
                        td.classList.add("column_row_block");
                    }
                    if (x == 1) {
                        td.innerHTML = project + " Total";
                        td.style.backgroundColor = backgroundColor;
                        td.classList.add("column_row_block");
                        td.classList.add("col2");
                    }
                    if (x == 2) {
                        //td.innerHTML = fye;
                        td.style.backgroundColor = backgroundColor;
                        td.classList.add("column_row_block");
                        td.classList.add("col3");
                    }
                    if (x == 3) {
                        //td.innerHTML = vic;
                        td.style.backgroundColor = backgroundColor;
                        td.classList.add("column_row_block");
                        td.classList.add("col4");
                    }
                    if (x == 4) {
                        //td.innerHTML = pic;
                        td.style.backgroundColor = backgroundColor;
                        td.classList.add("column_row_block");
                        td.classList.add("col5");
                    }
                    if (x == 5) {
                        //td.innerHTML = role;
                        td.style.backgroundColor = backgroundColor;
                        td.classList.add("column_row_block");
                        td.classList.add("col6");
                    }
                    if (x == 6) {
                        //td.innerHTML = assign;
                        td.style.backgroundColor = backgroundColor;
                        td.classList.add("column_row_block");
                        td.classList.add("col7");
                    }
                    if (x == 7) {
                        //td.innerHTML = budget;
                        td.innerHTML = sumBudget;                       
                        td.style.cssText = "text-align:right; background-color: " + backgroundColor;
                        td.classList.add("column_row_block");
                        td.classList.add("col8");
                        sumBudget = 0;
                    }
                    if (x == 8) {
                        //td.innerHTML = assigned;
                        td.innerHTML = sumAssigned;
                        td.style.cssText = "text-align:right; background-color: " + backgroundColor;
                        td.classList.add("column_row_block");
                        td.classList.add("col9");
                        sumAssigned = 0;
                    }
                    if (x == 9) {
                        //td.innerHTML = diff;
                        td.innerHTML = sumDiff;
                        td.style.cssText = "text-align:right; background-color: " + backgroundColor;
                        td.classList.add("column_row_block");
                        td.classList.add("col10");
                        sumDiff = 0;
                    }
                    for (c = 0; c < daysArray.length; c++) {
                        if (x == c + 10) {
                            if (daysArray[c] != 0) {
                                td.innerHTML = Math.ceil(daysArray[c]);
                            }
                            
                            td.style.backgroundColor = backgroundColor;
                            td.align = "right";
                            
                            if (document.getElementById("h_month" + (c + 1)).innerHTML != "") {
                                td.style.borderLeft = "solid 1px lightgray";
                            }
                        }
                    }

                    tr.appendChild(td);
                }

                table.appendChild(tr);


                for (var x = 0; x < detailArray.length; x++) {
                    var xtr = document.createElement('tr');
                    for (var f = 0; f < 62; f++) {
                        var xtd = document.createElement('td');
                        xtd.innerHTML = detailArray[x][f];

                        if (f == 0) {
                            xtd.style.backgroundColor = "white";
                            xtd.classList.add("column_row_block");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f == 1) {
                            xtd.style.backgroundColor = "white";
                            xtd.classList.add("column_row_block");
                            xtd.classList.add("col2");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f == 2) {
                            xtd.style.backgroundColor = "white";
                            xtd.classList.add("column_row_block");
                            xtd.classList.add("col3");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f == 3) {
                            xtd.style.backgroundColor = "white";
                            xtd.classList.add("column_row_block");
                            xtd.classList.add("col4");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f == 4) {
                            xtd.style.backgroundColor = "white";
                            xtd.classList.add("column_row_block");
                            xtd.classList.add("col5");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f == 5) {
                            xtd.style.backgroundColor = "white";
                            xtd.classList.add("column_row_block");
                            xtd.classList.add("col6");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f == 6) {
                            xtd.style.backgroundColor = "white";
                            xtd.classList.add("column_row_block");
                            xtd.classList.add("col7");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f == 7) {
                            //xtd.style.backgroundColor = "white";
                            xtd.style.cssText = "text-align:right; background-color: white";
                            xtd.classList.add("column_row_block");
                            xtd.classList.add("col8");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f == 8) {
                            //xtd.style.backgroundColor = "white";
                            xtd.style.cssText = "text-align:right; background-color: white";
                            xtd.classList.add("column_row_block");
                            xtd.classList.add("col9");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f == 9) {
                            //xtd.style.backgroundColor = "white";
                            xtd.style.cssText = "text-align:right; background-color: white";
                            xtd.classList.add("column_row_block");
                            xtd.classList.add("col10");
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }
                        if (f >= 10) {
                            xtd.style.textAlign = "right";
                            if (document.getElementById("h_month" + (f - 9)).innerHTML != "") {
                                xtd.style.borderLeft = "solid 1px lightgray";
                            }
                            
                            if(x == detailArray.length - 1){
                                xtd.style.borderBottom = "solid 1px lightgray";
                            }
                        }

                        xtr.appendChild(xtd);
                    }

                    table.appendChild(xtr);
                }
                detailArray = [];
            }
           
            for (var a = 0; a < daysArray.length; a++) {
                daysArray[a] = 0;                
            }
            
        },
        beforeSend: function (xhr, settings) {
            //処理中
            //$("#loadingSpinner").css("visibility", "visible");
            //$("#btn_load").attr('disabled', true);
            jQuery('#loader-bg').show();
        },
        complete: function (xhr, textStatus) {
            //$("#loadingSpinner").css("visibility", "hidden");
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

function getOverallTotalColNo(year, month, day, week) {
    var colNo = "";
    for (var i = 0; i < week.length; i++) {
        if (year == week[i].year && month == week[i].month && day == week[i].day) {
            colNo = i;
            break;
        }
    }
    return colNo + 1;
}

function convFormula(formula) {
    if (formula.match("SUM")) {

    } else {

    }
    return val;
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
    array[56] = "BE";
    array[57] = "BF";
    array[58] = "BG";
    array[59] = "BH";
    array[60] = "BI";
    array[61] = "BJ";

    return array;
}

function getMonthEng(month){
    var eng = "";
    if(month == 1){
        eng = "Jan";
    }else if(month == 2){
        eng = "Feb";
    }else if(month == 3){
        eng = "Mar";
    }else if(month == 4){
        eng = "Apr";
    }else if(month == 5){
        eng = "May";
    }else if(month == 6){
        eng = "Jun";
    }else if(month == 7){
        eng = "Jul";
    }else if(month == 8){
        eng = "Aug";
    }else if(month == 9){
        eng = "Sep";
    }else if(month == 10){
        eng = "Oct";
    }else if(month == 11){
        eng = "Nov";
    }else if(month == 12){
        eng = "Dec";
    }
    
    return eng;
}

function clearShowFilter(){
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
    
    document.getElementById("filter_date_from").value = "";
    document.getElementById("filter_date_to").value = "";
}
