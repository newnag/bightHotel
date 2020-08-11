    <?php  require_once "mains/header.php"; ?>
    <div class="meeting-page">
    <div class="title">
        <h1><?=$article->title?></h1>
        <span><?=$article->description?></span>
    </div>

    <div class="meeting-page-zone">
        <div class="gird-room">
            <?=$meeting?>
        </div>

        <div class="detail-order">
            <div class="detial" style="background:url('<?=ROOT_URL?>img/BG.jpg')">
                <div class="title">
                    <h2>ติดต่อจองห้องประชุม</h2>
                </div>
                
                <div class="form">
                    <div class="inputDual">
                        <div class="input-box">
                            <label>ชื่อ</label>
                            <input type="text" class="txt_name" placeholder="กรุณากรอกชื่อ">
                        </div>
                        <div class="input-box">
                            <label>อีเมล</label>
                            <input type="text" class="txt_email" placeholder="กรอกอีเมล">
                        </div>
                    </div>
                    <div class="inputSingle">
                        <label>เบอร์โทร</label>
                        <input type="tel" class="txt_tel" placeholder="กรอกเบอร์โทร">
                    </div>
                    <div class="inputSingle">
                        <label>เรื่อง</label>
                        <input type="text" class="txt_subject" placeholder="กรอกเรื่อง">
                    </div>
                    <div class="textSingle">
                        <label>ข้อความ</label>
                        <textarea name="" id="" class="txt_message" cols="30" rows="5" placeholder="กรอกข้อความ"></textarea>
                    </div>
                    <div class="button"><button class="reserv_meeting">ส่ง</button></div>
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



<?php
    require_once "mains/footer.php";
?>
    <script src="<?=ROOT_URL?>js/page/room.js?v=<?=time()?>"></script>    
<script src="<?=ROOT_URL."js/page/meeting.js?v=1.2.3".time()?>"></script>

</body>