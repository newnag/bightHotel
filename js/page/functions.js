function checkIsNumber(val){
    if(val.match(/^-{0,1}\d+$/)){
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