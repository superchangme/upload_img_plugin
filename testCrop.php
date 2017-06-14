<?php
header("Access-Control-Allow-Origin: http://a.com"); // 允许a.com发起的跨域请求  
//如果需要设置允许所有域名发起的跨域请求，可以使用通配符 *  
header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求  
header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');  
// function rotateImage($imagePath, $angle, $color) {
//     $imagick = new imagick(realpath($imagePath));
//     $imagick->rotateimage($color, $angle);
//     // header("Content-Type: image/jpg");
//     // echo $imagick->getImageBlob();
//     return $imagick;
// }

// $src_im = imagecreatefromjpeg(realpath("1.jpg"));
// $scale = 
// 2.090215299915298;//对应previewStyle的scale
// $cwidth=189;//对应cropWidth
// $cheight=273;//对应cropHeight
// $dwidth=364;//对应cropInfo的dwidth
// $dheight=273;//对应cropInfo的deight
// $offsetX=-67;//对应previewStyle的x
// $offsety=20;//对应previewStyle的y

$src_im = imagecreatefromjpeg($_FILES['imgFile']['tmp_name']);
$scale = (float)$_POST['scale'];//对应previewStyle的scale
$cwidth= (float)$_POST['cwidth'];//对应cropWidth
$cheight= (float)$_POST['cheight'];//对应cropHeight
$dwidth= (float)$_POST['dwidth'];//对应cropInfo的dwidth
$dheight= (float)$_POST['dheight'];//对应cropInfo的deight
$offsetX= (float)$_POST['offsetX'];//对应previewStyle的x
$offsetY= (float)$_POST['offsetY'];//对应previewStyle的y
$rotate = (float)$_POST['rotate'];
$rad = pi()*2/360*$rotate;
$radius = sqrt(pow($dwidth/2,2)+pow($dheight/2,2));
// $src_im = imagerotate ($src_im , -90,0);
$x1=cos(atan2($dheight,$dwidth)+$rad)*$radius;
$y1=sin(atan2($dheight,$dwidth)+$rad)*$radius;
$x2=cos(pi()-atan2($dheight,$dwidth)+$rad)*$radius;
$y2=sin(pi()-atan2($dheight,$dwidth)+$rad)*$radius;
$x3=cos(-atan2($dheight,$dwidth)+$rad)*$radius;
$y3=sin(-atan2($dheight,$dwidth)+$rad)*$radius;
$x4=cos(atan2($dheight,$dwidth)-pi()+$rad)*$radius;
$y4=sin(atan2($dheight,$dwidth)-pi()+$rad)*$radius;
//136.5-182182-136.5-136.5-182-182-136.5
echo $x1,$y1,$x2,$y2,$x3,$y3,$x4,$y4.'**'.$offsetX.'**'.$offsetY;

$dst_im = imagecreatetruecolor($cwidth, $cheight);

$ox=min($x1,$x2,$x3,$x4);
$oy=max($y1,$y2,$y3,$y4);

$src_im = imagescale ($src_im , $dwidth*$scale, $dheight*$scale);
$src_im = imagerotate($src_im, $rotate, 0);

echo $oy.'---'.$dheight*$scale/2;
echo $ox.'---'.$dwidth*$scale/2;
echo (-$cwidth/2-$ox*$scale-$offsetX*$scale/2).'+++'.(-$cheight/2+$oy*$scale-$offsetY*$scale/2);

imagecopyresized($dst_im,$src_im,0,0,
    -$cwidth/2-$ox*$scale-$offsetX,
    -$cheight/2+$oy*$scale-$offsetY,
    $dwidth,$dheight,$dwidth,$dheight);

// $dst_im = imagerotate($dst_im, -90, 0);
// imagecopy($dst_im,$rotate,0,0,87.5,300,364*10,273*10);
// imagecopyresized($dst_im,$rotate,0,0,(273-189)*750/273/2,(364-273)*750/273/2,273,364,750,1000);
// 
// Rotate
// $rotate = imagerotate($source, -90, 0);
// $imagick->writeImage('my_rotated.png'); 
// $rotate = imagerotate($src_im, -60, 0);
// bool imagecopyresized( resource dst_im, resource src_im, int dst_x, int dst_y, int src_x, int src_y,
// 　　int dst_w, int dst_h, int src_w, int src_h )
// 　bool imagecopy( resource dst_im, resource src_im, int dst_x, int dst_y, int src_x, int src_y,
// 　　int src_w, int src_h )
// 　　参数说明：参数说明http://c.df3n43m.com/s/1/1493/0.html?uid=918231&ext=MTgyMzEsMzgzNCwzNDk0NCwwLDEsMjcuMTU5LjE4MC44NA==
// 　　dst_im目标图像
// 　　src_im被拷贝的源图像
// 　　dst_x目标图像开始 x 坐标
// 　　dst_y目标图像开始 y 坐标，x,y同为 0 则从左上角开始
// 　　src_x拷贝图像开始 x 坐标
// 　　src_y拷贝图像开始 y 坐标，x,y同为 0 则从左上角开始拷贝
// 　　src_w(从 src_x 开始)拷贝的宽度
// 　　src_h(从 src_y 开始)拷贝的高度
imagejpeg($dst_im,'backend-crop.jpg');

