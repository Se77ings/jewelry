<?php
session_start();
if (!isset($_SESSION["login"])) {
    header('Location: ../../index.php?unlog');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/lib/css/styles.css">
    <link rel="stylesheet" href="../assets/lib/css/personalStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <title>Consulta Pessoas</title>
    <style>
        #nome {
            margin: 20px 40px 10px 35px;

        }

        #telefone {
            margin: 20px 40px 10px 15px;

        }

        #args {
            font-weight: bold;
            text-decoration: underline;
            font-style: italic;
            display: inline;
        }

        #responseFilter {
            width: 300px;
            margin: auto;
            text-align: center;
        }

        #responseFilter div {
            width: 100px;
            margin: auto;
        }

        table {
            min-width: 300px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 style="margin-top:25px">Consultar pessoas cadastradas:</h3>
        <h4 style="text-align:center;margin-top:40px">Filtro:</h4>
        <div class="input-group mb-3" style="width:300px; margin:auto;">
            <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                    height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg></span>
            <input type="text" class="form-control" placeholder="Pesquisar por nome" onkeyup="filtro(this.value)"
                aria-label="Username" aria-describedby="basic-addon1">
        </div>

        <table class="table" style="margin:auto;">
            <thead>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Alterar</th>
            </thead>
            <tbody>
                <?php
                require_once '../../model/conexao.php';

                $sql = "SELECT id, nome, telefone FROM pessoas ORDER BY nome ASC";
                $result = $conexao->query($sql);
                while ($row = $result->fetch()) {
                    echo "<tr id='" . $row['id'] . "'>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['telefone'] . "</td>";
                    echo "<td><button class='btn btn-success' onclick='alterar(" . $row['id'] . ")'>Alterar</button></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>

        </table>
        <div id="responseFilter"></div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    window.addEventListener('resize', function () {
        const popup = Swal.getPopup();
        const isOpen = Swal.isVisible();

        if (isOpen) {
            const windowHeight = window.innerHeight;
            const popupHeight = popup.offsetHeight;

            // Calcule a altura máxima do popup
            const maxHeight = Math.min(windowHeight - 20, popupHeight);

            // Ajuste a altura do popup
            popup.style.maxHeight = maxHeight + 'px';
        }
    });

    function filtro(args) {
        if (args.length > 3) {
            $.ajax({
                type: "POST",
                url: "../../controller/pessoas/filtroPessoas.php",
                data: {
                    opt: "pesquisa",
                    nome: args
                },
                success: function (data) {
                    var table = document.getElementsByTagName('tbody')[0];
                    if (data == 0) {
                        table.innerHTML = ``;
                        document.getElementById("responseFilter").innerHTML = `<p>Nenhum resultado para: <span id='args'> ${args} </span> !</p><p>Deseja cadastrar essa pessoa?</p><div><button class='btn btn-success' onclick="popupCadastro('${args}')">Cadastrar</button></div>`;
                    } else {
                        table.innerHTML = data;
                    }
                }
            });
        } else
            if (args.length == 0) {
                $.ajax({
                    type: "POST",
                    url: "../../controller/pessoas/filtroPessoas.php",
                    data: {
                        opt: "todos"
                    },
                    success: function (data) {
                        var table = document.getElementsByTagName('tbody')[0];
                        table.innerHTML = data;
                    }
                });
            }

    }
    function popupCadastro(args) {
        Swal.fire({
            title: 'Cadastre o usuário abaixo:',
            margin: 'auto',
            // icon: 'info',
            html: `<input id="swal-input1" class="swal2-input" style="margin:5px 0px" placeholder="Nome" value="${args}"> 
                <input id="swal-input3" class="swal2-input" style="margin:5px 0px" placeholder="Telefone">`,
            scrollbarPadding: false,
            focusConfirm: false,
            preConfirm: () => {
                const nome = Swal.getPopup().querySelector('#swal-input1').value
                const telefone = Swal.getPopup().querySelector('#swal-input3').value
                if (!nome || !telefone) {
                    Swal.showValidationMessage(`Preencha todos os campos!`)
                }
                return { nome: nome, telefone: telefone }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "../../controller/pessoas/cadastrar_pessoa.php",
                    data: {
                        nome: result.value.nome,
                        telefone: result.value.telefone
                    },
                    success: function (data) {
                        if (data == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cadastrado com sucesso!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Erro ao cadastrar!',
                            })
                        }
                    }
                });
            }
        })

    }

    function alterar(id) {
        const personId = id;
        var id = document.getElementById(id);

        Swal.fire({
            title: 'Alterar Dados:',
            html: `<label>Nome:</label><input type="text" id="nome" class="swal2-input" placeholder="Nome" value='${id.children[0].innerHTML}'>
            <br><label>Telefone:</label><input type="text" id="telefone" class="swal2-input" placeholder="Telefone" value= '${id.children[1].innerHTML}'>`,
            confirmButtonText: 'Alterar',
            focusConfirm: false,
            preConfirm: () => {
                const nome = Swal.getPopup().querySelector('#nome').value
                const telefone = Swal.getPopup().querySelector('#telefone').value
                if (!nome || !telefone) {
                    Swal.showValidationMessage(`Preencha todos os campos`)
                }
                return {
                    nome: nome,
                    telefone: telefone
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "../../controller/pessoas/alterarPessoa.php",
                    data: {
                        id: personId, // Use the extracted ID here
                        nome: result.value.nome,
                        telefone: result.value.telefone
                    },
                    success: function (data) {
                        if (data == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Alterado com sucesso!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Erro ao alterar!',
                            })
                        }
                    }
                });
            }
        })
    }       
</script>

</html>