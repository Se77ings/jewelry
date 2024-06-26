<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../index.html?unlog');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Página Inicial</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="assets/lib/css/styles.css" rel="stylesheet" />
    <style>
        :root{
            --bs-green: #00ac3b;
            --magenta-color: #D7008E
        }

        #sidebarToggle{
            border: solid 1px black;
            box-shadow: 3px 1px 10px black;
            margin-right: 15px;
        }

        a:hover {
            cursor: pointer;
        }

        .collapse {
            background-color: var(--magenta-color);
            color: black;
            border-radius: 10px;
        }

        .sidebar-heading {
            background-color: var(--magenta-color);
        }

        #sidebar-wrapper {
            border-right: solid 1px var(--magenta-color);
        }

        .sub-menu {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .sub-menu a {
            padding-left: 20px;
            background: lightgrey;
            color: black;
            position: relative;
        }

        .sidebar-heading {
            background-color: var(--magenta-color);
        }

        #sidebar-wrapper {
            border-right: solid 1px var(--magenta-color);
        }

        #sairOption {
            margin: 0;
            color: white;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                height: 0;
            }

            to {
                opacity: 1;
                height: auto;
            }
        }

        @media screen and (max-width:500px) {
            .collapse {
                background: none;
            }

            .collapse #sairOption {
                position: fixed;
                top: 50px;
                right: 15px;
                background: none;
                border-radius: 10px;
                text-align: center;
                color: white;
                background-color: grey;
                width: 60px;
                margin-left: 80%;
                padding: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading" style="height: 58px; color:white">Jewelry System</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" id="1"
                    onclick="exibeContent(this)">Registrar Nova venda</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" id="2"
                    onclick="exibeContent(this)">Consultar Vendas</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" id="3"
                    onclick="exibeContent(this)">Carteira</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" id="4"
                    onclick="exibeContent(this)">Títulos > Direito</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" id="5"
                    onclick="exibeContent(this)">Manutenção</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" id="6"
                    onclick="exibeContent(this)">Consultas <span id="arrowbaixa"></span></a>
                <div class="sub-menu" id="consultasSubMenu" style="display: none;">
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" id="7"
                        onclick="exibeContent(this)"> ▶ Pessoas</a>
                    <!-- <a class="list-group-item list-group-item-action list-group-item-light p-3" id="7"
                        onclick="exibeContent(this)">▶ Subitem 2</a> -->
                </div>

            </div>
        </div>
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light sidebar-heading" style="height: 58px;">
                <div class="container-fluid">
                    <button class="btn btn-success-pink" id="sidebarToggle">
                        << Menu</button>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation"><span
                                    class="navbar-toggler-icon"></span></button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                    <li class="nav-item active"><a class="nav-link" href="../model/encerrarSessao.php">
                                            <p id="sairOption">Sair</p>

                                        </a></li>
                                    <!-- <li class="nav-item active"><a class="nav-link" href="#!">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="#!">Link</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#!">Action</a>
                                    <a class="dropdown-item" href="#!">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="../model/encerrarSessao.php">Sair</a>
                                </div> -->
                                    </li>
                                </ul>
                            </div>
                </div>
            </nav>
            <iframe src="" id="frame" width="100%" height="90%" frameborder="0"></iframe>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script>
        exibeContent(document.getElementById("3")); //voltar pra 1
        function exibeContent(opt) {
            gerenciaSelected();
            var frame = document.getElementById('frame');
            switch (opt.id) {
                case '1':
                    frame.src = "NovaVenda/index.php";
                    document.body.classList.remove('sb-sidenav-toggled');
                    break;
                case '2':
                    frame.src = "ConsultaVendas/index.php";
                    document.body.classList.remove('sb-sidenav-toggled');
                    break;
                case '3':
                    frame.src = "Carteira/index.php";
                    document.body.classList.remove('sb-sidenav-toggled');
                    break;
                case '4':
                    frame.src = "Titulos/Direito/index.php";
                    document.body.classList.remove('sb-sidenav-toggled');
                    break;
                case '5':
                    frame.src = "Manutenção/index.php";
                    document.body.classList.remove('sb-sidenav-toggled');
                    break;
                case '6':
                    var submenu = document.getElementById('consultasSubMenu');
                    submenu.style.display = (submenu.style.display === 'none' || submenu.style.display === '') ? 'block' : 'none';
                    //document.getElementById('arrowbaixa').innerHTML = (submenu.style.display === 'none' || submenu.style.display === '') ? '' : '▼';
                    break;
                case '7':
                    frame.src = "Consultas/pessoas.php";
                    document.body.classList.remove('sb-sidenav-toggled');
                    break;
            }

            function gerenciaSelected() {
                var btns = document.getElementsByClassName('list-group-item');
                for (var i = 0; i < btns.length; i++) {
                    btns[i].classList.remove('active');
                }
                opt.classList.add('active');
            }

        }
    </script>
</body>

</html>