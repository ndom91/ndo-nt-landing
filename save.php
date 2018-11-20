<?php
// sleep for a while so we can see the indicator in demo
if ($_POST['slow']) {
    usleep(500000);
}
/*f (is_array($_POST['value'])) {
    echo implode(', ', $_POST['value']);
} else {
    echo $_POST['value'];
}


echo $label;
echo $url;*/
$inputstring=$_POST['value'];
$str_explode=explode(",",$inputstring);
$label = $str_explode[0];
$url = $str_explode[1];

setcookie($_POST['id'], json_encode($str_explode), time()+36000);

echo $_POST['value'];
