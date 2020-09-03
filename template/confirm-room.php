<body>
<?php require_once "mains/header.php"; ?>
<div class="bookingroom-page">
    <div class="title">
        <h1><?=$article->title?></h1>
        <span><?=$article->description?></span>
    </div>

    <div class="bookingroom-page-zone">
        <div class="detail-booking-zone">
            <div class="title"><h2><?=$lang_config['page_confirm_title_h2']?></h2></div>

            <div class="box-bookingRoom">
                <?=$detail['html']?>
            </div>
            
            <div class="cancle-button" data-id="<?=$detail['result']['id']?>">
                <button><?=$lang_config['page_confirm_booking_btn_cancel']?></button>
            </div>

            <div class="title"><h2><?=$lang_config['page_confirm_booking_detail_header_h2']?></h2></div>

            <div class="info-person">
                <div class="dataPersonal">
                    <div class="row">
                        <div class="input-box">
                            <label><?=$lang_config['page_confirm_detail_label_name']?></label>
                            <input type="text" placeholder="ชื่อ" value="<?=$detail['contact']['name']?>" disabled>
                        </div>

                        <div class="input-box">
                            <label><?=$lang_config['page_confirm_detail_label_lastname']?></label>
                            <input type="text" placeholder="นามสกุล" value="<?=$detail['contact']['lastname']?>" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-box">
                            <label><?=$lang_config['page_confirm_detail_label_tel']?></label>
                            <input type="tel" placeholder="เบอร์โทร"   value="<?=$detail['contact']['tel']?>"  disabled>
                        </div>

                        <div class="input-box">
                            <label><?=$lang_config['page_confirm_detail_label_email']?></label>
                            <input type="email" placeholder="Email"  value="<?=$detail['contact']['email']?>"  disabled>
                        </div>

                        <div class="input-box">
                            <label><?=$lang_config['page_confirm_detail_label_line']?></label>
                            <input type="text" placeholder="Line"  value="<?=$detail['contact']['line']?>"  disabled>
                        </div>
                        <div class="input-box">
                            <label><?=$lang_config['page_confirm_detail_label_idcard']?></label>
                            <input type="text" placeholder="เลขบัตรประชาชน 4 หลักท้าย"    value="<?=$detail['contact']['otp']?>"  disabled>
                        </div>
                        <div class="input-box taxinvoice">
                            <div>
                            <input type="checkbox" class="taxinvoice-check" DISABLED <?=(($detail['contact']['taxinvoice_name'] != "")?"CHECKED":"")?> > 
                            <label for="taxinvoice-check">รับใบกำกับภาษี</label>
                            </div>
                            
                            <input type="text" name="tax_invoice"  class="txt_invoice" value="<?=$detail['contact']['taxinvoice_name']?>" DISABLED placeholder="ใส่ชื่อบริษัท">
                        </div>
                    </div>
                </div>

                <div class="address">
                    <div class="row">
                        <div class="input-box">
                            <label>*ที่อยู่</label>
                            <input type="text" placeholder="ที่อยู่"  value="<?=$detail['contact']['address']?>"  disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-box">
                            <label>*ตำบล</label>
                            <input type="text" placeholder="ตำบล"  value="<?=$detail['contact']['district']?>"  disabled>
                        </div>

                        <div class="input-box">
                            <label>*อำเภอ</label>
                            <input type="text" placeholder="อำเภอ" value="<?=$detail['contact']['subdistrict']?>"  disabled>
                        </div>

                        <div class="input-box">
                            <label>*จังหวัด</label>
                            <input type="text" placeholder="จังหวัด" value="<?=$detail['contact']['province']?>"  disabled>
                        </div>

                        <div class="input-box">
                            <label>*รหัสไปรษณีย์</label>
                            <input type="text" placeholder="รหัสไปรษณีย์"  value="<?=$detail['contact']['postcode']?>"  disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-box">
                            <label>หมายเหตุ</label>
                            <input type="text" placeholder="ข้อความ" value="<?=$detail['contact']['description']?>"  disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="buttonPayment">
                <div class="PayBank">
                    <button><?=$lang_config['page_confirm_detail_btn_bank']?></button>
                </div>
                <div class="PayCredit">
                    <button><?=$lang_config['page_confirm_detail_btn_credit']?></button>
                </div>
            </div>

            <div class="title"><h2><?=$lang_config['page_confirm_payment_title_h2']?></h2></div>
            <div class="box-payment">
                <div class="box-bank">
                    <div class="input-box">
                        <label><?=$lang_config['page_confirm_payment_slc_bank']?></label>
                        <select name="" id="slc_bank">
                            <option disabled>เลือกธนาคาร</option>
                            <?=$slc_bank?>
                        </select>
                    </div>
                    <div class="input-box">
                        <label><?=$lang_config['page_confirm_payment_input_name']?></label>
                        <input type="text" class="txt_name" placeholder="ชื่อ">
                    </div>
                    <div class="input-box">
                        <label><?=$lang_config['page_confirm_payment_input_price']?> / บาท</label>
                        <input type="text" class="txt_price" placeholder="จำนวนเงิน" disabled value="<?=$detail['result']['netpay']?>">
                    </div>

                    <div class="date-box">
                        <div class="input-box">
                            <label><?=$lang_config['page_confirm_payment_input_date']?></label>
                            <input type="date" placeholder="xx-xx-xxxx" class="dateCheck">
                        </div>
                    </div>
                </div>

                <div class="right-box">
                    

                    <div class="upload-slip">
                        <figure><img src="<?=ROOT_URL?>img/icon/photo.svg" alt=""></figure>
                        <span><?=$lang_config['page_confirm_payment_btn_uploadimg']?></span>
                        <label for="slip-upload" id="inputfile"></label>
                        <input type="hidden" id="add-images-hidden" value="">
                        <input type="file" id="slip-upload" data-id="<?=$getpost['id']?>">
                    </div>

                    <figure class="review"><img src="" alt=""></figure>
                </div>
            </div>

            <div class="box-payment credit">
                <div class="box-bank">
                    <div class="input-box">
                        <label>Credit Card Number</label>
                        <input type="text" placeholder="">
                    </div>

                    <div class="input-box">
                        <label>Country/Region</label>
                        <select name="" id="">
                            <option value="">Thailand</option>
                            <option value="">Japan</option>
                            <option value="">Germany</option>
                        </select>
                    </div>
                </div>

                <div class="right-box">
                    <div class="date-box">
                        <div class="input-box">
                            <label>Expiration</label>
                            <select name="" id="">
                                <option value="">01</option>
                                <option value="">02</option>
                                <option value="">03</option>
                                <option value="">04</option>
                                <option value="">05</option>
                                <option value="">06</option>
                                <option value="">07</option>
                                <option value="">08</option>
                                <option value="">09</option>
                                <option value="">10</option>
                                <option value="">11</option>
                                <option value="">12</option>
                            </select>
                        </div>

                        <div class="input-box">
                            <label></label>
                            <select name="" id="">
                                <option value="">2020</option>
                                <option value="">2021</option>
                                <option value="">2022</option>
                                <option value="">2023</option>
                                <option value="">2024</option>
                            </select>
                        </div>

                        <div class="input-box">
                            <label>CSC</label>
                            <input type="tel" maxlength="3">
                        </div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="input-box">
                        <label>First Name</label>
                        <input type="text"  class="txt_name" placeholder="">
                    </div>
                    <div class="input-box">
                        <label>Last Name</label>
                        <input type="text" class="txt_lastname" placeholder="">
                    </div>
                </div>
            </div>

            <div class="buttonFinalPay" data-id="<?=$_SESSION['payment_id']?>">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">
                <button ><?=$lang_config['page_confirm_payment_btn_confirm']?></button>
            </div>
        </div>

        <div class="detail-order">
            <div class="detial" style="background:url('<?=ROOT_URL?>img/BG.jpg')">
                <div class="title">
                    <h2><?=$lang_config['page_confirm_result_h2']?></h2>
                </div>

                <div class="box-date">
                    <div class="input-box">
                        <label>เช็คอิน</label>
                        <label class="result"><?=$date_in?></label>
                    </div>
                    <div class="input-box">
                        <label>เช็คเอาท์</label>
                        <label><?=$date_out?></label>
                    </div>
                </div>

                <div class="detail-list">
                    <?=$detail['room_result']?>
                </div>

                <div class="amound-price">
                        <div class="list">
                        
                            <span>การเข้าพัก</span>
                            <span>
                                <span class="room-amount"><?=$detail['result']['amount']?></span> คืน</span>
                        </div>
                        <div class="list">
                            <span>ค่าห้อง </span>
                            <span class="room-price"><?=$detail['result']['price']?></span>
                        </div>
                        <div class="list">
                            <span>ค่าอาหารเช้า </span>
                            <span class="room-breakfast"><?=$detail['result']['breakfast']?></span>
                        </div>
                      
                        <div class="list">
                            <span>ค่าบริการเตียงเสริม</span>
                            <span class="room-vat"><?=$detail['result']['extra']?></span>
                        </div>
                        <!-- <div class="list">
                            <span>ส่วนลด</span>
                            <span class="room-discount"><?=$detail['result']['discount']?></span>
                        </div> -->
                        <div class="list res-discount">
                            <?=$_SESSION['cart']['result']['discount_desc']?>
                        </div>
                        <div class="list">
                            <span>ค่าใช้จ่ายทั้งหมด</span>
                            <span class="room-netpay"><?=$detail['result']['netpay']?></span>
                        </div>
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
    function getReCaptcha(){
        grecaptcha.ready(function() {
            grecaptcha.execute('6LfYAbwZAAAAAMHxHuGHnNWfFR3-lr9UVrbCAoQH', {action: 'submit_contact'}).then(function(token) {
                // ค่า token ที่ถูกส่งกลับมา จะถูกนำไปใช้ส่งไปตรวจสอบกับ api อีกครั้ง
                // เราเอาค่า token ไปไว้ใน input hidden ชื่อg-recaptcha-response
                document.getElementById('g-recaptcha-response').value = token;
            });
        });
    }
    getReCaptcha();
    setInterval(function(){getReCaptcha();}, 60000);
    </script>
</body>