<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lấy SSL Miễn Phí</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
    }
    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
    }
    .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
        padding: 10px 0;
        background-color: #007bff;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn:hover {
        background-color: #0056b3;
    }
    .ketqua {
        margin-top: 20px;
        padding: 10px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    .form-group input {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#btn-getssl").click(function(){
        var username = $("#username").val();
        var password = $("#password").val();
        var iphost = $("#iphost").val();

        $(".ketqua").html("<p>Đang lấy SSL...</p>");
        $.ajax({
            url: "getssl.php",
            data: {
                username: username,
                password: password,
                iphost: iphost
            },
            success: function(result){
                $(".ketqua").html(result);
            }
        });
    });

    $("#btn-installssl").click(function(){
        var username = $("#username").val();
        var password = $("#password").val();
        var iphost = $("#iphost").val();

        $(".ketqua").html("<p>Đang cài đặt SSL...</p>");
        $.ajax({
            url: "installssl.php",
            data: {
                username: username,
                password: password,
                iphost: iphost
            },
            success: function(result){
                $(".ketqua").html(result);
            }
        });
    });
});
</script>
</head>
<body>

<div class="container">
    <h1>Chức năng Lấy SSL Miễn Phí</h1>
    <p>Chức năng này giúp bạn lấy chứng chỉ SSL miễn phí cho tên miền: <strong style="color:red;"><?php echo $_SERVER['HTTP_HOST']; ?></strong> của bạn.
    
     <br>  <br><font color="red"></font>Bạn cần nhập chính xác tài khoản và mật khẩu host của bạn để có thể cài chứng chỉ SSL miễn phí!
    </font>
    </p>
    <div class="form-group">
        <label for="username">Tài khoản:</label>
        <input type="text" id="username" name="username" placeholder="Nhập tài khoản">
    </div>
    <div class="form-group">
        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
    </div>
    <div class="form-group">
        <label for="iphost">IP Host:</label>
        <input type="text" id="iphost" name="iphost" value="<?=$_SERVER['SERVER_ADDR'];?>">
    </div>
    <button id="btn-getssl" class="btn">Lấy SSL</button>
    <button id="btn-installssl" class="btn">Cài đặt SSL</button>
    <div class="ketqua"></div>
</div>

</body>
</html>
