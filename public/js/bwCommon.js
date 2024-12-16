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

//from, to : mm/dd/yyyy形式の文字列
//fromよりtoが大きい場合True
function compFromToDate(fromDate, toDate) {
    var isBiggerThanToDate = false;
    var fromDateArray = fromDate.split("/");
    var toDateArray = toDate.split("/");
    
    var date1 = new Date(fromDateArray[2], fromDateArray[0], fromDateArray[1], 00, 00, 00);
    var date2 = new Date(toDateArray[2], toDateArray[0], toDateArray[1], 00, 00, 00);
    
    isBiggerThanToDate = date1 >= date2;
    
    return isBiggerThanToDate;
}

function getHeightClamped(text) {
  const minHeight = 1;
  const maxHeight = 10;
  const textHeight = text.split("\n").length;
  if (textHeight < minHeight) {
      return minHeight;
  } else if (textHeight > maxHeight) {
      return maxHeight;
  } else {
      return textHeight;
  }
}