$(".form-contact").on('click',"button",function(){
   
    let param = validate_contact_data(); 
    if(param){
        $.ajax({
            url: hostname+'api/myapi.php',
            type: 'POST',
            dataType: 'json',
            data: param,
            success: function(response){
                    console.log(response)
            },
            error: function(_error){
                console.log('error');
            }
        }); 
    }
  
});
 

function validate_contact_data(){
    let param = {
        action: 'submit_contact',
        name: $(".form-contact .txt_name").val(),
        lastname: $(".form-contact .txt_lastname").val(),
        tel: $(".form-contact .txt_tel").val(),
        email: $(".form-contact .txt_email").val(),
        subject: $(".form-contact .txt_subject").val(),
        message: $(".form-contact .txt_message").val(),
        token: ''
    }
 
    if(param.name.length < 1 ){
        console.log("required name");
        $(".form-contact .txt_name").addClass('error')
    } else{
        $(".form-contact .txt_name").removeClass('error')
    }  
    if(param.lastname.length < 1 ){
        console.log("required lastname");
        $(".form-contact .txt_lastname").addClass('error')
    } else{
        $(".form-contact .txt_lastname").removeClass('error')
    }  
    if(param.tel.length !==  10 ){
        console.log("required tel");
        $(".form-contact .txt_tel").addClass('error')
    } else{
        $(".form-contact .txt_tel").removeClass('error')
    }  
    if(!validateEmail(param.email)){
        console.log("required email");
        $(".form-contact .txt_email").addClass('error')
    }else{
        $(".form-contact .txt_email").removeClass('error')
    }

    if(param.subject.length < 1 ){
        console.log("required subject");
        $(".form-contact .txt_subject").addClass('error')
    } else{
        $(".form-contact .txt_subject").removeClass('error')
    }  

    if(param.message.length < 1 ){
        console.log("required message");
        $(".form-contact .txt_message").addClass('error')
    } else{
        $(".form-contact .txt_message").removeClass('error')
    }
    
    if(  $(".form-contact input").hasClass('error')){
        console.log('contact is required ')
        return false;
    }
    if( $(".form-contact  textarea").hasClass('error')){
        console.log('message is required')
        return false;
    }

    return param;
 
}

mytest();
function mytest(){
    $(".txt_name").val('testset 1 2');
    $(".txt_lastname").val('testset 2321');
    $(".txt_email").val('sdd@sf.sd');
    $(".txt_tel").val('testse345t');
    $(".txt_subject").val('tes435373tset');
    $(".txt_message").val('testset 89034'); 
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}