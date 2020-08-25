window.onload = ()=>{
    DragImg('.room .grid-room') 
    checkNumberTel('nav .search input') 
    checkNumberTel('.input-box .txt_tel') 
}
window.onscroll = ()=>{
    scrollFunction()
}

function clickMenuMobile(){
    document.querySelector('.header .menu').classList.toggle('active')
    document.querySelector('.header .hamburger').classList.toggle('active')
}

function toggleSubRoom(){
    document.querySelector('.room .subroom').classList.toggle('active')
}


$('.dateCheck').flatpickr({
    minDate: "today",
    dateFormat: "d-m-Y",
    disableMobile: "true",
});
var check_in = $('.header_checkin').flatpickr({
    dateFormat: "d-m-Y",
    disableMobile: "true",
    minDate: "today",
    onChange: function(dateStr){
        check_out.set('minDate',(dateStr[0].fp_incr(1)))
    }
});
var check_out = $('.header_checkout').flatpickr({
    minDate: new Date().fp_incr(1),
    dateFormat: "d-m-Y",
    disableMobile: "true",
});

$('.box-payment .right-box .input-box input').flatpickr({
    enableTime: true,
    dateFormat: "d-m-Y H:i",
    disableMobile: "true",
});

function checkNumberTel(ele) {
    try{
        document.querySelector(ele).addEventListener("keypress",(e)=>{
            if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.which >= 48 && e.which <= 57)) {
                return true;
            } 
            else {
                e.preventDefault();
                return false;
            }
        })
    }
    catch(e){

    }
}

function warpTop(){
    document.querySelector('.buttonTop').addEventListener('click',()=>{
        window.scrollTo({
            top: 0,
            left: 0,
            behavior: 'smooth'
        });
    })
}

function scrollFunction(){
    if(document.body.scrollTop > 100 || document.documentElement.scrollTop > 100){
      document.querySelector('.buttonTop').style.display = 'flex'
    }
    else{
      document.querySelector('.buttonTop').style.display = 'none'
    }
}

// document.querySelector('.content .icon .item.share').addEventListener('click',()=>{
//     Swal.fire({
//         html: `
//             <div style="margin:5% 0;">
//             <a href="" style="display:flex;align-items:center;justify-content:center;color:#3085d6">
//                 <img style="width:30px;margin-right:10px;filter:invert(45%) contrast(100%) brightness(30%) sepia(100) saturate(100) hue-rotate(220deg);" src="https://brighthotel.co.th/img/icon/facebook-brands.svg">
//                 แชร์ Facebook
//             </a>
//             </div>
//         `,
//         confirmButtonText: 'ปิดหน้าต่าง',
//     })
// })