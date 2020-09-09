window.onload = ()=>{
    openBigImg()
    closeBigImg()
    clickImgUrlBig()
    buttonNext_BigPic()
    buttonPrev_BigPic()
}

function clickImgUrlBig(){
    const img = document.querySelectorAll('.gallary-zone figure img')
    //console.log(img)
    img.forEach(Img=>{
        Img.addEventListener('click',()=>{
            let url = Img.src
            document.querySelector('.showpic .bigpic figure img').src = url
        })
    })
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
    document.querySelector('.bigpic .close').addEventListener('click',()=>{
        document.querySelector('.showpic').classList.remove('active')
    })
}

function warpToCurrentPic(){
    const pic = document.querySelectorAll('.gallary-zone figure')
    lastPic = [].slice.call(pic).pop()
    setTimeout(() => {
        lastPic.scrollIntoView()
    },50);
}

function get_more_images(){
    let amount = 15;
    let number = $(".gallary-zone figure").length / amount;
    let param = {
        action: 'get_image_gallery',
        amount,
        number
    }

    $.ajax({
        url: location.origin+'/api/myapi.php',
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            
            if(response['message'] === 'success'){
                warpToCurrentPic()
                $(".gallary-zone").append(response['images'])
                openBigImg()
                clickImgUrlBig()
                if((15 - response['amount'])  > 0){
                    $(".loadmore button").hide();
                }
            }else{
                $(".loadmore button").hide();
            }
        },
        error: function(res){
            console.log('errr')
        }
    });

}

function buttonNext_BigPic(){
    document.querySelector('.showpic .right-button').addEventListener('click',()=>{
        let targetEle = document.querySelector('.bigpic figure img').getAttribute('src')
        const eleListPic = document.querySelectorAll('.gallary-zone figure img')
        for(i=0;i<eleListPic.length;i++){
            if(eleListPic[i].getAttribute('src') === targetEle && i<eleListPic.length){
                let target = eleListPic[i].parentElement.nextElementSibling.children[0]
                document.querySelector('.bigpic figure img').setAttribute('src',target.getAttribute('src'))
            }
        }
    })
}
function buttonPrev_BigPic(){
    document.querySelector('.showpic .left-button').addEventListener('click',()=>{
        let targetEle = document.querySelector('.bigpic figure img').getAttribute('src')
        const eleListPic = document.querySelectorAll('.gallary-zone figure img')
        for(i=0;i<eleListPic.length;i++){
            if(eleListPic[i].getAttribute('src') === targetEle && i>0){
                let target = eleListPic[i].parentElement.previousElementSibling.children[0]
                document.querySelector('.bigpic figure img').setAttribute('src',target.getAttribute('src'))
            }
        }
    })
}

// function get_more_images(){
//     let formData = new FormData();
//     formData.append("action","get_image_gallery");
//     formData.append("set","me");
//     fetch('site_url+"api/myapi.php"', {
//         method: "POST",
//         body: formData 
//     }).then(function (response) {
// 	// The API call was successful!
// 	    console.log('success!', response);
//     }).catch(function (err) {
//         // There was an error
//         console.warn('Something went wrong.', err);
//     });
// }


