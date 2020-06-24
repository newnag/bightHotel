// กดปุ่มเปิดเมนูในมือถือ
document.querySelector('.header .hamburger').addEventListener('click',clickMenu)
function clickMenu(){
    document.querySelector('.header .menu').classList.toggle('active')
}

function toggleSubRoom(){
    document.querySelector('.subroom').classList.toggle('active')
}
function openSubRoom(){
    document.querySelector('.subroom').classList.add('active')
}
function closeSubRoom(){
    document.querySelector('.subroom').classList.remove('active')
}

// กดดูคำอธิบายของในห้องพัก
Array.from(document.querySelectorAll('.item')).map(e => {
    e.addEventListener('click',function(e){
        console.log(e.target.closest('.item'))
    })
})

// date pick
$('.formBook .input-box .dateCheck').flatpickr({
    dateFormat: "d-m-Y",
    disableMobile: "true"
})