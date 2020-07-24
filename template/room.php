<body>

    <?php  require_once "mains/header.php"; ?>
    <div class="room-page">
        <div class="title">
            <h1><?=$lang_config['page_room_h1']?></h1>
            <span><?=$lang_config['page_room_title_span']?></span>
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

                    <div class="detail-list">
                        <?=(($cart_list!=="")?$cart_list:"")?>
                    </div>

                    <div class="amound-price">
                        <div class="list">
                            <span>การเข้าพัก</span>
                            <span>
                                <span class="room-amount">0</span> คืน</span>
                        </div>
                        <div class="list">
                            <span>ค่าห้อง ( รวมค่าอาหารเช้า )</span>
                            <span class="room-price">0</span>
                        </div>
                      
                        <div class="list">
                            <span>ค่าบริการเพิ่มเติม</span>
                            <span class="room-vat">0</span>
                        </div>
                        <div class="list">
                            <span>ส่วนลด</span>
                            <span class="room-discount">0</span>
                        </div>
                        <div class="list">
                            <span>ค่าใช้จ่ายทั้งหมด</span>
                            <span class="room-netpay">0</span>
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
                        <a href="<?=ROOT_URL."ยืนยันการจอง"?>" ><button>จอง</button> </a>
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
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/desk.svg" alt="">
                        <p>
                            ข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่าง
                            ข้อความตัวอย่างข้อความตัวอย่าง ข้อความตัวอย่าง ข้อความตัวอย่าง ข้อความตัวอย่าง
                            ข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่าง
                        </p>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/bed.svg" alt="">
                        <p>
                            ข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่าง
                            ข้อความตัวอย่างข้อความตัวอย่าง ข้อความตัวอย่าง ข้อความตัวอย่าง ข้อความตัวอย่าง
                            ข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่าง
                        </p>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/balcony.svg" alt="">
                        <p>
                            ข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่าง
                            ข้อความตัวอย่างข้อความตัวอย่าง ข้อความตัวอย่าง ข้อความตัวอย่าง ข้อความตัวอย่าง
                            ข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่างข้อความตัวอย่าง
                        </p>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/bathroom.svg" alt="">
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