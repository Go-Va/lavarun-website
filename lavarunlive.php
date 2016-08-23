<?php
/**
 * Created by PhpStorm.
 * User: Sava
 * Date: 21.8.2016.
 * Time: 19.53
 */
$error = "";
$state = rand(1000000, 9999999);
function validate($inputString, $type){
    switch($type) {
        case "username":
            return preg_match("/^[A-Z a-z 0-9 _]{3,16}$/", $inputString);
            break;
        case "port":
            return preg_match("/^([1-9][0-9]{0,3}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5])$/", $inputString);
            break;
        case "confirmation":
            return preg_match("/^[1-9][0-9]{9}/", $inputString);
            break;
        case "ip":
            return filter_var($inputString, FILTER_VALIDATE_IP);
            break;
    }
    return false;
}

if(!isset($_GET["username"]) || !validate($_GET["username"], "username")){
    global $error;
    $error = "The username is blank or invalid";
}elseif (!isset($_GET["port"]) || !validate($_GET["port"], "port")){
    global $error;
    $error = "port is blank or invalid";
}elseif (!isset($_GET["confirmation"]) || !validate($_GET["confirmation"], "confirmation")){
    global $error;
    $error = "The confirmation code is blank or invalid";
}elseif (!isset($_GET["ip"]) || !validate($_GET["ip"], "ip")){
    global $error;
    $error = "The ip is blank or invalid";
}else{
    //Data is good
    setcookie("username",$_GET["username"]);
    setcookie("port",$_GET["port"]);
    setcookie("confirmation",$_GET["confirmation"]);
    setcookie("ip",$_GET["ip"]);
    setcookie("state",$state);
}
?>

<head>
    <title>Lava run beam integration</title>
</head>
<body>
<h1>
<?php if ($error != ""){
    echo $error;
}else{
    echo "Success, redirecting";
    $redirect_url = "invalid";
    $clientid = "invalid";
    require_once("settings.php");
    header("Location: https://beam.pro/oauth/authorize?response_type=token&redirect_uri=".$redirect_url."&scope=tetris:robot:self&client_id=".$clientid."&state=".$state);
    die();
}
?>
</h1>
</body>
