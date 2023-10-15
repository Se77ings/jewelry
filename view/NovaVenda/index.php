<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>Registrar Nova Venda</title>
    <style>
        #valor {
            width: 164px;
        }

        .col {
            display: flex;
            align-items: center;
            justify-content: center;
            /* width: 80%; */
        }

        .row {
            /* width: 80%; */
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin: 20px;
        }

        .input-group {
            width: 100%;
        }

        .exibir-em-celular {
            display: flex;
            flex-direction: column;
        }

        .exibir-em-celular .col {
            display: contents;
        }

        form {
            width: 100%;
        }

        #sugestoes {
            background-color: #fff;
            border: 1px solid black;
            border-radius: 5px;
        }

        .item {
            padding: 8px;
            cursor: pointer;
        }

        .item:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="container container-fluid border rounded-2" style="margin:auto;">


        <h4>Registrar Nova venda</h4>
        <form action="../../model/vendas/recebePedido.php" method="post" class="form form-flex " id="formulario">
            <div class="row" style="display: flex;">
                <h5>Dados do Produto:</h5>
                <hr>
                <div class="col">
                    <div class="form-flex">
                        <label for="id_produto" class="form-label">ID do Produto:</label>
                        <input class="form-control" style="width: 100px" type="text" name="id_produto" id="id_produto">
                    </div>
                </div>
                <div class="col">
                    <div class="form-flex">
                        <label class="form-label" for="valor">Valor:</label><br>
                        <div class="input-group form-flex">
                            <span class="input-group-text">R$</span>
                            <input type="text" name="valor" id="valor" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="display: flex;">
                <h5>Dados do cliente:</h5>
                <hr>
                <div class="col">
                    <div class="form-flex">
                        <label class="form-label" for="nome">Nome:</label>
                        <input class="form-control" type="text" name="nome" id="nome" autocomplete="off">
                        <div id="sugestoes" class="sugestoes" style="display:none"></div>
                        <!-- Container para sugestões -->
                    </div>
                </div>
                <div class="col">
                    <div class="form-flex">
                        <label class="form-label" for="telefone">Telefone:</label>
                        <input class="form-control" type="text" name="telefone" id="telefone">
                    </div>
                </div>
            </div>

            <div class="row" style="display: flex;">
                <h5>Condição de Pagamento:</h5>
                <hr>
                <div class="form-flex mb-3">
                    <label for="dataVenda">Data da Venda:</label>
                    <input type="date" id="dataVenda" name="dataVenda">
                </div>
                <div class="col">
                    <label for="condiçãoPagamento">&nbsp;</label>
                    <select name="condiçãoPagamento" id="condiçãoPagamento" class="form-select"
                        onchange="calculaParcelas(this)">
                        <option value="" selected hidden>Escolha Abaixo:</option>
                        <option value="1">À Vista</option>
                        <option value="2">Próximo Mês</option>
                        <option value="3">À Prazo - 2x</option>
                    </select>
                </div>
                <div class="col">
                    <div class="container border" id="valores"></div>
                </div>
            </div>
            <div class="row">
                <div clas="col">
                    <div class="" style="text-align:center;">
                        <button id="next" class="btn btn-primary">Registrar Venda</button>
                    </div>
                </div>
            </div>
    </div>
    </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function calculaParcelas(element)  {
            console.log(element.value);
            const valores = document.getElementById("valores");
            valores.innerHTML = "";
            i f (element.value ==  1) {
                valores.innerHTML = `<p>Valor Total: R$ ${document.getElementById("valor").value} - PAGO</p>`;
             } els e if (element.value  == 2) {
                valores.innerHTML = `<p>Valor Total: R$ ${document.getElementById("valor").value}</p>`;
             }  else if (element.val ue == 3) {
                valores.innerHTML = `<p>Valor Total: R$ ${document.getElementById("valor").value}</p>
                <p>2 Parcelas de : R$ ${document.getElementById("valo r ").value / 2}</p>`;
            }
        }

        ajustarExibicao();
        window.addEventListener('resize', ajustarExibicao);
        function ajustarExibicao() {
            var formulario = document.getElementById('formulario');

            if (window.innerWidth <= 768) { // Largura de 768px ou menos é geralmente considerada como um dispositivo móvel
                formulario.classList.add('exibir-em-celular');
            } else {
                formulario.classList.remove('exibir-em-celular');
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            const inputNome = document.getElementById("nome");
            const inputTelefone = document.getElementById("telefone");
            const sugestoes = document.getElementById("sugestoes");
            const nextButton = document.getElementById("next");

            inputNome.addEventListener("input", function () {
                const termo = inputNome.value;

                if (termo.length >= 2) {
                    fetch(`../../model/pessoas/buscar_nomes.php?termo=${termo}%`)
                        .then((response) => response.json())
                        .then((data) => {
                            sugestoes.innerHTML = "";
                            if (data && data.length > 0) { // Verifique se há dados

                                const dropdown = document.createElement("div");
                                dropdown.classList.add("dropdown");

                                data.forEach((object) => {
                                    const sugestao = document.createElement("div");
                                    sugestao.classList.add("item");
                                    sugestao.textContent = object.nome;
                                    sugestao.onclick = foo;
                                    dropdown.appendChild(sugestao);

                                    function foo() {
                                        inputNome.value = object.nome;
                                        sugestoes.innerHTML = "";
                                        sugestoes.style.display = "none";
                                        inputTelefone.value = object.telefone;
                                        nextButton.disabled = false;
                                    }
                                });
                                sugestoes.style.display = "block";
                                sugestoes.appendChild(dropdown);
                            } else {
                                const dropdown = document.createElement("div");
                                dropdown.classList.add("dropdown");

                                const criarUsuario = document.createElement("div");
                                criarUsuario.classList.add("item");
                                criarUsuario.innerHTML = "Cadastrar Usuário";
                                criarUsuario.removeEventListener("click", function () { });
                                criarUsuario.onclick = cadastrarUsuario;
                                dropdown.appendChild(criarUsuario);

                                function cadastrarUsuario() {
                                    Swal.fire({
                                        title: 'Cadastre o usuário abaixo:',
                                        margin: 'auto',
                                        icon: 'info',
                                        html: '<input id="swal-input1" class="swal2-input" style="margin:5px 0px" placeholder="Nome">' +
                                            '<input id="swal-input3" class="swal2-input" style="margin:5px 0px" placeholder="Telefone">',
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
                                            var xhr = new XMLHttpRequest();
                                            xhr.open("POST", "../../model/pessoas/cadastrar_pessoa.php", true);
                                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                            xhr.send(`nome=${result.value.nome}&telefone=${result.value.telefone}`);
                                            xhr.onreadystatechange = function () {
                                                if (xhr.readyState == 4 && xhr.status == 200) {
                                                    if (xhr.responseText == "1") {
                                                        Swal.fire({
                                                            title: 'Usuário cadastrado com sucesso!',
                                                            icon: 'success',
                                                            confirmButtonText: 'Ok'
                                                        })
                                                    } else {
                                                        Swal.fire({
                                                            title: 'Erro ao cadastrar usuário!',
                                                            icon: 'error',
                                                            confirmButtonText: 'Ok'

                                                        })
                                                    }
                                                }
                                            }
                                            inputNome.value = result.value.nome;
                                            inputTelefone.value = result.value.telefone;
                                            nextButton.disabled = false;
                                            sugestoes.style.display = "none";
                                            sugestoes.innerHTML = "";
                                        }
                                    })

                                }
                                sugestoes.style.display = "block";
                                sugestoes.appendChild(dropdown);
                            }
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                } else {
                    sugestoes.innerHTML = "";
                    nextButton.disabled = true;
                }
            });
    });

    </script>
</body>

</html>