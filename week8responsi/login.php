<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX</title>
</head>

<body>
    <p id="test"></p>
    <p>
        <label>Username</label>
        <input type="text" id="username">
    </p>
    <p>
        <label>Password</label>
        <input type="password" id="password">
    </p>
    <p>
        <button id="btnlogin">LOGIN</button>
    </p>
</body>
<script src="jquery-3.5.1.min.js"></script>
<script>
    $('#btnlogin').click(function() {
        var username = $('#username').val();
        var password = $('#password').val();

        $.post('http://localhost/demofsp/week8responsi/login_ajax.php', {
            uname: username,
            passwd: password
        }).done(function(data) {
            var result = JSON.parse(data);
            if (result.iduser == username) {
                $('#test').html('sukses');
                window.location = 'index.php';
            } else {
                $('#test').html('gagal');
            }
        })

        // $.ajax({
        //     method: 'post',
        //     data: {
        //         uname: username,
        //         passwd: password
        //     },
        //     url: 'http://localhost/demofsp/week8responsi/login_ajax.php',
        //     success: function(data) {
        //         var result = JSON.parse(data);
        //         if (result.iduser == username) {
        //             $('#test').html('sukses');
        //             window.location = 'index.php';
        //         } else {
        //             $('#test').html('gagal');
        //         }
        //     }
        // })
    });
</script>

</html>