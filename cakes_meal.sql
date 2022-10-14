-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 14 2022 г., 14:39
-- Версия сервера: 5.7.38
-- Версия PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cakes_meal`
--

-- --------------------------------------------------------

--
-- Структура таблицы `background_images`
--

CREATE TABLE `background_images` (
  `column_1` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `background_images`
--

INSERT INTO `background_images` (`column_1`, `name`) VALUES
(1, '/background_images/banner-footer.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `cached_tables`
--

CREATE TABLE `cached_tables` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `cache_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `catalog`
--

CREATE TABLE `catalog` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL,
  `short_content` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `catalog`
--

INSERT INTO `catalog` (`id`, `name`, `alias`, `visible`, `menu_position`, `short_content`, `img`) VALUES
(1, 'Бисквитные торты', 'biskvitnye-torty', 1, 1, 'Рецепт бисквитного торта имеет давнюю историю. Главное в нем - бисквитные коржи.', 'assortment_img/bisquit.jpg'),
(2, 'Муссовые торты', 'mussovye-torty', 1, 2, 'Мусс очень популярен в Европе. Это кондитерское изделие, состоящее из нескольких слоев: Основа, мусс, начинка, покрытие.', 'assortment_img/muss.jpg'),
(3, 'Бенто торты', 'bento-torty', 1, 3, 'Бенто торт - это мини-торт, рассчитаный на одного-двух человек. Такие десерты. Такие десерты в России получили популярность летом 2021 года.', 'assortment_img/bento.jpg'),
(4, 'Капкейки', 'kapkeyki', 1, 4, 'Капкейк - маленькое бисквитное пирожное, стильно украшенный кекс для одного. В переводе с английского слово cupcake означает \"Торт в чашке\".', 'assortment_img/cupcake.jpg'),
(5, 'Трайфлы', 'trayfli', 1, 5, 'Блюдо английской кухни, представляющее собой десерт из бисквитного теста с заварным кремом, фруктовым соком или желе и взбитыми сливками, расположенные послойно в стаканчике.', 'assortment_img/trifle.jpg'),
(6, 'Меренга', 'merenga', 1, 6, 'Меренга - это тип десерта или конфеты, традиционно приготовленный из взбитых яичных белков и сахара и иногда кислого ингредиента.', 'assortment_img/bento.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `filters`
--

CREATE TABLE `filters` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `filters`
--

INSERT INTO `filters` (`id`, `name`, `parent_id`) VALUES
(1, 'Тип изделия', NULL),
(2, 'Начинка', NULL),
(3, 'Бисквитный торт', 1),
(4, 'Муссовый торт', 1),
(5, 'Бенто торт', 1),
(6, 'Пирожное', 1),
(7, 'Трайфл', 1),
(8, 'Ягодная', 2),
(9, 'Фруктовая', 2),
(10, 'Крем-чиз', 2),
(11, 'Шоколадная', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `filters_goods`
--

CREATE TABLE `filters_goods` (
  `filters_id` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `filters_goods`
--

INSERT INTO `filters_goods` (`filters_id`, `goods_id`, `id`) VALUES
(3, 1, 1),
(8, 1, 2),
(9, 1, 3),
(4, 6, 4),
(4, 7, 5),
(4, 8, 6),
(4, 9, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_content` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL,
  `hit` tinyint(1) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name`, `short_content`, `alias`, `img`, `visible`, `menu_position`, `hit`, `parent_id`, `price`, `content`) VALUES
(1, 'Прага', 'На основе натурального шоколада', 'praga_1', 'goods/bisquit/bisquit_praga2.jpg', 1, 1, 1, 1, 900, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(2, 'Медовик', 'Сделан по классическому рецепту', 'medovik_2', 'goods/bisquit/bisquit1.jpg', 1, 1, 1, 1, 1100, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(3, 'Сметанник', 'На основе нежнейшего сметанного крема', 'smetannik_3', 'goods/bisquit/bisquit2.jpg', 1, 1, NULL, 1, 980, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(4, 'Эстерхази', 'Ореховый. Королевский.', 'esterxhazi_4', 'goods/bisquit/bisquit4.jpg', 1, 1, NULL, 1, 1850, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(5, 'Фруктовый', 'Сливочные коржи с прослойкой свежих фруктов', 'fruits_5', 'goods/bisquit/bisquit_fruit.jpg', 1, 1, 1, 1, 1150, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(6, 'Чизкейк Нью Йорк', 'Нестареющая классика', 'cheesecake_6', 'goods/muss/muss1.jpg', 1, 2, 1, 2, 1100, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(7, 'Три шоколада', 'Бельгийский темный, молочный и белый', 'tri-shokolada_7', 'goods/muss/muss2.jpg', 1, 2, 1, 2, 980, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(8, 'Черничный мусс', 'Черника с нежным муссом', 'chernichny-muss_8', 'goods/muss/muss_chernika.jpg', 1, 2, 1, 2, 900, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(9, 'Киви мусс', 'И мусс и киви', 'kivi-muss_9', 'goods/muss/muss_qivi.jpg', 1, 2, 1, 2, 1550, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(10, 'Бенто тортик с изображением', 'Натуральный шоколад, бисквит и мастика', 'bento-tort1_10', 'goods/bento/bento1.jpg', 1, 3, 1, 3, 500, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(11, 'Бенто тортик с изображением', 'Натуральный шоколад, бисквит и мастика', 'bento-tort2_11', 'goods/bento/bento2.jpg', 1, 3, 1, 3, 500, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(12, 'Бенто тортик с изображением', 'Натуральный шоколад, бисквит и мастика', 'bento-tort3_12', 'goods/bento/bento3.jpg', 1, 3, 1, 3, 500, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(13, 'Бенто тортик с изображением', 'Натуральный шоколад, бисквит и мастика', 'bento-tort4_13', 'goods/bento/bento4.jpg', 1, 3, 1, 3, 500, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(14, 'Бенто тортик с изображением', 'Натуральный шоколад, бисквит и мастика', 'bento-tort5_14', 'goods/bento/bento5.jpg', 1, 3, 1, 3, 500, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(15, 'Бенто тортик с изображением', 'Натуральный шоколад, бисквит и мастика', 'bento-tort6_15', 'goods/bento/bento8.jpg', 1, 3, 1, 3, 500, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(16, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake1_16', 'goods/cupcakes/cupcake.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(17, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake2_17', 'goods/cupcakes/cupcake2.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(18, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake3_18', 'goods/cupcakes/cupcake7.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(19, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake4_19', 'goods/cupcakes/cupcake4.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(20, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake5_20', 'goods/cupcakes/cupcake5.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(21, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake6_21', 'goods/cupcakes/cupcake6.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(22, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake7_22', 'goods/cupcakes/cupcake7.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(23, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake8_23', 'goods/cupcakes/cupcake8.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(24, 'Ягодный трайфл', 'Ягоды со сливочным кремом', 'yagodny-trifle_24', 'goods/trifles/trifle5.jpg', 1, 5, 1, 5, 290, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(25, 'Шоколадный трайфл', 'Натуральный шоколад и печенье', 'shokoladny-trifle_25', 'goods/trifles/trifle3.jpg', 1, 5, 1, 5, 290, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(26, 'Трайфл с фруктовым бисквитом', 'Много шоколада', 'trifle-fruit_26', 'goods/trifles/trifle6.jpg', 1, 5, 1, 5, 290, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(27, 'Меренга на палочке', 'Для самых маленьких', 'merenga-desert_27', 'goods/merengy/merenga1.jpg', 1, 6, 1, 6, 180, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(28, 'Меренга классическая', 'Воздушное безе', 'merenga-desert_28', 'goods/merengy/merenga3.jpg', 1, 6, 1, 6, 180, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(29, 'Меренга на палочке', 'Для самых маленьких', 'merenga-desert_29', 'goods/merengy/merenga1.jpg', 1, 6, 1, 6, 180, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(30, 'Меренга с фруктами', 'Нежное безе со свежими фруктами и сливочным кремом', 'merenga-desert_30', 'goods/merengy/merengue-hover.jpg', 1, 6, 1, 6, 180, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.'),
(31, 'Меренга-цветок', 'Подойдет для дней рождения и других праздников', 'merenga-desert_31', 'goods/merengy/merenga-hover.jpg', 1, 6, 1, 6, 180, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.');

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `name` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `num` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `name`, `answer`, `num`, `menu_position`) VALUES
(1, 'Как рассчитать вес торта?', 'Вес торта напрямую зависит от количества гостей на мероприятии. Средний кусочек торта 150-200 г.\nМы рекомендуем брать 200 г на взрослого и 150 г на ребёнка.', 'One', 1),
(2, 'Что входит в стоимость торта?', 'В стоимость входит следующий декор:\n- свежие сезонные ягоды/фрукты;\n- меренга или меренга на палочке любых цветов;\n- конфеты, шоколад, печенье, надпись из шоколада;\n- шоколадные или цветные потеки;\n- кремовые цветы.\n- упаковка.', 'Two', 2),
(3, 'Что оплачивается отдельно?', 'Декор сахарными или шоколадными цветами; \nдекор пряниками (стоимость зависит от размера, сложности, количества); \nдекор сахарной картинкой; \nгорки/полянки ягод (несезонных); \nтопперы;\n', 'Three', 3),
(4, 'Какой срок годности торта?', 'Срок годности тортика или капкейка 3 дня в холодильнике. Он такой маленький, поскольку мы используем исключительно свежие ингредиенты и никаких консервантов.', 'Four', 4),
(5, 'Можно ли отказаться от заказа?', 'Можно, и мы всегда возвращаем предоплату, если отмена заказа произошла не позднее, чем за 2 дня до даты.', 'Five', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_content` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `menu_position` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sales`
--

INSERT INTO `sales` (`id`, `name`, `short_content`, `img`, `external_alias`, `visible`, `menu_position`) VALUES
(1, 'В честь открытия', 'При заказе бисквитного торта - букет меренги в подарок!', 'sales/slideOne.jpg', NULL, 1, 1),
(2, 'Если торт - то Cakes Meal', 'Самые вкусные десерты!', 'sales/slideTwo.jpg', NULL, 1, 1),
(3, 'Доставим Ваш заказ бесплатно', 'Осуществляем быструю доставку по городу Калуга', 'sales/slideFive.jpg', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `socials`
--

CREATE TABLE `socials` (
  `id` int(11) NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `menu_position` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `socials`
--

INSERT INTO `socials` (`id`, `img`, `name`, `visible`, `menu_position`) VALUES
(1, 'socials_images/vk.jpg', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tizzers`
--

CREATE TABLE `tizzers` (
  `id` int(11) NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_content` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `menu_position` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tizzers`
--

INSERT INTO `tizzers` (`id`, `img`, `name`, `short_content`, `visible`, `menu_position`) VALUES
(1, 'tizzers/fast.png', 'Бесплатная доставка', 'Доставляем тортики бесплатно и быстро по городу Калуга', 1, 1),
(2, 'tizzers/natural.png', 'Качество', 'Используем только натуральные продукты в любом рецепте', 1, 1),
(3, 'tizzers/money.png', 'Красота', 'Наш кондитер внедряет дизайнерские решения в каждый тортик', 1, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `background_images`
--
ALTER TABLE `background_images`
  ADD PRIMARY KEY (`column_1`);

--
-- Индексы таблицы `cached_tables`
--
ALTER TABLE `cached_tables`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `catalog`
--
ALTER TABLE `catalog`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `filters_goods`
--
ALTER TABLE `filters_goods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_goods_id_fk` (`parent_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `socials`
--
ALTER TABLE `socials`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tizzers`
--
ALTER TABLE `tizzers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `background_images`
--
ALTER TABLE `background_images`
  MODIFY `column_1` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `cached_tables`
--
ALTER TABLE `cached_tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `catalog`
--
ALTER TABLE `catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `filters_goods`
--
ALTER TABLE `filters_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `socials`
--
ALTER TABLE `socials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `tizzers`
--
ALTER TABLE `tizzers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `goods_goods_id_fk` FOREIGN KEY (`parent_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
