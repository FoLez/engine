function goReg( levent ) {
    levent.preventDefault();
    var form, check, pass, r_pass;
    form = $( "form" ).serialize();

    pass = $( "#password" ).val();

    r_pass = $( '#r_password' ).val();

    if( pass != r_pass ) {
        Materialize.toast('Пароли не совпадают!', 4000, "red darken-4");

        return false;
    }

    var url = "http://app.test/Applications/core/modules_public/register.php";

    var data_url = url;

    $.ajax({
        type: "POST",
        url: data_url,
        data: $("form").serialize(),
        dataType: "json",
        success: function (data) {
            if(data.status == "success") {
                Materialize.toast( "Регистрация прошла успешна.", 10*1000, "green darken-1" );
                $("#reg_result").html(data.msg);
            } else {
                $("#reg_result").html(data.msg);
            }
        }
    });
}