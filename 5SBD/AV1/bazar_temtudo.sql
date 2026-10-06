-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/10/2026 às 15:50
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `temtudo`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `carregar_clientes` ()   BEGIN

    INSERT INTO clientes(
        nome,
        email,
        cpf,
        telefone,
        endereco,
        numero,
        complemento,
        cidade,
        estado,
        cep,
        pais
    )
    SELECT DISTINCT
        c.buyer_name,
        c.buyer_email,
        c.cpf,
        c.buyer_phone_number,
        c.ship_address_1,
        NULL,
        c.ship_address_2,
        c.ship_city,
        c.ship_state,
        c.ship_postal_code,
        c.ship_country
    FROM carga_pedidos c
    WHERE c.cpf IS NOT NULL
      AND NOT EXISTS(
          SELECT 1
          FROM clientes cl
          WHERE cl.cpf = c.cpf
      )
    GROUP BY
        c.cpf,
        c.buyer_name,
        c.buyer_email,
        c.buyer_phone_number,
        c.ship_address_1,
        c.ship_address_2,
        c.ship_city,
        c.ship_state,
        c.ship_postal_code,
        c.ship_country;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `carregar_itens_pedido` ()   BEGIN

    INSERT INTO itens_pedido(
        id_pedido,
        id_produto,
        order_item_id,
        quantidade,
        preco_unitario
    )
    SELECT
        p.id_pedido,
        pr.id_produto,
        c.order_item_id,
        c.quantity_purchased,
        c.item_price
    FROM carga_pedidos c
    INNER JOIN pedidos p
        ON p.order_id = c.order_id
    INNER JOIN produtos pr
        ON pr.sku=c.sku
    WHERE NOT EXISTS(
        SELECT 1
        FROM itens_pedido i
        WHERE i.id_pedido=p.id_pedido
          AND i.order_item_id = c.order_item_id
    );

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `carregar_pedidos` ()   BEGIN

    INSERT INTO pedidos(
        order_id,
        id_cliente,
        data_compra,
        data_pagamento,
        valor_total
    )
    SELECT
        c.order_id,
        cl.id_cliente,
        MIN(c.purchase_date),
        MIN(c.payments_date),
        SUM(c.quantity_purchased * c.item_price)
    FROM carga_pedidos c
    INNER JOIN clientes cl
        ON cl.cpf = c.cpf
    WHERE NOT EXISTS(
        SELECT 1
        FROM pedidos p
        WHERE p.order_id = c.order_id
    )
    GROUP BY
        c.order_id,
        cl.id_cliente;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `carregar_produtos` ()   BEGIN

    INSERT INTO produtos(
        id_produto,
        sku,
        nome,
        preco,
        estoque
    )
    SELECT
        NULL,
        c.sku,
        MAX(c.product_name),
        MAX(c.item_price),
        0
    FROM carga_pedidos c
    WHERE NOT EXISTS(
        SELECT 1
        FROM produtos p
        WHERE p.sku = c.sku
    )
    GROUP BY c.sku;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `gerar_compras` ()   BEGIN

    INSERT INTO compras(
        id_produto,
        id_pedido,
        quantidade,
        status
    )
    SELECT
        i.id_produto,
        i.id_pedido,
        i.quantidade - p.estoque,
        'PENDENTE'
    FROM itens_pedido i
    INNER JOIN produtos p
        ON p.id_produto = i.id_produto
    INNER JOIN pedidos ped
        ON ped.id_pedido = i.id_pedido
    WHERE ped.status = 'PENDENTE'
      AND p.estoque < i.quantidade
      AND NOT EXISTS(
          SELECT 1
          FROM compras c
          WHERE c.id_produto = i.id_produto
            AND c.id_pedido = i.id_pedido
            AND c.status = 'PENDENTE'
      );

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `processar_estoque` ()   BEGIN

    DECLARE fim INT DEFAULT 0;

    DECLARE v_id_pedido INT;
    DECLARE v_id_produto INT;
    DECLARE v_quantidade INT;
    DECLARE v_estoque INT;
    DECLARE v_itens_faltantes INT;

    DECLARE cursor_pedidos CURSOR FOR
        SELECT
            id_pedido
        FROM pedidos
        WHERE status='PENDENTE'
        ORDER BY valor_total DESC;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fim = 1;

    OPEN cursor_pedidos;

    loop_pedidos: LOOP

        FETCH cursor_pedidos
        INTO v_id_pedido;

        IF fim=1 THEN
            LEAVE loop_pedidos;
        END IF;

        SELECT COUNT(*)
        INTO v_itens_faltantes
        FROM itens_pedido i
        INNER JOIN produtos p
            ON p.id_produto = i.id_produto
        WHERE i.id_pedido = v_id_pedido
          AND p.estoque < i.quantidade;

        IF v_itens_faltantes=0 THEN

            BEGIN

                DECLARE fim_itens INT DEFAULT 0;

                DECLARE cursor_itens CURSOR FOR
                    SELECT
                        i.id_produto,
                        i.quantidade
                    FROM itens_pedido i
                    WHERE i.id_pedido = v_id_pedido;

                DECLARE CONTINUE HANDLER FOR NOT FOUND
                    SET fim_itens = 1;

                OPEN cursor_itens;

                loop_itens: LOOP

                    FETCH cursor_itens
                    INTO v_id_produto, v_quantidade;

                    IF fim_itens = 1 THEN
                        LEAVE loop_itens;
                    END IF;

                    SELECT estoque
                    INTO v_estoque
                    FROM produtos
                    WHERE id_produto = v_id_produto;

                    INSERT INTO movimentacao_estoque(
                        id_produto,
                        id_pedido,
                        quantidade,
                        estoque_anterior,
                        estoque_atual
                    )
                    VALUES (
                        v_id_produto,
                        v_id_pedido,
                        v_quantidade,
                        v_estoque,
                        v_estoque - v_quantidade
                    );

                    UPDATE produtos
                    SET estoque = estoque - v_quantidade
                    WHERE id_produto = v_id_produto;

                END LOOP;

                CLOSE cursor_itens;

            END;

            UPDATE pedidos
            SET status='ATENDIDO'
            WHERE id_pedido = v_id_pedido;

        END IF;

    END LOOP;

    CLOSE cursor_pedidos;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `receber_produtos` ()   BEGIN

    UPDATE produtos p
    INNER JOIN carga_fornecedor c
        ON c.sku = p.sku
    SET p.estoque = p.estoque + c.quantidade
    WHERE c.status = 'PENDENTE';

    UPDATE carga_fornecedor
    SET status='PROCESSADO'
    WHERE status='PENDENTE';

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `carga_fornecedor`
--

