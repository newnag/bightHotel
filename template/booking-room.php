<body>
<?php require_once "mains/header.php"; ?>
<div class="bookingroom-page">
    <div class="title">
        <h1><?=$article->title?></h1>
        <span><?=$article->description?></span>
    </div>
 
    <div class="bookingroom-page-zone">
        <div class="detail-booking-zone"> 
            <div class="title"><h2>รายละเอียดการจอง</h2></div>
            <div class="box-bookingRoom">
                   <?=$orders['detail']?>
            </div>
            <div class="button"><a href=""><button>กดเพิ่มการจอง</button></a></div>
            <div class="title"><h2>ข้อมูลที่ใช้จอง</h2></div>
            <div class="info-person">
                <div class="dataPersonal">
                    <div class="row">
                        <div class="input-box">
                            <label>*ชื่อ</label>
                            <input type="text" placeholder="ชื่อ" class="txt_name">
                        </div>

                        <div class="input-box">
                            <label>*นามสกุล</label>
                            <input type="text" placeholder="นามสกุล" class="txt_lastname">
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-box">
                            <label>*เบอร์โทร</label>
                            <input type="tel" maxlength="10"  placeholder="เบอร์โทร" class="txt_tel" >
                        </div>

                        <div class="input-box">
                            <label>*Email</label>
                            <input type="email" placeholder="Email" class="txt_email">
                        </div>

                        <div class="input-box">
                            <label>Line:ID</label>
                            <input type="text" placeholder="Line" class="txt_line">
                        </div>

                        <div class="input-box">
                            <label><?=$lang_config['page_confirm_detail_label_idcard']?></label>
                            <input type="tel" maxlength="4"  placeholder="รหัสบัตร 4 หลักท้าย " class="txt_code" >
                        </div>
                    </div>
                </div>

                <div class="address">
                    <div class="row">
                        <div class="input-box">
                            <label>*ที่อยู่</label>
                            <input type="text" placeholder="ที่อยู่" class="txt_address">
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-box">
                            <label>*ตำบล</label>
                            <input type="text" placeholder="ตำบล" class="txt_subdistrict">
                        </div>

                        <div class="input-box">
                            <label>*อำเภอ</label>
                            <input type="text" placeholder="อำเภอ" class="txt_district">
                        </div>

                        <div class="input-box">
                            <label>*จังหวัด</label>
                            <input type="text" placeholder="จังหวัด" class="txt_province">
                        </div>

                        <div class="input-box">
                            <label>*รหัสไปรษณีย์</label>
                            <input type="tel" maxlength="5" placeholder="รหัสไปรษณีย์" class="txt_postcode">
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-box">
                            <label>หมายเหตุ</label>
                            <input type="text" placeholder="ข้อความ" class="txt_message">
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">
                <button>จอง</button>
            </div>
        </div>

        <div class="detail-order">
            <div class="detial" style="background:url('<?=ROOT_URL?>img/BG.jpg')">
                <div class="title">
                    <h2><?=$lang_config['page_bookingroom_detail_title_h2']?></h2>
                </div>

                <div class="box-date">
                    <div class="input-box">
                        <label>เช็คอิน</label>
                        <label><?=$date_in?></label>
                    </div>

                    <div class="input-box">
                        <label>เช็คเอาท์</label>
                        <label><?=$date_out?></label>
                    </div>
                </div>

                <div class="detail-list">
                     
                     <?=$orders['result']?>

                </div>

                <div class="amound-price">
                        <div class="list">
                            <span>การเข้าพัก</span>
                            <span>
                                <span class="room-amount"><?=$cart_result['result']['amount']?></span> คืน
                            </span>
                        </div>
                        <div class="list">
                            <span>ค่าห้อง ( รวมค่าอาหารเช้า )</span>
                            <span class="room-price"><?=$cart_result['result']['price']?></span>
                        </div>
                      
                        <div class="list">
                            <span>ค่าบริการเพิ่มเติม</span>
                            <span class="room-vat"><?=$cart_result['result']['extra']?></span>
                        </div>
                        <div class="list">
                            <span>ส่วนลด</span>
                            <span class="room-discount"><?=$cart_result['result']['discount']?></span>
                        </div>
                        <div class="list">
                            <span>ค่าใช้จ่ายทั้งหมด</span>
                            <span class="room-netpay"><?=$cart_result['result']['netpay']?></span>
                        </div>
                    </div>
                    <div class="discount">
                        <div class="input-box">
                            <label>ส่วนลด</label>
                            <input type="text" >
                        </div>
                        <div class="button">
                            <button>ลด</button>
                        </div>
                    </div>
                </div>

            <div class="contact" style="background:url('<?=ROOT_URL?>img/BG.jpg')">
                <div class="title">
                    <h2>ติดต่อเรา</h2>
                </div>

                <div class="logo">
                    <img src="<?=ROOT_URL?>img/icon/logo-white-01.svg" alt="">
                </div>

                <div class="icon">
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/mobile-solid.svg" alt="">
                        <span>098-1201970</span>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/envelope-open-solid.svg" alt="">
                        <span>brighthotelkhonkaen@gmail.com</span>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/home.svg" alt="">
                        <span>177/88 ถนนมิตรภาพ หมู่17 ต.ในเมือง อ.เมือง จ.ขอนแก่น 40000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

   <!-- Footer -->
   <?php 
        require_once "mains/footer.php"; 
    ?>
    <script src="<?=ROOT_URL?>js/page/booking-room.js?v=1.1.2<?=time()?>"></script>
    <script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LfYAbwZAAAAAMHxHuGHnNWfFR3-lr9UVrbCAoQH', {action: 'submit_contact'}).then(function(token) {
            // ค่า token ที่ถูกส่งกลับมา จะถูกนำไปใช้ส่งไปตรวจสอบกับ api อีกครั้ง
            // เราเอาค่า token ไปไว้ใน input hidden ชื่อg-recaptcha-response
            document.getElementById('g-recaptcha-response').value = token;
        });
    });
    </script>   
</body>