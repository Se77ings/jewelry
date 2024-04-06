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
    <title>Registrar Nova Venda</title>
    <link rel="stylesheet" href="../assets/lib/css/styles.css">
    <link rel="stylesheet" href="../assets/lib/css/personalStyle.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,400;0,500;1,300;1,700&display=swap"
        rel="stylesheet">
    <style>
        #sugestoes {
            border: 1px solid black;
            border-radius: 5px;
        }

        #valores {
            margin-top: 8px;
            width: fit-content;
            border: none;
            border-radius: 20px;
            padding: 5px;
            background-color: white;
            border: solid 1px black;
        }

        #valores p {
            margin: 0px;
            padding: 0px;
            font-size: 16px;
        }

        .last-Son {
            flex: 0.5;
            /* Ajuste a flexibilidade da terceira coluna conforme necessário */
            /* Além disso, você pode ajustar a largura conforme necessário */
            max-width: 50px;
        }

        .bi-plus-circle-fill:hover {
            cursor: pointer;
            scale: 1.2;
            transition: 0.2s;
        }

        .bi-plus-circle-fill:active {
            color: blue;
            scale: 2.2;
        }

        #tableProdutos {
            border-collapse: collapse;
            width: 100%;
        }

        #tableProdutos th {
            text-align: center;
        }

        #tableProdutos td {
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 1px;
            text-align: center;
        }

        .listProd {
            display: flex;
            justify-content: space-between;
            width: 200px;
            margin-bottom: 0px;
            margin: auto;
        }

        @media screen and (max-width:500px) {
            #valor {
                width: 80px;
            }
        }

        @media screen and (min-width:700px) {
            #valor {
                width: 124px;
            }

            #pai {
                width: 170px;
            }
        }
    </style>
</head>

