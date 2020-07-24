window.onload = ()=>{
    DragImg('.room .grid-room') 
    checkNumberTel() 
}
window.onscroll = ()=>{
    scrollFunction()
}

function clickMenuMobile(){
    document.querySelector('.header .menu').classList.toggle('active')
    document.querySelector('.header .hamburger').classList.toggle('active')
}

function toggleSubRoom(){
    if(screen.width <= 1024 && navigator.userAgent.match(/iPhone|iPad|iPod|Android/i)){
        console.log('mobile')
        document.querySelector('.room .subroom').classList.toggle('active')
    }  
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

function checkNumberTel() {
    document.querySelector('nav .search input').addEventListener("keyup",(evt)=>{
        let charCode = (evt.which) ? evt.which : event.keyCode
        // charcode ตอนแรกเป็นโค้ดkeyของ แป้นตัวหนังสือ ตอนสองเป็นฝั่ง numpad 
        if(charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 96 || charCode > 105)){ 
            document.querySelector('nav .search input').value = ''
        }
    })
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