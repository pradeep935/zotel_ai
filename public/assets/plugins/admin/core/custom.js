$(document).on("click",".datepicker",function(){
	$(this).datepicker({
    	format:"dd-mm-yyyy",
    	todayHighlight:true,
    	autoclose: true,
    });
	$(this).datepicker("show");
});


function showSnackbar(message, additional_class) {
  var x = document.getElementById("snackbar");
  x.innerHTML = message;
  x.className = "show "+additional_class;
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}