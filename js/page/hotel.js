window.onload = ()=>{
    DragImg('.room .grid-room') 
    checkNumberTel() 
}

function clickMenuMobile(){
    document.querySelector('.header .menu').classList.toggle('active')
}

function toggleSubRoom(){
    if(screen.width <= 1024){
        document.querySelector('.room .subroom').classList.toggle('active')
    }  
}

$('.dateCheck').flatpickr({
    dateFormat: "d-m-Y",
    disableMobile: "true",
});

function checkNumberTel() {
    let val = document.querySelector('nav .search input').value
    document.querySelector('nav .search input').addEventListener("keyup",(e)=>{
        if(!checkIsNumber(val)){
            e.target.value=parseFloat(e.target.value)||''
        }
    })
    
}