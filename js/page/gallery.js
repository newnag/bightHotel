function clickImgUrlBig(){
    // const img = document.querySelectorAll('.gallary-zone figure img')
    // img.forEach(Img=>{

    // })
}

function openBigImg(){
    const img = document.querySelectorAll('.gallary-zone figure img')
    img.forEach(Img=>{
        Img.addEventListener('click',()=>{
            document.querySelector('.showpic').classList.add('active')
        })
    })
}

function closeBigImg(){
    const close = document.querySelectorAll('.bigpic .close')
}