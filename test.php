<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$css_body = 'background: url(' . 'img/new-bg.jpg); padding:20px; color:#c93; font-size: 16px; width:768px; margin:auto';
    $css_article = 'background:#22324b; font-family: LucidaGrande,tahoma,verdana,arial,sans-serif; padding: 20px 0px; border: 1px solid #c93;';
    $css_logo = 'width:300px; margin:auto;';
    $css_name = 'text-align:center;';
    $css_info = 'display:flex; justify-content:space-between; padding:10px 20px; border-bottom:1px solid #666666';
    $css_background_payment = 'background:#f4f4f4; padding:10px 20px';
    $css_contact = 'margin-top:30px; display: flex; justify-content: space-between;';
    $css_contactText = 'font-size:14px; padding:5px 20px';
    $address_block = 'background:#f4f4f4; margin:30px 80px; padding:20px; border-radius: 5px; text-align:center;';

    $html = '<div style="'.$css_body.'">
              <article style="'.$css_article.'">
                <div style="'.$css_logo.'">
                  <img src="'.'img/logo-01.png" style=" width:100%;">
                </div>

                <div style="'.$css_name.'"><h1>//**ชื่อผู้จอง**//</h1></div>
                <div style="'.$css_name.'"><span>การจองของท่านได้รับการยืนยันแล้ว</span></div>

                <div style="'.$address_block.'">
                    <div><h2>Bright Hotel</h2></div>
                    <div><p>บริษัท ไบรท์โฮเต็ล จำกัด

                    เลขที่ 177/88 ถนนมิตรภาพ หมู่17 ต.ในเมือง อ.เมือง จ.ขอนแก่น 40000</p></div>
                </div>

                <div style="'.$css_name.'"><h2>รายละเอียดการจอง</h2></div>

                <div style="'.$css_info.'">
                  <div>การจองห้องพัก:</div>
                  <div>//**จำนวนคืนห้องพัก**//</div>
                </div>
                <div style="'.$css_info.'">
                  <div>เช็คอิน:</div>
                  <div>//**วันเช็คอิน**//</div>
                </div>
                <div style="'.$css_info.'">
                  <div>เช็คเอาท์:</div>
                  <div>//**วันเช็คเอาท์**//</div>
                </div>
                <div style="'.$css_info.'">
                  <div>ชื่อผู้เข้าพัก:</div>
                  <div></div>
                </div>
                <div style="'.$css_info.'">
                  <div>อาหารเช้า:</div>
                  <div>รับ</div>
                </div>
                <div style="'.$css_info.'">
                  <div>โปรโมชั่น:</div>
                  <div></div>
                </div>

                <div style="'.$css_background_payment.'">
                  <div style="'.$css_info.'">
                    <div>//**ชนิดของห้องพัก**//</div>
                    <div>//**ราคาของห้องพัก**//</div>
                  </div>
                  <div style="'.$css_info.'">
                    <div>ราคารวม</div>
                    <div>//**ราคารวมทั้งหมด**//</div>
                  </div>
                </div>

                <div style="'.$css_contact.'">
                    <div style="'.$css_contactText.'"><span>เบอร์โทร:098-765-4321 / 043-306777-79<span></div>
                    <div style="'.$css_contactText.'"><span>อีเมล์: brighthotel@gmail.com<span></div>
                    <div style="'.$css_contactText.'"><span>Facebook: brighthotelkhonkaen<span></div>
                </div>
              </article>
            </div>';
        echo $html;
?>