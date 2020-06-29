window.onload = ()=>{
    selectImgReview()
    if(screen.width >= 1366){
        DragImg('.img-review .carousel .list-img')
    }
    if($('.room-page-zone')){
        openDialogRoom()
        closeDialogRoom()
    }
    if(screen.width < 1366){
        facilitiesIcon()
    } 
    clickImgChangeURL()
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
    try {
        document.querySelector('.dialog-fullview .inner-dialog .close button').addEventListener('click',()=>{
            document.querySelector('.dialog-fullview').style.display = 'none'
        })
    } catch (error) {
        console.log(error)
    }
}

function facilitiesIcon(){
    let span = document.querySelectorAll('.detail-room .inroom .item')
    span.forEach(faSpan => {
        faSpan.addEventListener('click',()=>{
            let spanActive = faSpan.children[0].className
            if(spanActive !== 'active'){
                faSpan.children[0].classList.add('active')
            }
            else{
                faSpan.children[0].classList.remove('active')
            }
        })
    })
}

function clickImgChangeURL(){
    let urlBig = document.querySelectorAll('.list-room .img-review')
    urlBig.forEach(big=>{
        let urlImg = big.querySelectorAll('.carousel .list-img figure img')
        urlImg.forEach(url=>{
            url.addEventListener('click',()=>{
                big.children[0].children[0].src = url.src
            })
        })
    })
}