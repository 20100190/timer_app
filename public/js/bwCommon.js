function setProjectData(isMulti) {

    var client = $('#client').val();
    if (client == "") {
        client = "blank";
    }

    $.ajax({
        url: "/project/data/" + client + "/",
    }).done(function (data) {
        $('#project').children().remove();
        var project = document.getElementById('project');
        if(!isMulti){
            document.createElement('option')
            var option = document.createElement('option');
            option.setAttribute('value', "blank");
            option.innerHTML = "&nbsp;";
            project.appendChild(option);
        }
        
        for (var i = 0; i < data.projectData.length; i++) {
            if (data.projectData[i].project_name != null){
                var option = document.createElement('option');
                option.setAttribute('value', data.projectData[i].project_name);
                option.innerHTML = data.projectData[i].project_name;
                project.appendChild(option);
            }
        };

        $('#project').multiselect('rebuild');

    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        //alert('error!!!');
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    });

}