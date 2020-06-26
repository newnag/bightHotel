window.onload = ()=>{
    selectImgReview()
    openDialogRoom()
    closeDialogRoom()
    if(screen.width >= 1366){
        DragImg()
    }
}

function DragImg(){
    const slider = document.querySelectorAll('.img-review .carousel .list-img');
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

// ยังไม่เสร็จ
function selectImgReview(){
    // const slider = document.querySelectorAll('.img-review .carousel')
    // const img = document.querySelectorAll('.img-review .carousel .list-img figure img')
    // slider.forEach(element => {
    //     element.addEventListener('click',()=>{    
    //         const sl = element.querySelectorAll('.list-img figure img')
    //         sl.forEach(s=>{
    //             //console.log(s)
    //             s.classList.remove('active')
    //         })   
    //     }) 
    // })
    // img.forEach(Image=>{
    //     Image.addEventListener('click',()=>{
    //         console.log(Image)
    //         Image.classList.add('active')
    //     })
    // }) 
    console.log("Active รูปห้องเล็กยังทำไม่เสร็จ ")
}

function openDialogRoom(){
    const viewFull = document.querySelectorAll('.room-page-zone .gird-room .list-room .img-review .virwFull')
    viewFull.forEach(ele=>{
        ele.addEventListener('click',()=>{
            document.querySelector('.dialog-fullview').style.display = 'block'
        })
    })
}

function closeDialogRoom(){
    document.querySelector('.dialog-fullview .inner-dialog .close button').addEventListener('click',()=>{
        document.querySelector('.dialog-fullview').style.display = 'none'
    })
}