<body>
    <main class="container container-fluid" style="margin:auto;">
        <h3 style="margin-top:5px; margin-bottom:0px;">Registrar Nova venda</h3>
        <form action="../../controller/vendas/recebePedido.php" method="post" class="form form-flex " id="formulario">
            <div class="row" style="display: flex;margin: 15px 10px;">
                <h5>Dados do Produto:</h5>
                <hr>
                <div style="display:flex;justify-content:space-around">
                    <div class="col-4">
                        <div class="form-flex">
                            <label for="id_produto">Código:</label>
                            <input class="form-control" style="width: 90px" type="number" name="id_produto"
                                id="id_produto">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-flex">
                            <label for="valor">Valor:</label>
                            <div class="input-group form-flex" style="flex-direction: row" id="pai">
                                <span class="input-group-text">R$</span>
                                <input type="text" id="valor" class="form-control" placeholder="0,00">
                            </div>

                        </div>
                    </div>
                </div>
                <div style="display:flex;margin-top:12px;justify-content:space-around;">
                    <div class="col-8">
                        <div class="form-flex">
                            <input type="text" placeholder="Descrição" class="form-control" id="descricao_produto"
                                name="descricao_produto">
                        </div>
                    </div>
                    <div class="col last-Son">
                        <div class="form-flex">
                            <h2 style="color:green;" class="bi bi-plus-circle-fill" onclick="adicionarProduto()"></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="display: flex; flex-direction: column">
                <h5>Dados do cliente:</h5>
                <hr>
                <div class="col">
                    <div class="form-flex">
                        <label for="nome" required>Nome:</label>
                        <input class="form-control" type="text" name="nome" id="nome" autocomplete="off" required>
                        <div id="sugestoes" class="sugestoes" style="display:none"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-flex">
                        <label for="telefone">Telefone:</label>
                        <input class="form-control" type="text" name="telefone" id="telefone" required>
                    </div>
                </div>
            </div>

            <div class="row" style="display: flex; flex-direction: column; margin: 10px 10px;">
                <div style="display: flex;">
                    <h5 class="col-10">Condição de Pagamento:</h5>
                    <h1 id="lock" onclick="showMessage()" class="bi bi-lock-fill"></h1>
                    <h1 id="unlock" class="bi bi-unlock-fill" style="display:none;"></h1>
                </div>
                <hr>
                <div class="mb-3 col">
                    <div class="form-flex">
                        <label for="dataVenda" style="text-align:left;">Data da Venda:</label>
                        <input type="date" id="dataVenda"
                            style="width:282px; padding: 0.375rem 0.75rem; border-radius:8px; border: solid 1px gainsboro;"
                            name="dataVenda" class="form-control" disabled required>
                    </div>
                </div>
                <div class="col">
                    <label for="condiçãoPagamento">&nbsp;</label>
                    <select name="condiçãoPagamento" id="condiçãoPagamento" class="form-select"
                        style="width: 280px; padding: 0.375rem 0.75rem; border-radius:8px; margin-left:0px;"
                        onchange="calculaParcelas(this)" disabled required>
                        <option value="" selected hidden>Escolha o tipo de pagamento:</option>
                        <option value="1">À Vista</option>
                        <option value="2">Próximo Mês</option>
                        <option value="3">À Prazo - 2x</option>
                        <option value="4">1 + 2 Parcelas</option>
                    </select>
                </div>
                <div class="col">
                    <div class="container" id="valores"></div>
                </div>
            </div>
            <div class="row" style="display: none;" id="produtos">
            </div>

            <input type="hidden" name="num_produtos" id="num_produtos" value="0">
            <div id="produtos_container"></div>


            <input type="text" name="entrada" value="" id="entrada" style="display:none;">
            <input type="text" style='display:none' id='valorFinal' name="valorFinal">
            <div class="" style="text-align:center;">
                <button id="next" type="submit" class="btn btn-success" disabled>Registrar Venda</button>
            </div>
    </main>
    </form>

    </div>
    <script src="../assets\global_functions\dateAndNumberFormattingJS.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('valor').addEventListener('blur', function () {
            var valor = convertePonto(this.value);
            valorParsed = parseFloat(valor).toFixed(2);
            this.value = valorParsed;
            

        });

        
        function exibeValor(valor) {

            if(valor === null || valor === undefined || valor === '') {
                return '';
            }
            if(typeof valor === 'string') {
            valorFinal = valor.replace('.', ',');
            return  valorFinal;
            }else if(typeof valor === 'number') {
                    return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

}

        function updateListaProdutos() {
            var produtosContainer = document.getElementById("produtos_container");
            var numProdutosInput = document.getElementById("num_produtos");
            produtosContainer.innerHTML = "";


            var produtos = document.getElementById("produtos");
            var tabelaHTML = "<h5>Produtos neste pedido:</h5><hr><table id='tableProdutos'><tr><th>Código</th><th>Valor</th><th>Ações</th></tr>";
            produtos.style.display = "table"; // Altera o display para "table"

            Produtos.forEach(produto => {
                tabelaHTML += `
            <tr>
                <td>${produto.id}</td>
                <td>R$ ${exibeValor(produto.valor)}</td>
                <td>
                    <h2 style="color:red;" class="bi bi-x-circle-fill" onclick="removerProduto(${produto.id})"></h2>
                </td>
            </tr>`;
            });
            Produtos.forEach(produto => {
                produtosContainer.innerHTML += `
            <input type="hidden" name="produto_id[]" value="${produto.id}">
            <input type="hidden" name="produto_valor[]" value="${produto.valor}">
            <input type="hidden" name="produto_descricao[]" value="${produto.descricao_produto}">
        `;
            });

            tabelaHTML += "</table>";
            tabelaHTML += "<p style='text-align:center'> <b>Valor Total:</b> R$ " + exibeValor(Produtos.reduce((total, produto) => total + parseFloat(produto.valor), 0)) + "</p>";

            valorFinal = Produtos.reduce((total, produto) => total + parseFloat(produto.valor), 0);
            document.getElementById("valorFinal").value = valorFinal;

            produtos.innerHTML = tabelaHTML;
            numProdutosInput.value = Produtos.length;

            var lock = document.getElementById("lock");
            var unlock = document.getElementById("unlock");
            var dataVenda = document.getElementById("dataVenda");
            var condiçãoPagamento = document.getElementById("condiçãoPagamento");
            var botaoFinal = document.getElementById("next");
            var divValores = document.getElementById("valores");
            if (Produtos.length == 0) {
                condiçãoPagamento.options.selectedIndex = 0;
                divValores.innerHTML = "";
                produtos.style.display = "none";
                lock.style.display = "block";
                unlock.style.display = "none";
                dataVenda.disabled = true;
                condiçãoPagamento.disabled = true;
                botaoFinal.disabled = true;
            } else {
                lock.style.display = "none";
                unlock.style.display = "block";
                dataVenda.disabled = false;
                condiçãoPagamento.disabled = false;
                botaoFinal.disabled = false;
            }

        }

        document.getElementById("formulario").addEventListener("submit", function (event) {
            // Chama a função para atualizar os campos ocultos com os dados da lista de produtos
            var id_produto = document.getElementById("id_produto").value;
            var valor = document.getElementById("valor").value;
            var descricao_produto = document.getElementById("descricao_produto").value;

            if (id_produto && valor && descricao_produto) {
                Swal.fire({
                    title: "Ops!",
                    html: "<p>Parece que o seguinte produto não foi adicionado no pedido:</p>" +
                        "<p class='listProd'><b>ID do Produto:</b> " + id_produto + "</p>" +
                        "<p class='listProd'><b>Valor:</b> " + valor + "</p>" +
                        "<p class='listProd'><b>Descrição:</b> " + descricao_produto + "</p>" +
                        "<p>Deseja adicionar?</p>",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sim",
                    cancelButtonText: "Não"
                }).then((result) => {
                    if (result.isConfirmed) {
                        adicionarProduto();
                        updateListaProdutos();
                    }
                    else if(result.dismiss === Swal.DismissReason.cancel){
                        document.getElementById("id_produto").value = "";
                        document.getElementById("valor").value = "";
                        document.getElementById("descricao_produto").value = "";
                    }
                });
                event.preventDefault();
            } else {
                updateListaProdutos();
            }
        });


        var Produtos = [];

        function produtoJaInserido(id) {
            return Produtos.some(produto => produto.id === id);
        }

        function showMessage() {
            Swal.fire({
                title: 'Atenção!',
                text: 'Você precisa adicionar pelo menos um produto para registrar a venda!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            })
        }

        function adicionarProduto() {
            var idProduto = document.getElementById('id_produto');
            var valor = document.getElementById('valor');
            var descricao_produto = document.getElementById('descricao_produto');


            if (idProduto.value == "" && valor.value == "") {
                return;
                // console.log("Vazio");
            } else if (idProduto.value == '' && valor.value != '') {
                return;
                // console.log("Valor preenchido, id vazio");
            } else if (idProduto.value != '' && valor.value == '') {
                return;
                // console.log("Id preenchido, valor vazio");
            } else {
                // console.log("Ambos preenchidos");

                // Verifica se o produto já está na lista
                if (!produtoJaInserido(idProduto.value)) {
                    var produto = {
                        id: idProduto.value,
                        valor: valor.value,
                        descricao_produto: descricao_produto.value
                    };
                    idProduto.value = "";
                    valor.value = "";
                    descricao_produto.value = "";

                    Produtos.push(produto);
                    // console.log("Produto inserido:", produto);
                    // console.log("Lista de produtos:", Produtos);
                } else {
                    alert("Produto já está na lista! Insira outro")
                    // console.log("Produto já está na lista. Não foi inserido novamente.");
                }
            }
            updateListaProdutos();

        }

        function removerProduto(id) {
            // console.log("Removendo produto com ID:", id);

            // Convertendo o id para string para garantir uma comparação correta
            id = id.toString();

            Produtos = Produtos.filter(produto => produto.id !== id);
            // console.log("Produto removido com sucesso. Nova lista de produtos:", Produtos);
            updateListaProdutos();
        }

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

        function calculaParcelas(element) {
            // console.log(element.value);
            const valores = document.getElementById("valores");
            valores.innerHTML = "";
            if (element.value == 1) {
                valores.innerHTML = `<p>Valor Total: R$ ${document.getElementById("valorFinal").value} - PAGO</p>`;
            } else if (element.value == 2) {
                valores.innerHTML = `<p>Valor Total: R$ ${document.getElementById("valorFinal").value}</p>`;
            } else if (element.value == 3) {
                valores.innerHTML = `<p>Valor Total: R$ ${document.getElementById("valorFinal").value}</p>
                <p>2 Parcelas de : R$ ${document.getElementById("valorFinal").value / 2}</p>`;
            } else if (element.value == 4) {
                Swal.fire({
                    title: 'Digite o valor da entrada:',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: false,
                    allowOutsideClick: false,
                    confirmButtonText: 'Ok',
                    showLoaderOnConfirm: true,
                    preConfirm: (valor) => {
                        if (valor == "") {
                            Swal.showValidationMessage(`Preencha o campo!`)
                        }
                        return { valor: valor }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("entrada").value = result.value.valor;
                        valores.innerHTML = `<p>Valor Total: R$ ${document.getElementById("valorFinal").value}</p>
                        <p>Entrada: R$ ${result.value.valor}</p>
                        <p>2 Parcelas de : R$ ${(document.getElementById("valorFinal").value - result.value.valor) / 2}</p>`;
                    }

                })
            }

            if (valores.innerHTML != "") {
                document.getElementById("next").disabled = false;
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
                    fetch(`../../controller/pessoas/buscar_nomes.php?termo=${termo}%`)
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
                                        // icon: 'info',
                                        html: '<input id="swal-input1" class="swal2-input" style="margin:5px 0px" placeholder="Nome">' +
                                            '<input id="swal-input3" type="number" class="swal2-input" style="margin:5px 0px" placeholder="Telefone">',
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
                                            var xhr = new XMLHttpRequest();
                                            xhr.open("POST", "../../controller/pessoas/cadastrar_pessoa.php", true);
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