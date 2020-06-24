<!-- หัวเว็บมีโลโก้ เมนู ค้นหา -->
<header>
        <div class="header">
            <div class="logo"><figure><a href=""><img src="<?=ROOT_URL?>img/logo-01.png" alt=""></a></figure></div>
            <nav>
                <ul class="menu">
                    <li class="menulist"><a href="" class="active">หน้าแรก</a></li>
                    <li class="menulist">
                        <a href="#" class="room" onclick="openSubRoom()" onmouseover="openSubRoom()">ห้อง <img src="<?=ROOT_URL?>img/icon/down-arrow.svg" alt=""></a>
                            <ul class="subroom">
                                <li><a href="">Triple Room</a></li>
                                <li><a href="">Deluxe Room</a></li>
                                <li><a href="">President Room</a></li>
                                <li><a href="">Suite Room</a></li>
                                <li><a href="">Superior Room</a></li>
                                <li><a href="">Meeting Room</a></li>
                            </ul>
                    </li>
                    <li class="menulist"><a href="">โปรโมชั่น</a></li>
                    <li class="menulist"><a href="">แกลเลอรี่</a></li>
                    <li class="menulist"><a href="">ติดต่อเรา</a></li>    
                    
                    <div class="icon">
                        <a href=""><img src="<?=ROOT_URL?>img/icon/mobile-solid.svg" alt=""></a>
                        <a href=""><img src="<?=ROOT_URL?>img/icon/instagram-brands.svg" alt=""></a>
                        <a href=""><img src="<?=ROOT_URL?>img/icon/facebook-brands.svg" alt=""></a>
                    </div>
                </ul>
                <div class="hamburger">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
                <div class="search">
                    <input type="tel" maxlength="10" placeholder="ค้นหาการจองจากเบอร์โทร">
                    <img src="<?=ROOT_URL?>img/icon/search-solid.svg" alt="">
                </div>
            </nav>
        </div>
    </header>
       <!-- ตัวสไลด์ ไฟล์จัดการชื่อ slide.js -->
       <div class="slide owl-carousel">
        <div class="item"><img src="<?=ROOT_URL?>img/view.jpg" alt=""></div>
        <div class="item"><img src="<?=ROOT_URL?>img/view.jpg" alt=""></div>
        <div class="item"><img src="<?=ROOT_URL?>img/view.jpg" alt=""></div>
        <div class="item"><img src="<?=ROOT_URL?>img/view.jpg" alt=""></div>
        <div class="item"><img src="<?=ROOT_URL?>img/view.jpg" alt=""></div>
    </div>

    <!-- โซนการจอง -->
    <div class="booking" style="background:url('<?=ROOT_URL?>img/BG.jpg')">
        <div class="formBook">
            <div class="input-box">
                <label>เช็คอิน</label>
                <input type="date" class="dateCheck">
            </div>

            <div class="input-box">
                <label>เช็คเอาท์</label>
                <input type="date" class="dateCheck">
            </div>

            <div class="input-box adult">
                <label>ผู้ใหญ่/คน</label>
                <input type="number" id="adult" value="0">
                <img class="left" src="<?=ROOT_URL?>img/icon/minus.svg" alt="">
                <img class="right" src="<?=ROOT_URL?>img/icon/plus.svg" alt="">
            </div>

            <div class="input-box child">
                <label>เด็ก/คน</label>
                <input type="number" id="child" value="0">
                <img class="left" src="<?=ROOT_URL?>img/icon/minus.svg" alt="">
                <img class="right" src="<?=ROOT_URL?>img/icon/plus.svg" alt="">
            </div>

            <div class="button"><button>จอง</button></div>
        </div>
    </div>