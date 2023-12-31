<?php
session_start();
if (isset($_SESSION["login"])) {
    header('Location: ./view/index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
    <link rel="stylesheet" href="view/assets/lib/css/loginCSS.css">

</head>

<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form action="model/verificaLogin.php" method="POST" class="login">
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" name="username" id="username" autocomplete="false"
                            placeholder="Nome de Usuário">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" class="login__input" name="password" id="password" autocomplete="false"
                            placeholder="Senha">
                    </div>
                    <button class="button login__submit" type="submit">
                        <span class="button__text">Entrar</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                </form>
                <div class="social-login">
                    <h3 style="border: solid 1px white; border-radius: 20px; padding: 5px;">Visualizar Demo</h3>
                </div>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
    <script>
        var url = window.location.href;
        var returnGET = url.split("?")[1];
        var response = returnGET.split("=")[1];
        if (response == "loginError"){
            alert("Usuário ou senha incorretos!");
        }
    </script>
</body>

</html>