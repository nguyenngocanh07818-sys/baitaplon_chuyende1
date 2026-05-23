-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 16, 2025 lúc 02:26 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `labtmdt`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `country`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Armand De Brignac', 'armand-de-brignac', 'Pháp', NULL, '2025-08-25 01:58:08', '2025-08-25 01:58:08'),
(2, 'Domaine Faiveley', 'domaine-faiveley', 'Pháp', 'Domain Faiveley là một trong những nhà làm vang danh giá bậc nhất Bourgogne, với lịch sử hơn 200 năm thuộc về một gia đình duy nhất, mang trong mình di sản tinh hoa làm vang của vùng. Triết lý làm vang đầy tâm huyết với sự tôn trọng vẻ đẹp của từng thổ nhưỡng mà họ trực tiếp sở hữu, canh tác organic (hữu cơ) tỉ mỉ đến từng ruộng nho, chú trọng tới chất lượng từng trái nho và lên men từ men tự nhiên, đã đưa Domain Faiveley trở thành nhà làm vang tiêu biểu thể hiện rõ nét nhất sự đa dạng của vùng Bourgogne. Khám phá danh mục vang Faiveley có thể đem lại được bức tranh trải nghiệm trọn vẹn vùng đất danh tiếng này, bởi Faiveley là nhà làm vang hiếm hoi ở Bourgogne sở hữu nhiều Grand Cru và Premier Cru nhất, và ruộng nho nằm trên 60 appellation (khu vực làm vang được định danh) trải dài từ Bắc xuống Nam, mỗi appellation đều được chăm sóc kĩ lưỡng và tâm huyết truyền tải chân thực nhất hương vị của từng thửa ruộng trong từng chai vang.', '2025-09-15 17:03:05', '2025-09-15 17:03:05'),
(3, 'Maison Louis Latour', 'maison-louis-latour', 'Pháp', 'Với phương châm kết hợp hài hòa giữa thiên nhiên và bàn tay con người, trải qua hơn 2 thế kỷ lịch sử, những nhà làm vang tại Maison Louis Latour vẫn miệt mài gìn giữ hương vị cổ điển từ những ruộng nho chất lượng trên đồi Corton.', '2025-09-15 17:03:42', '2025-09-15 17:03:42'),
(4, 'Collefrisio', 'collefrisio', 'Italy', 'Collefrisio luôn theo đuổi triết lý tạo ra rượu vang chất lượng cao, tiến hành canh tác, sản xuất hữu cơ, cho ra đời những dòng rượu vang Montepulciano d\'Abruzzo ôm trọn tinh hoa thổ nhưỡng, đất trời Abruzzo', '2025-09-15 17:04:52', '2025-09-15 17:04:52'),
(5, 'San Marzano', 'san-marzano', 'Italy', 'San Marzano là một biểu tượng tiêu biểu của ngành sản xuất rượu vang Ý, kết tinh từ sự giao thoa giữa truyền thống lâu đời và sự sáng tạo hiện đại. Với lịch sử phong phú, thổ nhưỡng chất lượng và đặc biệt là đội ngũ những nhà làm vang đầy đam mê, San Marzano đã chinh phục trái tim của hàng triệu tín đồ rượu vang trên khắp thế giới.', '2025-09-15 17:05:25', '2025-09-15 17:05:25'),
(6, 'CVNE', 'cvne', 'Tây Ban Nha', 'Hơn 140 năm phát triển, CVNE đã luôn trung thành với các giá trị truyền thống đồng thời không ngừng đổi mới, đem lại hương vị rượu vang Tây Ban Nha chất lượng, được ưa chuộng ở khắp 90 quốc gia trên thế giới. Những dòng rượu vang Tây Ban Nha ngon nhất từ gia đình 5 thế hệ tâm huyết đã chính thức có mặt tại WINECELLAR.vn.', '2025-09-15 17:06:02', '2025-09-15 17:06:02'),
(7, 'Bodegas Muga', 'bodegas-muga', 'Tây Ban Nha', NULL, '2025-09-15 17:06:32', '2025-09-15 17:06:32'),
(8, 'Clos Apalta', 'clos-apalta', 'Chile', NULL, '2025-09-15 17:06:57', '2025-09-15 17:06:57'),
(9, 'Lapostolle', 'lapostolle', 'Chile', NULL, '2025-09-15 17:07:14', '2025-09-15 17:07:14'),
(10, 'My Favorite Neighbor', 'my-favorite-neighbor', 'Mỹ', 'My Favorite Neighbor là một thương hiệu rượu vang đặc biệt tôn vinh tình bạn, cộng đồng và những khoảnh khắc đáng nhớ trong cuộc sống. Với tinh thần sẻ chia và kết nối, những chai rượu vang từ My Favorite Neighbor là lựa chọn hoàn hảo cho mọi dịp, từ những buổi tối yên bình tại nhà đến các bữa tiệc sang trọng.', '2025-09-15 17:07:51', '2025-09-15 17:07:51'),
(11, 'Marchesi Antinori', 'marchesi-antinori', 'Mỹ', NULL, '2025-09-15 17:08:14', '2025-09-15 17:08:14'),
(12, 'Hope Family Wines', 'hope-family-wines', 'Mỹ', 'Một trong những nhà sản xuất rượu vang Mỹ xuất sắc hàng đầu với bộ sưu tập rượu vang đa dạng, chất lượng, mang đậm dấu ấn riêng.', '2025-09-15 17:08:32', '2025-09-15 17:08:32'),
(13, 'Kim Crawford', 'kim-crawford', 'New Zealand', NULL, '2025-09-15 17:09:16', '2025-09-15 17:09:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Rượu Vang Pháp', 'ruou-vang-phap', NULL, 1, 1, '2025-08-25 01:58:48', '2025-08-25 01:58:48'),
(2, NULL, 'Rượu Vang Ý', 'ruou-vang-y', 'Nước Ý nổi tiếng với đa dạng chủng loại rượu vang trắng, đỏ, hồng và vang sủi. Hãy cùng khám phá rượu vang Ý từ nhiều thương hiệu uy tín, nhiều vùng sản xuất nổi tiếng, nhiều lựa chọn từ vang bình dân đến vang cao cấp đắt tiền.', 1, 0, '2025-09-15 16:57:48', '2025-09-15 16:57:48'),
(3, NULL, 'Rượu Vang Tây Ban Nha', 'ruou-vang-tay-ban-nha', 'Hương vị thơm ngon, độc đáo của những dòng rượu vang Tây Ban Nha từ lâu đã được thực khách trên khắp thế giới ưa chuộng. Đừng bỏ lỡ hương vị tuyệt vời của những dòng vang Tây Ban Nha với mức giá hợp lý.', 1, 0, '2025-09-15 16:58:11', '2025-09-15 16:58:11'),
(4, NULL, 'Rượu Vang Chile', 'ruou-vang-chile', 'Khám phá hương vị rượu vang Chile - quốc gia xuất khẩu rượu vang lớn thứ 4 thế giới với những dòng vang thơm ngon, chất lượng hương vị được đánh giá cao nhưng có giá thành hợp lý, phù hợp để thưởng thức hàng ngày.', 1, 0, '2025-09-15 16:59:54', '2025-09-15 16:59:54'),
(5, NULL, 'Rượu Vang Mỹ', 'ruou-vang-my', 'Hãy cùng khám phá danh mục Rượu Vang Mỹ với đa dạng dòng vang từ bình dân đến cao cấp, từ những vùng sản xuất danh tiếng hàng đầu với hương vị chất lượng, được giới chuyên gia và tín đồ rượu vang đánh giá cao.', 1, 0, '2025-09-15 17:00:28', '2025-09-15 17:00:28'),
(6, NULL, 'Rượu Vang New Zealand', 'ruou-vang-new-zealand', 'Rượu vang New Zealand ngày càng được đánh giá cao trên thị trường thế giới bởi hương vị cá tính, tươi mới; thiết kế dáng chai, nhãn chai hiện đại cùng giá thành cực kỳ hợp lý.', 1, 0, '2025-09-15 17:01:17', '2025-09-15 17:01:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `inventories`
--

INSERT INTO `inventories` (`id`, `product_id`, `stock`, `low_stock_threshold`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 5, '2025-08-25 02:00:40', '2025-09-15 16:21:06'),
(2, 2, 0, 5, '2025-09-15 17:11:21', '2025-09-15 17:11:21'),
(3, 3, 0, 5, '2025-09-15 17:13:14', '2025-09-15 17:13:14'),
(4, 4, 0, 5, '2025-09-15 17:14:37', '2025-09-15 17:14:37'),
(5, 5, 0, 5, '2025-09-15 17:16:32', '2025-09-15 17:16:32'),
(6, 6, 0, 5, '2025-09-15 17:17:32', '2025-09-15 17:17:32'),
(7, 7, 0, 5, '2025-09-15 17:18:51', '2025-09-15 17:18:51'),
(8, 8, 0, 5, '2025-09-15 17:19:56', '2025-09-15 17:19:56'),
(9, 9, 0, 5, '2025-09-15 17:21:02', '2025-09-15 17:21:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_19_013007_update_users_table_add_role_and_fix_columns', 2),
(5, '2025_08_25_074154_create_categories_table', 3),
(6, '2025_08_25_074156_create_brands_table', 3),
(7, '2025_08_25_074200_create_products_table', 3),
(8, '2025_08_25_074205_create_product_images_table', 3),
(9, '2025_08_25_074211_create_inventories_table', 3),
(10, '2025_08_25_074350_create_orders_table', 3),
(11, '2025_08_25_074355_create_order_items_table', 3),
(12, '2025_09_08_071829_alter_order_items_nullable_product_name', 4),
(13, '2025_09_15_204913_create_reviews_table', 5),
(14, '2025_09_15_221039_create_reviews_table_fix', 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `ward` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `age_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','processing','paid','shipped','completed','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` enum('COD','online') NOT NULL DEFAULT 'COD',
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tracking_number` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `phone`, `email`, `address_line1`, `address_line2`, `ward`, `district`, `province`, `postal_code`, `age_confirmed`, `status`, `payment_method`, `subtotal`, `discount`, `shipping_fee`, `tax`, `total`, `tracking_number`, `paid_at`, `shipped_at`, `notes`, `created_at`, `updated_at`) VALUES
(6, 3, 'test mail', NULL, '22111060625@hunre.edu.vn', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'completed', 'online', 7800000.00, 0.00, 0.00, 0.00, 7800000.00, NULL, NULL, NULL, 'Thanh toán qua VNPay, mã giao dịch: 17579635328104', '2025-09-15 12:12:32', '2025-09-15 16:16:50'),
(7, 3, 'test mail', '0321321321', '22111060625@hunre.edu.vn', '039 ABC', NULL, 'Phường Nghĩa Đô', 'Quận Cầu Giấy', 'Thành phố Hà Nội', '100000', 1, 'completed', 'online', 7800000.00, 0.00, 0.00, 0.00, 7800000.00, NULL, '2025-09-13 13:28:18', NULL, 'Thanh toán qua VNPay, mã giao dịch: 17579676548066. Chờ xác nhận giao hàng.', '2025-09-13 13:28:18', '2025-09-13 15:06:16'),
(8, 3, 'Đôn Thị Lan Anh', '0321321321', '22111060625@hunre.edu.vn', '1001 ABC', NULL, 'Thị trấn Quốc Oai', 'Huyện Quốc Oai', 'Thành phố Hà Nội', '100000', 1, 'completed', 'online', 7800000.00, 0.00, 0.00, 0.00, 7800000.00, NULL, '2025-09-15 16:21:06', NULL, 'Thanh toán qua VNPay, mã giao dịch: 17579784328312. Chờ xác nhận giao hàng.', '2025-09-15 16:21:06', '2025-09-15 16:22:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `volume_ml` smallint(5) UNSIGNED DEFAULT NULL,
  `alcohol_content` decimal(4,1) DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `line_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `sku`, `volume_ml`, `alcohol_content`, `quantity`, `price`, `line_total`, `created_at`, `updated_at`) VALUES
(2, 6, 1, 'Rượu Champagne Armand De Brignac Gold – Champagne Át Bích – ABV 5.1%', NULL, NULL, NULL, 1, 7800000.00, 7800000.00, '2025-09-15 12:12:32', '2025-09-15 12:12:32'),
(3, 7, 1, 'Rượu Champagne Armand De Brignac Gold – Champagne Át Bích – ABV 5.1%', NULL, NULL, NULL, 1, 7800000.00, 7800000.00, '2025-09-15 13:28:18', '2025-09-15 13:28:18'),
(4, 8, 1, 'Rượu Champagne Armand De Brignac Gold – Champagne Át Bích – ABV 5.1%', NULL, NULL, NULL, 1, 7800000.00, 7800000.00, '2025-09-15 16:21:06', '2025-09-15 16:21:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `alcohol_content` decimal(4,1) DEFAULT NULL,
  `volume_ml` smallint(5) UNSIGNED NOT NULL DEFAULT 750,
  `vintage` smallint(5) UNSIGNED DEFAULT NULL,
  `grape_variety` varchar(255) DEFAULT NULL,
  `origin_country` varchar(255) DEFAULT NULL,
  `price` decimal(12,0) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('draft','active','hidden') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `slug`, `sku`, `description`, `alcohol_content`, `volume_ml`, `vintage`, `grape_variety`, `origin_country`, `price`, `sale_price`, `thumbnail`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Rượu Champagne Armand De Brignac Gold – Champagne Át Bích – ABV 5.1%', '24h-9700', '24H-9700', 'Nếu như có cơ hội được thưởng thức rượu Champagne của Pháp nói chung, thì chắc chắn rằng bạn có thể phân biệt được một cách hết sức rõ nét về rượu vang đến từ quốc gia này so với nhiều chai vang Champagne đến từ quốc gia khác trên thế giới. Sở dĩ có sự khác biệt đến như vậy là bởi vì một phong cách rất riêng của vang Pháp nói chung. Đó là một bí quyết sản xuất riêng của nhà làm rượu đồng thời là niềm tự hào, là nét đặc trưng của vang đến từ quốc gia này.\n\nBạn dùng sẽ cảm thấy ấn tượng với chai vang ngay từ cái nhìn đầu tiên. Đó là vẻ đẹp bởi hình thức bên ngoài với một thứ ánh màu vàng rơm óng ả. Màu vàng là ánh màu của niềm tin, của biết bao tia hy vọng thắp sáng lên bên trong tâm hồn.\n\nKhi thưởng thức từng tầng lớp hương vị như kế tiếp nhau tấn công trong vòm họng những người thưởng thức vang. Đó là hương thơm của những trái cây chín như Hương đào, mơ, quả mọng đỏ, tiếp theo là cam quýt kết tinh, hoa cam và gợi ý của brioche. Vòm miệng phong phú với trái cây lạ, anh đào và một chút chanh, vani và mật ong. Hương vị của một chút hoa trắng rừng càng làm phong phú thêm hương vị của rượu vang.\n\nNồng độ 12.5 % nhẹ nhàng đủ để bạn cảm nhận được sự phong phú trong lượng tanin mịn màng và 1 cấu trúc cân bằng của rượu.\n\nRượu được đánh giá 95 điểm bởi “Christelle Guibert, Tạp chí Decanter, tháng 11 năm 2016”\n\nGold Medal – 2015 New York International Wine Competition\n\nGold Medal – 2015 San Francisco International Wine Competition', 5.1, 750, 2015, '40% Pinot Noir – 40% Chardonnay – 20% Pinot Meunier', 'Pháp', 7800000, NULL, 'https://ruouvang24h.vn/wp-content/uploads/2020/08/R%C6%B0%E1%BB%A3u-Champagne-Armand-De-Brignac-Gold-2.jpg', 1, 'active', '2025-08-25 02:00:40', '2025-08-25 02:00:40'),
(2, 1, 2, 'Rượu Vang Pháp Domaine Faiveley Bourgogne Chardonnay 2022', 'ruou-vang-phap-domaine-faiveley-bourgogne-chardonnay-2022', 'VT/0676', 'Rượu được ủ từ 8 đến 10 tháng trong hầm rượu tại Nuits-Saint-Georges. Một phần của cuvée (thay đổi tùy theo niên vụ) được ủ trong thùng gỗ sồi. Những thùng gỗ này đến từ các nhà sản xuất thùng gỗ sồi chất lượng cao và được lựa chọn kỹ lưỡng để đảm bảo hương vị thơm ngon. Rượu có hương thơm trái cây quyến rũ, chất vị thơm ngon, dễ uống.', 12.5, 750, 2022, 'Chardonnay', 'Pháp', 13000000, 12500000.00, 'https://winecellar.vn/wp-content/uploads/2025/07/domaine-faiveley-bourgogne-chardonnay.jpg', 0, 'active', '2025-09-15 17:11:21', '2025-09-15 17:11:21'),
(3, 1, 2, 'Rượu Vang Pháp Domaine Faiveley Bourgogne Pinot Noir 2023', 'ruou-vang-phap-domaine-faiveley-bourgogne-pinot-noir-2023', 'VD/1602', 'Các tu sĩ của Tu viện Cîteaux, nổi tiếng với đam mê dành cho nghề trồng nho và kiến thức sâu rộng về thổ nhưỡng, đã hồi sinh những vườn nho tại Bourgogne từ thế kỷ 11. Rượu mang sắc đỏ ruby tuyệt đẹp, lan tỏa hương thơm dễ chịu của trái cây đỏ chín mọng. Hương vị rượu phong phú, thơm ngon, vị chát mịn màng và tròn đầy. Đây là một dòng rượu vang có cấu trúc tốt và dễ thưởng thức.', 13.0, 750, 2023, 'Pinot Noir', 'Pháp', 1364000, NULL, 'https://winecellar.vn/wp-content/uploads/2025/07/domaine-faiveley-bourgogne-pinot-noir.jpg', 0, 'active', '2025-09-15 17:13:14', '2025-09-15 17:13:14'),
(4, 1, 3, 'Rượu vang Pháp Louis Latour Pouilly-Fuissé 2023', 'ruou-vang-phap-louis-latour-pouilly-fuisse-2023', 'VT/0172-23', 'Hương thơm nồng nàn của hoa keo, hoa kim ngân, cân bằng với hương vị tươi sáng, tròn đầy và hương hạnh nhân tươi mới ở hậu vị.', 13.0, 750, 2023, 'Chardonnay', 'Pháp', 2500000, 1573000.00, 'https://winecellar.vn/wp-content/uploads/2018/12/louis-latour-pouilly-fuisse.jpg', 0, 'active', '2025-09-15 17:14:37', '2025-09-15 17:14:37'),
(5, 2, NULL, 'Rượu Vang Ý CF Collefrisio Cerasuolo d’Abruzzo', 'ruou-vang-y-cf-collefrisio-cerasuolo-d-abruzzo', 'CF01', 'Rượu mang sắc hồng anh đào đậm, lan tỏa hương trái cây đỏ đặc trưng với dâu dại, anh đào đỏ và anh đào đen. Cấu trúc rượu cân bằng, tươi mát, hậu vị kéo dài đầy ấn tượng. Một dòng vang hồng quyến rũ, phù hợp cho mọi dịp.', 13.0, 750, 2022, 'Montepulciano D\'abruzzo', 'Ý', 860000, NULL, 'https://winecellar.vn/wp-content/uploads/2025/08/cf-collefrisio-cerasuolo-dabruzzo.jpg', 1, 'active', '2025-09-15 17:16:32', '2025-09-15 17:16:32'),
(6, 2, 4, 'Rượu Vang Ý CF Collefrisio Viquadra Montepulciano D’Abruzzo', 'ruou-vang-y-cf-collefrisio-viquadra-montepulciano-d-abruzzo', '22112', 'Dòng rượu vang đỏ đậm đà, hài hòa với vị chát nhẹ nhàng, lôi cuốn, được làm từ 100% giống nho Montepulciano d’Abruzzo. Hương vị rượu cân bằng, nồng nàn hương thơm trái quả với sắc đỏ ruby vô cùng quyến rũ. Nhãn chai mang hình ảnh hoa mai, tượng trưng cho tài lộc, may mắn. Đây chính là sự lựa chọn hoàn hảo để dành tặng đối tác, khách hàng.', 14.0, 750, 2022, 'Montepulciano D\'abruzzo', 'Ý', 1500000, NULL, 'https://winecellar.vn/wp-content/uploads/2024/09/cf-collefrisio-viquadra.jpg', 1, 'active', '2025-09-15 17:17:32', '2025-09-15 17:17:32'),
(7, 2, 4, 'Rượu Vang Ý CF Collefrisio Pinot Grigio 2023', 'ruou-vang-y-cf-collefrisio-pinot-grigio-2023', 'Y121', 'Dòng vang trắng giá siêu tốt từ Collefrisio với sắc vàng rơm trong trẻo, lan tỏa hương thơm cam quýt hòa quyện cùng đào trắng. Rượu được lên men trong thùng thép không gỉ với nhiệt độ được kiểm soát, đem lại cấu trúc tốt và hương vị lưu luyến trên vòm miệng.', 13.0, 750, 2023, 'Pinot Gris (Pinot Grigio)', 'Ý', 1600000, NULL, 'https://winecellar.vn/wp-content/uploads/2024/03/cf-collefrisio-pinot-grigio.jpg', 0, 'active', '2025-09-15 17:18:51', '2025-09-15 17:18:51'),
(8, 3, 6, 'Rượu vang Tây Ban Nha Bela Ribera Del Duero', 'ruou-vang-tay-ban-nha-bela-ribera-del-duero', 'VD/1267', 'Rượu có sắc đỏ anh đào đậm đà, ánh tím quyến rũ, lan tỏa hương thơm phức hợp của hoa hòa quyện cùng trái cây, phát triển thêm với những nốt hương tinh tế của gia vị ngọt ngào và hương thơm vani, đinh hương quyến rũ từ việc ủ thùng gỗ sồi. Vị chát thơm ngon, cá tính, cuốn theo dư vị trái cây và khoáng chất đầy tinh tế.', 14.0, 750, 2023, 'Tempranillo', 'Tây Ban Nha', 480000, NULL, 'https://winecellar.vn/wp-content/uploads/2023/02/bela-ribera-del-duero.jpg', 0, 'active', '2025-09-15 17:19:56', '2025-09-15 17:19:56'),
(9, 3, 7, 'Rượu vang Tây Ban Nha Muga Rioja Reserva', 'ruou-vang-tay-ban-nha-muga-rioja-reserva', 'VD/0878', 'Rượu mang sắc đỏ ruby ánh garnet quyến rũ, lan tỏa hương thơm phức hợp, mạnh mẽ với những nốt hương trái cây rừng hòa quyện cùng gia vị. Một dòng rượu vang đỏ thanh lịch, cân bằng, độ chua sắc nét, vị chát mềm mại, hậu vị ngát hương trái cây. Rượu có tiềm năng lưu trữ lâu dài.', 14.0, 750, 2023, 'Blend', 'Tây Ban Nha', 1004000, NULL, 'https://winecellar.vn/wp-content/uploads/2017/07/muga-reserva-1.jpg', 0, 'active', '2025-09-15 17:21:02', '2025-09-15 17:21:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `path`, `alt`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'https://ruouvang24h.vn/wp-content/uploads/2020/08/R%C6%B0%E1%BB%A3u-Champagne-Armand-De-Brignac-Gold-3.jpg', 'Giới Thiệu Về Hãng sản xuất Champagne Armand De Brignac', 2, '2025-08-25 02:01:17', '2025-08-25 02:01:17'),
(2, 1, 'https://ruouvang24h.vn/wp-content/uploads/2020/08/R%C6%B0%E1%BB%A3u-Champagne-Armand-De-Brignac-Gold-1.jpg', 'Rượu Champagne Armand De Brignac Gold', 3, '2025-08-25 02:01:36', '2025-08-25 02:01:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `order_id`, `product_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 3, 7, 1, 4, 'Tốt', '2025-09-15 15:13:28', '2025-09-15 15:13:28'),
(2, 3, 6, 1, 1, 'Lần 2 mua cảm thấy rất tệ', '2025-09-15 16:17:10', '2025-09-15 16:17:10'),
(3, 3, 8, 1, 5, 'Lần thứ 3 mua sản phẩm rất tốt', '2025-09-15 16:23:05', '2025-09-15 16:23:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Jcp12dpPiXqF25aACvKlQhS75GkeJCjNto5E6MHB', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiU3ZzZDAwUkozSmhxRkR6THJuZ3k3bnBnYVRiVjVpc252Q1p6aTNOQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VyL29yZGVycyI7fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VyL2hvbWUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1757982244);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@gmail.com', NULL, '$2y$12$lJ5HhTMneVWEzXUEOCvRV.4AiiP/gjU4ywG.5n7PX5s/G/YAzkAQO', 'admin', NULL, '2025-08-18 18:34:50', '2025-08-18 18:34:50'),
(2, 'User', 'user@gmail.com', NULL, '$2y$12$yPdZCpCgv2Os.Chs6AnHXOs1O4utMy2qGuTNNlrPD40WO9p2YkSZq', 'customer', NULL, '2025-08-18 18:50:57', '2025-08-18 18:50:57'),
(3, 'Đôn Thị Lan Anh', '22111060625@hunre.edu.vn', NULL, '$2y$12$P8aeu2wNXcWJP.xkUZQ7K.QMTJy4y158mqxkywmDKhYPzssTnounS', 'customer', 'H8yBsujDzEyZwYH3EaaBwmNxI4meqa5GK2kHEsHnkDDKDQ2aDZQTnTW8md0v', '2025-08-18 19:23:24', '2025-09-15 16:23:57');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_status_payment_method_index` (`status`,`payment_method`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_order_id_product_id_index` (`order_id`,`product_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_category_id_brand_id_status_index` (`category_id`,`brand_id`,`status`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_order_id_product_id_unique` (`user_id`,`order_id`,`product_id`),
  ADD KEY `reviews_order_id_foreign` (`order_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
