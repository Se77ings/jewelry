-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22-Nov-2023 às 17:22
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `jewelry_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carteira`
--

CREATE TABLE `carteira` (
  `ID` int(11) NOT NULL,
  `saldo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `carteira`
--

INSERT INTO `carteira` (`ID`, `saldo`) VALUES
(1, 100.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `forma_pagamento`
--

CREATE TABLE `forma_pagamento` (
  `ID` int(11) NOT NULL,
  `condicao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `forma_pagamento`
--

INSERT INTO `forma_pagamento` (`ID`, `condicao`) VALUES
(1, '1x - À Vista'),
(2, '1x - Próximo Mês'),
(3, '2x - À Prazo'),
(4, '3x - 1 + 2 Parcelas');

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico_carteira`
--

CREATE TABLE `historico_carteira` (
  `ID` int(11) NOT NULL,
  `id_titulo` float NOT NULL,
  `data_transacao` datetime NOT NULL,
  `valor_transacao` decimal(10,2) NOT NULL,
  `saldo_anterior` decimal(10,2) NOT NULL,
  `saldo_atual` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `historico_carteira`
--

INSERT INTO `historico_carteira` (`ID`, `id_titulo`, `data_transacao`, `valor_transacao`, `saldo_anterior`, `saldo_atual`) VALUES
(1, 1, '2023-11-16 09:45:00', 100.00, 0.00, 100.00),
(2, 1, '2023-11-16 00:00:00', 0.00, 100.00, 100.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `manutencao`
--

CREATE TABLE `manutencao` (
  `ID` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `motivo` varchar(200) NOT NULL,
  `retornou` bit(1) NOT NULL,
  `imagem_problema` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `ID` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`ID`, `id_produto`, `valor`, `id_cliente`, `data`) VALUES
(1, 33, 500.00, 5, '2023-11-16');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoas`
--

CREATE TABLE `pessoas` (
  `ID` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pessoas`
--

INSERT INTO `pessoas` (`ID`, `nome`, `telefone`) VALUES
(1, 'admin', '1799999999'),
(2, 'ah', '15 12345'),
(3, 'Gabriel Lucas', '179999555'),
(4, 'Simone Teste', '17123456'),
(5, 'Gabriel', '17 1234565'),
(6, 'fulano', '179123456');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto_pedido`
--

CREATE TABLE `produto_pedido` (
  `id_produto` int(11) NOT NULL,
  `descricao` varchar(150) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `pedido_referencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `titulos`
--

CREATE TABLE `titulos` (
  `ID` int(11) NOT NULL,
  `valor_venda` decimal(10,2) NOT NULL,
  `valor_pago` decimal(10,2) NOT NULL,
  `data_emissao` date NOT NULL,
  `data_vencimento` date NOT NULL,
  `pedido_referencia` int(11) NOT NULL,
  `pago` bit(1) NOT NULL,
  `data_quitacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `titulos`
--

INSERT INTO `titulos` (`ID`, `valor_venda`, `valor_pago`, `data_emissao`, `data_vencimento`, `pedido_referencia`, `pago`, `data_quitacao`) VALUES
(1, 100.00, 100.00, '2023-11-16', '2023-11-16', 1, b'1', '2023-11-16'),
(2, 200.00, 0.00, '2023-11-16', '2023-12-16', 1, b'0', '0000-00-00'),
(3, 200.00, 0.00, '2023-11-16', '2024-01-15', 1, b'0', '0000-00-00');

--
-- Acionadores `titulos`
--
DELIMITER $$
CREATE TRIGGER `after_insert_pago` AFTER INSERT ON `titulos` FOR EACH ROW BEGIN
    IF NEW.pago = 1 THEN
        -- Obtenha o saldo anterior da carteira diretamente na consulta
        INSERT INTO historico_carteira (id_titulo, data_transacao, valor_transacao, saldo_anterior, saldo_atual)
        SELECT NEW.id, NOW(), NEW.valor_pago, c.saldo, c.saldo + NEW.valor_pago
        FROM carteira c
        WHERE c.id = 1;

        -- Atualize o saldo na tabela da carteira
        UPDATE carteira
        SET saldo = saldo + NEW.valor_pago
        WHERE id = 1;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_valor_pago` AFTER UPDATE ON `titulos` FOR EACH ROW BEGIN
    DECLARE valor_transacao FLOAT;
    DECLARE saldoOperacao FLOAT;
    DECLARE saldo_anterior_carteira FLOAT;

    IF NEW.valor_pago <> OLD.valor_pago OR NEW.data_quitacao <> OLD.data_quitacao THEN
        SET valor_transacao = NEW.valor_pago - OLD.valor_pago;
        SET saldoOperacao = OLD.valor_venda - OLD.valor_pago;

        SELECT saldo INTO saldo_anterior_carteira FROM carteira WHERE id = 1;

        UPDATE carteira
        SET saldo = saldo + valor_transacao;

        INSERT INTO historico_carteira (id_titulo, data_transacao, valor_transacao, saldo_anterior, saldo_atual)
        VALUES (NEW.id, NEW.data_quitacao, valor_transacao, saldo_anterior_carteira, saldo_anterior_carteira + valor_transacao);

    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `ativo` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`ID`, `login`, `senha`, `ativo`) VALUES
(1, 'admin', '2b64f2e3f9fee1942af9ff60d40aa5a719db33b8ba8dd4864bb4f11e25ca2bee00907de32a59429602336cac832c8f2eeff5177cc14c864dd116c8bf6ca5d9a9', b'1'),
(4, 'simone', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', b'1');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `carteira`
--
ALTER TABLE `carteira`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `forma_pagamento`
--
ALTER TABLE `forma_pagamento`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `historico_carteira`
--
ALTER TABLE `historico_carteira`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `manutencao`
--
ALTER TABLE `manutencao`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pedido_pessoa` (`id_cliente`);

--
-- Índices para tabela `pessoas`
--
ALTER TABLE `pessoas`
  ADD PRIMARY KEY (`ID`);

--
-- Índices para tabela `produto_pedido`
--
ALTER TABLE `produto_pedido`
  ADD PRIMARY KEY (`id_produto`);

--
-- Índices para tabela `titulos`
--
ALTER TABLE `titulos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `relacao_titulo_pedido` (`pedido_referencia`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carteira`
--
ALTER TABLE `carteira`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `historico_carteira`
--
ALTER TABLE `historico_carteira`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `manutencao`
--
ALTER TABLE `manutencao`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `titulos`
--
ALTER TABLE `titulos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedido_pessoa` FOREIGN KEY (`id_cliente`) REFERENCES `pessoas` (`ID`);

--
-- Limitadores para a tabela `titulos`
--
ALTER TABLE `titulos`
  ADD CONSTRAINT `relacao_titulo_pedido` FOREIGN KEY (`pedido_referencia`) REFERENCES `pedidos` (`ID`);

--
-- Limitadores para a tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `ligacao_usuario_pessoas` FOREIGN KEY (`ID`) REFERENCES `pessoas` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
