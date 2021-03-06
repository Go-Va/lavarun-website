<?php
require('vendor/autoload.php');
use WebSocket\Client;
$error = "";
/**
 * Created by PhpStorm.
 * User: Sava
 * Date: 21.8.2016.
 * Time: 20.04
 */
if(isset($_GET["token_type"]) && $_GET["token_type"]=="Bearer") {
    if (!isset($_GET["access_token"]) || !isset($_GET["state"])){
        $error = "Parameters incorrect error, please try again";
    }elseif (!isset($_COOKIE["state"]) || $_GET["state"] != $_COOKIE["state"]){
        $error = "Security token error, please try again later";
    }elseif (!isset($_COOKIE["ip"]) || !isset($_COOKIE["port"]) || !isset($_COOKIE["confirmation"]) || !isset($_COOKIE["username"])) {
        $error = "Data storage error";
    }else{
        try {
            $client = new Client("ws://".$_COOKIE["ip"].":".$_COOKIE["port"]);
            $ip = $_SERVER['HTTP_CLIENT_IP']?:($_SERVER['HTTP_X_FORWARDE‌​D_FOR']?:$_SERVER['REMOTE_ADDR']);
            $client->send("{\"ip\":\"".$ip."\",\"username\":\"".$_COOKIE["username"]."\",\"port\":\"".$_COOKIE["port"]."\",\"serverIP\":\"".$_COOKIE["ip"]."\",\"confirmation\":\"".$_COOKIE["confirmation"]."\",\"token\":\"".$_GET["access_token"]."\"}");
            $client->close();
        }
        catch (WebSocket\ConnectionException $e) {
            $error = $e;
        }
    }
}else{
    $error = "Wrong token type, please try again";
}
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Lava run beam integration</title>
    <link rel="stylesheet" href="main.css"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <script src="https://use.fontawesome.com/929b26d666.js"></script>
</head>
<body>
<?php if ($error != ""){
    echo "<div><i class=\"fa fa-times error\" aria-hidden=\"true\"></i> <p class = \"error\">".$error."</p></div>";
}else {
    echo "<div> <i class=\"fa fa-check success\" aria-hidden=\"true\"></i> <p class = \"success\">Success, you can close this tab now</p></div>";
}
?>
</body>
