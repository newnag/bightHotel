<?php 
/**
 * เป็นไฟล์ Route สำหรับ SEO และเป็นเหมือน middleware
 * จะทำการตรวจสอบว่า Route ไปเส้นทางไหน 
 * ดึงข้อมูล จาก table category , post เพื่อมาใส่ข้อมูลใน head html 
 * Route แต่ละเส้นทางจะเหมือนกันกับ route.php
 */

#[*]=======================================
#[*]            หน้าแรก
#[*]=======================================
Route::get(2,function(){
  global $App,$CATEGORY;
  $DataSEO = $App->getCategoryFieldByCateID(1,"title,description,keyword,thumbnail,url");
  $CATEGORY = $DataSEO;
  $App->setHeaderSEO([
    "title"       => $DataSEO->title,
    "description" => $DataSEO->description,
    "keyword"     => $DataSEO->keyword,
    "thumbnail"   => $DataSEO->thumbnail,
    "url"         => $DataSEO->url,
  ]);
});


#[*]=======================================
#[*]            หน้า วิธีการจอง
#[*]=======================================
Route::get(12,function(){
  global $App,$CATEGORY;
  $DataSEO = $App->getCategoryFieldByCateID(12,"title,description,keyword,thumbnail,url");
  $CATEGORY = $DataSEO;
  $App->setHeaderSEO([
    "title"       => $DataSEO->title,
    "description" => $DataSEO->description,
    "keyword"     => $DataSEO->keyword,
    "thumbnail"   => $DataSEO->thumbnail,
    "url"         => $DataSEO->url,
  ]);
});


#[*]=======================================
#[*]            หน้า แกลเลอรี่
#[*]=======================================
Route::get(3,function(){
  global $App,$CATEGORY;
  
  $DataSEO = $App->getCategoryFieldByCateID(3,"title,description,keyword,thumbnail,url");
  $CATEGORY = $DataSEO;
  $App->setHeaderSEO([
    "title"       => $DataSEO->title,
    "description" => $DataSEO->description,
    "keyword"     => $DataSEO->keyword,
    "thumbnail"   => $DataSEO->thumbnail,
    "url"         => $DataSEO->url,
  ]);
});



#[*]=======================================
#[*]            หน้า ยืนยันการจอง
#[*]=======================================
Route::get(4,function(){
  global $App,$CATEGORY;
  $DataSEO = $App->getCategoryFieldByCateID(4,"title,description,keyword,thumbnail,url");
  $CATEGORY = $DataSEO;
  $App->setHeaderSEO([
    "title"       => $DataSEO->title,
    "description" => $DataSEO->description,
    "keyword"     => $DataSEO->keyword,
    "thumbnail"   => $DataSEO->thumbnail,
    "url"         => $DataSEO->url,
  ]);
});



#[*]=======================================
#[*]            หน้า เกี่ยวกับเรา
#[*]=======================================
Route::get(5,function(){
  global $App,$CATEGORY;
  $DataSEO = $App->getCategoryFieldByCateID(5,"title,description,keyword,thumbnail,url");
  $CATEGORY = $DataSEO;
  $App->setHeaderSEO([
    "title"       => $DataSEO->title,
    "description" => $DataSEO->description,
    "keyword"     => $DataSEO->keyword,
    "thumbnail"   => $DataSEO->thumbnail,
    "url"         => $DataSEO->url,
  ]);
});


#[*]=======================================
#[*]            หน้า ติดต่อเรา
#[*]=======================================
Route::get(6,function(){
  global $App,$CATEGORY;
  $DataSEO = $App->getCategoryFieldByCateID(6,"title,description,keyword,thumbnail,url");
  $CATEGORY = $DataSEO;
  $App->setHeaderSEO([
    "title"       => $DataSEO->title,
    "description" => $DataSEO->description,
    "keyword"     => $DataSEO->keyword,
    "thumbnail"   => $DataSEO->thumbnail,
    "url"         => $DataSEO->url,
  ]);
});



#[*]=======================================
#[*]            หน้า ค้นหาการจอง
#[*]=======================================
Route::get(11,function(){
  global $App,$CATEGORY;
  $DataSEO = $App->getCategoryFieldByCateID(11,"title,description,keyword,thumbnail,url");
  $CATEGORY = $DataSEO;
  $App->setHeaderSEO([
    "title"       => $DataSEO->title,
    "description" => $DataSEO->description,
    "keyword"     => $DataSEO->keyword,
    "thumbnail"   => $DataSEO->thumbnail,
    "url"         => $DataSEO->url,
  ]);
});
