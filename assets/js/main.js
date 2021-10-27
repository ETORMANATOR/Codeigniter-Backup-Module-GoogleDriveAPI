function startbackup(){
    $.ajax({
        type: "POST",
        url: window.location.origin + "/Backup/start",
        success: function(results) {
            console.log(results);
            if(results=="done"){
                $('#loadingmodal').modal('hide');
                swal({
                    title: "Success!",
                    text: "Backup Successfully!",
                    icon: "success",
                    button: "Ok!",
                });
            }else{
                $('#loadingmodal').modal('hide');
                swal({
                    title: "Error!",
                    text: "Backup Fail!",
                    icon: "warning",
                    button: "Ok!",
                });
            }
           
        },
        erorr: function(results){
    
        }
    })
    }

    // for the time picker
document.querySelectorAll('input[type=number]')
.forEach(e => e.oninput = () => {
    // Always 2 digits
    if (e.value.length >= 2) e.value = e.value.slice(0, 2);
    // 0 on the left (doesn't work on FF)
    if (e.value.length === 1) e.value = '0' + e.value;
    // // Avoiding letters on FF
    if (!e.value) e.value = '00';
});
    //Check if enable auto backup
    function checkifenableAutobackup(){
        $.ajax({
            type: "POST",
            url: window.location.origin + "/Backup/checkifenable",
            success: function(results) {
                console.log(results);
                var data = JSON.parse(results);
                console.log(data);
                var timeBackup = data[0].backup_time.split(":");
                var Hour = timeBackup[0];
                var Minute = timeBackup[1];
                $('#cronHours').attr('value', Hour);
                $('#cronMinutes').attr('value', Minute);
                $('#google-backup-path').attr('value', data[0].backup_path);

               if(data[0].admin_enable_backup == "1"){
                    $('#customSwitch1').attr('checked','');
               }else{
                    $('#customSwitch1').removeAttr('checked');
                    $('#cronHours').attr('disabled','');
                    $('#cronMinutes').attr('disabled','');
                    $('#google-backup-path').attr('disabled','');
                    $('#inputGroupSelect02').attr('disabled','');
               }

               if(data[0].backup_type == "Daily"){
                $('#emptyres').removeAttr('selected');
                $('#Minutes').removeAttr('selected');
                $('#Daily').attr('selected','');
                }else if(data[0].backup_type == "Minutes"){
                    $('#emptyres').removeAttr('selected');
                    $('#Daily').removeAttr('selected');
                    $('#Minutes').attr('selected','');
                }else{
                    $('#emptyres').attr('selected','');
                    $('#Minutes').removeAttr('selected');
                    $('#Daily').removeAttr('selected','');
                }
            },
            erorr: function(results){
        
            }
        })
        }

        $(document).ready(function() {
            $( "#customSwitch1" ).click(function() {
                if($('#customSwitch1').is(':checked') ){
                    $('#cronHours').removeAttr('disabled');
                    $('#cronMinutes').removeAttr('disabled');
                    $('#google-backup-path').removeAttr('disabled');
                    $('#inputGroupSelect02').removeAttr('disabled');
                }else{
                    $('#cronHours').attr('disabled','');
                    $('#cronMinutes').attr('disabled','');
                    $('#google-backup-path').attr('disabled','');
                    $('#inputGroupSelect02').attr('disabled','');
                }
              });
        
        
        
            $(".setupcron-form").submit(function(e) {
                e.preventDefault();
                if($('#customSwitch1').is(':checked') ){
                    $.ajax({
                        type: "POST",
                        url: window.location.origin + "/Backup/setCron",
                        data: {
                            hours: $("#cronHours").val(),
                            minutes: $("#cronMinutes").val(),
                            type: $("#inputGroupSelect02").val(),
                            path: $("#google-backup-path").val(),
                           
                        },
                        success: function(result) {
                            // console.log(result);
                            if (result == "done") {
                                $('#setupcron').modal('hide');
                                swal({
                                    title: "Success!",
                                    text: "Save Successfully!",
                                    icon: "success",
                                    button: "Ok!",
                                });
            
                            } else {
                                swal({
                                    title: "Internal Error!",
                                    text: "Error! try again.",
                                    icon: "error",
                                    button: "Ok!",
                                });
                            }
            
                        }
                    })
                }else{
                    $.ajax({
                        type: "POST",
                        url: window.location.origin + "/Backup/disableautobackup",
                        success: function(result) {
                            $('#setupcron').modal('hide');
                                swal({
                                    title: "Turn Off Auto Backup!",
                                    text: "Save Successfully!",
                                    icon: "success",
                                    button: "Ok!",
                                });
                        }
                    });
                }
               
               
            });
        })