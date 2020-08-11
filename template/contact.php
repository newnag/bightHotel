<body>
<?php  require_once "mains/header.php"; ?>
    <div class="contact-page">
        <div class="title">
            <h1><?=$article->title?></h1>
            <span><?=$article->description?></span>
        </div>
        <div class="content-contact">
            <div class="left">
                <div class="ck">
                    <?=$article->content?>
                </div>
                <div class="icon">
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/mobile-solid.svg" alt="">
                        <span><?=$CONTACT_WEB->mobilephone?></span>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/phone.svg" alt="">
                        <span><?=$CONTACT_WEB->phone?></span>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/envelope-open-solid.svg" alt="">
                        <span><?=$CONTACT_WEB->email?></span>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/instagram-brands.svg" alt="">
                        <span><?=$CONTACT_WEB->ig?></span>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/facebook-brands.svg" alt="">
                        <span><?=$CONTACT_WEB->facebook?></span>
                    </div>
                    <div class="item">
                        <img src="<?=ROOT_URL?>img/icon/share-alt-solid.svg" alt="">
                        <span>share</span>
                    </div>
                </div>
            </div>
            <div class="right">
                <figure><img src="<?=ROOT_URL.$article->thumbnail?>" alt=""></figure>
            </div>
        </div>

        <div class="map-form">
            <div class="map">
                <?=$CONTACT_WEB->map?>
            </div>
            <div class="form-contact">
                <div class="title">
                    <h2><?=$lang_config['page_contact_title_h2']?></h2>
                </div>
                
                <div class="inputDual">
                    <div class="input-box">
                        <label>ชื่อ</label>
                        <input type="text" class="txt_name" placeholder="กรอกชื่อ">
                    </div>
                    <div class="input-box">
                        <label>นามสกุล</label>
                        <input type="text" class="txt_lastname" placeholder="กรอกนามสกุล">
                    </div>
                </div>
                <div class="inputDual">
                    <div class="input-box">
                        <label>เบอร์โทร</label>
                        <input type="tel" class="txt_tel" placeholder="กรอกเบอร์โทร">
                    </div>
                    <div class="input-box">
                        <label>อีเมล</label>
                        <input type="email" class="txt_email" placeholder="กรอกอีเมล">
                    </div>
                </div>
                <div class="inputSingle">
                    <label>เรื่อง</label>
                    <input type="text" class="txt_subject" placeholder="กรอกเรื่อง">
                </div>
                <div class="textSingle">
                    <label>ข้อความ</label>
                    <textarea class="txt_message" name="" id="" cols="30" rows="5" placeholder="กรอกข้อความ"></textarea>
                </div>
                <div class="button"><button>ส่งข้อความ</button></div>
            </div>
        </div>
    </div>
<?php  require_once "mains/footer.php"; ?>
</body>