window.onload = ()=>{
    DragImg('.room .grid-room')
}

function clickMenuMobile(){
    document.querySelector('.header .menu').classList.toggle('active')
}

function toggleSubRoom(){
    if(screen.width < 1024){
        document.querySelector('.room .subroom').classList.toggle('active')
    }  
}

// กดดูคำอธิบายของในห้องพัก เดี๋ยวมาทำต่อ
// Array.from(document.querySelectorAll('.item')).map(e => {
//     e.addEventListener('click',function(e){
//         console.log(e.target.closest('.item'))
//     })
// })

// date pick
$('.formBook .input-box .dateCheck').flatpickr({
    dateFormat: "d-m-Y",
    disableMobile: "true"
});

function checkNumberTel() {
    let val = document.querySelector('nav .search input').value
    if(!checkIsNumber(val)){
        Swal.fire('กรุณากรอกเป็นตัวเลข')
        document.querySelector('nav .search input').value = ''
    }
}