-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Дек 17 2023 г., 08:46
-- Версия сервера: 10.11.4-MariaDB-1:10.11.4+maria~ubu2004
-- Версия PHP: 8.1.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `diveev_ReadyDrive`
--

-- --------------------------------------------------------

--
-- Структура таблицы `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL DEFAULT current_timestamp(),
  `end_date` datetime NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `car_id`, `start_date`, `end_date`, `status`) VALUES
(4, 11, 2, '2023-11-20 12:00:00', '2023-11-20 19:00:00', 'confirmed');

-- --------------------------------------------------------

--
-- Структура таблицы `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `reg_number` varchar(25) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `daily_price` int(11) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cars`
--

INSERT INTO `cars` (`car_id`, `brand`, `model`, `year`, `reg_number`, `category_id`, `daily_price`, `status`, `image`) VALUES
(2, 'Mercedes', 'Benz', 2015, '678234', NULL, 2400, 'booked', '/web/product_photo/image_product_0bT8SIV3FcgZwJJ1FD5yu_6k1YKBeGbvuEvtQpnP.jpg'),
(5, 'Toyota', 'Camri', 2019, '208956', NULL, 2000, 'available', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` varchar(1000) DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `car_id`, `rating`, `review_text`, `review_date`) VALUES
(1, 11, 2, 4, 'для меня ок', '2023-11-21 08:11:58');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `token` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `role`, `token`) VALUES
(11, 'updated v3', 'updated V2', 'test@test.ru', '$2y$13$3kmVsH0h19hD6gjwaJqQm.udxZyqUiSMuitEpyLejysxde5cFCpMG', NULL, NULL, 'admin', 'e3nCr0Aflcth_aWd8wSGDqqemU_ViryE'),
(12, 'NName', 'dherffe', 'ufryera@re.ru', '$2y$13$zcAodMyW2P41SHCQ38aXweLEXZoPJlm1J1iUkn0NvNu3k6d0/Cejm', NULL, NULL, NULL, 'j8G84ysXe7ev7RricL8eugDI6F2fSJNk'),
(18, 'Mihael', 'Diveev', 'milka@ya.ru', '$2y$13$hbJUuS/o7mOzcGWSjXrlLOlI7GPu4NyHMBOzU0LauxSAzm4uJEnfS', '88005553535', NULL, NULL, 'F-NJwpSfTLCM6pRpQJKA2O7j3ZSOf6PA'),
(25, NULL, NULL, 'milka4@ya.ru', '$2y$13$7HdbfIQeeUUKalh.qu0lRulZ1jeRO3MhqPAviPFPnbL1XuivrtJPK', NULL, NULL, NULL, 'kHz0gtqRAOQRg6aq_hDsZ1siLo_fl_pP'),
(26, NULL, NULL, 'miko@ya.ru', '$2y$13$Zm4BTCLoeLxaDigtOlRabuiqwEAxenj.dS703oEx3TXLzDcEJWhv2', NULL, NULL, NULL, '_iIJwNHnG-xobzWbAj-w95ue4xBlz02p'),
(27, 'Mihael', 'Diveev', 'milka5@ya.ru', '$2y$13$KIAq7rSou4VjrpV0fXZYd.pKASTr6rgQPr.WNtxiek1Y/GsSjsFgC', '88005553535', NULL, 'user', 'P4g1JgC_iOA1C4IPIzG_kmco2Ht4G8je'),
(28, 'Mihael', 'Diveev', 'milk@ya.ru', '$2y$13$IBbKu8QT6uDxJ1UXDUswyu3nCUZo1OnL5p3fnjG40s6pYdrt/6utG', NULL, NULL, 'user', 'oJuzug6hkTmJFfcK8E9WTrQq3M_fWKQA'),
(29, 'Mihael', 'Diveev', 'milk@ya.ru', '$2y$13$XS9mHnnXlzfFshWYnb7k7.IMfSwyGlkjVzbk1u9HO6sKOaBOOUlTy', NULL, NULL, 'user', 'iI_72icVDWi2pJqVMYBd3e1z5oKt2rFe'),
(30, 'Mihael', 'Diveev', 'mil4k@ya.ru', '$2y$13$nugm2j43FXKtXb9vmdWQQ.8f5xwxyvNywlM5JO0YY.aao9AGkp8iq', NULL, NULL, 'user', 'I31LgLn6jWk0EIkK-AXKHd2-Bv9j0XCK'),
(31, 'Mihael', 'Diveev', 'miliurk@ya.ru', '$2y$13$ya1jhzbNB8Z8KJmE.Tykh.fsS/m0chlYMPaWPyUD3sTJ.k9Vd2.A6', NULL, NULL, 'user', 'LHghm2GbbHL4kW-Tg44Aax6N2GrYL_BP'),
(32, 'Mihael', 'Diveev', 'miliu56rk@ya.ru', '$2y$13$/.ldNmY7aAAAu3w7LyV98.pKzZHDruFOIgMCZkV32IOx7nWPqVAee', NULL, NULL, 'user', 'WQ9JYzrljQN5r_AwMW8_l--VycP6Zmvw'),
(33, 'Mihael', 'Diveev', 'milk15@ya.ru', '$2y$13$sOC4Aw3vGdEeoxZPZCLx8ucWqLuPqyOUp0ZpViEaMz/yPmHNHHxu2', '+71234567890', NULL, 'user', 'oR9GExPIbA7vP29TWDV-CChLEXdXMvbM'),
(34, 'Mihael', 'Diveev', 'miu56rk@ya.ru', '$2y$13$yURvIZlnjTYoFCQ5eUs2POD3yBsfKhIqpQU2InTpQ04U7jMMFLWSy', NULL, NULL, 'user', 'RmOvK9p8Wpe9MfRpGIChjYI8Ks9M4gIQ');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `ReadyDrive_ibfk_1` (`user_id`),
  ADD KEY `ReadyDrive_ibfk_2` (`car_id`);

--
-- Индексы таблицы `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `ReadyDrive_ibfk_7` (`category_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `ReadyDrive_ibfk_4` (`car_id`),
  ADD KEY `ReadyDrive_ibfk_5` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `ReadyDrive_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ReadyDrive_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `ReadyDrive_ibfk_7` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `ReadyDrive_ibfk_4` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ReadyDrive_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
