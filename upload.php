<?php
header( 'Access-Control-Allow-Origin:*' );
header( 'Access-Control-Allow-Credentials:true' );
header('Access-Control-Allow-Methods:GET, POST, OPTIONS');

$serverUrl= "http".'://'.$_SERVER['HTTP_HOST'];
$returnJson=array('result'=>0,"msg"=>'保存失败');
$uploadDir="/upload";
$uploadPath=realpath(dirname(__file__)).$uploadDir;

  $filename=rand().time().rand();
   $filepath=$uploadPath.'/'.$filename.'.jpg';
    $fileRealpath=$uploadPath.'/'.$filename.'.jpg';

if(isset($_POST['uploadType'])){
    switch($_POST['uploadType']){
        case "imgFile":
                move_uploaded_file($_FILES['imgFile']['tmp_name'],$fileRealpath);
                  $returnJson["result"]=1;
                $returnJson["msg"]='保存成功';
                $returnJson["imgpath"]=$serverUrl.$filepath;
                break;
        case "base64":
                $fileData=base64_decode($_POST['base64']);
                file_put_contents($fileRealpath,$fileData);
                  $returnJson["result"]=1;
                    $returnJson["msg"]='保存成功';
                    $returnJson["imgpath"]=$serverUrl.$filepath;
                break;
    }


        echo json_encode($returnJson);
}


