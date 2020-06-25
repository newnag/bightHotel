function checkIsNumber(val){
    if(val.match(/^-{0,1}\d+$/)){
        return true
    }
    else{
        return false
    }
}