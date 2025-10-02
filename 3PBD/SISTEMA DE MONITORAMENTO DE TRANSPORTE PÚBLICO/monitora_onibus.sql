-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/09/2025 às 02:01
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `monitora_onibus`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento`
--

CREATE TABLE `evento` (
  `Id` int(11) NOT NULL,
  `Impacto` enum('Leve','Mediano','Grave') NOT NULL,
  `Local` varchar(100) NOT NULL,
  `Descricao` varchar(100) NOT NULL,
  `hora_inicio` varchar(50) NOT NULL,
  `hora_final` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `linha`
--

CREATE TABLE `linha` (
  `Cod_linha` int(11) NOT NULL,
  `Id_motorista` int(11) NOT NULL,
  `Hora_inicio` varchar(50) NOT NULL,
  `Hora_termino` varchar(50) NOT NULL,
  `Paradas` text NOT NULL,
  `Entensao` int(11) NOT NULL,
  `Itinerario` varchar(100) NOT NULL,
  `Nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitoramento`
--

CREATE TABLE `monitoramento` (
  `Id_monitoramento` int(11) NOT NULL,
  `Id_viagem` int(11) NOT NULL,
  `Embarque_Passageiros` smallint(6) NOT NULL,
  `Desembarque_Passageiros` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `motorista`
--

CREATE TABLE `motorista` (
  `Id_motorista` int(11) NOT NULL,
  `Cod_linha` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `CNH` varchar(2) NOT NULL,
  `Status` enum('ativo','inativo','ferias','licenca') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `onibus`
--

CREATE TABLE `onibus` (
  `Placa_onibus` varchar(7) NOT NULL,
  `Ano_Fabricacao` year(4) NOT NULL,
  `Status` enum('ativo','manutencao','inativo','') DEFAULT 'ativo',
  `capacidade` tinyint(4) NOT NULL,
  `Modelo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `passageiro`
--

CREATE TABLE `passageiro` (
  `Id_passageiro` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `CPF` varchar(14) NOT NULL,
  `Email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `predicao`
--

CREATE TABLE `predicao` (
  `Id_predicao` int(11) NOT NULL,
  `Cod_linha` int(11) NOT NULL,
  `Lotacao` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rota_passageiro`
--

CREATE TABLE `rota_passageiro` (
  `Id_passageiro` int(11) NOT NULL,
  `Placa_onibus` varchar(7) NOT NULL,
  `Cod_linha` int(11) NOT NULL,
  `hora_entrada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hora_saida` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `viagem`
--

CREATE TABLE `viagem` (
  `Id_viagem` int(11) NOT NULL,
  `Cod_linha` int(11) NOT NULL,
  `Placa_onibus` varchar(7) NOT NULL,
  `qtd_passageiro` smallint(6) NOT NULL,
  `hora_inicio` varchar(50) NOT NULL,
  `hora_final` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `linha`
--
ALTER TABLE `linha`
  ADD PRIMARY KEY (`Cod_linha`),
  ADD KEY `Id_motorista` (`Id_motorista`);

--
-- Índices de tabela `monitoramento`
--
ALTER TABLE `monitoramento`
  ADD KEY `Id_viagem` (`Id_viagem`);

--
-- Índices de tabela `motorista`
--
ALTER TABLE `motorista`
  ADD PRIMARY KEY (`Id_motorista`),
  ADD KEY `Cod_linha` (`Cod_linha`);

--
-- Índices de tabela `onibus`
--
ALTER TABLE `onibus`
  ADD PRIMARY KEY (`Placa_onibus`);

--
-- Índices de tabela `passageiro`
--
ALTER TABLE `passageiro`
  ADD PRIMARY KEY (`Id_passageiro`),
  ADD UNIQUE KEY `CPF` (`CPF`);

--
-- Índices de tabela `predicao`
--
ALTER TABLE `predicao`
  ADD PRIMARY KEY (`Id_predicao`),
  ADD KEY `Cod_linha` (`Cod_linha`);

--
-- Índices de tabela `rota_passageiro`
--
ALTER TABLE `rota_passageiro`
  ADD KEY `Cod_linha` (`Cod_linha`),
  ADD KEY `Id_passageiro` (`Id_passageiro`),
  ADD KEY `Placa_onibus` (`Placa_onibus`);

--
-- Índices de tabela `viagem`
--
ALTER TABLE `viagem`
  ADD PRIMARY KEY (`Id_viagem`),
  ADD KEY `Placa_onibus` (`Placa_onibus`),
  ADD KEY `Cod_linha` (`Cod_linha`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `linha`
--
ALTER TABLE `linha`
  MODIFY `Cod_linha` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `motorista`
--
ALTER TABLE `motorista`
  MODIFY `Id_motorista` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `passageiro`
--
ALTER TABLE `passageiro`
  MODIFY `Id_passageiro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `viagem`
--
ALTER TABLE `viagem`
  MODIFY `Id_viagem` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `linha`
--
ALTER TABLE `linha`
  ADD CONSTRAINT `linha_ibfk_1` FOREIGN KEY (`Id_motorista`) REFERENCES `motorista` (`Id_motorista`);

--
-- Restrições para tabelas `monitoramento`
--
ALTER TABLE `monitoramento`
  ADD CONSTRAINT `monitoramento_ibfk_1` FOREIGN KEY (`Id_viagem`) REFERENCES `viagem` (`Id_viagem`);

--
-- Restrições para tabelas `motorista`
--
ALTER TABLE `motorista`
  ADD CONSTRAINT `motorista_ibfk_1` FOREIGN KEY (`Cod_linha`) REFERENCES `linha` (`COD_linha`);

--
-- Restrições para tabelas `predicao`
--
ALTER TABLE `predicao`
  ADD CONSTRAINT `predicao_ibfk_1` FOREIGN KEY (`Cod_linha`) REFERENCES `linha` (`Cod_linha`);

--
-- Restrições para tabelas `rota_passageiro`
--
ALTER TABLE `rota_passageiro`
  ADD CONSTRAINT `rota_passageiro_ibfk_1` FOREIGN KEY (`Cod_linha`) REFERENCES `linha` (`COD_linha`),
  ADD CONSTRAINT `rota_passageiro_ibfk_2` FOREIGN KEY (`Id_passageiro`) REFERENCES `passageiro` (`Id_passageiro`),
  ADD CONSTRAINT `rota_passageiro_ibfk_3` FOREIGN KEY (`Placa_onibus`) REFERENCES `onibus` (`Placa_onibus`);

--
-- Restrições para tabelas `viagem`
--
ALTER TABLE `viagem`
  ADD CONSTRAINT `viagem_ibfk_1` FOREIGN KEY (`Placa_onibus`) REFERENCES `onibus` (`Placa_onibus`),
  ADD CONSTRAINT `viagem_ibfk_2` FOREIGN KEY (`Cod_linha`) REFERENCES `linha` (`COD_linha`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
