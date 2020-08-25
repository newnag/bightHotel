function checkIsNumber(val){
    let num = /^[-+]?[0-9]+$/
    if(val.match(num)){
        return true;
    }
    else{
        return false;
    }
}

function DragImg(ele){
    const slider = document.querySelectorAll(ele);
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.forEach(element => {
        element.addEventListener('mousedown', (e) => {
            isDown = true;
            element.classList.add('drag');
            startX = e.pageX - element.offsetLeft;
            scrollLeft = element.scrollLeft;
        });
        element.addEventListener('mouseleave', () => {
            isDown = false;
            element.classList.remove('drag');
        });
        element.addEventListener('mouseup', () => {
            isDown = false;
            element.classList.remove('drag');
        });
        element.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - element.offsetLeft;
            const walk = (x - startX) * 3; //scroll-fast
            element.scrollLeft = scrollLeft - walk;
        });
    });
}

function facilitiesIcon(ele){
    const span = document.querySelectorAll(ele)
    span.forEach(faSpan => {
        faSpan.addEventListener('click',()=>{
            let spanActive = faSpan.children[0].className
            if(spanActive !== 'active'){
                const allSpan = document.querySelectorAll('.detail-room .inroom .item span')
                allSpan.forEach(all => {
                    all.classList.remove('active')
                })
                faSpan.children[0].classList.add('active')
            }
            else{
                faSpan.children[0].classList.remove('active')
            }
        })
    })
}


$("header .search").on("click","img",function(){  
    let tel = $(".header .reservation_search").val();
    if(tel.length == 10){
        location.href = hostname+"ประวัติการจอง/"+tel; 
    }   
  });
  
  $("header .search").on("keyup","input.reservation_search",function(e){ 
    let keycode = e.keyCode;
    let tel = $(".header .search .reservation_search").val();
    if(tel.length == 10 && keycode == 13){
        location.href = hostname+"ประวัติการจอง/"+tel; 
    }
  });


$("article .content.room").on("click","button",function(){
    location.href = hostname+"ห้อง/ห้องพัก";
});