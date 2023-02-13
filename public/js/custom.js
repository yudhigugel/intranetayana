
 function validate_password_format(password){
    var special = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    if(!special.test(password)){
      return false;
    }

    /* VALIDASI NUMBER*/
    var contain_number=/\d/.test(password);
    if(!contain_number){
      return false; 
    }

    /* VALIDASI uppercase */
    var capital= /[A-Z]/;
    if (!capital.test(password)){
      return false;
    }

    if(password.length<8){
      return false
    }

    return true;
}
