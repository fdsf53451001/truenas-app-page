<?php include('dealRequest.php'); ?>
<?php include('sqlProvider.php'); ?>

<?php

session_start();
$postdata = file_get_contents("php://input",'r');
$data = json_decode($postdata,true);

// load from file
$GLOBALS['DATA'] = load_data();
if($GLOBALS['DATA'] === null || $GLOBALS['DATA'] === []) {
    $GLOBALS['DATA'] = array();
    $GLOBALS['DATA']['MANAGE_PAGE']='localhost';
    $GLOBALS['DATA']['MANAGE_PAGE_PORT']='8080';
    $GLOBALS['DATA']['APPS']=array();
    save_data();
}

// dealing command----------------------------------------------------
$type = $data['type'];

switch($type){
    case 'get_apps_data':
        apiGetAppsData();
        break;
    case 'add_apps':
        $ip = $data['ip'];
        $port = $data['port'];
        $info = $data['info'];
        apiAddApps($ip,$port,$info);
        break;
    default: //work
        break;
}

function apiGetAppsData(){
    $respond = array();
    $respond['sucess'] = 'true';
    $respond['data'] = $GLOBALS['DATA'];
    echo json_encode($respond);
}

function apiAddApps($ip,$port,$info){
    $respond = array();
    $respond['sucess'] = 'false';

    if(count($GLOBALS['DATA']['APPS'])<100){
        $GLOBALS['DATA']['APPS'][] = array('ip'=>$ip,'port'=>$port,'info'=>$info);
        save_data();
        $respond['sucess'] = 'true';
    }
    echo json_encode($respond);
}

function save_data(){
    echo $GLOBALS['DATA'];
    file_put_contents('data.json',json_encode(['wtf'=>'fxxk']),FILE_USE_INCLUDE_PATH);
}

function load_data(){
    return json_decode(file_get_contents('data.json'),true);
}

?>
