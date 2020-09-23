@extends('layouts.main')

@section('content') 

<form action="work" method="POST" name="s" style="margin-left: 20px">
    {{ csrf_field() }}  
    <div id="filter_area" style="margin-top: 30px;">
        <div id="filter_left" style="float: left;height: 150px;margin-bottom: 50px">
            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3" >
                    <span class="line-height">Client<font style="color: red;vertical-align: middle">&nbsp;&nbsp;&nbsp;*</font></span>
                </div>
                <div class="col col-md-3">
                    <select id="client" name="client" class="form-control select2" data-display="static">    
                        <option value="">&nbsp;</option>
                        @foreach ($client as $clients)
                        <option value="{{$clients->id}}">{{$clients->name}}</option>
                        @endforeach
                    </select>
                </div>  
            </div>

            <div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-3">
                    <span class="line-height">Project Type<font style="color: red;vertical-align: middle">&nbsp;*</font></span>
                </div>
                <div class="col col-md-1">
                    <select id="project" name="project" style="width: 200px">     
                        <option value="">&nbsp;</option>
                        @foreach ($project as $projects)
                        <option value="{{$projects->id}}">{{$projects->project_type}}</option>
                        @endforeach
                    </select>
                </div>                
            </div>
            <!--<div class="row entry-filter-bottom" style="zoom: 100%">
                <div class="col col-md-2">
                    <span class="line-height">Group</span>
                </div>
                <div class="col col-md-1">
                    <select id="group" name="group" style="width: 200px">     
                        <option value="">&nbsp</option>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>                        
                    </select>
                </div>                
            </div>-->

            <div class="row entry-filter-bottom">    
                <div class="col col-md-1" style="margin-left: 6px" >
                </div>
                
                <div class="col col-md-3" >
                    <input type="button" class="btn btn-default" value="Clear" onclick="clearFilter()" style="background-color: white;width: 150px;margin-left: 85px">
                </div>
                <div class="col" >
                    <button id="btn_load" name="btn_load" class="btn btn-primary" type="button" onclick="loadPhaseData()" style="width: 150px;margin-left: 140px">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="visibility: hidden"></span>
                        <span id="loadingText">Search</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div style="clear: both"></div>

    <div>
        @for($i=1;$i<=10;$i++)
        <div style="margin-bottom: 50px">
            <div><label style="font-size: 20px;width: 135px"><input type="text" id="label_phase{{$i}}" name="label_phase{{$i}}" style="vertical-align: middle;border:solid 0px;" readonly></label><input type="button" id="contact_list{{$i}}" name="contact_list{{$i}}" class="btn btn-primary btn-sm" style="width: 150px" value="Add" onclick="appendPhase1Row(this)"></div>
            <table border="0" id="phase_{{$i}}" class="table table-sm" style="font-size: 14px;table-layout: fixed;width: 650px">  
                <thead>
                    <tr>
                        <th style="width: 50px">No</th>
                        <th style="width: 150px">Task</th>
                        <th style="width: 300px">Description</th>
                    </tr>
                </thead>
                <tbody id="phase{{$i}}_body"></tbody>
            </table>
        </div>
        @endfor
    </div>
    
    <div class="form-group">            
        <div class="col-md-4">
            <input class="btn btn-primary" type="submit" value="Update">
        </div>
    </div>  

    <input type="hidden" value="" id="postArray" name="postArray">
    <input type="hidden" id="budget_info" name="budget_info" value="">

</form>

