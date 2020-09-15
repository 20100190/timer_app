@extends('layouts.main')
@section("content")
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#loader-bg').hide();
    });
      
    function appendContactRow() {
        var objTBL = document.getElementById("contact_list");
        if (!objTBL)
            return;

        insertContactRow("", "", "", true);
    }
    
    function appendUsRow() {
        var objTBL = document.getElementById("us_shareholder_list");
        if (!objTBL)
            return;

        insertUsShareholderRow("", "", "", true);
    }
    
    function appendForeignRow() {
        var objTBL = document.getElementById("foreign_shareholder_list");
        if (!objTBL)
            return;

        insertForeignShareholderRow("", "", "", true);
    }
    
    function appendOfficerRow() {
        var objTBL = document.getElementById("officer_table_list");
        if (!objTBL)
            return;

        insertOfficerRow("", "", "", true);
    }

    function insertContactRow(name, status, taskId, isNew) {
        // 最終行に新しい行を追加
        var contact_tbody = document.getElementById("contact_person_body");
        var bodyLength = contact_tbody.rows.length;
        var count = bodyLength + 1;
        var row = contact_tbody.insertRow(bodyLength);

        var imagesUrl = '{{URL::asset('/image')}}';

        // 列の追加
        var c1 = row.insertCell(0);
        var c2 = row.insertCell(1);
        var c3 = row.insertCell(2);
        var c4 = row.insertCell(3);
        var c5 = row.insertCell(4);
        var c6 = row.insertCell(5);
        var c7 = row.insertCell(6);
        var c8 = row.insertCell(7);
        var c9 = row.insertCell(8);

        // 各列に表示内容を設定
        c1.innerHTML = '<span class="seqno-contact">' + count + '</span>';
        c2.innerHTML = '<input class="form-control inpcontact" type="text" id="contact_person' + count + '" name="contact_person' + count + '" value="" style="width: 100%">';
        c3.innerHTML = '<input class="form-control inptitle" type="text" id="title' + count + '" name="title' + count + '" value="" style="width: 100%">';
        c4.innerHTML = '<input class="form-control inpcontactjp" type="text" id="contact_person_jp' + count + '" name="contact_person_jp' + count + '" value="" style="width: 100%">';
        c5.innerHTML = '<input class="form-control inptelephone" type="text" id="telephone' + count + '" name="telephone' + count + '" value="" style="width: 100%">';
        c6.innerHTML = '<input class="form-control inpcellphone" type="text" id="cellphone' + count + '" name="cellphone' + count + '" value="" style="width: 100%">';
        c7.innerHTML = '<input class="form-control inpfax" type="text" id="fax' + count + '" name="fax' + count + '" value="" style="width: 100%">';
        c8.innerHTML = '<input class="form-control inpemail" type="text" id="email' + count + '" name="email' + count + '" value="" style="width: 100%">';
        c9.innerHTML = '<button class="delbtn btn btn-sm" type="button" id="delBtnContact' + count + '" value="Delete" onclick="return deleteContactRow(this)" style="background-color: transparent"><img src="' + imagesUrl + "/delete.png" + '"></button>';
    }
    
    function insertUsShareholderRow(name, status, taskId, isNew) {
        // 最終行に新しい行を追加
        var us_tbody = document.getElementById("us_shareholder_body");
        var bodyLength = us_tbody.rows.length;
        var count = bodyLength + 1;
        var row = us_tbody.insertRow(bodyLength);

        var imagesUrl = '{{URL::asset('/image')}}';

        // 列の追加
        var c1 = row.insertCell(0);
        var c2 = row.insertCell(1);
        var c3 = row.insertCell(2);
        var c4 = row.insertCell(3);
        
        // 各列に表示内容を設定
        c1.innerHTML = '<span class="seqno-usshareholder">' + count + '</span>';
        c2.innerHTML = '<input class="form-control inpusname" type="text" id="us_name' + count + '" name="us_name' + count + '" value="" style="width: 100%">';
        c3.innerHTML = '<input class="form-control inpuspercent" type="text" id="us_percent' + count + '" name="us_percent' + count + '" value="" style="width: 100%">';        
        c4.innerHTML = '<button class="delusbtn btn btn-sm" type="button" id="delBtnUs' + count + '" value="Delete" onclick="return deleteUsRow(this)" style="background-color: transparent"><img src="' + imagesUrl + "/delete.png" + '"></button>';
    }
    
    function insertForeignShareholderRow(name, status, taskId, isNew) {
        // 最終行に新しい行を追加
        var foreign_tbody = document.getElementById("foreign_shareholder_body");
        var bodyLength = foreign_tbody.rows.length;
        var count = bodyLength + 1;
        var row = foreign_tbody.insertRow(bodyLength);

        var imagesUrl = '{{URL::asset('/image')}}';

        // 列の追加
        var c1 = row.insertCell(0);
        var c2 = row.insertCell(1);
        var c3 = row.insertCell(2);
        var c4 = row.insertCell(3);
        
        // 各列に表示内容を設定
        c1.innerHTML = '<span class="seqno-foreignshareholder">' + count + '</span>';
        c2.innerHTML = '<input class="form-control inpforeignname" type="text" id="foreign_name' + count + '" name="foreign_name' + count + '" value="" style="width: 100%">';
        c3.innerHTML = '<input class="form-control inpforeignpercent" type="text" id="foreign_percent' + count + '" name="foreign_percent' + count + '" value="" style="width: 100%;text-align: right">';        
        c4.innerHTML = '<button class="delforeignbtn btn btn-sm" type="button" id="delBtnForeign' + count + '" value="Delete" onclick="return deleteForeignRow(this)" style="background-color: transparent"><img src="' + imagesUrl + "/delete.png" + '"></button>';
    }
    
    function insertOfficerRow(name, status, taskId, isNew) {
        // 最終行に新しい行を追加
        var officers_tbody = document.getElementById("officers_body");
        var bodyLength = officers_tbody.rows.length;
        var count = bodyLength + 1;
        var row = officers_tbody.insertRow(bodyLength);

        var imagesUrl = '{{URL::asset('/image')}}';

        // 列の追加
        var c1 = row.insertCell(0);
        var c2 = row.insertCell(1);
        var c3 = row.insertCell(2);
        var c4 = row.insertCell(3);
        
        // 各列に表示内容を設定
        c1.innerHTML = '<span class="seqno-officer">' + count + '</span>';
        c2.innerHTML = '<input class="form-control inpofficername" type="text" id="officer_name' + count + '" name="officer_name' + count + '" value="" style="width: 100%">';
        c3.innerHTML = '<input class="form-control inpofficertitle" type="text" id="officer_title' + count + '" name="officer_title' + count + '" value="" style="width: 100%;">';        
        c4.innerHTML = '<button class="delofficerbtn btn btn-sm" type="button" id="delBtnOfficer' + count + '" value="Delete" onclick="return deleteOfficerRow(this)" style="background-color: transparent;width: 100%"><img src="' + imagesUrl + "/delete.png" + '"></button>';
    }

    function deleteContactRow(obj) {
        delRowCommon(obj, "seqno-contact");

        // id/name ふり直し
        var tagElements = document.getElementsByTagName("input");
        if (!tagElements)
            return false;

        var seq = 1;
        reOrderElementTag(tagElements, "inpcontact", "contact_person");
        reOrderElementTag(tagElements, "inptitle", "title");
        reOrderElementTag(tagElements, "inpcontactjp", "contact_person_jp");
        reOrderElementTag(tagElements, "inptelephone", "telephone");
        reOrderElementTag(tagElements, "inpcellphone", "cellphone");
        reOrderElementTag(tagElements, "inpfax", "fax");
        reOrderElementTag(tagElements, "inpemail", "email");

        reOrderElementTag(tagElements, "delbtn", "delBtn");

        //reOrderTaskNo();
    }
    
    function deleteUsRow(obj) {
        delRowCommon(obj, "seqno-usshareholder");

        // id/name ふり直し
        var tagElements = document.getElementsByTagName("input");
        if (!tagElements)
            return false;

        var seq = 1;
        reOrderElementTag(tagElements, "inpusname", "us_name");
        reOrderElementTag(tagElements, "inpuspercent", "us_percent");        
        
        reOrderElementTag(tagElements, "delusbtn", "delBtnUs");

        //reOrderTaskNo();
    }
    
    function deleteForeignRow(obj) {
        delRowCommon(obj, "seqno-foreignshareholder");

        // id/name ふり直し
        var tagElements = document.getElementsByTagName("input");
        if (!tagElements)
            return false;

        var seq = 1;
        reOrderElementTag(tagElements, "inpforeignname", "foreign_name");
        reOrderElementTag(tagElements, "inpforeignpercent", "foreign_percent");        
        
        reOrderElementTag(tagElements, "delforeignbtn", "delBtnForeign");

        //reOrderTaskNo();
    }
    
    function deleteOfficerRow(obj) {
        delRowCommon(obj, "seqno-officer");

        // id/name ふり直し
        var tagElements = document.getElementsByTagName("input");
        if (!tagElements)
            return false;

        var seq = 1;
        reOrderElementTag(tagElements, "inpofficername", "officer_name");
        reOrderElementTag(tagElements, "inpofficertitle", "officer_title");        
        
        reOrderElementTag(tagElements, "delofficerbtn", "delBtnOfficer");

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
    
</script>
<div style="margin-left: 20px;margin-top: 20px">
    <!--<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">                            
                    <div class="panel-body">-->
    <a href="{{ url("master/client") }}" title="Back"><button class="btn btn-primary btn-sm">Back</button></a>
    <br />
    <br />

    @if ($errors->any())
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif


    <form method="POST" action="/master/client/store" class="form-horizontal">
        {{ csrf_field() }}

        <div style="float: left;margin-right: 50px">
            <table class="table table-borderless">                                
                <tbody>                    
                    <tr>
                        <th style="vertical-align: middle;">Name</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="name" type="text" id="name" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">FYE</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="fye" type="text" id="fye" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Vic Status</th>
                        <td style="vertical-align: middle;">                            
                            <select id="vic_status" name="vic_status" class="form-control" >                            
                                <option value="VIC">VIC</option>
                                <option value="IC">IC</option>
                                <option value="C">C</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Group Companies</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="group_companies" type="text" id="group_companies" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Website</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="website" type="text" id="website" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Address US</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="address_us" type="text" id="address_us" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Address JP</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="address_jp" type="text" id="address_jp" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Mailing Address</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="mailing_address" type="text" id="mailing_address" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Tel1</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="tel1" type="text" id="tel1" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Tel2</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="tel2" type="text" id="tel2" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Tel3</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="tel3" type="text" id="tel3" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Fax</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="fax" type="text" id="fax" value=""></td>
                    </tr>                                
                </tbody>
            </table>
        </div>    

        <div style="float: left;margin-right: 50px">
            <table class="table table-borderless">                  
                <tbody>                
                    <tr>
                        <th style="vertical-align: middle;">Federal ID</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="federal_id" type="text" id="federal_id" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">State ID</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="state_id" type="text" id="state_id" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Edd ID</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="edd_id" type="text" id="edd_id" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Note</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="note" type="text" id="note" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">PIC</th>
                        <td style="vertical-align: middle;">                           
                            <select id="pic" name="pic" class="form-control" >                            
                                @foreach ($picData as $items)
                                <option value="{{$items->id}}">{{$items->initial}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Nature of Business</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="nature_of_business" type="text" id="nature_of_business" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Incorporation Date</th>
                        <td style="vertical-align: middle;"><input class="form-control datepicker1" name="incorporation_date" type="text" id="incorporation_date" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Incorporation State</th>
                        <td style="vertical-align: middle;"><input class="form-control" name="incorporation_state" type="text" id="incorporation_state" value=""></td>
                    </tr>
                    <tr>
                        <th style="vertical-align: middle;">Business Started</th>
                        <td style="vertical-align: middle;"><input class="form-control datepicker1" name="business_started" type="text" id="business_started" value=""></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="float: left">
            <div><label style="font-size: 20px;width: 180px">Contact Person</label><input type="button" id="contact_list" name="contact_list" class="btn btn-primary btn-sm project-button" value="Add" onclick="appendContactRow()"></div>
            <table border="0" id="contact_person_table" class="table table-sm" style="font-size: 12px;table-layout: fixed;width: 650px">                
                <thead>
                    <tr>
                        <th class="project-font-size" style="text-align:center; width:40px;">No</th>
                        <th class="project-font-size" style="width: 200px">Contact Person</th>
                        <th class="project-font-size" style="width: 120px">Title</th>
                        <th class="project-font-size" style="width: 200px">Contact Person 日本語</th>
                        <th class="project-font-size" style="width: 100px">TelePhone</th>
                        <th class="project-font-size" style="width: 100px">Cell Phone</th>
                        <th class="project-font-size" style="width: 100px">FAX</th>
                        <th class="project-font-size" style="width: 150px">Email</th>
                        <th style="width:40px;"> </th>
                    </tr> 
                </thead>
                <tbody id="contact_person_body">                    
                </tbody>
            </table>

            <div style="float: left;margin-top: 20px;margin-bottom: 20px"> 
                <div><label style="font-size: 20px;width: 180px">US Shareholders</label><input type="button" id="us_list" name="us_list" class="btn btn-primary btn-sm project-button" value="Add" onclick="appendUsRow()"></div>
                <table class="table" border="0" id="us_shareholder_list" style="width: 400px">
                    <thead>                    
                        <tr>
                            <th class="project-font-size" style="text-align:center; width:40px;">No</th>
                            <th class="project-font-size" style="width: 250px">Name</th>
                            <th class="project-font-size" style="width: 70px">%</th>  
                            <th style="width:40px;"> </th>
                        </tr> 
                    </thead>
                    <tbody id="us_shareholder_body">                       
                    </tbody>
                </table>
            </div>
            <div style="float: left;margin-top: 20px;margin-left: 110px">
                <div><label style="font-size: 20px;width: 200px">Foreign Shareholders</label><input type="button" id="foreign_list" name="foreign_list" class="btn btn-primary btn-sm project-button" value="Add" onclick="appendForeignRow()"></div>
                <table class="table" border="0" id="foreign_shareholder_list" style="width: 400px">
                    <thead>                    
                        <tr>
                            <th class="project-font-size" style="text-align:center; width:40px;">No</th>
                            <th class="project-font-size" style="width: 250px">Name</th>
                            <th class="project-font-size" style="width: 70px">%</th>                   
                        </tr> 
                    </thead>
                    <tbody id="foreign_shareholder_body">                        
                    </tbody>
                </table>
            </div>     

            <div style="width: 440px">
                <div><label style="font-size: 20px;width: 180px">Officers</label><input type="button" id="officers_list" name="officers_list" class="btn btn-primary btn-sm project-button" value="Add" onclick="appendOfficerRow()"></div>
                <table class="table" id="officer_table_list">
                    <thead>                   
                        <tr>
                            <th class="project-font-size" style="text-align:center; width:40px;">No</th>
                            <th class="project-font-size" style="width: 200px">Name</th>
                            <th class="project-font-size" style="width: 200px">Title</th>   
                            <th style="width:40px;"> </th>
                        </tr> 
                    </thead>
                    <tbody id="officers_body">   
                    </tbody>
                </table>
            </div>
        </div>

        <div style="clear: both"></div>

        <div class="form-group">            
            <div class="col-md-4">
                <input class="btn btn-primary" type="submit" value="Create">
            </div>
        </div>   
    </form>
    
    <script type="text/javascript">     
    $('.datepicker1').datepicker({
        format: "mm/dd/yyyy",
        language: "en",
        autoclose: true,
        orientation: 'bottom left'
    });
    </script>


    <!--</div>
</div>
</div>
</div>
</div>-->
</div>
@endsection
