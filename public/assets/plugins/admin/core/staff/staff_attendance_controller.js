app.controller("Staff_Attendance_Controller", function($scope, $http, DBService, Upload) {
  
  $scope.cityCenter = {};
  $scope.filterData ={
    show: true,
  };
  $scope.students = [];
  $scope.dates = [];
  $scope.loading = true;
  $scope.processing = false;
  $scope.export = false;
  $scope.checkIn = {};
  $scope.site_id = 0;
  $scope.check_in = false;
  $scope.member_ids = [];
  $scope.bulk_processing = false;

  $scope.getSites = function(tag){
    DBService.getCall('/api/get-sites')
    .then(function(data){
      $scope.sites = data.sites;
      $scope.loading = false;
    });
  }

  $scope.getStaff = function(){
    $scope.processing = true;
    DBService.postCall({
      site_id:$scope.filterData.site_id,
      fromDate: $scope.filterData.fromDate,
      toDate: $scope.filterData.toDate,
      export : $scope.export,
    },'/api/users/attendance')
    .then(function(data){
        $scope.dates = data.dates;
        $scope.staffMembers = data.staffMembers;
        $scope.processing = false;
    });
  }

  $scope.getAttendance = function(){
    $scope.getStaff();
  }

  $scope.exportStaffAttendance = function(){
    $scope.exportProcessing = true;
    DBService.postCall({
      site_id:$scope.filterData.site_id,
      fromDate: $scope.filterData.fromDate,
      toDate: $scope.filterData.toDate,
    },'/api/users/attendance/staff-attendance-export').then(function(data){
        if(data.success){
          window.open(data.file_name,"_blank");
        }
        $scope.exportProcessing = false;
    });
  }

   $scope.addSatffAttendance = function(date, convert_date_show){
    $scope.convert_date_show = convert_date_show;
    $scope.bulk_date = date;
    $("#attendance_bulk_modal").modal('show');
  }

  $scope.submitBulkStaffAtendance = function(attendanceType){

    bootbox.confirm("Are you sure?",(check)=> {
      if (check) {
        $scope.bulk_processing = true;
        for (var i = 0; i < $scope.staffMembers.length; i++) {
          $scope.member_ids.push($scope.staffMembers[i].id);
        }

        DBService.postCall({
          member_ids : $scope.member_ids,
          attendanceType : attendanceType,
          date : $scope.bulk_date,
          site_id : $scope.filterData.site_id
        },'/api/users/attendance/save-bulk-attendance').then(function(data){
          if(data.success){
            $scope.member_ids = [];
            $("#attendance_bulk_modal").modal('hide');
            $scope.getStaff();
          }
        $scope.bulk_processing = false;
        });
      }
    });
  }
  

  $scope.switchStaffAttendance = function(staff, date){
    var idx_present = staff.present.indexOf(date);
    var idx_absent = staff.absent.indexOf(date);

    if(idx_present == -1 && idx_absent == -1){
      staff.present.push(date);
    } else if(idx_present > -1 ) {
      staff.present.splice(idx_present,1);
      staff.absent.push(date);
    } else if(idx_absent > -1 ) {
      staff.absent.splice(idx_present,1);
    }
  }

  $scope.staffAttendance = function(staff, date, convert_date_show){
    $scope.allCheckInData(staff.id, date);
    $scope.staffAttendanceData = staff;
    $scope.staffAttendanceData.date = date;
    $scope.staffAttendanceData.convert_date_show = convert_date_show;
    $("#attendance_modal").modal('show');
  }

  $scope.allCheckInData = function(user_id, date){
    DBService.postCall({user_id : user_id, date : date},'/api/users/attendance/check-in-data').then(function(data){
      if(data.success){
        $scope.checkInData = data.checkInData;
        $scope.events = data.events;
      }
    });
  }

  $scope.addCheckIn = function(staffAttendanceData){
    $scope.checkIn = {};
    $scope.checkIn.date = staffAttendanceData.date;
    $scope.checkIn.city_id = staffAttendanceData.city_id;
    $scope.checkIn.user_id = staffAttendanceData.id;
    DBService.postCall({city_id : staffAttendanceData.city_id},'/api/users/attendance/check-in-center').then(function(data){
      if(data.success){
        $scope.centers = data.centers;
        $scope.check_in = true;
      }
    });
  }

  $scope.cancelStaffCheckIn = function(){
    $scope.check_in = false;
    $scope.checkInForm.$setPristine();
    $scope.checkIn = {};
  }

  $scope.submitStaffCheckIn = function(){
    $scope.check_in_processing = true;
    DBService.postCall($scope.checkIn,'/api/users/attendance/check-in-store').then(function(data){
      if(data.success){
        bootbox.alert(data.message);
        $scope.allCheckInData($scope.checkIn.user_id, $scope.checkIn.date);
        // $scope.checkInForm.$setPristine();
        // $scope.checkIn = {};
        $scope.check_in = false;

      }
    $scope.check_in_processing = false;
    });
  }



$scope.submitStaffAtendance = function(attendanceType){
    $scope.staffAttendanceData.attendanceType = attendanceType;

    var idx_present = $scope.staffAttendanceData.present.indexOf($scope.staffAttendanceData.date);
    if(idx_present > -1) $scope.staffAttendanceData.present.splice(idx_present, 1);
    
    var idx_absent = $scope.staffAttendanceData.absent.indexOf($scope.staffAttendanceData.date);
    if(idx_absent > -1) $scope.staffAttendanceData.absent.splice(idx_absent, 1);   
    
    var idx_leave = $scope.staffAttendanceData.leave.indexOf($scope.staffAttendanceData.date);
    if(idx_leave > -1)$scope.staffAttendanceData.leave.splice(idx_leave, 1);

    var idx_cancel = $scope.staffAttendanceData.cancel.indexOf($scope.staffAttendanceData.date);
    if(idx_cancel > -1)$scope.staffAttendanceData.cancel.splice(idx_cancel, 1);

    if(attendanceType == 1){
      $scope.staffAttendanceData.present.push($scope.staffAttendanceData.date);
      $scope.staffProcessing_pre = true;
    }    
    if(attendanceType == 0){
      $scope.staffAttendanceData.absent.push($scope.staffAttendanceData.date);
      $scope.staffProcessing_abs = true;
    }    
    if(attendanceType == 2){
      $scope.staffAttendanceData.leave.push($scope.staffAttendanceData.date);
      $scope.staffProcessing_leave = true;
    }
    if(attendanceType == 3){
      $scope.staffAttendanceData.cancel.push($scope.staffAttendanceData.date);
      $scope.staffProcessing_cancel = true;
    }

    DBService.postCall({staffAttendanceData : $scope.staffAttendanceData},"/api/users/attendance/save-staff-attendance").then(function(data){
      if(data.success) {
        $scope.staffProcessing_pre = false;
        $scope.staffProcessing_abs = false;
        $scope.staffProcessing_leave = false;
        $scope.staffProcessing_cancel = false;
      }
    });
}

  $scope.saveStaffAttendance = function(){
    $scope.saveprocessing = true;
    DBService.postCall({
      staffMembers : $scope.staffMembers,
      dates : $scope.dates,
      city_id : $scope.filterData.city_id,
    },
      "/api/users/attendance/save-attendance")
    .then(function(data){
        if (data.success) {
            $scope.saveprocessing = false;
            $scope.getStaff();
            bootbox.alert(data.message);
        }else{
            bootbox.alert(data.message);
            $scope.saveprocessing = false;
        }
    });
  }

  $scope.submitStaffEventAtendance = function(att_type, index){

    $scope.events[index].status = att_type;

    if($scope.events[index].status == 1){
      $scope.events[index].staff_event_pre = true;
    } else {
      $scope.events[index].staff_event_abs = true;
    }


    DBService.postCall($scope.events[index],"/api/users/attendance/save-staff-event-attendance").then(function(data){
        if(data.success) {
          if($scope.events[index].status == 1){
            $scope.events[index].staff_event_pre = false;
          } else {
            $scope.events[index].staff_event_abs = false;
          }
        }else{
          if($scope.events[index].status == 1){
            $scope.events[index].staff_event_pre = false;
          } else {
            $scope.events[index].staff_event_abs = false;
          }
        }
    });
  }  

  $scope.removeCheckIn = function(item, index){

    bootbox.confirm("Are you sure?",(check)=> {
      if (check) {
        item.check_in_remove_processing = true;

        DBService.postCall({id : item.id},"/api/users/attendance/remove-check-in").then(function(data){
            if(data.success) {
              bootbox.alert(data.message);
              $scope.checkInData.splice(index,1);
            }
        item.check_in_remove_processing = false;
        });
      }
    });
  }

});
