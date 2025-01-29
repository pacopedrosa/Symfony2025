-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-01-2025 a las 10:20:32
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `card_game`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `suit` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cards`
--

INSERT INTO `cards` (`id`, `number`, `suit`, `image`) VALUES
(53, 1, 'hearts', NULL),
(54, 2, 'hearts', NULL),
(55, 3, 'hearts', NULL),
(56, 4, 'hearts', NULL),
(57, 5, 'hearts', NULL),
(58, 6, 'hearts', NULL),
(59, 7, 'hearts', NULL),
(60, 8, 'hearts', NULL),
(61, 9, 'hearts', NULL),
(62, 10, 'hearts', NULL),
(63, 11, 'hearts', NULL),
(64, 12, 'hearts', NULL),
(65, 13, 'hearts', NULL),
(66, 1, 'diamonds', NULL),
(67, 2, 'diamonds', NULL),
(68, 3, 'diamonds', NULL),
(69, 4, 'diamonds', NULL),
(70, 5, 'diamonds', NULL),
(71, 6, 'diamonds', NULL),
(72, 7, 'diamonds', NULL),
(73, 8, 'diamonds', NULL),
(74, 9, 'diamonds', NULL),
(75, 10, 'diamonds', NULL),
(76, 11, 'diamonds', NULL),
(77, 12, 'diamonds', NULL),
(78, 13, 'diamonds', NULL),
(79, 1, 'clubs', NULL),
(80, 2, 'clubs', NULL),
(81, 3, 'clubs', NULL),
(82, 4, 'clubs', NULL),
(83, 5, 'clubs', NULL),
(84, 6, 'clubs', NULL),
(85, 7, 'clubs', NULL),
(86, 8, 'clubs', NULL),
(87, 9, 'clubs', NULL),
(88, 10, 'clubs', NULL),
(89, 11, 'clubs', NULL),
(90, 12, 'clubs', NULL),
(91, 13, 'clubs', NULL),
(92, 1, 'spades', NULL),
(93, 2, 'spades', NULL),
(94, 3, 'spades', NULL),
(95, 4, 'spades', NULL),
(96, 5, 'spades', NULL),
(97, 6, 'spades', NULL),
(98, 7, 'spades', NULL),
(99, 8, 'spades', NULL),
(100, 9, 'spades', NULL),
(101, 10, 'spades', NULL),
(102, 11, 'spades', NULL),
(103, 12, 'spades', NULL),
(104, 13, 'spades', NULL),
(105, 21, 'hearts', '177170701-logotipo-de-letra-l-con-color-dorado-de-lujo-y-diseno-de-monograma-plantilla-de-logotipo-lujoso-679783450db91.jpg'),
(106, 1, 'hearts', NULL),
(107, 2, 'hearts', NULL),
(108, 3, 'hearts', NULL),
(109, 4, 'hearts', NULL),
(110, 5, 'hearts', NULL),
(111, 6, 'hearts', NULL),
(112, 7, 'hearts', NULL),
(113, 8, 'hearts', NULL),
(114, 9, 'hearts', NULL),
(115, 10, 'hearts', NULL),
(116, 1, 'diamonds', NULL),
(117, 2, 'diamonds', NULL),
(118, 3, 'diamonds', NULL),
(119, 4, 'diamonds', NULL),
(120, 5, 'diamonds', NULL),
(121, 6, 'diamonds', NULL),
(122, 7, 'diamonds', NULL),
(123, 8, 'diamonds', NULL),
(124, 9, 'diamonds', NULL),
(125, 10, 'diamonds', NULL),
(126, 1, 'clubs', NULL),
(127, 2, 'clubs', NULL),
(128, 3, 'clubs', NULL),
(129, 4, 'clubs', NULL),
(130, 5, 'clubs', NULL),
(131, 6, 'clubs', NULL),
(132, 7, 'clubs', NULL),
(133, 8, 'clubs', NULL),
(134, 9, 'clubs', NULL),
(135, 10, 'clubs', NULL),
(136, 1, 'spades', NULL),
(137, 2, 'spades', NULL),
(138, 3, 'spades', NULL),
(139, 4, 'spades', NULL),
(140, 5, 'spades', NULL),
(141, 6, 'spades', NULL),
(142, 7, 'spades', NULL),
(143, 8, 'spades', NULL),
(144, 9, 'spades', NULL),
(145, 10, 'spades', NULL),
(146, 1, 'hearts', NULL),
(147, 2, 'hearts', NULL),
(148, 3, 'hearts', NULL),
(149, 4, 'hearts', NULL),
(150, 5, 'hearts', NULL),
(151, 6, 'hearts', NULL),
(152, 7, 'hearts', NULL),
(153, 8, 'hearts', NULL),
(154, 9, 'hearts', NULL),
(155, 10, 'hearts', NULL),
(156, 1, 'diamonds', NULL),
(157, 2, 'diamonds', NULL),
(158, 3, 'diamonds', NULL),
(159, 4, 'diamonds', NULL),
(160, 5, 'diamonds', NULL),
(161, 6, 'diamonds', NULL),
(162, 7, 'diamonds', NULL),
(163, 8, 'diamonds', NULL),
(164, 9, 'diamonds', NULL),
(165, 10, 'diamonds', NULL),
(166, 1, 'clubs', NULL),
(167, 2, 'clubs', NULL),
(168, 3, 'clubs', NULL),
(169, 4, 'clubs', NULL),
(170, 5, 'clubs', NULL),
(171, 6, 'clubs', NULL),
(172, 7, 'clubs', NULL),
(173, 8, 'clubs', NULL),
(174, 9, 'clubs', NULL),
(175, 10, 'clubs', NULL),
(176, 1, 'spades', NULL),
(177, 2, 'spades', NULL),
(178, 3, 'spades', NULL),
(179, 4, 'spades', NULL),
(180, 5, 'spades', NULL),
(181, 6, 'spades', NULL),
(182, 7, 'spades', NULL),
(183, 8, 'spades', NULL),
(184, 9, 'spades', NULL),
(185, 10, 'spades', NULL),
(186, 1, 'hearts', NULL),
(187, 2, 'hearts', NULL),
(188, 3, 'hearts', NULL),
(189, 4, 'hearts', NULL),
(190, 5, 'hearts', NULL),
(191, 6, 'hearts', NULL),
(192, 7, 'hearts', NULL),
(193, 8, 'hearts', NULL),
(194, 9, 'hearts', NULL),
(195, 10, 'hearts', NULL),
(196, 1, 'diamonds', NULL),
(197, 2, 'diamonds', NULL),
(198, 3, 'diamonds', NULL),
(199, 4, 'diamonds', NULL),
(200, 5, 'diamonds', NULL),
(201, 6, 'diamonds', NULL),
(202, 7, 'diamonds', NULL),
(203, 8, 'diamonds', NULL),
(204, 9, 'diamonds', NULL),
(205, 10, 'diamonds', NULL),
(206, 1, 'clubs', NULL),
(207, 2, 'clubs', NULL),
(208, 3, 'clubs', NULL),
(209, 4, 'clubs', NULL),
(210, 5, 'clubs', NULL),
(211, 6, 'clubs', NULL),
(212, 7, 'clubs', NULL),
(213, 8, 'clubs', NULL),
(214, 9, 'clubs', NULL),
(215, 10, 'clubs', NULL),
(216, 1, 'spades', NULL),
(217, 2, 'spades', NULL),
(218, 3, 'spades', NULL),
(219, 4, 'spades', NULL),
(220, 5, 'spades', NULL),
(221, 6, 'spades', NULL),
(222, 7, 'spades', NULL),
(223, 8, 'spades', NULL),
(224, 9, 'spades', NULL),
(225, 10, 'spades', NULL),
(226, 1, 'hearts', NULL),
(227, 2, 'hearts', NULL),
(228, 3, 'hearts', NULL),
(229, 4, 'hearts', NULL),
(230, 5, 'hearts', NULL),
(231, 6, 'hearts', NULL),
(232, 7, 'hearts', NULL),
(233, 8, 'hearts', NULL),
(234, 9, 'hearts', NULL),
(235, 10, 'hearts', NULL),
(236, 1, 'diamonds', NULL),
(237, 2, 'diamonds', NULL),
(238, 3, 'diamonds', NULL),
(239, 4, 'diamonds', NULL),
(240, 5, 'diamonds', NULL),
(241, 6, 'diamonds', NULL),
(242, 7, 'diamonds', NULL),
(243, 8, 'diamonds', NULL),
(244, 9, 'diamonds', NULL),
(245, 10, 'diamonds', NULL),
(246, 1, 'clubs', NULL),
(247, 2, 'clubs', NULL),
(248, 3, 'clubs', NULL),
(249, 4, 'clubs', NULL),
(250, 5, 'clubs', NULL),
(251, 6, 'clubs', NULL),
(252, 7, 'clubs', NULL),
(253, 8, 'clubs', NULL),
(254, 9, 'clubs', NULL),
(255, 10, 'clubs', NULL),
(256, 1, 'spades', NULL),
(257, 2, 'spades', NULL),
(258, 3, 'spades', NULL),
(259, 4, 'spades', NULL),
(260, 5, 'spades', NULL),
(261, 6, 'spades', NULL),
(262, 7, 'spades', NULL),
(263, 8, 'spades', NULL),
(264, 9, 'spades', NULL),
(265, 10, 'spades', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `player1_id` int(11) NOT NULL,
  `player2_id` int(11) DEFAULT NULL,
  `winner_id` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `player1_card1_id` int(11) DEFAULT NULL,
  `player1_card2_id` int(11) DEFAULT NULL,
  `player2_card1_id` int(11) DEFAULT NULL,
  `player2_card2_id` int(11) DEFAULT NULL,
  `available_cards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`available_cards`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `games`
