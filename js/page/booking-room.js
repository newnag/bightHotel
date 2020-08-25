$('.detail-booking-zone .box-payment .box-bank .date-box .dateCheck').flatpickr({
    disableMobile: "true",
    enableTime: true,
    dateFormat: "d-m-Y H:i",
    defaultHour: 00,
    defaultMinute: 0,
    time_24hr: true,
})

window.onload = ()=>{
    valuePeopleBook()
    ClickLabel('EB')
    ClickLabel('Bf')
    ClickLabel('TAX')
}

function valuePeopleBook(){
    const val1 = document.querySelectorAll('.inputAdult')
    val1.forEach(value=>{
        const left = value.parentElement.querySelector('.left')
        const right = value.parentElement.querySelector('.right')
        let inputVal = Number(value.value)

        left.addEventListener('click',()=>{
            if(inputVal > 1){
                inputVal -= 1
            }
            value.value = inputVal
        })
        right.addEventListener('click',()=>{
            
            inputVal += 1
            value.value = inputVal
            
            
        })
    })

    const val2 = document.querySelectorAll('.inputChild')
    val2.forEach(value2=>{
        const left = value2.parentElement.querySelector('.left')
        const right = value2.parentElement.querySelector('.right')
        let inputVal = Number(value2.value)

        left.addEventListener('click',()=>{
            if(inputVal > 0){
                inputVal -= 1
            }
            value2.value = inputVal
        })
        right.addEventListener('click',()=>{
            inputVal += 1
            value2.value = inputVal
        })
    })
}

function ClickLabel(type){
    if(type === 'Bf'){
        let ele = document.querySelectorAll('.breakfast') 
        ele.forEach(check=>{
            check.children[1].addEventListener('click',()=>{
                check.querySelector('.breakfast-check').click()
            })
        })
    }
    else if(type === 'EB'){
        let ele = document.querySelectorAll('.extrabed') 
        ele.forEach(check=>{
            check.children[1].addEventListener('click',()=>{
                check.querySelector('.extrabed-check').click()
            })
        })
    }
}

// $(".detail-booking-zone").on("click","",function(){
//     $(".taxinvoice-check")
// });