CREATE TABLE `carga_fornecedor` (
  `id_carga` int(11) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'PENDENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carga_fornecedor`
--

INSERT INTO `carga_fornecedor` (`id_carga`, `sku`, `quantidade`, `status`) VALUES
(2, 'TECLADO001', 5, 'PROCESSADO'),
(4, 'MOUSE001', 10, 'PROCESSADO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `carga_pedidos`
--

CREATE TABLE `carga_pedidos` (
  `id_carga` int(11) NOT NULL,
  `order_id` varchar(100) DEFAULT NULL,
  `order_item_id` varchar(100) DEFAULT NULL,
  `purchase_date` datetime DEFAULT NULL,
  `payments_date` datetime DEFAULT NULL,
  `buyer_email` varchar(150) DEFAULT NULL,
  `buyer_name` varchar(150) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `buyer_phone_number` varchar(30) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `upc` varchar(100) DEFAULT NULL,
  `product_name` varchar(200) DEFAULT NULL,
  `quantity_purchased` int(11) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `ship_service_level` varchar(100) DEFAULT NULL,
  `ship_address_1` varchar(200) DEFAULT NULL,
  `ship_address_2` varchar(200) DEFAULT NULL,
  `ship_address_3` varchar(200) DEFAULT NULL,
  `ship_city` varchar(100) DEFAULT NULL,
  `ship_state` varchar(100) DEFAULT NULL,
  `ship_postal_code` varchar(20) DEFAULT NULL,
  `ship_country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carga_pedidos`
--

INSERT INTO `carga_pedidos` (`id_carga`, `order_id`, `order_item_id`, `purchase_date`, `payments_date`, `buyer_email`, `buyer_name`, `cpf`, `buyer_phone_number`, `sku`, `upc`, `product_name`, `quantity_purchased`, `currency`, `item_price`, `ship_service_level`, `ship_address_1`, `ship_address_2`, `ship_address_3`, `ship_city`, `ship_state`, `ship_postal_code`, `ship_country`) VALUES
(1, '1001', '1001-1', '2026-09-26 10:00:00', '2026-09-26 10:05:00', 'joao@email.com', 'João da Silva', '111.111.111-11', '21999999999', 'TV001', '789000001', 'Smart TV 50 Polegadas', 1, 'BRL', 2500.00, 'Express', 'Rua A', '', '', 'Rio de Janeiro', 'RJ', '20000-000', 'Brasil'),
(2, '1001', '1001-2', '2026-09-26 10:00:00', '2026-09-26 10:05:00', 'joao@email.com', 'João da Silva', '111.111.111-11', '21999999999', 'CEL001', '789000002', 'Celular Samsung', 1, 'BRL', 1800.00, 'Express', 'Rua A', '', '', 'Rio de Janeiro', 'RJ', '20000-000', 'Brasil'),
(3, '1001', '1001-3', '2026-09-26 10:00:00', '2026-09-26 10:05:00', 'joao@email.com', 'João da Silva', '111.111.111-11', '21999999999', 'FONE001', '789000003', 'Fone Bluetooth', 2, 'BRL', 250.00, 'Express', 'Rua A', '', '', 'Rio de Janeiro', 'RJ', '20000-000', 'Brasil'),
(4, '1002', '1002-1', '2026-09-26 11:00:00', '2026-09-26 11:05:00', 'maria@email.com', 'Maria Oliveira', '222.222.222-22', '21988888888', 'MOUSE001', '789000004', 'Mouse sem fio', 1, 'BRL', 120.00, 'Standard', 'Rua B', '', '', 'Niterói', 'RJ', '24000-000', 'Brasil'),
(5, '1003', '1003-1', '2026-09-28 14:00:00', '2026-09-28 14:05:00', 'carlos@email.com', 'Carlos Souza', '333.333.333-33', '21977777777', 'TECLADO001', '789000005', 'Teclado Gamer', 1, 'BRL', 300.00, 'Standard', 'Rua C', NULL, NULL, 'Rio de Janeiro', 'RJ', '21000-000', 'Brasil'),
(6, '2001', '2001-1', '2026-09-28 15:00:00', '2026-09-28 15:05:00', 'ana@email.com', 'Ana Souza', '444.444.444-44', '21966666666', 'FONE001', '789000006', 'Fone Bluetooth', 4, 'BRL', 250.00, 'Express', 'Rua D', NULL, NULL, 'Rio de Janeiro', 'RJ', '22000-000', 'Brasil'),
(7, '2002', '2002-1', '2026-09-28 15:10:00', '2026-09-28 15:15:00', 'bruno@email.com', 'Bruno Lima', '555.555.555-55', '21955555555', 'FONE001', '789000006', 'Fone Bluetooth', 2, 'BRL', 250.00, 'Express', 'Rua E', NULL, NULL, 'Rio de Janeiro', 'RJ', '23000-000', 'Brasil'),
(8, 'PED001', 'ITEM001', '2026-10-01 10:00:00', '2026-10-01 10:30:00', 'joao@email.com', 'Joao Silva', '11111111111', '21999990001', 'SKU001', '789000000001', 'Notebook Dell', 2, 'BRL', 3500.00, 'Standard', 'Rua das Flores', '100', NULL, 'Rio de Janeiro', 'RJ', '20000-000', 'Brasil'),
(9, 'PED001', 'ITEM002', '2026-10-01 10:00:00', '2026-10-01 10:30:00', 'joao@email.com', 'Joao Silva', '11111111111', '21999990001', 'SKU002', '789000000002', 'Mouse Logitech', 3, 'BRL', 120.00, 'Standard', 'Rua das Flores', '100', NULL, 'Rio de Janeiro', 'RJ', '20000-000', 'Brasil'),
(10, 'PED002', 'ITEM003', '2026-10-02 14:00:00', '2026-10-02 14:20:00', 'maria@email.com', 'Maria Santos', '22222222222', '21999990002', 'SKU003', '789000000003', 'Teclado Mecânico', 1, 'BRL', 250.00, 'Express', 'Avenida Brasil', '250', NULL, 'Niteroi', 'RJ', '24000-000', 'Brasil'),
(11, 'PED003', 'ITEM004', '2026-10-03 09:00:00', '2026-10-03 09:15:00', 'carlos@email.com', 'Carlos Oliveira', '33333333333', '21999990003', 'SKU001', '789000000001', 'Notebook Dell', 5, 'BRL', 3500.00, 'Standard', 'Rua do Comercio', '500', NULL, 'Sao Goncalo', 'RJ', '24400-000', 'Brasil'),
(12, 'PED004', 'ITEM005', '2026-10-04 16:00:00', '2026-10-04 16:30:00', 'ana@email.com', 'Ana Costa', '44444444444', '21999990004', 'SKU004', '789000000004', 'Monitor LG', 2, 'BRL', 900.00, 'Standard', 'Rua Principal', '80', NULL, 'Duque de Caxias', 'RJ', '25000-000', 'Brasil'),
(13, 'PED005', 'ITEM006', '2026-10-05 11:00:00', '2026-10-05 11:20:00', 'lucas@email.com', 'Lucas Souza', '55555555555', '21999990005', 'SKU005', '789000000005', 'Headset Gamer', 4, 'BRL', 300.00, 'Express', 'Rua Central', '150', NULL, 'Nova Iguacu', 'RJ', '26200-000', 'Brasil');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `email`, `cpf`, `telefone`, `endereco`, `numero`, `complemento`, `cidade`, `estado`, `cep`, `pais`) VALUES
(1, 'João da Silva', 'joao@email.com', '111.111.111-11', '21999999999', 'Rua A', NULL, '', 'Rio de Janeiro', 'RJ', '20000-000', 'Brasil'),
(2, 'Maria Oliveira', 'maria@email.com', '222.222.222-22', '21988888888', 'Rua B', NULL, '', 'Niterói', 'RJ', '24000-000', 'Brasil'),
(4, 'Carlos Souza', 'carlos@email.com', '333.333.333-33', '21977777777', 'Rua C', NULL, NULL, 'Rio de Janeiro', 'RJ', '21000-000', 'Brasil'),
(5, 'Ana Souza', 'ana@email.com', '444.444.444-44', '21966666666', 'Rua D', NULL, NULL, 'Rio de Janeiro', 'RJ', '22000-000', 'Brasil'),
(6, 'Bruno Lima', 'bruno@email.com', '555.555.555-55', '21955555555', 'Rua E', NULL, NULL, 'Rio de Janeiro', 'RJ', '23000-000', 'Brasil'),
(7, 'Joao Silva', 'joao@email.com', '11111111111', '21999990001', 'Rua das Flores', NULL, '100', 'Rio de Janeiro', 'RJ', '20000-000', 'Brasil'),
(8, 'Maria Santos', 'maria@email.com', '22222222222', '21999990002', 'Avenida Brasil', NULL, '250', 'Niteroi', 'RJ', '24000-000', 'Brasil'),
(9, 'Carlos Oliveira', 'carlos@email.com', '33333333333', '21999990003', 'Rua do Comercio', NULL, '500', 'Sao Goncalo', 'RJ', '24400-000', 'Brasil'),
(10, 'Ana Costa', 'ana@email.com', '44444444444', '21999990004', 'Rua Principal', NULL, '80', 'Duque de Caxias', 'RJ', '25000-000', 'Brasil'),
(11, 'Lucas Souza', 'lucas@email.com', '55555555555', '21999990005', 'Rua Central', NULL, '150', 'Nova Iguacu', 'RJ', '26200-000', 'Brasil');

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `data_compra` datetime DEFAULT current_timestamp(),
  `status` varchar(30) DEFAULT 'PENDENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compras`
--

INSERT INTO `compras` (`id_compra`, `id_produto`, `id_pedido`, `quantidade`, `data_compra`, `status`) VALUES
(3, 4, 2, 1, '2026-09-28 19:25:14', 'PENDENTE'),
(4, 6, 7, 2, '2026-10-06 10:48:27', 'PENDENTE'),
(5, 7, 7, 3, '2026-10-06 10:48:27', 'PENDENTE'),
(6, 8, 8, 1, '2026-10-06 10:48:27', 'PENDENTE'),
(7, 6, 9, 5, '2026-10-06 10:48:27', 'PENDENTE'),
(8, 9, 10, 2, '2026-10-06 10:48:27', 'PENDENTE'),
(9, 10, 11, 4, '2026-10-06 10:48:27', 'PENDENTE');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id_item` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `order_item_id` varchar(100) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id_item`, `id_pedido`, `id_produto`, `order_item_id`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 1, '1001-1', 1, 2500.00),
(2, 1, 2, '1001-2', 1, 1800.00),
(3, 1, 3, '1001-3', 2, 250.00),
(4, 2, 4, '1002-1', 1, 120.00),
(8, 4, 5, '1003-1', 1, 300.00),
(9, 5, 3, '2001-1', 4, 250.00),
(10, 6, 3, '2002-1', 2, 250.00),
(11, 7, 6, 'ITEM001', 2, 3500.00),
(12, 7, 7, 'ITEM002', 3, 120.00),
(13, 8, 8, 'ITEM003', 1, 250.00),
(14, 9, 6, 'ITEM004', 5, 3500.00),
(15, 10, 9, 'ITEM005', 2, 900.00),
(16, 11, 10, 'ITEM006', 4, 300.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacao_estoque`
--

CREATE TABLE `movimentacao_estoque` (
  `id_movimentacao` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `estoque_anterior` int(11) NOT NULL,
  `estoque_atual` int(11) NOT NULL,
  `data_movimentacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `movimentacao_estoque`
--

INSERT INTO `movimentacao_estoque` (`id_movimentacao`, `id_produto`, `id_pedido`, `quantidade`, `estoque_anterior`, `estoque_atual`, `data_movimentacao`) VALUES
(1, 1, 1, 1, 10, 9, '2026-09-28 18:15:52'),
(2, 2, 1, 1, 5, 4, '2026-09-28 18:15:52'),
(3, 3, 1, 2, 20, 18, '2026-09-28 18:15:52'),
(4, 3, 5, 4, 10, 6, '2026-09-28 18:54:59'),
(5, 3, 6, 2, 6, 4, '2026-09-28 18:54:59'),
(6, 5, 4, 1, 5, 4, '2026-09-28 18:54:59'),
(8, 4, 2, 1, 10, 9, '2026-09-28 20:00:52');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `data_compra` datetime DEFAULT NULL,
  `data_pagamento` datetime DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT 0.00,
  `status` varchar(30) NOT NULL DEFAULT 'PENDENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `order_id`, `id_cliente`, `data_compra`, `data_pagamento`, `valor_total`, `status`) VALUES
(1, '1001', 1, '2026-09-26 10:00:00', '2026-09-26 10:05:00', 4800.00, 'ATENDIDO'),
(2, '1002', 2, '2026-09-26 11:00:00', '2026-09-26 11:05:00', 120.00, 'ATENDIDO'),
(4, '1003', 4, '2026-09-28 14:00:00', '2026-09-28 14:05:00', 300.00, 'ATENDIDO'),
(5, '2001', 5, '2026-09-28 15:00:00', '2026-09-28 15:05:00', 1000.00, 'ATENDIDO'),
(6, '2002', 6, '2026-09-28 15:10:00', '2026-09-28 15:15:00', 500.00, 'ATENDIDO'),
(7, 'PED001', 7, '2026-10-01 10:00:00', '2026-10-01 10:30:00', 7360.00, 'PENDENTE'),
(8, 'PED002', 8, '2026-10-02 14:00:00', '2026-10-02 14:20:00', 250.00, 'PENDENTE'),
(9, 'PED003', 9, '2026-10-03 09:00:00', '2026-10-03 09:15:00', 17500.00, 'PENDENTE'),
(10, 'PED004', 10, '2026-10-04 16:00:00', '2026-10-04 16:30:00', 1800.00, 'PENDENTE'),
(11, 'PED005', 11, '2026-10-05 11:00:00', '2026-10-05 11:20:00', 1200.00, 'PENDENTE');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `sku`, `nome`, `preco`, `estoque`) VALUES
(1, 'TV001', 'Smart TV 50 Polegadas', 2500.00, 9),
(2, 'CEL001', 'Celular Samsung', 1800.00, 4),
(3, 'FONE001', 'Fone Bluetooth', 250.00, 4),
(4, 'MOUSE001', 'Mouse sem fio', 120.00, 9),
(5, 'TECLADO001', 'Teclado Gamer', 300.00, 14),
(6, 'SKU001', 'Notebook Dell', 3500.00, 1),
(7, 'SKU002', 'Mouse Logitech', 120.00, 2),
(8, 'SKU003', 'Teclado Mecânico', 250.00, 1),
(9, 'SKU004', 'Monitor LG', 900.00, 0),
(10, 'SKU005', 'Headset Gamer', 300.00, 2);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carga_fornecedor`
--
ALTER TABLE `carga_fornecedor`
  ADD PRIMARY KEY (`id_carga`);

--
-- Índices de tabela `carga_pedidos`
--
ALTER TABLE `carga_pedidos`
  ADD PRIMARY KEY (`id_carga`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `movimentacao_estoque`
--
ALTER TABLE `movimentacao_estoque`
  ADD PRIMARY KEY (`id_movimentacao`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carga_fornecedor`
--
ALTER TABLE `carga_fornecedor`
  MODIFY `id_carga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `carga_pedidos`
--
ALTER TABLE `carga_pedidos`
  MODIFY `id_carga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `movimentacao_estoque`
--
ALTER TABLE `movimentacao_estoque`
  MODIFY `id_movimentacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`),
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);

--
-- Restrições para tabelas `movimentacao_estoque`
--
ALTER TABLE `movimentacao_estoque`
  ADD CONSTRAINT `movimentacao_estoque_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`),
  ADD CONSTRAINT `movimentacao_estoque_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
