<?php
echo date('Y-m-d H:i:s', mktime(date('H'),date('i'),date('s'), date('m'),date('d')+30,date('Y')))."\n";
?>