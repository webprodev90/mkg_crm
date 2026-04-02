-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el8
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 02 2026 г., 09:17
-- Версия сервера: 8.0.26
-- Версия PHP: 7.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `u375143_crm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin_tasks`
--

CREATE TABLE `admin_tasks` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `task_description` varchar(1000) NOT NULL COMMENT 'Описание задачи',
  `is_completed` int DEFAULT '0' COMMENT 'Выполнено ли',
  `completion_date` date DEFAULT NULL COMMENT 'Дата выполнения задачи'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `answer`
--

CREATE TABLE `answer` (
  `id` int NOT NULL,
  `question_id` int NOT NULL,
  `name_answer` varchar(1000) NOT NULL COMMENT 'Текст ответа',
  `is_correct` int NOT NULL COMMENT 'Правильный ли ответ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `api_log`
--

CREATE TABLE `api_log` (
  `id` int NOT NULL,
  `modul_api` varchar(255) NOT NULL COMMENT 'Модуль api',
  `text_api` int NOT NULL COMMENT 'Текст лога',
  `row_change_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время последнего обновления записи'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Логи api';

-- --------------------------------------------------------

--
-- Структура таблицы `atc_call_log`
--

CREATE TABLE `atc_call_log` (
  `id` int NOT NULL,
  `phone_number` varchar(255) NOT NULL COMMENT 'Номер телефона из ATC',
  `agent_id` varchar(5) NOT NULL COMMENT 'агент из ATC',
  `is_call` int NOT NULL COMMENT 'флаг звонка (1 - поступил звонок)',
  `row_change_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_call_log` int NOT NULL,
  `method_call` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `audiorecordings`
--

CREATE TABLE `audiorecordings` (
  `id` int NOT NULL,
  `name` varchar(256) NOT NULL COMMENT 'Название файла',
  `phone_number` bigint NOT NULL COMMENT 'Телефон',
  `link` varchar(1000) NOT NULL COMMENT 'Ссылка на файл в Яндекс Диске'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `balance`
--

CREATE TABLE `balance` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_cities_group`
--

CREATE TABLE `bez_cities_group` (
  `id` int NOT NULL,
  `name` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_department`
--

CREATE TABLE `bez_department` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_excel_update`
--

CREATE TABLE `bez_excel_update` (
  `region` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Регион',
  `phone_number` bigint NOT NULL COMMENT 'Номер телефона',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Источник данных',
  `status_id_in` int NOT NULL DEFAULT '0' COMMENT 'ID статуса до обработки',
  `status_id_out` int DEFAULT NULL COMMENT 'ID статуса после обработки (может отсутствовать)',
  `comment` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Комментарий к записи',
  `date_create` date NOT NULL COMMENT 'Дата создания записи'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица для загрузки данных из Excel (массовое обновление статусов)';

-- --------------------------------------------------------

--
-- Структура таблицы `bez_fix_plan`
--

CREATE TABLE `bez_fix_plan` (
  `partner_id` int NOT NULL,
  `count` int NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `is_city` int DEFAULT '0' COMMENT 'Проверять ли город',
  `is_audio` int NOT NULL DEFAULT '1' COMMENT 'Отправка аудио записей'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_group_request`
--

CREATE TABLE `bez_group_request` (
  `id` int NOT NULL,
  `count` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_leads_telegram`
--

CREATE TABLE `bez_leads_telegram` (
  `id` int NOT NULL COMMENT 'Уникальный идентификатор записи, который автоматически увеличивается',
  `date` date DEFAULT NULL COMMENT 'Дата поступления лида (в формате ДД.ММ.ГГГГ)',
  `username` varchar(255) DEFAULT NULL COMMENT 'Имя пользователя в Telegram (например, @oleg_zakup)',
  `fio` varchar(255) DEFAULT NULL COMMENT 'ФИО или иное указание имени (например, «Олег|Telegram Ads»)',
  `tgID` bigint DEFAULT NULL COMMENT 'ID пользователя в Telegram (уникальный идентификатор)',
  `initial_identifier` varchar(50) DEFAULT NULL COMMENT 'Начальное значение/идентификатор (например, «test0003»). Используется для отслеживания источника лида или тестовой кампании',
  `otvet1` varchar(255) DEFAULT NULL COMMENT 'Ответы на вопрос о типах долгов (можно выбрать несколько вариантов, значения разделены запятыми, например, «1, 2, 3, 4»)',
  `otvet2` decimal(15,2) DEFAULT NULL COMMENT 'Общая сумма по всем кредитам и займам (число в рублях, например, 500000)',
  `otvet3` tinyint(1) DEFAULT NULL COMMENT 'Ответ на вопрос о наличии просрочек (1 — да, 0 — нет)',
  `otvet3_1` int DEFAULT NULL COMMENT 'Количество месяцев, в течение которых не могут платить (например, 12)',
  `otvet4` varchar(255) DEFAULT NULL COMMENT 'Ответы на вопрос об ипотеке/автокредите и залоге имущества (можно выбрать несколько вариантов, значения разделены запятыми, например, «1, 3»)',
  `otvet5` varchar(255) DEFAULT NULL COMMENT 'Ответы на вопрос о жилье в собственности, за которое есть долги (можно выбрать несколько вариантов, значения разделены запятыми, например, «1, 2»)',
  `otvet5_1` varchar(255) DEFAULT NULL COMMENT 'Информация о прописанных и собственниках жилья (например, «прописан 1»)',
  `otvet6` varchar(255) DEFAULT NULL COMMENT 'Ответ на вопрос о предпринятых действиях для списания/уменьшения долгов (например, «каникулы»)',
  `otvet7` varchar(255) DEFAULT NULL COMMENT 'Город проживания клиента (например, «питер») — используется для связи с юристом региона',
  `otvet8` varchar(255) DEFAULT NULL COMMENT 'Имя клиента (например, «иван») — предпочтительный способ обращения',
  `otvet9` varchar(20) DEFAULT NULL COMMENT 'Номер телефона клиента для связи (например, «8987666666»)',
  `otvet2_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `otvet2_2` varchar(255) NOT NULL,
  `otvet10` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_lead_picker_status`
--

CREATE TABLE `bez_lead_picker_status` (
  `id` int NOT NULL,
  `status_name` varchar(256) NOT NULL COMMENT 'Название статуса',
  `status_id` int NOT NULL COMMENT 'id статуса'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_partners_plan`
--

CREATE TABLE `bez_partners_plan` (
  `id` int NOT NULL,
  `partner_id` int NOT NULL,
  `city` varchar(256) DEFAULT NULL,
  `total_quantity` int DEFAULT NULL,
  `quantity_per_day` varchar(256) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end1` datetime DEFAULT NULL,
  `otbrakovka` int DEFAULT NULL,
  `date_end2` datetime DEFAULT NULL,
  `vopros` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_partners_plan_dates`
--

CREATE TABLE `bez_partners_plan_dates` (
  `id` int NOT NULL,
  `partner_plan_id` int NOT NULL,
  `date` date DEFAULT NULL,
  `stage` int NOT NULL,
  `request_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_plan`
--

CREATE TABLE `bez_plan` (
  `partner_id` int NOT NULL,
  `count` int NOT NULL,
  `date` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_reg`
--

CREATE TABLE `bez_reg` (
  `id` int NOT NULL,
  `login` varchar(200) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `active_hex` varchar(32) NOT NULL,
  `status` int NOT NULL,
  `role` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user` varchar(50) NOT NULL,
  `online` int NOT NULL,
  `address_id` varchar(10) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `id_otdel` int NOT NULL COMMENT 'id отдела',
  `id_atc` int NOT NULL COMMENT 'id абонента АТС',
  `view_password` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_role`
--

CREATE TABLE `bez_role` (
  `id` int NOT NULL,
  `name_role` varchar(50) NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_sale_request`
--

CREATE TABLE `bez_sale_request` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `partner_id` int NOT NULL,
  `price` int NOT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_status`
--

CREATE TABLE `bez_status` (
  `id` int NOT NULL,
  `status_name` varchar(256) NOT NULL,
  `status_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_unprocessed`
--

CREATE TABLE `bez_unprocessed` (
  `id` int NOT NULL,
  `fio` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone_number` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `vopros` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` int NOT NULL,
  `date_create` date NOT NULL,
  `timez` time NOT NULL,
  `user_id` varchar(5) NOT NULL,
  `date_zagruzki` date NOT NULL,
  `is_dubl` varchar(1) NOT NULL,
  `istochnik` varchar(50) NOT NULL,
  `date_time_status_change` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_unprocessed_base`
--

CREATE TABLE `bez_unprocessed_base` (
  `id` int NOT NULL,
  `fio` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone_number` bigint DEFAULT NULL,
  `vopros` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city_group` int DEFAULT NULL,
  `status` int NOT NULL,
  `date_create` date NOT NULL,
  `date_time_status_change` datetime DEFAULT NULL,
  `timez` time NOT NULL,
  `user_id` varchar(5) NOT NULL,
  `partner` varchar(2000) NOT NULL,
  `source` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `is_ship` int NOT NULL,
  `id_otdel` int NOT NULL COMMENT 'id отдела',
  `group_source` int DEFAULT NULL,
  `date_time_of_last_save` datetime DEFAULT NULL,
  `past_status` int DEFAULT NULL,
  `is_double` varchar(1) DEFAULT NULL,
  `manual` varchar(1) NOT NULL COMMENT 'null - автоматическая / r -ручная ',
  `date_time_lead_save` datetime DEFAULT NULL COMMENT 'дата сохранения лида',
  `date_time_of_first_touch` datetime DEFAULT NULL COMMENT 'Дата и время первого касания',
  `is_audio_check` int NOT NULL COMMENT 'Флаг прослушивания записи (для руководителей)',
  `auto_city_group` int DEFAULT NULL COMMENT 'Регион по номеру',
  `mobile_operator_id` int DEFAULT NULL COMMENT 'Оператор мобильной связи',
  `debt_banks` int NOT NULL COMMENT 'Долги: банк',
  `debt_mfo` int NOT NULL COMMENT 'Долги: МФО',
  `taxes_fines` int NOT NULL COMMENT 'Долги: налоги, штрафы',
  `debt_zhkh` int NOT NULL COMMENT 'Долги: ЖКХ',
  `owners` int NOT NULL COMMENT 'Прописанных/собственников',
  `delays` varchar(1) DEFAULT NULL COMMENT 'Просрочки: y - Да, n - Нет',
  `mortgage` varchar(1) DEFAULT NULL COMMENT 'Ипотека: m - Да+еще недвижимость, s - Да, единственная, n - Нет',
  `car_loan` varchar(1) DEFAULT NULL COMMENT 'Автокредит: y - Да, n - Нет',
  `other_movables` varchar(256) DEFAULT NULL COMMENT 'иное движимое имущество',
  `other_early_action` varchar(256) DEFAULT NULL COMMENT 'иные варианты что предпринимали',
  `messenger_phone_number` varchar(256) DEFAULT NULL COMMENT 'номер в мессенджерах',
  `additional_comment` varchar(1000) NOT NULL DEFAULT '' COMMENT 'Дополнительный комментарий',
  `hold_status_id` int DEFAULT NULL COMMENT 'Cтатус Холда',
  `date_time_hold_calling` datetime DEFAULT NULL COMMENT 'Дата и время созвона Холда',
  `is_sog` int NOT NULL COMMENT 'Согласие (1-получено, 0-отправлено)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_unprocessed_base2`
--

CREATE TABLE `bez_unprocessed_base2` (
  `id` int NOT NULL,
  `fio` varchar(256) NOT NULL,
  `phone_number` varchar(256) NOT NULL,
  `vopros` varchar(2000) NOT NULL,
  `city` varchar(256) NOT NULL,
  `status` int NOT NULL,
  `date_create` date NOT NULL,
  `date_time_status_change` datetime DEFAULT NULL,
  `user_id` varchar(5) NOT NULL,
  `сompany_name` varchar(256) DEFAULT NULL,
  `sales_department` varchar(256) DEFAULT NULL,
  `experience` varchar(256) DEFAULT NULL,
  `have_crm` varchar(256) DEFAULT NULL,
  `time_difference` varchar(256) DEFAULT NULL,
  `job` varchar(256) DEFAULT NULL,
  `created_by_user_id` int DEFAULT NULL COMMENT 'id пользователя, который создал заявку'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_unprocessed_base2_comments`
--

CREATE TABLE `bez_unprocessed_base2_comments` (
  `id` int NOT NULL,
  `request2_id` int NOT NULL,
  `comment` varchar(2000) NOT NULL,
  `date_create` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_unprocessed_base_05052025`
--

CREATE TABLE `bez_unprocessed_base_05052025` (
  `id` int NOT NULL DEFAULT '0',
  `fio` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone_number` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `vopros` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city_group` int DEFAULT NULL,
  `status` int NOT NULL,
  `date_create` date NOT NULL,
  `date_time_status_change` datetime DEFAULT NULL,
  `timez` time NOT NULL,
  `user_id` varchar(5) NOT NULL,
  `partner` varchar(2000) NOT NULL,
  `source` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `is_ship` int NOT NULL,
  `id_otdel` int NOT NULL COMMENT 'id отдела',
  `group_source` int DEFAULT NULL,
  `date_time_of_last_save` datetime DEFAULT NULL,
  `past_status` int DEFAULT NULL,
  `is_double` varchar(1) DEFAULT NULL,
  `manual` varchar(1) NOT NULL COMMENT 'null - автоматическая / r -ручная ',
  `date_time_lead_save` datetime DEFAULT NULL COMMENT 'дата сохранения лида',
  `date_time_of_first_touch` datetime DEFAULT NULL COMMENT 'Дата и время первого касания'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bez_unprocessed_base_dubl`
--

CREATE TABLE `bez_unprocessed_base_dubl` (
  `id` int NOT NULL,
  `phone_number` varchar(25) NOT NULL,
  `fio` varchar(256) NOT NULL,
  `source` varchar(256) NOT NULL,
  `vopros` varchar(2000) NOT NULL,
  `city` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Таблица для проверки дубликатов';

-- --------------------------------------------------------

--
-- Структура таблицы `bez_unprocessed_base_excel`
--

CREATE TABLE `bez_unprocessed_base_excel` (
  `id` int NOT NULL,
  `fio` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone_number` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `vopros` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city_group` int DEFAULT NULL,
  `status` int NOT NULL,
  `date_create` date NOT NULL,
  `date_time_status_change` datetime DEFAULT NULL,
  `timez` time NOT NULL,
  `user_id` varchar(5) NOT NULL,
  `partner` varchar(2000) NOT NULL,
  `source` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `is_ship` int NOT NULL,
  `id_otdel` int NOT NULL COMMENT 'id отдела',
  `group_source` int DEFAULT NULL,
  `date_time_of_last_save` datetime DEFAULT NULL,
  `past_status` int DEFAULT NULL,
  `is_double` varchar(1) DEFAULT NULL,
  `manual` varchar(1) NOT NULL COMMENT 'null - автоматическая / r -ручная ',
  `auto_city_group` int DEFAULT NULL COMMENT 'Регион по номеру',
  `mobile_operator_id` int DEFAULT NULL COMMENT 'Оператор мобильной связи'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `block_initial_course`
--

CREATE TABLE `block_initial_course` (
  `id` int NOT NULL,
  `block_name` varchar(256) NOT NULL COMMENT 'Название блока'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `def_codes`
--

CREATE TABLE `def_codes` (
  `id` int NOT NULL,
  `defcode` int NOT NULL,
  `from_code` int NOT NULL,
  `to_code` int NOT NULL,
  `region_name` varchar(1000) NOT NULL,
  `mobile_operator` varchar(1000) NOT NULL COMMENT 'Оператор мобильной связи'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `early_action`
--

CREATE TABLE `early_action` (
  `id` int NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `form_submissions`
--

CREATE TABLE `form_submissions` (
  `id` int NOT NULL COMMENT 'Уникальный идентификатор записи (автоинкремент)',
  `form_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Внутренний ID формы в Elementor (например, e44822d)',
  `form_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Название формы в интерфейсе Elementor (например, "New Form")',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Номер телефона из поля типа "tel". Может быть пустым.',
  `privacy_policy_consent` tinyint NOT NULL DEFAULT '0' COMMENT 'Согласие с политикой конфиденциальности: 1 = согласен, 0 = не согласен (по умолчанию). Определяется по наличию фразы в поле формы.',
  `fields` json NOT NULL COMMENT 'Полный набор полей формы в формате JSON. Содержит: id, type, title, value, required для каждого поля.',
  `submission_date` date NOT NULL COMMENT 'Дата отправки формы (из метаданных, формат YYYY-MM-DD)',
  `submission_time` time NOT NULL COMMENT 'Время отправки формы (из метаданных, формат HH:MM:SS)',
  `page_url` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL страницы, где была заполнена форма',
  `remote_ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'IP‑адрес пользователя, отправившего форму (v4 или v6)',
  `user_agent` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Строка User-Agent браузера пользователя',
  `credit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Elementor' COMMENT 'Источник отправки. По умолчанию — "Elementor"',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата и время создания записи в БД',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата и время последнего обновления записи'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Хранилище данных форм Elementor: поля, метаданные, согласие с политикой конфиденциальности.';

-- --------------------------------------------------------

--
-- Структура таблицы `lead_filling_stats`
--

CREATE TABLE `lead_filling_stats` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'id пользователя',
  `request_id` int DEFAULT NULL COMMENT 'id заявки',
  `start_datetime` datetime NOT NULL COMMENT 'Дата и время начала заполнения',
  `end_datetime` datetime DEFAULT NULL COMMENT 'Дата и время окончания заполнения',
  `is_completed` int DEFAULT '0' COMMENT 'Перешел ли оператор к другой заявке',
  `duration_seconds` int DEFAULT NULL COMMENT 'Потраченное время в секундах'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lg_form_submissions`
--

CREATE TABLE `lg_form_submissions` (
  `id` bigint NOT NULL,
  `row_change_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `record_date` date DEFAULT (curdate()),
  `sent_rows` int DEFAULT '0',
  `received_rows` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `lg_user_atc`
--

CREATE TABLE `lg_user_atc` (
  `id` int NOT NULL,
  `phone_number` varchar(25) NOT NULL COMMENT 'Номер телефон',
  `status_id` int NOT NULL COMMENT 'Статус',
  `user_id` int NOT NULL COMMENT 'Пользователь',
  `row_change_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Метка времени'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Логи изменения статусов пользователем';

-- --------------------------------------------------------

--
-- Структура таблицы `lg_user_event`
--

CREATE TABLE `lg_user_event` (
  `id` int NOT NULL,
  `phone_number` varchar(25) NOT NULL COMMENT 'Номер телефон',
  `status_id` int NOT NULL COMMENT 'Статус',
  `user_id` int NOT NULL COMMENT 'Пользователь',
  `row_change_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Метка времени',
  `trunk` varchar(256) DEFAULT NULL COMMENT 'Транк'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Логи изменения статусов пользователем';

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `row_change_time` datetime NOT NULL,
  `value` varchar(256) NOT NULL,
  `text` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` varchar(256) NOT NULL,
  `modul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `messengers`
--

CREATE TABLE `messengers` (
  `id` int NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `mobile_operators`
--

CREATE TABLE `mobile_operators` (
  `id` int NOT NULL,
  `operator_name` varchar(256) NOT NULL COMMENT 'Название оператора мобильной связи',
  `operator_id` int NOT NULL COMMENT 'id оператора мобильной связи'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `movables`
--

CREATE TABLE `movables` (
  `id` int NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `options`
--

CREATE TABLE `options` (
  `id` int NOT NULL,
  `param_name` varchar(256) NOT NULL,
  `string_value` varchar(256) DEFAULT NULL,
  `number_value` int DEFAULT NULL,
  `date_value` date DEFAULT NULL,
  `commentary` varchar(4000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `price`
--

CREATE TABLE `price` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `city_id` int NOT NULL,
  `amount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `progress_by_blocks_in_initial_course`
--

CREATE TABLE `progress_by_blocks_in_initial_course` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `block_id` int NOT NULL,
  `status` enum('Не начат','В процессе','Не выполнен','Выполнен') NOT NULL COMMENT 'Статус выполнения',
  `start_date` date NOT NULL COMMENT 'Дата прохождения',
  `is_completed` int DEFAULT '0' COMMENT 'Пройдено ли успешно'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `progress_on_tasks_in_initial_course`
--

CREATE TABLE `progress_on_tasks_in_initial_course` (
  `id` int NOT NULL,
  `progress_by_block_id` int NOT NULL,
  `user_id` int NOT NULL,
  `task_id` int NOT NULL,
  `score` int NOT NULL COMMENT 'Набранные баллы',
  `is_done` enum('сдано','не сдано') DEFAULT NULL COMMENT 'Выполнено ли'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `question`
--

CREATE TABLE `question` (
  `id` int NOT NULL,
  `testing_id` int NOT NULL,
  `name_question` varchar(1000) NOT NULL COMMENT 'Текст вопроса'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `real_estate`
--

CREATE TABLE `real_estate` (
  `id` int NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `request_early_action`
--

CREATE TABLE `request_early_action` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `early_action_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `request_messengers`
--

CREATE TABLE `request_messengers` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `messenger_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `request_movables`
--

CREATE TABLE `request_movables` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `movables_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `request_real_estate`
--

CREATE TABLE `request_real_estate` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `real_estate_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `name_value` varchar(255) NOT NULL,
  `string_value` varchar(255) NOT NULL,
  `date_value` date NOT NULL,
  `login_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `statistic`
--

CREATE TABLE `statistic` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `leads` int NOT NULL,
  `coming` int NOT NULL,
  `date_create` date NOT NULL,
  `row_change_time` datetime NOT NULL,
  `leads_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `st_addres_s`
--

CREATE TABLE `st_addres_s` (
  `id` int NOT NULL,
  `name_addres` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `st_city_s`
--

CREATE TABLE `st_city_s` (
  `id` int NOT NULL,
  `name_city` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `st_partner_s`
--

CREATE TABLE `st_partner_s` (
  `id` int NOT NULL,
  `partner_name` varchar(2000) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `active` tinyint NOT NULL DEFAULT '1',
  `login` varchar(200) DEFAULT NULL,
  `pass` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `st_report_user`
--

CREATE TABLE `st_report_user` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `work_hours` int NOT NULL DEFAULT '8',
  `row_change_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='учет рабочего времени';

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_initial_course`
--

CREATE TABLE `tasks_initial_course` (
  `id` int NOT NULL,
  `block_id` int NOT NULL,
  `type_task` varchar(256) NOT NULL COMMENT 'Тип задания',
  `description_task` varchar(2000) NOT NULL COMMENT 'Описание задания',
  `passing_score` int DEFAULT NULL COMMENT 'Проходные баллы',
  `test_id` int DEFAULT NULL,
  `link` varchar(256) DEFAULT NULL COMMENT 'Ссылка на задание'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `test`
--

CREATE TABLE `test` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `age` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `testing`
--

CREATE TABLE `testing` (
  `id` int NOT NULL,
  `name` varchar(256) NOT NULL COMMENT 'Название теста'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `touches_phone_number`
--

CREATE TABLE `touches_phone_number` (
  `id` int NOT NULL,
  `phone_number` bigint NOT NULL COMMENT 'Номер телефона',
  `count_touches` int NOT NULL COMMENT 'Количество касаний до номера телефона'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_atc`
--

CREATE TABLE `user_atc` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'id пользователя',
  `atc_id` int NOT NULL COMMENT 'id atc',
  `actual_start_date` datetime NOT NULL COMMENT 'Дата начала актуальности',
  `actual_end_date` datetime DEFAULT NULL COMMENT 'Дата окончания актуальности'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin_tasks`
--
ALTER TABLE `admin_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `api_log`
--
ALTER TABLE `api_log`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `atc_call_log`
--
ALTER TABLE `atc_call_log`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `audiorecordings`
--
ALTER TABLE `audiorecordings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bez_cities_group`
--
ALTER TABLE `bez_cities_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_cities_group_id` (`id`);

--
-- Индексы таблицы `bez_department`
--
ALTER TABLE `bez_department`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bez_excel_update`
--
ALTER TABLE `bez_excel_update`
  ADD KEY `idx_phone_number` (`phone_number`),
  ADD KEY `idx_date_create` (`date_create`),
  ADD KEY `idx_status_id_in` (`status_id_in`),
  ADD KEY `idx_status_id_out` (`status_id_out`),
  ADD KEY `idx_region` (`region`),
  ADD KEY `idx_date_status` (`date_create`,`status_id_in`);

--
-- Индексы таблицы `bez_group_request`
--
ALTER TABLE `bez_group_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_group_request_date` (`date`,`id`);

--
-- Индексы таблицы `bez_leads_telegram`
--
ALTER TABLE `bez_leads_telegram`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_initial_identifier` (`initial_identifier`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_fio` (`fio`),
  ADD KEY `idx_tgID` (`tgID`),
  ADD KEY `idx_city` (`otvet7`),
  ADD KEY `idx_phone` (`otvet9`);

--
-- Индексы таблицы `bez_lead_picker_status`
--
ALTER TABLE `bez_lead_picker_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bez_partners_plan`
--
ALTER TABLE `bez_partners_plan`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bez_partners_plan_dates`
--
ALTER TABLE `bez_partners_plan_dates`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bez_reg`
--
ALTER TABLE `bez_reg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reg_id` (`id`);

--
-- Индексы таблицы `bez_role`
--
ALTER TABLE `bez_role`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Индексы таблицы `bez_sale_request`
--
ALTER TABLE `bez_sale_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sr_request_id` (`request_id`),
  ADD KEY `idx_sr_partner_id` (`partner_id`),
  ADD KEY `idx_sale_request_request_partner` (`request_id`,`partner_id`),
  ADD KEY `idx_sale_request_join` (`request_id`,`partner_id`,`date_time`),
  ADD KEY `idx_sr_date_time` (`date_time`);

--
-- Индексы таблицы `bez_status`
--
ALTER TABLE `bez_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status_id` (`status_id`),
  ADD KEY `idx_status_id_name` (`status_id`,`status_name`);

--
-- Индексы таблицы `bez_unprocessed`
--
ALTER TABLE `bez_unprocessed`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bez_unprocessed_base`
--
ALTER TABLE `bez_unprocessed_base`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone_number_index` (`phone_number`),
  ADD KEY `idx_ub_phone_number` (`phone_number`),
  ADD KEY `idx_ub_user_id` (`user_id`),
  ADD KEY `idx_ub_status` (`status`),
  ADD KEY `idx_ub_status_date` (`status`,`date_time_status_change`),
  ADD KEY `idx_ub_date_create` (`date_create`),
  ADD KEY `idx_ub_source` (`source`),
  ADD KEY `idx_ub_id_otdel` (`id_otdel`),
  ADD KEY `idx_ub_group_source` (`group_source`),
  ADD KEY `idx_ub_multi_filter` (`source`,`status`,`date_create`),
  ADD KEY `idx_ub_manual` (`manual`),
  ADD KEY `idx_unprocessed_base_status_date` (`status`,`date_time_of_last_save`,`date_create`,`is_double`,`manual`,`source`,`user_id`),
  ADD KEY `idx_date_filter` (`date_time_of_last_save`,`date_create`),
  ADD KEY `idx_unprocessed_status_date` (`status`,`date_create`),
  ADD KEY `idx_unprocessed_city` (`city`);

--
-- Индексы таблицы `bez_unprocessed_base2`
--
ALTER TABLE `bez_unprocessed_base2`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bez_unprocessed_base2_comments`
--
ALTER TABLE `bez_unprocessed_base2_comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bez_unprocessed_base_dubl`
--
ALTER TABLE `bez_unprocessed_base_dubl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone_number_index` (`phone_number`) USING BTREE;

--
-- Индексы таблицы `bez_unprocessed_base_excel`
--
ALTER TABLE `bez_unprocessed_base_excel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone_number_index` (`phone_number`);

--
-- Индексы таблицы `block_initial_course`
--
ALTER TABLE `block_initial_course`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `def_codes`
--
ALTER TABLE `def_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `defcode_index` (`defcode`),
  ADD KEY `codes_index` (`from_code`,`to_code`);

--
-- Индексы таблицы `early_action`
--
ALTER TABLE `early_action`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `form_submissions`
--
ALTER TABLE `form_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_phone` (`phone`);

--
-- Индексы таблицы `lead_filling_stats`
--
ALTER TABLE `lead_filling_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_start` (`user_id`,`start_datetime`),
  ADD KEY `idx_request` (`request_id`);

--
-- Индексы таблицы `lg_form_submissions`
--
ALTER TABLE `lg_form_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lg_user_atc`
--
ALTER TABLE `lg_user_atc`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lg_user_event`
--
ALTER TABLE `lg_user_event`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `messengers`
--
ALTER TABLE `messengers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `mobile_operators`
--
ALTER TABLE `mobile_operators`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `movables`
--
ALTER TABLE `movables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `price`
--
ALTER TABLE `price`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `progress_by_blocks_in_initial_course`
--
ALTER TABLE `progress_by_blocks_in_initial_course`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `progress_on_tasks_in_initial_course`
--
ALTER TABLE `progress_on_tasks_in_initial_course`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `real_estate`
--
ALTER TABLE `real_estate`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `request_early_action`
--
ALTER TABLE `request_early_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_request_early_action_request` (`request_id`),
  ADD KEY `idx_request_early_action_early_action` (`early_action_id`);

--
-- Индексы таблицы `request_messengers`
--
ALTER TABLE `request_messengers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_request_messengers_request` (`request_id`),
  ADD KEY `idx_request_messengers_messenger` (`messenger_id`);

--
-- Индексы таблицы `request_movables`
--
ALTER TABLE `request_movables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_request_movables_request` (`request_id`),
  ADD KEY `idx_request_movables_movables` (`movables_id`);

--
-- Индексы таблицы `request_real_estate`
--
ALTER TABLE `request_real_estate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_request_real_estate_request` (`request_id`),
  ADD KEY `idx_request_real_estate_real_estate` (`real_estate_id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `statistic`
--
ALTER TABLE `statistic`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `st_addres_s`
--
ALTER TABLE `st_addres_s`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `st_city_s`
--
ALTER TABLE `st_city_s`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_city` (`name_city`);

--
-- Индексы таблицы `st_partner_s`
--
ALTER TABLE `st_partner_s`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `st_report_user`
--
ALTER TABLE `st_report_user`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks_initial_course`
--
ALTER TABLE `tasks_initial_course`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `testing`
--
ALTER TABLE `testing`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `touches_phone_number`
--
ALTER TABLE `touches_phone_number`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD KEY `idx_phone_number` (`phone_number`),
  ADD KEY `idx_count_touches` (`count_touches`);

--
-- Индексы таблицы `user_atc`
--
ALTER TABLE `user_atc`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin_tasks`
--
ALTER TABLE `admin_tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `api_log`
--
ALTER TABLE `api_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `atc_call_log`
--
ALTER TABLE `atc_call_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `audiorecordings`
--
ALTER TABLE `audiorecordings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `balance`
--
ALTER TABLE `balance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_cities_group`
--
ALTER TABLE `bez_cities_group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_department`
--
ALTER TABLE `bez_department`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_group_request`
--
ALTER TABLE `bez_group_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_leads_telegram`
--
ALTER TABLE `bez_leads_telegram`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор записи, который автоматически увеличивается';

--
-- AUTO_INCREMENT для таблицы `bez_lead_picker_status`
--
ALTER TABLE `bez_lead_picker_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_partners_plan`
--
ALTER TABLE `bez_partners_plan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_partners_plan_dates`
--
ALTER TABLE `bez_partners_plan_dates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_reg`
--
ALTER TABLE `bez_reg`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_role`
--
ALTER TABLE `bez_role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_sale_request`
--
ALTER TABLE `bez_sale_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_status`
--
ALTER TABLE `bez_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_unprocessed`
--
ALTER TABLE `bez_unprocessed`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_unprocessed_base`
--
ALTER TABLE `bez_unprocessed_base`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_unprocessed_base2`
--
ALTER TABLE `bez_unprocessed_base2`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_unprocessed_base2_comments`
--
ALTER TABLE `bez_unprocessed_base2_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_unprocessed_base_dubl`
--
ALTER TABLE `bez_unprocessed_base_dubl`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bez_unprocessed_base_excel`
--
ALTER TABLE `bez_unprocessed_base_excel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `block_initial_course`
--
ALTER TABLE `block_initial_course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `def_codes`
--
ALTER TABLE `def_codes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `early_action`
--
ALTER TABLE `early_action`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `form_submissions`
--
ALTER TABLE `form_submissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор записи (автоинкремент)';

--
-- AUTO_INCREMENT для таблицы `lead_filling_stats`
--
ALTER TABLE `lead_filling_stats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lg_form_submissions`
--
ALTER TABLE `lg_form_submissions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lg_user_atc`
--
ALTER TABLE `lg_user_atc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lg_user_event`
--
ALTER TABLE `lg_user_event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `messengers`
--
ALTER TABLE `messengers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `mobile_operators`
--
ALTER TABLE `mobile_operators`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `movables`
--
ALTER TABLE `movables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `options`
--
ALTER TABLE `options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `price`
--
ALTER TABLE `price`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `progress_by_blocks_in_initial_course`
--
ALTER TABLE `progress_by_blocks_in_initial_course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `progress_on_tasks_in_initial_course`
--
ALTER TABLE `progress_on_tasks_in_initial_course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `question`
--
ALTER TABLE `question`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `real_estate`
--
ALTER TABLE `real_estate`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `request_early_action`
--
ALTER TABLE `request_early_action`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `request_messengers`
--
ALTER TABLE `request_messengers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `request_movables`
--
ALTER TABLE `request_movables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `request_real_estate`
--
ALTER TABLE `request_real_estate`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `statistic`
--
ALTER TABLE `statistic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `st_addres_s`
--
ALTER TABLE `st_addres_s`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `st_city_s`
--
ALTER TABLE `st_city_s`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `st_partner_s`
--
ALTER TABLE `st_partner_s`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `st_report_user`
--
ALTER TABLE `st_report_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tasks_initial_course`
--
ALTER TABLE `tasks_initial_course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `test`
--
ALTER TABLE `test`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `testing`
--
ALTER TABLE `testing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `touches_phone_number`
--
ALTER TABLE `touches_phone_number`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_atc`
--
ALTER TABLE `user_atc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `lead_filling_stats`
--
ALTER TABLE `lead_filling_stats`
  ADD CONSTRAINT `fk_lfs_user_id` FOREIGN KEY (`user_id`) REFERENCES `bez_reg` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_filling_stats_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `bez_unprocessed_base` (`id`);

--
-- Ограничения внешнего ключа таблицы `request_early_action`
--
ALTER TABLE `request_early_action`
  ADD CONSTRAINT `request_early_action_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `bez_unprocessed_base` (`id`),
  ADD CONSTRAINT `request_early_action_ibfk_2` FOREIGN KEY (`early_action_id`) REFERENCES `early_action` (`id`);

--
-- Ограничения внешнего ключа таблицы `request_messengers`
--
ALTER TABLE `request_messengers`
  ADD CONSTRAINT `request_messengers_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `bez_unprocessed_base` (`id`),
  ADD CONSTRAINT `request_messengers_ibfk_2` FOREIGN KEY (`messenger_id`) REFERENCES `messengers` (`id`);

--
-- Ограничения внешнего ключа таблицы `request_movables`
--
ALTER TABLE `request_movables`
  ADD CONSTRAINT `request_movables_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `bez_unprocessed_base` (`id`),
  ADD CONSTRAINT `request_movables_ibfk_2` FOREIGN KEY (`movables_id`) REFERENCES `movables` (`id`);

--
-- Ограничения внешнего ключа таблицы `request_real_estate`
--
ALTER TABLE `request_real_estate`
  ADD CONSTRAINT `request_real_estate_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `bez_unprocessed_base` (`id`),
  ADD CONSTRAINT `request_real_estate_ibfk_2` FOREIGN KEY (`real_estate_id`) REFERENCES `real_estate` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
