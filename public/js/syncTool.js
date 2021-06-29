$(document).ready(function () {
    jQuery('#loader-bg').hide();   

    var m = moment();    
    var text = m.format('MM/DD/YYYY') + "\n";
    document.getElementById("time-entry-to").value = text;
    m.add(-14, "day");
    text = m.format('MM/DD/YYYY') + "\n";
    document.getElementById("time-entry-from").value = text;
});


function syncProjectData() {
   
    $.ajax({
        url: "/sync_tools/project",
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: "updated",
            showConfirmButton: false,
            timer: 1500
        });
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

function syncUserData() {
   
    $.ajax({
        url: "/sync_tools/user",
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: "updated",
            showConfirmButton: false,
            timer: 1500
        });
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

function syncClientData() {
   
    $.ajax({
        url: "/sync_tools/client",
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: "updated",
            showConfirmButton: false,
            timer: 1500
        });
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

function syncTimeEntry() {
    var dateFrom = document.getElementById("time-entry-from").value.split("/").join("-");
    var dateTo = document.getElementById("time-entry-to").value.split("/").join("-");

    if(dateFrom == "" || dateTo == ""){
        return;
    }
   
    $.ajax({
        url: "/sync_tools/time_entry/" + dateFrom + "/" + dateTo,        
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: "updated",
            showConfirmButton: false,
            timer: 1500
        });
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

function syncEngagementFee() { 

    $.ajax({
        url: "/sync_tools/engagement_fee",        
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: "updated",
            showConfirmButton: false,
            timer: 1500
        });
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

function syncInvoiceData() {
   
    $.ajax({
        url: "/sync_tools/invoice",
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: "updated",
            showConfirmButton: false,
            timer: 1500
        });
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

function syncTaskData() {
   
    $.ajax({
        url: "/sync_tools/task",
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: "updated",
            showConfirmButton: false,
            timer: 1500
        });
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

function syncExpenseData() {
   
    $.ajax({
        url: "/sync_tools/expense",
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: "updated",
            showConfirmButton: false,
            timer: 1500
        });
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

function createProjectData(){
    $.ajax({
        url: "/sync_tools/create_project",
        beforeSend: function () {
            //処理中           
            jQuery('#loader-bg').show();
        },
    }).success(function (data) {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: "updated",
            showConfirmButton: false,
            timer: 1500
        });
    }).error(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).done(function () {                  
        jQuery('#loader-bg').hide();
    });
}

