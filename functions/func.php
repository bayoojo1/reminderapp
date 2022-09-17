<?php
function aa(a){
  nameid = "name"+a;
  txtnameid = "txtname"+a;
  var name = document.getElementById(nameid).innerHTML;
  document.getElementById(nameid).innerHTML="<input type='text' value='"+name+"' id='"+txtnameid+"'>";

  updateid = "update"+a;
  document.getElementById(a).style.visibility="hidden";
  document.getElementById(updateid).style.visibility="visible";
}
?>