--

INSERT INTO `games` (`id`, `player1_id`, `player2_id`, `winner_id`, `status`, `player1_card1_id`, `player1_card2_id`, `player2_card1_id`, `player2_card2_id`, `available_cards`) VALUES
(1, 1, 2, 2, 'finished', NULL, NULL, NULL, NULL, '[]'),
(2, 2, 1, 1, 'finished', NULL, NULL, NULL, NULL, '[]'),
(3, 1, 2, 2, 'finished', NULL, NULL, NULL, NULL, '[]'),
(4, 2, 1, 2, 'finished', NULL, NULL, NULL, NULL, '[]'),
(5, 1, 2, 1, 'finished', NULL, NULL, NULL, NULL, '[]'),
(6, 2, 1, 2, 'finished', NULL, NULL, NULL, NULL, '[]'),
(7, 1, 2, NULL, 'finished', 105, NULL, 83, 92, '[]'),
(8, 1, NULL, NULL, 'open', 63, NULL, NULL, NULL, '[]'),
(9, 1, 2, NULL, 'open', NULL, NULL, NULL, NULL, '[]'),
(10, 2, NULL, NULL, 'open', NULL, NULL, NULL, NULL, '[]'),
(11, 2, NULL, NULL, 'waiting', 106, 107, NULL, NULL, '[108,129,128,111,124,107,133,106]'),
(12, 3, 2, 3, 'finished', 152, 182, 150, 173, '[173,154,148,152,150,157,182,180]'),
(13, 1, 3, 3, 'finished', 187, 197, 191, 201, '[225,202,187,213,201,197,208,191]'),
(14, 1, NULL, NULL, 'open', 228, 229, NULL, NULL, '[260,248,229,264,228,255,251,232]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `roles`, `password`, `created_at`) VALUES
(1, 'admin', '[\"ROLE_ADMIN\"]', '$2y$13$dFc8XvAVKyEVkU4/Hs0NZe.ibo6aaCJeR8IMEvDOhxGWBpu1i2DrG', '2025-01-27 11:52:35'),
(2, 'paco12', '[\"ROLE_USER\"]', '$2y$13$bCTGw0h02cfNH.F.Is6cYeXrKLOf6pMTrDJBAn0NvVwCUMHjr1U2O', '2025-01-27 11:55:47'),
(3, 'saul12', '[\"ROLE_USER\"]', '$2y$13$a28mUor5XuMESxEm6Dv85uN8fHQUZTnl76yaWZj/gyUy/YJr7cZHq', '2025-01-28 17:07:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FF232B31C0990423` (`player1_id`),
  ADD KEY `IDX_FF232B31D22CABCD` (`player2_id`),
  ADD KEY `IDX_FF232B315DFCD4B8` (`winner_id`),
  ADD KEY `IDX_FF232B31ACAA855B` (`player1_card1_id`),
  ADD KEY `IDX_FF232B31BE1F2AB5` (`player1_card2_id`),
  ADD KEY `IDX_FF232B31479D3E58` (`player2_card1_id`),
  ADD KEY `IDX_FF232B31552891B6` (`player2_card2_id`);

--
-- Indices de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

--
-- AUTO_INCREMENT de la tabla `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `FK_FF232B31479D3E58` FOREIGN KEY (`player2_card1_id`) REFERENCES `cards` (`id`),
  ADD CONSTRAINT `FK_FF232B31552891B6` FOREIGN KEY (`player2_card2_id`) REFERENCES `cards` (`id`),
  ADD CONSTRAINT `FK_FF232B315DFCD4B8` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_FF232B31ACAA855B` FOREIGN KEY (`player1_card1_id`) REFERENCES `cards` (`id`),
  ADD CONSTRAINT `FK_FF232B31BE1F2AB5` FOREIGN KEY (`player1_card2_id`) REFERENCES `cards` (`id`),
  ADD CONSTRAINT `FK_FF232B31C0990423` FOREIGN KEY (`player1_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_FF232B31D22CABCD` FOREIGN KEY (`player2_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
