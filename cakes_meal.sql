-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 16 2022 г., 19:24
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

--
-- Дамп данных таблицы `cached_tables`
--

INSERT INTO `cached_tables` (`id`, `name`, `date`, `cache_time`) VALUES
(1, 'translate_elements', '2022-11-16 01:29:32', NULL),
(2, 'user_delivery', '2022-10-27 23:19:24', NULL),
(3, 'users', '2022-11-08 14:01:12', NULL),
(4, 'visitors', '2022-11-12 16:34:52', NULL);

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
(6, 'Меренга', 'merenga', 1, 6, 'Меренга - это тип десерта или конфеты, традиционно приготовленный из взбитых яичных белков и сахара и иногда кислого ингредиента.', 'assortment_img/merenga.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `delivery`
--

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `delivery`
--

INSERT INTO `delivery` (`id`, `name`) VALUES
(1, 'Самовывоз'),
(2, 'Доставка');

-- --------------------------------------------------------

--
-- Структура таблицы `delivery_terms`
--

CREATE TABLE `delivery_terms` (
  `id` int(11) NOT NULL,
  `min_price_delivery` int(11) DEFAULT NULL,
  `min_price_export` int(11) DEFAULT NULL,
  `gift_price` int(11) DEFAULT NULL,
  `work_start` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_end` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gift` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `delivery_terms`
--

INSERT INTO `delivery_terms` (`id`, `min_price_delivery`, `min_price_export`, `gift_price`, `work_start`, `work_end`, `gift`) VALUES
(1, 1500, 1000, 3000, '08:00', '22:00', 'Плитка шоколада');

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
(2, 'Крем', NULL),
(3, 'Бисквитный торт', 1),
(4, 'Муссовый торт', 1),
(5, 'Бенто торт', 1),
(6, 'Пирожное', 1),
(7, 'Трайфл', 1),
(8, 'Сливочный', 2),
(9, 'Шоколадный', 2),
(10, 'Сметанный', 2),
(11, 'Крем-чиз', 2),
(12, 'Заварной', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `filters_goods`
--

CREATE TABLE `filters_goods` (
  `filters_id` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `filters_goods`
--

INSERT INTO `filters_goods` (`filters_id`, `goods_id`) VALUES
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(5, 10),
(5, 11),
(5, 12),
(5, 13),
(5, 14),
(5, 15),
(6, 16),
(6, 17),
(6, 18),
(6, 19),
(6, 20),
(6, 21),
(6, 22),
(6, 23),
(7, 24),
(7, 25),
(7, 26),
(12, 27),
(12, 28),
(12, 29),
(12, 30),
(12, 31),
(4, 6),
(4, 7),
(4, 8),
(9, 1),
(8, 1),
(10, 2),
(12, 2),
(10, 3),
(8, 4),
(12, 4),
(8, 5),
(11, 5),
(11, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 10),
(8, 11),
(8, 12),
(12, 13),
(12, 14),
(11, 15),
(9, 16),
(8, 17),
(12, 18),
(11, 19),
(8, 20),
(11, 21),
(8, 21),
(9, 22),
(9, 23),
(8, 24),
(9, 25),
(9, 26),
(11, 6),
(8, 9),
(4, 9),
(4, 9);

-- --------------------------------------------------------

--
-- Структура таблицы `general_settings`
--

CREATE TABLE `general_settings` (
  `id` int(11) NOT NULL DEFAULT '1',
  `cache_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `gifts`
--

CREATE TABLE `gifts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `gifts`
--

INSERT INTO `gifts` (`id`, `name`, `price`) VALUES
(1, 'Плитка шоколада', 3000),
(2, 'Коробка капкейков', 5000),
(3, 'Шарик', 1000);

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
  `content` text COLLATE utf8mb4_unicode_ci,
  `gallery_img` text COLLATE utf8mb4_unicode_ci,
  `discount` tinyint(3) DEFAULT NULL,
  `old_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name`, `short_content`, `alias`, `img`, `visible`, `menu_position`, `hit`, `parent_id`, `price`, `content`, `gallery_img`, `discount`, `old_price`) VALUES
(1, 'Прага', 'На основе натурального шоколада', 'praga_1', 'goods/bisquit/bisquit_praga2.jpg', 1, 1, 1, 1, 900, 'Торт «Прага» – десерт, который очень любят многие. Этот десерт считается классикой на ровне с «Наполеоном», «Медовиком» или «Киевским». И не зря! Ведь рецепт торта «Прага» не отличается замысловатостью. Десерт состоит из шоколадных бисквитов, заварного крема со взбитым маслом, абрикосового джема, ароматной пропитки и шоколадной глазури. Невероятное удовольствие для любителей шоколадных десертов!', '[\"goods\\/bento\\/bento.jpg\",\"goods\\/bento\\/bento1.jpg\",\"goods\\/bento\\/bento2.jpg\"]', 10, 100500),
(2, 'Медовик', 'Сделан по классическому рецепту', 'medovik_2', 'goods/bisquit/bisquit1.jpg', 1, 1, 1, 1, 1100, 'Если вы хотите побаловать своих близких вкусным медовым тортиком, можете заказать торт \"Медовик\". Коржи готовятся на основе мёда, муки и сливочного масла. Для того, чтобы коржи получились мягкими и ароматными, мы используем пропитку. В рецепте классического медовика для пропитки мы используем сахар, сметану и банан. Мы всегда используем натуральные продукты, и наш торт получается ещё вкуснее.', NULL, 5, NULL),
(3, 'Сметанник', 'На основе нежнейшего сметанного крема', 'smetannik_3', 'goods/bisquit/bisquit2.jpg', 1, 1, NULL, 1, 980, 'Сметанник, сметанный торт - один из самых популярных видов торта, который пекли на праздники еще наши бабушки. Сметанник делают на основе сметанных коржей, промазанных сметанным кремом. Классический рецепт сметанного торта  определить довольно сложно, так как готовят его по-разному, но основа сметанника неизменна - бисквитное тесто из муки, сметаны, яиц с добавлением  сахара и соды.', NULL, 10, NULL),
(4, 'Эстерхази', 'Ореховый. Королевский.', 'esterxhazi_4', 'goods/bisquit/bisquit4.jpg', 1, 1, NULL, 1, 1850, 'Крем для торта «Эстерхази» готовят на заварной основе с добавление сливочного масла и ароматного коньяка. Что получается в итоге? \nВкусный, невероятно нежный, но хрустящий ореховый десерт со сливочным, слегка терпким кремом. Конечно, если вы заказываете торт для ребенка, то алкоголь можно не добавлять. ', NULL, NULL, NULL),
(5, 'Фруктовый', 'Сливочные коржи с прослойкой свежих фруктов', 'fruits_5', 'goods/bisquit/bisquit_fruit.jpg', 1, 1, 1, 1, 1150, 'Нежный бисквит и сочное фруктовое ассорти! Торт бисквитный с фруктами - один из десертов, которые всегда ассоциируются с праздником или торжеством. Бисквитные коржи, приготовленные классическим способом, прослоены воздушным кремом из взбитых сливок и кусочками сочных фруктов. При любом способе и варианте украшения торт будет выглядеть нарядно.', NULL, NULL, NULL),
(6, 'Чизкейк Нью Йорк', 'Нестареющая классика', 'cheesecake_6', 'goods/muss/muss1.jpg', 1, 2, 1, 2, 1100, 'Чизкейк - один из самых известных и любимых десертов мира.\nВ переводе с английского \"cheese cake\" означает \"сырный пирог\". Это невероятно вкусный десерт, приготовленный из чистого сливочного сыра, сливок, яиц и сахара.', NULL, NULL, NULL),
(7, 'Три шоколада', 'Бельгийский темный, молочный и белый', 'tri-shokolada_7', 'goods/muss/muss2.jpg', 1, 2, 1, 2, 1100, 'Нежное сочетание шоколадного бисквита и трёх видов бельгийского шоколада в муссе создают изысканную вкусовую гармонию. Такой десерт придётся по вкусу и взрослым и детям. Торт три шоколада — кондитерский шедевр, который станет украшением вашего праздника или отличным поводом собраться с друзьями за чашечкой чая.', NULL, NULL, NULL),
(8, 'Черничный мусс', 'Черника с нежным муссом', 'chernichny-muss_8', 'goods/muss/muss_chernika.jpg', 1, 2, 1, 2, 900, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(9, 'Киви мусс', 'И мусс и киви', 'kivi-muss_9', 'goods/muss/muss_qivi.jpg', 0, 2, 1, 2, 950, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(10, 'Бенто тортик №1', 'Бисквит, сливочный крем, молочная пропитка', 'bento-tort1_10', 'goods/bento/bento1.jpg', 1, 3, 1, 3, 500, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, 30, NULL),
(11, 'Бенто тортик №2', 'Бисквит, сливочный крем, молочная пропитка', 'bento-tort2_11', 'goods/bento/bento2.jpg', 1, 3, 1, 3, 500, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(12, 'Бенто тортик №3', 'Бисквит, сливочный крем, клубника', 'bento-tort3_12', 'goods/bento/bento3.jpg', 1, 3, 1, 3, 500, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(13, 'Бенто тортик №4', 'Бисквит, заварной крем, фруктовое конфи', 'bento-tort4_13', 'goods/bento/bento4.jpg', 1, 3, 1, 3, 600, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(14, 'Бенто тортик №5', 'Бисквит, заварной крем, фруктовое конфи', 'bento-tort5_14', 'goods/bento/bento5.jpg', 1, 3, 1, 3, 600, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(15, 'Бенто тортик №6', 'Красный бархат XS size', 'bento-tort6_15', 'goods/bento/bento8.jpg', 1, 3, 1, 3, 900, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(16, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake1_16', 'goods/cupcakes/cupcake.jpg', 0, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(17, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake2_17', 'goods/cupcakes/cupcake2.jpg', 0, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(18, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake3_18', 'goods/cupcakes/cupcake7.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(19, 'Капкейк', 'просто капкейк', 'cupcake4_19', 'goods/cupcakes/cupcake4.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(20, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake5_20', 'goods/cupcakes/cupcake5.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(21, 'Капкейк \'Красный бархат\'', 'Нежный бисквит со сливочным кремом', 'cupcake6_21', 'goods/cupcakes/cupcake6.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(22, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake7_22', 'goods/cupcakes/cupcake7.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(23, 'Шоколадный капкейк', 'Шоколад снаружи и внутри', 'cupcake8_23', 'goods/cupcakes/cupcake8.jpg', 1, 4, 1, 4, 230, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(24, 'Ягодный трайфл', 'Ягоды со сливочным кремом', 'yagodny-trifle_24', 'goods/trifles/trifle5.jpg', 1, 5, 1, 5, 350, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(25, 'Шоколадный трайфл', 'Натуральный шоколад и печенье', 'shokoladny-trifle_25', 'goods/trifles/trifle3.jpg', 1, 5, 1, 5, 290, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(26, 'Трайфл с фруктами', 'Много шоколада с фруктами', 'trifle-fruit_26', 'goods/trifles/trifle6.jpg', 1, 5, 1, 5, 350, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(27, 'Меренга на палочке', 'Для самых маленьких', 'merenga-desert_27', 'goods/merengy/merenga1.jpg', 0, 6, 1, 6, 100, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(28, 'Меренга классическая', 'Воздушное безе', 'merenga-desert_28', 'goods/merengy/merenga3.jpg', 0, 6, 1, 6, 180, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(29, 'Меренга на палочке', 'Для самых маленьких', 'merenga-desert_29', 'goods/merengy/merenga1.jpg', 1, 6, 1, 6, 180, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(30, 'пирожное \'Павлова\'', 'Нежное безе со свежими фруктами и заварным кремом', 'merenga-desert_30', 'goods/merengy/merengue-hover.jpg', 1, 6, 1, 6, 299, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL),
(31, 'Меренга-цветок', 'Нежное безе с украшением из сахарных бусин', 'merenga-desert_31', 'goods/merengy/merenga-hover.jpg', 1, 6, 1, 6, 260, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At eligendi in iste iure odio quae quaerat. Culpa cumque eos exercitationem in modi necessitatibus porro quasi quis repellat repudiandae sunt, tempore, veniam voluptate! Animi atque beatae, cumque deleniti dicta et impedit labore laborum nemo, non quaerat repellendus, repudiandae suscipit vitae voluptatem.', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `payments_id` int(11) DEFAULT NULL,
  `visitors_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `address` text COLLATE utf8mb4_unicode_ci,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `delivery_id` int(11) DEFAULT NULL,
  `orders_statuses_id` int(11) DEFAULT NULL,
  `total_sum` float DEFAULT NULL,
  `total_qty` int(11) DEFAULT NULL,
  `date_delivery` date DEFAULT NULL,
  `total_old_sum` float DEFAULT NULL,
  `gift` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `payments_id`, `visitors_id`, `date`, `address`, `comment`, `delivery_id`, `orders_statuses_id`, `total_sum`, `total_qty`, `date_delivery`, `total_old_sum`, `gift`) VALUES
(1, 1, 9, '2022-11-20 23:27:42', 'Калуга ул Кирова д1', 'лалала', 2, 1, 1000, 3, '2022-11-22', 1000, NULL),
(2, 2, 9, '2022-11-16 00:28:16', 'Калуга ул Пушкина 1', 'кцузаывжлвар', 1, 2, 100500, 1, '2022-12-31', 150, 'шоколад');

-- --------------------------------------------------------

--
-- Структура таблицы `orders_goods`
--

CREATE TABLE `orders_goods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_price` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders_goods`
--

INSERT INTO `orders_goods` (`id`, `name`, `old_price`, `price`, `discount`, `orders_id`, `goods_id`, `qty`) VALUES
(1, 'товар1', NULL, 500, NULL, 1, NULL, 1),
(2, 'товар2', NULL, 250, NULL, 1, NULL, 2),
(3, 'Прага', NULL, 1000, NULL, 2, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `orders_statuses`
--

CREATE TABLE `orders_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders_statuses`
--

INSERT INTO `orders_statuses` (`id`, `name`, `menu_position`) VALUES
(1, 'принято', 1),
(2, 'доставлено', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `payments`
--

INSERT INTO `payments` (`id`, `name`, `menu_position`, `visible`) VALUES
(1, 'Наличные', 1, 1),
(2, 'Карта', 2, 1),
(3, 'Перевод', 3, 1);

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
(3, 'Доставим Ваш заказ точно и в срок!', 'Осуществляем быструю доставку по городу Калуга', 'sales/slideFive.jpg', NULL, 1, 1);

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
(1, 'tizzers/delivery-air.png', 'Бесплатная доставка', 'Доставляем тортики бесплатно и быстро по городу Калуга', 1, 1),
(2, 'tizzers/cake.png', 'Качество', 'Используем только натуральные продукты в любом рецепте', 1, 1),
(3, 'tizzers/discount(1).png', 'Отличные цены', 'Наши цены Вас приятно удивят', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `translate_elements`
--

CREATE TABLE `translate_elements` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `el_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `translate_elements`
--

INSERT INTO `translate_elements` (`id`, `name`, `el_name`) VALUES
(1, 'Товар добавлен в корзину', NULL),
(2, 'Товар добавлен в корзину', NULL),
(3, 'Товар добавлен в корзину', NULL),
(4, 'Товар добавлен в корзину', NULL),
(5, 'Товар добавлен в корзину', NULL),
(6, 'Товар добавлен в корзину', NULL),
(7, 'Товар добавлен в корзину', NULL),
(8, 'Товар добавлен в корзину', NULL),
(9, 'Товар добавлен в корзину', NULL),
(10, 'Товар добавлен в корзину', NULL),
(11, 'Товар добавлен в корзину', NULL),
(12, 'Товар добавлен в корзину', NULL),
(13, 'Товар добавлен в корзину', NULL),
(14, 'Товар добавлен в корзину', NULL),
(15, 'Товар добавлен в корзину', NULL),
(16, 'Товар добавлен в корзину', NULL),
(17, 'Товар добавлен в корзину', NULL),
(18, 'Товар добавлен в корзину', NULL),
(19, 'Товар добавлен в корзину', NULL),
(20, 'Товар добавлен в корзину', NULL),
(21, 'Товар добавлен в корзину', NULL),
(22, 'Товар добавлен в корзину', NULL),
(23, 'Товар добавлен в корзину', NULL),
(24, 'Товар добавлен в корзину', NULL),
(25, 'Товар добавлен в корзину', NULL),
(26, 'Товар добавлен в корзину', NULL),
(27, 'Товар добавлен в корзину', NULL),
(28, 'Товар добавлен в корзину', NULL),
(29, 'Товар добавлен в корзину', NULL),
(30, 'Товар добавлен в корзину', NULL),
(31, 'Товар добавлен в корзину', NULL),
(32, 'Товар добавлен в корзину', NULL),
(33, 'Товар добавлен в корзину', NULL),
(34, 'Товар добавлен в корзину', NULL),
(35, 'Товар добавлен в корзину', NULL),
(36, 'Товар добавлен в корзину', NULL),
(37, 'Товар добавлен в корзину', NULL),
(38, 'Товар добавлен в корзину', NULL),
(39, 'Товар добавлен в корзину', NULL),
(40, 'Товар добавлен в корзину', NULL),
(41, 'Товар добавлен в корзину', NULL),
(42, 'Товар добавлен в корзину', NULL),
(43, 'Товар добавлен в корзину', NULL),
(44, 'Товар добавлен в корзину', NULL),
(45, 'Товар добавлен в корзину', NULL),
(46, 'Товар добавлен в корзину', NULL),
(47, 'Товар добавлен в корзину', NULL),
(48, 'Товар добавлен в корзину', NULL),
(49, 'Товар добавлен в корзину', NULL),
(50, 'Товар добавлен в корзину', NULL),
(51, 'Товар добавлен в корзину', NULL),
(52, 'Товар добавлен в корзину', NULL),
(53, 'Товар добавлен в корзину', NULL),
(54, 'Товар добавлен в корзину', NULL),
(55, 'Товар добавлен в корзину', NULL),
(56, 'Товар добавлен в корзину', NULL),
(57, 'Товар добавлен в корзину', NULL),
(58, 'Товар добавлен в корзину', NULL),
(59, 'Товар добавлен в корзину', NULL),
(60, 'Товар добавлен в корзину', NULL),
(61, 'Товар добавлен в корзину', NULL),
(62, 'Товар добавлен в корзину', NULL),
(63, 'Товар добавлен в корзину', NULL),
(64, 'Товар добавлен в корзину', NULL),
(65, 'Товар добавлен в корзину', NULL),
(66, 'Товар добавлен в корзину', NULL),
(67, 'Товар добавлен в корзину', NULL),
(68, 'Товар добавлен в корзину', NULL),
(69, 'Товар добавлен в корзину', NULL),
(70, 'Товар добавлен в корзину', NULL),
(71, 'Товар добавлен в корзину', NULL),
(72, 'Товар добавлен в корзину', NULL),
(73, 'Товар добавлен в корзину', NULL),
(74, 'Товар добавлен в корзину', NULL),
(75, 'Товар добавлен в корзину', NULL),
(76, 'Товар добавлен в корзину', NULL),
(77, 'Товар добавлен в корзину', NULL),
(78, 'Товар добавлен в корзину', NULL),
(79, 'Товар добавлен в корзину', NULL),
(80, 'Товар добавлен в корзину', NULL),
(81, 'Товар добавлен в корзину', NULL),
(82, 'Товар добавлен в корзину', NULL),
(83, 'Товар добавлен в корзину', NULL),
(84, 'Товар добавлен в корзину', NULL),
(85, 'Товар добавлен в корзину', NULL),
(86, 'Товар добавлен в корзину', NULL),
(87, 'Товар добавлен в корзину', NULL),
(88, 'Товар добавлен в корзину', NULL),
(89, 'Товар добавлен в корзину', NULL),
(90, 'Товар добавлен в корзину', NULL),
(91, 'Товар добавлен в корзину', NULL),
(92, 'Товар добавлен в корзину', NULL),
(93, 'Товар добавлен в корзину', NULL),
(94, 'Товар добавлен в корзину', NULL),
(95, 'Товар добавлен в корзину', NULL),
(96, 'Товар добавлен в корзину', NULL),
(97, 'Товар добавлен в корзину', NULL),
(98, 'Товар добавлен в корзину', NULL),
(99, 'Товар добавлен в корзину', NULL),
(100, 'Товар добавлен в корзину', NULL),
(101, 'Товар добавлен в корзину', NULL),
(102, 'Товар добавлен в корзину', NULL),
(103, 'Товар добавлен в корзину', NULL),
(104, 'Товар добавлен в корзину', NULL),
(105, 'Товар добавлен в корзину', NULL),
(106, 'Товар добавлен в корзину', NULL),
(107, 'Товар добавлен в корзину', NULL),
(108, 'Товар добавлен в корзину', NULL),
(109, 'Товар добавлен в корзину', NULL),
(110, 'Товар добавлен в корзину', NULL),
(111, 'Товар добавлен в корзину', NULL),
(112, 'Товар добавлен в корзину', NULL),
(113, 'Товар добавлен в корзину', NULL),
(114, 'Товар добавлен в корзину', NULL),
(115, 'Товар добавлен в корзину', NULL),
(116, 'Товар добавлен в корзину', NULL),
(117, 'Товар добавлен в корзину', NULL),
(118, 'Товар добавлен в корзину', NULL),
(119, 'Товар добавлен в корзину', NULL),
(120, 'Товар добавлен в корзину', NULL),
(121, 'Товар добавлен в корзину', NULL),
(122, 'Товар добавлен в корзину', NULL),
(123, 'Товар добавлен в корзину', NULL),
(124, 'Товар добавлен в корзину', NULL),
(125, 'Товар добавлен в корзину', NULL),
(126, 'Товар добавлен в корзину', NULL),
(127, 'Товар добавлен в корзину', NULL),
(128, 'Товар добавлен в корзину', NULL),
(129, 'Товар добавлен в корзину', NULL),
(130, 'Товар добавлен в корзину', NULL),
(131, 'Товар добавлен в корзину', NULL),
(132, 'Товар добавлен в корзину', NULL),
(133, 'Товар добавлен в корзину', NULL),
(134, 'Товар добавлен в корзину', NULL),
(135, 'Товар добавлен в корзину', NULL),
(136, 'Товар добавлен в корзину', NULL),
(137, 'Товар добавлен в корзину', NULL),
(138, 'Товар добавлен в корзину', NULL),
(139, 'Товар добавлен в корзину', NULL),
(140, 'Товар добавлен в корзину', NULL),
(141, 'Товар добавлен в корзину', NULL),
(142, 'Товар добавлен в корзину', NULL),
(143, 'Товар добавлен в корзину', NULL),
(144, 'Товар добавлен в корзину', NULL),
(145, 'Товар добавлен в корзину', NULL),
(146, 'Товар добавлен в корзину', NULL),
(147, 'Товар добавлен в корзину', NULL),
(148, 'Товар добавлен в корзину', NULL),
(149, 'Товар добавлен в корзину', NULL),
(150, 'Товар добавлен в корзину', NULL),
(151, 'Товар добавлен в корзину', NULL),
(152, 'Товар добавлен в корзину', NULL),
(153, 'Товар добавлен в корзину', NULL),
(154, 'Товар добавлен в корзину', NULL),
(155, 'Товар добавлен в корзину', NULL),
(156, 'Товар добавлен в корзину', NULL),
(157, 'Товар добавлен в корзину', NULL),
(158, 'Товар добавлен в корзину', NULL),
(159, 'Товар добавлен в корзину', NULL),
(160, 'Товар добавлен в корзину', NULL),
(161, 'Товар добавлен в корзину', NULL),
(162, 'Товар добавлен в корзину', NULL),
(163, 'Товар добавлен в корзину', NULL),
(164, 'Товар добавлен в корзину', NULL),
(165, 'Товар добавлен в корзину', NULL),
(166, 'Товар добавлен в корзину', NULL),
(167, 'Товар добавлен в корзину', NULL),
(168, 'Товар добавлен в корзину', NULL),
(169, 'Товар добавлен в корзину', NULL),
(170, 'Товар добавлен в корзину', NULL),
(171, 'Товар добавлен в корзину', NULL),
(172, 'Товар добавлен в корзину', NULL),
(173, 'Товар добавлен в корзину', NULL),
(174, 'Товар добавлен в корзину', NULL),
(175, 'Товар добавлен в корзину', NULL),
(176, 'Товар добавлен в корзину', NULL),
(177, 'Товар добавлен в корзину', NULL),
(178, 'Товар добавлен в корзину', NULL),
(179, 'Товар добавлен в корзину', NULL),
(180, 'Товар добавлен в корзину', NULL),
(181, 'Товар добавлен в корзину', NULL),
(182, 'Товар добавлен в корзину', NULL),
(183, 'Товар добавлен в корзину', NULL),
(184, 'Товар добавлен в корзину', NULL),
(185, 'Товар добавлен в корзину', NULL),
(186, 'Товар добавлен в корзину', NULL),
(187, 'Товар добавлен в корзину', NULL),
(188, 'Товар добавлен в корзину', NULL),
(189, 'Товар добавлен в корзину', NULL),
(190, 'Товар добавлен в корзину', NULL),
(191, 'Товар добавлен в корзину', NULL),
(192, 'Товар добавлен в корзину', NULL),
(193, 'Товар добавлен в корзину', NULL),
(194, 'Товар добавлен в корзину', NULL),
(195, 'Товар добавлен в корзину', NULL),
(196, 'Товар добавлен в корзину', NULL),
(197, 'Товар добавлен в корзину', NULL),
(198, 'Товар добавлен в корзину', NULL),
(199, 'Товар добавлен в корзину', NULL),
(200, 'Товар добавлен в корзину', NULL),
(201, 'Товар добавлен в корзину', NULL),
(202, 'Товар добавлен в корзину', NULL),
(203, 'Товар добавлен в корзину', NULL),
(204, 'Товар добавлен в корзину', NULL),
(205, 'Товар добавлен в корзину', NULL),
(206, 'Товар добавлен в корзину', NULL),
(207, 'Товар добавлен в корзину', NULL),
(208, 'Товар добавлен в корзину', NULL),
(209, 'Товар добавлен в корзину', NULL),
(210, 'Товар добавлен в корзину', NULL),
(211, 'Товар добавлен в корзину', NULL),
(212, 'Товар добавлен в корзину', NULL),
(213, 'Товар добавлен в корзину', NULL),
(214, 'Товар добавлен в корзину', NULL),
(215, 'Товар добавлен в корзину', NULL),
(216, 'Товар добавлен в корзину', NULL),
(217, 'Товар добавлен в корзину', NULL),
(218, 'Товар добавлен в корзину', NULL),
(219, 'Товар добавлен в корзину', NULL),
(220, 'Товар добавлен в корзину', NULL),
(221, 'Товар добавлен в корзину', NULL),
(222, 'Товар добавлен в корзину', NULL),
(223, 'Товар добавлен в корзину', NULL),
(224, 'Товар добавлен в корзину', NULL),
(225, 'Товар добавлен в корзину', NULL),
(226, 'Товар добавлен в корзину', NULL),
(227, 'Товар добавлен в корзину', NULL),
(228, 'Товар добавлен в корзину', NULL),
(229, 'Товар добавлен в корзину', NULL),
(230, 'Товар добавлен в корзину', NULL),
(231, 'Товар добавлен в корзину', NULL),
(232, 'Товар добавлен в корзину', NULL),
(233, 'Товар добавлен в корзину', NULL),
(234, 'Товар добавлен в корзину', NULL),
(235, 'Товар добавлен в корзину', NULL),
(236, 'Товар добавлен в корзину', NULL),
(237, 'Товар добавлен в корзину', NULL),
(238, 'Товар добавлен в корзину', NULL),
(239, 'Товар добавлен в корзину', NULL),
(240, 'Товар добавлен в корзину', NULL),
(241, 'Товар добавлен в корзину', NULL),
(242, 'Товар добавлен в корзину', NULL),
(243, 'Товар добавлен в корзину', NULL),
(244, 'Товар добавлен в корзину', NULL),
(245, 'Товар добавлен в корзину', NULL),
(246, 'Товар добавлен в корзину', NULL),
(247, 'Товар добавлен в корзину', NULL),
(248, 'Товар добавлен в корзину', NULL),
(249, 'Товар добавлен в корзину', NULL),
(250, 'Товар добавлен в корзину', NULL),
(251, 'Товар добавлен в корзину', NULL),
(252, 'Товар добавлен в корзину', NULL),
(253, 'Товар добавлен в корзину', NULL),
(254, 'Товар добавлен в корзину', NULL),
(255, 'Товар добавлен в корзину', NULL),
(256, 'Товар добавлен в корзину', NULL),
(257, 'Товар добавлен в корзину', NULL),
(258, 'Товар добавлен в корзину', NULL),
(259, 'Товар добавлен в корзину', NULL),
(260, 'Товар добавлен в корзину', NULL),
(261, 'Товар добавлен в корзину', NULL),
(262, 'Товар добавлен в корзину', NULL),
(263, 'Товар добавлен в корзину', NULL),
(264, 'Товар добавлен в корзину', NULL),
(265, 'Товар добавлен в корзину', NULL),
(266, 'Товар добавлен в корзину', NULL),
(267, 'Товар добавлен в корзину', NULL),
(268, 'Товар добавлен в корзину', NULL),
(269, 'Товар добавлен в корзину', NULL),
(270, 'Товар добавлен в корзину', NULL),
(271, 'Товар добавлен в корзину', NULL),
(272, 'Товар добавлен в корзину', NULL),
(273, 'Товар добавлен в корзину', NULL),
(274, 'Товар добавлен в корзину', NULL),
(275, 'Товар добавлен в корзину', NULL),
(276, 'Товар добавлен в корзину', NULL),
(277, 'Товар добавлен в корзину', NULL),
(278, 'Товар добавлен в корзину', NULL),
(279, 'Товар добавлен в корзину', NULL),
(280, 'Товар добавлен в корзину', NULL),
(281, 'Товар добавлен в корзину', NULL),
(282, 'Товар добавлен в корзину', NULL),
(283, 'Товар добавлен в корзину', NULL),
(284, 'Товар добавлен в корзину', NULL),
(285, 'Товар добавлен в корзину', NULL),
(286, 'Товар добавлен в корзину', NULL),
(287, 'Товар добавлен в корзину', NULL),
(288, 'Товар добавлен в корзину', NULL),
(289, 'Товар добавлен в корзину', NULL),
(290, 'Товар добавлен в корзину', NULL),
(291, 'Товар добавлен в корзину', NULL),
(292, 'Товар добавлен в корзину', NULL),
(293, 'Товар добавлен в корзину', NULL),
(294, 'Товар добавлен в корзину', NULL),
(295, 'Товар добавлен в корзину', NULL),
(296, 'Товар добавлен в корзину', NULL),
(297, 'Товар добавлен в корзину', NULL),
(298, 'Товар добавлен в корзину', NULL),
(299, 'Товар добавлен в корзину', NULL),
(300, 'Товар добавлен в корзину', NULL),
(301, 'Товар добавлен в корзину', NULL),
(302, 'Товар добавлен в корзину', NULL),
(303, 'Товар добавлен в корзину', NULL),
(304, 'Не заполнено поле', NULL),
(305, 'Не заполнено поле', NULL),
(306, 'Не заполнено поле', NULL),
(307, 'Товар добавлен в корзину', NULL),
(308, 'Не заполнено поле', NULL),
(309, 'Не заполнено поле', NULL),
(310, 'Не корректный формат для email в поле', NULL),
(311, 'Решистрация прошла успешно', NULL),
(312, 'Телефон', NULL),
(313, 'Такой Телефон уже зарегистрирован', NULL),
(314, 'Телефон', NULL),
(315, 'Решистрация прошла успешно', NULL),
(316, 'Такой Email уже зарегистрирован', NULL),
(317, 'Заполните поля для авторизации', NULL),
(318, 'Заполните поля для авторизации', NULL),
(319, 'Заполните поля для авторизации', NULL),
(320, 'Неправильные логин или пароль', NULL),
(321, 'Неправильные логин или пароль', NULL),
(322, 'Неправильные логин или пароль', NULL),
(323, 'Неправильные логин или пароль<br><a style=\"text-decoration: underline; font-size: 18px; color: white\" href=\"/login/restore_password/user/OTY2MTMyMTMxNjU%3D/\">Для восстановления пароля перейдите по ссылке</a>', NULL),
(324, 'Неправильные логин или пароль<br><a style=\"text-decoration: underline; font-size: 18px; color: white\" href=\"/login/restore_password/user/OTY2MTMyMTMxNjU%3D/\">Для восстановления пароля перейдите по ссылке</a>', NULL),
(325, 'Добро пожаловать', NULL),
(326, 'Не заполнено поле', NULL),
(327, 'Такой Email уже зарегистрирован', NULL),
(328, 'Неправильные логин или пароль', NULL),
(329, 'Неправильные логин или пароль', NULL),
(330, 'Неправильные логин или пароль<br><a style=\"text-decoration: underline; font-size: 18px; color: white\" href=\"/login/restore_password/user/OTY2MTMyMTMxNjU%3D/\">Для восстановления пароля перейдите по ссылке</a>', NULL),
(331, 'Добро пожаловать', NULL),
(332, 'Не заполнено поле', NULL),
(333, 'Добро пожаловать', NULL),
(334, 'Добро пожаловать', NULL),
(335, 'Добро пожаловать', NULL),
(336, 'Добро пожаловать', NULL),
(337, 'Добро пожаловать', NULL),
(338, 'Не заполнено поле', NULL),
(339, 'Решистрация прошла успешно', NULL),
(340, 'Не заполнено поле', NULL),
(341, 'Не балуйтесь', NULL),
(342, 'Решистрация прошла успешно', NULL),
(343, 'Неправильные логин или пароль', NULL),
(344, 'Неправильные логин или пароль', NULL),
(345, 'Добро пожаловать', NULL),
(346, 'Не заполнено поле', NULL),
(347, 'Добро пожаловать', NULL),
(348, 'Не заполнено поле', NULL),
(349, 'Добро пожаловать', NULL),
(350, 'Не заполнено поле', NULL),
(351, 'Не балуйтесь', NULL),
(352, 'Телефон', NULL),
(353, 'Такой Телефон уже зарегистрирован', NULL),
(354, 'Решистрация прошла успешно', NULL),
(355, 'Добро пожаловать', NULL),
(356, 'Телефон', NULL),
(357, 'Такой Телефон уже зарегистрирован', NULL),
(358, 'Данные обновлены', NULL),
(359, 'Данные обновлены', NULL),
(360, 'Данные обновлены', NULL),
(361, 'Добро пожаловать', NULL),
(362, 'Данные обновлены', NULL),
(363, 'Данные обновлены', NULL),
(364, 'Такой Email уже зарегистрирован', NULL),
(365, 'Неправильные логин или пароль', NULL),
(366, 'Неправильные логин или пароль', NULL),
(367, 'Неправильные логин или пароль', NULL),
(368, 'Неправильные логин или пароль', NULL),
(369, 'Пароли не совпадают', NULL),
(370, 'Решистрация прошла успешно', NULL),
(371, 'Данные обновлены', NULL),
(372, 'Неправильные логин или пароль', NULL),
(373, 'Добро пожаловать', NULL),
(374, 'Данные обновлены', NULL),
(375, 'Добро пожаловать', NULL),
(376, 'Товар добавлен в корзину', NULL),
(377, 'Товар добавлен в корзину', NULL),
(378, 'Товар добавлен в корзину', NULL),
(379, 'Товар добавлен в корзину', NULL),
(380, 'Товар добавлен в корзину', NULL),
(381, 'Добро пожаловать', NULL),
(382, 'Товар добавлен в корзину', NULL),
(383, 'Товар добавлен в корзину', NULL),
(384, 'Товар добавлен в корзину', NULL),
(385, 'Товар добавлен в корзину', NULL),
(386, 'Товар добавлен в корзину', NULL),
(387, 'Товар добавлен в корзину', NULL),
(388, 'Товар добавлен в корзину', NULL),
(389, 'Товар добавлен в корзину', NULL),
(390, 'Товар добавлен в корзину', NULL),
(391, 'Товар добавлен в корзину', NULL),
(392, 'Товар добавлен в корзину', NULL),
(393, 'Товар добавлен в корзину', NULL),
(394, 'Товар добавлен в корзину', NULL),
(395, 'Товар добавлен в корзину', NULL),
(396, 'Товар добавлен в корзину', NULL),
(397, 'Товар добавлен в корзину', NULL),
(398, 'Товар добавлен в корзину', NULL),
(399, 'Товар добавлен в корзину', NULL),
(400, 'Товар добавлен в корзину', NULL),
(401, 'Товар добавлен в корзину', NULL),
(402, 'Товар добавлен в корзину', NULL),
(403, 'Товар добавлен в корзину', NULL),
(404, 'Товар добавлен в корзину', NULL),
(405, 'Товар добавлен в корзину', NULL),
(406, 'Товар добавлен в корзину', NULL),
(407, 'Товар добавлен в корзину', NULL),
(408, 'Товар добавлен в корзину', NULL),
(409, 'Товар добавлен в корзину', NULL),
(410, 'Товар добавлен в корзину', NULL),
(411, 'Товар добавлен в корзину', NULL),
(412, 'Товар добавлен в корзину', NULL),
(413, 'Товар добавлен в корзину', NULL),
(414, 'Товар добавлен в корзину', NULL),
(415, 'Товар добавлен в корзину', NULL),
(416, 'Товар добавлен в корзину', NULL),
(417, 'Товар добавлен в корзину', NULL),
(418, 'Товар добавлен в корзину', NULL),
(419, 'Товар добавлен в корзину', NULL),
(420, 'Товар добавлен в корзину', NULL),
(421, 'Товар добавлен в корзину', NULL),
(422, 'Товар добавлен в корзину', NULL),
(423, 'Товар добавлен в корзину', NULL),
(424, 'Товар добавлен в корзину', NULL),
(425, 'Товар добавлен в корзину', NULL),
(426, 'Товар добавлен в корзину', NULL),
(427, 'Товар добавлен в корзину', NULL),
(428, 'Неправильные логин или пароль', NULL),
(429, 'Пароли не совпадают', NULL),
(430, 'Решистрация прошла успешно', NULL),
(431, 'Товар добавлен в корзину', NULL),
(432, 'Неправильные логин или пароль', NULL),
(433, 'Неправильные логин или пароль<br><a style=\"text-decoration: underline; font-size: 18px; color: white\" href=\"/login/restore_password/user/OTYyMzczNDQ0MQ%3D%3D/\">Для восстановления пароля перейдите по ссылке</a>', NULL),
(434, 'Неправильные логин или пароль', NULL),
(435, 'Неправильные логин или пароль', NULL),
(436, 'Неправильные логин или пароль', NULL),
(437, 'Телефон', NULL),
(438, 'Такой Телефон уже зарегистрирован', NULL),
(439, 'Добро пожаловать', NULL),
(440, 'Товар добавлен в корзину', NULL),
(441, 'Товар добавлен в корзину', NULL),
(442, 'Товар добавлен в корзину', NULL),
(443, 'Товар добавлен в корзину', NULL),
(444, 'Товар добавлен в корзину', NULL),
(445, 'Отсутствуют данные для оформления заказа', NULL),
(446, 'Отсутствуют данные для оформления заказа', NULL),
(447, 'Товар добавлен в корзину', NULL),
(448, 'Товар добавлен в корзину', NULL),
(449, 'Товар добавлен в корзину', NULL),
(450, 'Товар добавлен в корзину', NULL),
(451, 'Товар добавлен в корзину', NULL),
(452, 'Товар добавлен в корзину', NULL),
(453, 'Товар добавлен в корзину', NULL),
(454, 'Товар добавлен в корзину', NULL),
(455, 'Товар добавлен в корзину', NULL),
(456, 'Товар добавлен в корзину', NULL),
(457, 'Товар добавлен в корзину', NULL),
(458, 'Товар добавлен в корзину', NULL),
(459, 'Товар добавлен в корзину', NULL),
(460, 'Товар добавлен в корзину', NULL),
(461, 'Товар добавлен в корзину', NULL),
(462, 'Товар добавлен в корзину', NULL),
(463, 'Товар добавлен в корзину', NULL),
(464, 'Товар добавлен в корзину', NULL),
(465, 'Товар добавлен в корзину', NULL),
(466, 'Товар добавлен в корзину', NULL),
(467, 'Товар добавлен в корзину', NULL),
(468, 'Товар добавлен в корзину', NULL),
(469, 'Товар добавлен в корзину', NULL),
(470, 'Товар добавлен в корзину', NULL),
(471, 'Товар добавлен в корзину', NULL),
(472, 'Товар добавлен в корзину', NULL),
(473, 'Товар добавлен в корзину', NULL),
(474, 'Товар добавлен в корзину', NULL),
(475, 'Товар добавлен в корзину', NULL),
(476, 'Товар добавлен в корзину', NULL),
(477, 'Товар добавлен в корзину', NULL),
(478, 'Товар добавлен в корзину', NULL),
(479, 'Товар добавлен в корзину', NULL),
(480, 'Товар добавлен в корзину', NULL),
(481, 'Товар добавлен в корзину', NULL),
(482, 'Товар добавлен в корзину', NULL),
(483, 'Товар добавлен в корзину', NULL),
(484, 'Товар добавлен в корзину', NULL),
(485, 'Товар добавлен в корзину', NULL),
(486, 'Товар добавлен в корзину', NULL),
(487, 'Товар добавлен в корзину', NULL),
(488, 'Товар добавлен в корзину', NULL),
(489, 'Товар добавлен в корзину', NULL),
(490, 'Товар добавлен в корзину', NULL),
(491, 'Товар добавлен в корзину', NULL),
(492, 'Товар добавлен в корзину', NULL),
(493, 'Товар добавлен в корзину', NULL),
(494, 'Товар добавлен в корзину', NULL),
(495, 'Товар добавлен в корзину', NULL),
(496, 'Товар добавлен в корзину', NULL),
(497, 'Товар добавлен в корзину', NULL),
(498, 'Товар добавлен в корзину', NULL),
(499, 'Товар добавлен в корзину', NULL),
(500, 'Товар добавлен в корзину', NULL),
(501, 'Товар добавлен в корзину', NULL),
(502, 'Товар добавлен в корзину', NULL),
(503, 'Товар добавлен в корзину', NULL),
(504, 'Товар добавлен в корзину', NULL),
(505, 'Товар добавлен в корзину', NULL),
(506, 'Товар добавлен в корзину', NULL),
(507, 'Товар добавлен в корзину', NULL),
(508, 'Товар добавлен в корзину', NULL),
(509, 'Товар добавлен в корзину', NULL),
(510, 'Товар добавлен в корзину', NULL),
(511, 'Товар добавлен в корзину', NULL),
(512, 'Товар добавлен в корзину', NULL),
(513, 'Товар добавлен в корзину', NULL),
(514, 'Товар добавлен в корзину', NULL),
(515, 'Товар добавлен в корзину', NULL),
(516, 'Товар добавлен в корзину', NULL),
(517, 'Товар добавлен в корзину', NULL),
(518, 'Товар добавлен в корзину', NULL),
(519, 'Товар добавлен в корзину', NULL),
(520, 'Товар добавлен в корзину', NULL),
(521, 'Товар добавлен в корзину', NULL),
(522, 'Товар добавлен в корзину', NULL),
(523, 'Товар добавлен в корзину', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `visitors`
--

INSERT INTO `visitors` (`id`, `name`, `phone`, `email`, `password`, `birthday`) VALUES
(6, 'Кеша2', '9533298091', 'example@gmail.com', 'c6f057b86584942e415435ffb1fa93d4', '2001-01-01'),
(7, NULL, NULL, NULL, '', NULL),
(8, NULL, NULL, NULL, '', NULL),
(9, 'Андрей1', '1234567890', 'example1@gmail.com', 'c6f057b86584942e415435ffb1fa93d4', '2000-01-01'),
(10, 'Андрей', '9623734441', 'andrusha.kolmakov@yandex.ru', 'c6f057b86584942e415435ffb1fa93d4', '1996-06-24');

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
-- Индексы таблицы `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `delivery_terms`
--
ALTER TABLE `delivery_terms`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gifts`
--
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_goods_id_fk` (`parent_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_visitors_id_fk` (`visitors_id`),
  ADD KEY `orders_payments_id_fk` (`payments_id`),
  ADD KEY `orders_delivery_id_fk` (`delivery_id`),
  ADD KEY `orders_orders_statuses_id_fk` (`orders_statuses_id`);

--
-- Индексы таблицы `orders_goods`
--
ALTER TABLE `orders_goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_goods_orders_id_fk` (`orders_id`),
  ADD KEY `orders_goods_goods_id_fk` (`goods_id`);

--
-- Индексы таблицы `orders_statuses`
--
ALTER TABLE `orders_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

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
-- Индексы таблицы `translate_elements`
--
ALTER TABLE `translate_elements`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `visitors`
--
ALTER TABLE `visitors`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `catalog`
--
ALTER TABLE `catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `delivery_terms`
--
ALTER TABLE `delivery_terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `gifts`
--
ALTER TABLE `gifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `orders_goods`
--
ALTER TABLE `orders_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `orders_statuses`
--
ALTER TABLE `orders_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT для таблицы `translate_elements`
--
ALTER TABLE `translate_elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=524;

--
-- AUTO_INCREMENT для таблицы `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `goods_goods_id_fk` FOREIGN KEY (`parent_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_delivery_id_fk` FOREIGN KEY (`delivery_id`) REFERENCES `delivery` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_orders_statuses_id_fk` FOREIGN KEY (`orders_statuses_id`) REFERENCES `orders_statuses` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_payments_id_fk` FOREIGN KEY (`payments_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_visitors_id_fk` FOREIGN KEY (`visitors_id`) REFERENCES `visitors` (`id`);

--
-- Ограничения внешнего ключа таблицы `orders_goods`
--
ALTER TABLE `orders_goods`
  ADD CONSTRAINT `orders_goods_goods_id_fk` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_goods_orders_id_fk` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
