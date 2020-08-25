<!-- หัวเว็บมีโลโก้ เมนู ค้นหา -->
<header> 
        <div class="header">
            <div class="logo"><figure><a href="<?=ROOT_URL?>"><img src="<?=ROOT_URL.$CONTACT_WEB->logo?>" alt=""></a></figure></div>
            <nav>
                <div class="ham-social">
                    <div class="hamburger" onclick="clickMenuMobile()">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>

                    <div class="icon-mobile">
                        <a target="_blank" href="tel:<?=$CONTACT_WEB->phone?>"><img src="<?=ROOT_URL?>img/icon/mobile-solid.svg" alt="telephone icon"></a>
                        <!-- <a target="_blank" href="https://line.me/ti/p/~<?=$CONTACT_WEB->line?>"><img src="<?=ROOT_URL?>img/icon/instagram-brands.svg" alt="instagram icon"></a> -->
                        <a target="_blank" href="https://facebook.com/<?=$CONTACT_WEB->facebook?>"><img src="<?=ROOT_URL?>img/icon/facebook-brands.svg" alt="line icon"></a>
                    </div> 
                </div>
                <ul class="menu">
                    <?=$MYNAV_MENU_TOP?>
                    <div class="icon">
                        <a target="_blank" href="tel:<?=$CONTACT_WEB->phone?>"><img src="<?=ROOT_URL?>img/icon/mobile-solid.svg" alt="telephone icon"></a>
                        <!-- <a target="_blank" href="https://line.me/ti/p/~<?=$CONTACT_WEB->line?>"><img src="<?=ROOT_URL?>img/icon/instagram-brands.svg" alt="instagram icon"></a> -->
                        <a target="_blank" href="https://facebook.com/<?=$CONTACT_WEB->facebook?>"><img src="<?=ROOT_URL?>img/icon/facebook-brands.svg" alt="line icon"></a>
                    </div> 
                    <div class="searchBM">
                        <input type="tel" maxlength="10" placeholder="ค้นหาการจองจากเบอร์โทร" class="reservation_search" value="<?=$tel?>">
                        <img src="<?=ROOT_URL?>img/icon/search-solid.svg" alt="">
                    </div>
                </ul>
                <div class="search">
                    <input type="tel" maxlength="10" placeholder="ค้นหาการจองจากเบอร์โทร" class="reservation_search" value="<?=$tel?>">
                    <img src="<?=ROOT_URL?>img/icon/search-solid.svg" alt="">
                </div>
            </nav>
        </div>
    </header>
    <!-- ตัวสไลด์ ไฟล์จัดการชื่อ slide.js -->
    <div class="slide owl-carousel">
        <?=$myAds?>
    </div> 

    <!-- โซนการจอง -->
    <div class="booking" style="background:url('<?=ROOT_URL?>img/new-bg.jpg')">
        <div class="formBook">
            <div class="input-box">
                <label>เช็คอิน</label>
                <input type="date" class="dateCheck header_checkin" placeholder="XX-XX-XXXX">
            </div>

            <div class="input-box">
                <label>เช็คเอ้าท์</label>
                <input type="date" class="dateCheck header_checkout" placeholder="XX-XX-XXXX">
            </div>

            <div class="input-box adult">
                <label>ผู้ใหญ่/คน</label>
                <input type="number" id="adult" value="0" disabled>
                <img class="left" src="<?=ROOT_URL?>img/icon/minus.svg" alt="">
                <img class="right" src="<?=ROOT_URL?>img/icon/plus.svg" alt="">
            </div>

            <div class="input-box child">
                <label>เด็ก/คน</label>
                <input type="number" id="child" value="0" disabled>
                <img class="left" src="<?=ROOT_URL?>img/icon/minus.svg" alt="">
                <img class="right" src="<?=ROOT_URL?>img/icon/plus.svg" alt="">
            </div>

            <div class="button"><button>ค้นหา</button></div>
        </div>
    </div>