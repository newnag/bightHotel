<body>

    <?php  require_once "mains/header.php"; ?>
    <div class="room-page">
        <div class="title">
            <h1><?=$article->title?></h1>
            <span><?=$article->description?></span>
        </div>
        <div class="room-page-zone">    
            <div class="gird-room">
                <?=$rooms?>
            </div>
            <div class="detail-order" id="detail-order">
                <div class="detial" style="background:url('<?=$thumbgenerator?>img/BG.jpg&size=x300')">
                    <div class="title">
                        <h2><?=$lang_config['page_room_order_detail_h2']?></h2>
                    </div>
                    <div class="box-date">
                        <div class="input-box">
                            <label><?=$lang_config['page_room_order_detail_label']?></label>
                            <input type="date" class="dateCheck checkIn" id="input_checkin" placeholder="กรุณากรอกวันที่">
                        </div>

                        <div class="input-box">
                            <label><?=$lang_config['page_room_order_detail_label_checkout']?></label>
                            <input type="date" class="dateCheck checkOut" id="input_checkout" placeholder="กรุณากรอกวันที่"> 
                        </div>
                    </div>
        
                    <div class="detail-list" <?=(($cart_list!="")?'style="display:block;"':'style="display:none;"')?> >
                        <?=(($cart_list!="")?$cart_list:"")?>
                    </div>
 
                    <div class="amound-price">
                        <div class="list">
                            <span>การเข้าพัก</span>
                            <span>
                                <span class="room-amount"><?=$cart_result['result']['amount']?></span> คืน
                            </span>
                        </div>
                        <div class="list">
                            <span>ค่าห้อง </span>
                            <span class="room-price"><?=$cart_result['result']['price']?></span>
                        </div>
                        <div class="list">
                            <span>ค่าอาหารเช้า </span>
                            <span class="room-breakfast"><?=$cart_result['result']['breakfast']?></span>
                        </div>
                      
                        <div class="list">
                            <span>ค่าบริการเตียงเสริม</span>
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
                            <input type="text">
                        </div>
                        <div class="button">
                            <button>ลด</button>
                        </div>
                    </div>

                    <div class="booking-button">
                         <button>จอง</button> 
                    </div>
                </div>

                <div class="contact" style="background:url('<?=$thumbgenerator?>img/BG.jpg&size=x300')">
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

    <!-- this get more details -->
    <div class="dialog-fullview">
        <div class="inner-dialog">
            <div class="close"><button>X</button></div>

            <div class="image-review">
                <div class="img-bigbox"><figure><img src="<?=$thumbgenerator?>img/hotel/MRX_0268.JPG&size=x250" alt=""></figure></div>
                <div class="carousel">
                    <div class="list-img">
                        <figure><img class="active" src="<?=$thumbgenerator?>img/hotel/MRX_0303.JPG&size=x250" alt=""></figure>
                    </div>
                </div>
            </div>

            <div class="detail-room">
                <div class="logo"><figure><img src="<?=ROOT_URL?>img/logo-B-01.png" alt=""></figure></div>
                <div class="nameroom">
                    <h2>Triple Room</h2>
                </div>
                <div class="price">
                    <h2>1,400</h2>
                    <span>บาท / คืน</span>
                </div>
                <div class="description">
                    <p> Lorem ipsum dolor, sit amet consectetur adipisicing elit. Molestias temporibus quisquam fugiat repellendus? Quae illo inventore dolorum eveniet. Provident excepturi at incidunt laborum itaque. A molestiae quam saepe voluptate at.
                    </p>
                </div>
            </div>

            <div class="facilities">
                <div class="title">
                    <h5>สิ่งอำนวยความสะดวก</h5>
                </div>

                <div class="inroom">
            
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/tv.svg" alt="">
                        <p>
                            ข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่าง
                            ข้อความตัวอย่างข้อความตัวอย่าง ข้อความตัวอย่าง ข้อความตัวอย่าง ข้อความตัวอย่าง
                            ข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่าง
                        </p>
                    </div>
                  
                </div>
            </div>

            
        </div>
    </div>
    <!-- โซนฟุตเตอร์ -->
    <!-- หน้าห้อง --> 
    <?php require_once "mains/footer.php"; ?>
    <!-- หน้ารายละเอียดการจองห้อง -->
    <!-- หน้าประวัติการจอง -->
    <script src="<?=ROOT_URL?>js/page/room.js?v=<?=time()?>"></script>    

</body> 