<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#loader-bg').hide();
        var buttonWidth = "400px";

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
        $('#group').multiselect({
            buttonWidth: buttonWidth,
            maxHeight: 700,
            enableFiltering: false,
        });
    });

    function appendPhase1Row(obj) {
        var buttonName = obj.name;
        var buttonIndex = buttonName.replace("contact_list","")
        var objTBL = document.getElementById("phase_" + buttonIndex);
        if (!objTBL)
            return;

        insertPhase1Row("", "", "",buttonIndex);
    }

    function insertPhase1Row(id, name, description,buttonIndex) {
        // 最終行に新しい行を追加
        var phase1_tbody = document.getElementById("phase" + buttonIndex + "_body");
        var bodyLength = phase1_tbody.rows.length;
        var count = bodyLength + 1;
        var row = phase1_tbody.insertRow(bodyLength);
        
        if(id != ""){
            count = id;
        }

        var imagesUrl = '{{URL::asset('/image')}}';

        // 列の追加
        var c1 = row.insertCell(0);
        var c2 = row.insertCell(1);
        var c3 = row.insertCell(2);
        var c4 = row.insertCell(3);
        
        c1.style.cssText = "vertical-align: middle";

        // 各列に表示内容を設定
        c1.innerHTML = '<span class="seqno-phase' + buttonIndex + '">' + count + '</span>';
        c2.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'task" type="text" id="phase' + buttonIndex + '_task' + count + '" name="phase' + buttonIndex + '_task' + count + '" value="' + name + '" style="width: 100%">';
        c3.innerHTML = '<input class="form-control inpphase' + buttonIndex + 'description" type="text" id="phase' + buttonIndex + '_description' + count + '" name="phase' + buttonIndex + '_description' + count + '" value="' + description + '" style="width: 100%">';
        c4.innerHTML = '<button class="delphase' + buttonIndex + 'btn btn btn-sm" type="button" id="delPhase' + buttonIndex + 'Btn' + count + '" value="Delete" onclick="return deletePhase1Row(this,' + buttonIndex + ')" style="background-color: transparent"><img src="' + imagesUrl + "/delete.png" + '"></button>';
    }
    
    function deletePhase1Row(obj,buttonIndex) {
        delRowCommon(obj, "seqno-phase" + buttonIndex);

        // id/name ふり直し
        var tagElements = document.getElementsByTagName("input");
        if (!tagElements)
            return false;

        var seq = 1;
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "task", "phase" + buttonIndex + "_task");
        reOrderElementTag(tagElements, "inpphase" + buttonIndex + "description", "phase" + buttonIndex + "_description");        
        
        reOrderElementTag(tagElements, "delphase" + buttonIndex + "btn", "delPhase" + buttonIndex + "Btn");

        //reOrderTaskNo();
    }
    
    function delRowCommon(obj, seqNoId) {
        // 確認
        if (!confirm("この行を削除しますか？"))
            return;

        if (!obj)
            return;

        var objTR = obj.parentNode.parentNode;
        var objTBL = objTR.parentNode;

        if (objTBL)
            objTBL.deleteRow(objTR.sectionRowIndex);

        // <span> 行番号ふり直し
        var tagElements = document.getElementsByTagName("span");
        if (!tagElements)
            return false;

        var seq = 1;
        for (var i = 0; i < tagElements.length; i++)
        {
            //if (tagElements[i].className.match(seqno))
            if (tagElements[i].className === seqNoId)
                tagElements[i].innerHTML = seq++;
        }
    }

    function reOrderElementTag(tagElements, className, idName) {
        var seq = 1;
        for (var i = 0; i < tagElements.length; i++)
        {
            if (tagElements[i].className.match(className)) {
                tagElements[i].setAttribute("id", idName + seq);
                tagElements[i].setAttribute("name", idName + seq);
                ++seq;
            }
        }
    }
  

    function loadPhaseData() {

        var client = $("#client").val();
        var project = $("#project").val();
        //var group = $("#group").val();
        
        //if(group == ""){
        //    group = "blank";
        //}            

        $.ajax({
            url: "/test3/getPhaseInfo/" + client + "/" + project + "/" + "blank" + "/",
        }).success(function (data) {
            
           clearAllList();
           
           for(var i = 0; i < data.phase.length; i++){
               document.getElementById("label_phase" + (parseInt(i) + 1)).value = data.phase[i].name;
           }            
            
            //detail            
            for (var cnt = 0; cnt < data.phase1Detail.length; cnt++) {               
                for (var cnt2 = 0; cnt2 < data.phase1Detail[cnt].length; cnt2++) {
                    var buttonIndex = cnt + 1;
                    var rowId = cnt2 + 1;
                    insertPhase1Row(parseInt(rowId),data.phase1Detail[cnt][cnt2].name,data.phase1Detail[cnt][cnt2].description,buttonIndex);
                }
            }



        }).error(function (XMLHttpRequest, textStatus, errorThrown) {
            clearAllList();
            
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Error',
                html: "Project does not exist"
            });
            
            //alert('error!!!');
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    }
    
    function clearAllList(){
        for(var i=1; i<=10; i++){
            var table = document.getElementById("phase_" + parseInt(i));
            var label = document.getElementById("label_phase" + parseInt(i));
            //Label初期化
            label.value = "";
            //List初期化
            while( table.rows[ 1 ] ) table.deleteRow( 1 );
        }        
    }
    
    function clearFilter(){
        $('#client').multiselect('select',"");
        $('#project').multiselect('select',"");
        $('#group').multiselect('select',"");     
    }


</script>

@endsection