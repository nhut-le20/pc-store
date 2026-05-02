-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th5 01, 2026 lúc 05:25 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `banhang_online`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `created_at`, `status`) VALUES
(1, 3, 5000000.00, '2026-04-29 10:33:49', 'Đang giao'),
(2, 3, 5000000.00, '2026-04-29 10:35:04', 'Hoàn thành'),
(3, 3, 5000000.00, '2026-04-30 15:23:10', 'pending'),
(4, 9, 1500000.00, '2026-05-01 15:13:02', 'Hoàn thành'),
(5, 9, 22000000.00, '2026-05-01 15:15:28', 'pending'),
(6, 9, 18000000.00, '2026-05-01 15:20:44', 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `product_name`) VALUES
(1, 2, NULL, 1, 5000000.00, 'babydoll'),
(2, 3, NULL, 1, 5000000.00, 'babydoll'),
(3, 4, NULL, 1, 1500000.00, 'SSD Kingston 1TB'),
(4, 5, NULL, 1, 22000000.00, 'PC Gaming RTX 4060'),
(5, 6, NULL, 1, 18000000.00, 'Laptop Dell Inspiron 15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `type`, `image`, `created_at`) VALUES
(1, 'Bé Hiếu Shiba', 'asdasdadadasd', 'blog', '1777552235Screenshot 2023-07-19 204321.png', '2026-04-30 12:30:35'),
(4, 'zxsacsacsac', 'asxascsac', 'news', '1777561420Screenshot 2023-08-05 145719.png', '2026-04-30 15:03:40'),
(7, 'PC STORE – Công nghệ cho mọi người', 'PC STORE là cửa hàng chuyên cung cấp các sản phẩm công nghệ như máy tính, laptop, linh kiện và phụ kiện chính hãng. Chúng tôi hướng đến việc mang lại trải nghiệm mua sắm đơn giản, nhanh chóng và đáng tin cậy cho khách hàng.\r\n\r\nVới đội ngũ hỗ trợ nhiệt tình, PC STORE luôn sẵn sàng tư vấn để bạn chọn được sản phẩm phù hợp nhất với nhu cầu học tập, làm việc và giải trí.\r\n\r\nChúng tôi cam kết:\r\n\r\nSản phẩm chính hãng, chất lượng đảm bảo\r\nGiá cả hợp lý, nhiều ưu đãi\r\nHỗ trợ khách hàng nhanh chóng\r\nBảo hành rõ ràng, minh bạch\r\n\r\nPC STORE không chỉ bán sản phẩm, mà còn mang đến giải pháp công nghệ phù hợp cho bạn.', 'about', '1777562178Screenshot 2023-06-29 164054.png', '2026-04-30 15:16:18'),
(8, 'Chính sách bán hàng & bảo hành', 'PC STORE luôn đặt quyền lợi khách hàng lên hàng đầu, với các chính sách rõ ràng và minh bạch.\r\n\r\nGiao hàng\r\n\r\nGiao hàng toàn quốc\r\nThời gian từ 1–3 ngày tùy khu vực\r\nĐược kiểm tra hàng trước khi thanh toán\r\n\r\nĐổi trả\r\n\r\nHỗ trợ đổi trả nếu sản phẩm lỗi từ nhà sản xuất\r\nThời gian đổi trả trong vòng 7 ngày\r\nSản phẩm cần còn nguyên vẹn, đầy đủ phụ kiện\r\n\r\nBảo hành\r\n\r\nBảo hành chính hãng theo từng sản phẩm\r\nHỗ trợ kỹ thuật khi cần thiết\r\n\r\nThanh toán\r\n\r\nThanh toán khi nhận hàng (COD)\r\nChuyển khoản ngân hàng\r\n\r\nBảo mật thông tin\r\n\r\nThông tin khách hàng được bảo mật tuyệt đối\r\nKhông chia sẻ cho bên thứ ba', 'policy', '1777562209Screenshot 2023-06-29 153643.png', '2026-04-30 15:16:49'),
(9, 'asdsad', 'asdsad', 'news', '1777562268Screenshot 2023-08-05 145719.png', '2026-04-30 15:17:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `quantity`, `image`, `created_at`) VALUES
(1, 'babydoll', 5000000.00, 2, '1777381220_base64-1628947534706628981470.webp', '2026-04-28 13:00:20'),
(2, 'Laptop Dell Inspiron 15', 18000000.00, 10, 'uploads/dell.jpg', '2026-05-01 15:02:16'),
(3, 'Laptop HP Pavilion', 17000000.00, 8, 'uploads/hp.jpg', '2026-05-01 15:02:16'),
(4, 'Laptop Lenovo ThinkPad X1', 25000000.00, 5, 'uploads/lenovo.jpg', '2026-05-01 15:02:16'),
(5, 'PC Gaming RTX 4060', 22000000.00, 4, 'uploads/pc1.jpg', '2026-05-01 15:02:16'),
(6, 'PC Gaming i5 GTX 1660', 15000000.00, 6, 'uploads/pc2.jpg', '2026-05-01 15:02:16'),
(7, 'Chuột Logitech G102', 350000.00, 20, 'uploads/mouse1.jpg', '2026-05-01 15:02:16'),
(8, 'Chuột Razer DeathAdder', 900000.00, 15, 'uploads/mouse2.jpg', '2026-05-01 15:02:16'),
(9, 'Bàn phím cơ DareU EK87', 700000.00, 12, 'uploads/keyboard1.jpg', '2026-05-01 15:02:16'),
(10, 'Bàn phím cơ Keychron K2', 1800000.00, 7, 'uploads/keyboard2.jpg', '2026-05-01 15:02:16'),
(11, 'Màn hình LG 24 inch', 3200000.00, 10, 'uploads/monitor1.jpg', '2026-05-01 15:02:16'),
(12, 'Màn hình Samsung 27 inch', 4500000.00, 6, 'uploads/monitor2.jpg', '2026-05-01 15:02:16'),
(13, 'Tai nghe Gaming HyperX', 1200000.00, 9, 'uploads/headphone1.jpg', '2026-05-01 15:02:16'),
(14, 'Tai nghe Sony WH-1000XM4', 6500000.00, 5, 'uploads/headphone2.jpg', '2026-05-01 15:02:16'),
(15, 'SSD Kingston 1TB', 1500000.00, 14, 'uploads/ssd1.jpg', '2026-05-01 15:02:16'),
(16, 'SSD Samsung 970 EVO 500GB', 1300000.00, 11, 'uploads/ssd2.jpg', '2026-05-01 15:02:16'),
(17, 'RAM Corsair 16GB DDR4', 1200000.00, 18, 'uploads/ram1.jpg', '2026-05-01 15:02:16'),
(18, 'RAM G.Skill 32GB DDR4', 2500000.00, 10, 'uploads/ram2.jpg', '2026-05-01 15:02:16'),
(19, 'Card đồ họa RTX 4070', 18000000.00, 3, 'uploads/gpu1.jpg', '2026-05-01 15:02:16'),
(20, 'Card đồ họa RX 6700 XT', 12000000.00, 4, 'uploads/gpu2.jpg', '2026-05-01 15:02:16'),
(21, 'Webcam Logitech C920', 2000000.00, 8, 'uploads/webcam.jpg', '2026-05-01 15:02:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `role`, `created_at`, `phone`, `address`, `avatar`, `status`, `reset_token`, `reset_expire`) VALUES
(3, 'yyyy', 'hakuya', '$2y$10$RyD5/cEp53SoBpvOPNaMpunGja6ZRqneFNmXrTa2XrHpPiEBxnbLK', 'aasdad@gmail.com', 'user', '2026-04-28 12:49:40', '27358774374', 'hfhsbhjbhsd', '1777552911_Screenshot 2023-07-19 204321.png', 'active', NULL, NULL),
(4, NULL, 'admin', '$2y$10$7QvaPr1gkMOYJUGygfDerO21yms8Za99DrGzZLBAB7iP7LkLUOD/i', 'dbashdbhjsagbhdj@gmail.com', 'admin', '2026-04-28 12:55:33', NULL, NULL, NULL, 'active', NULL, NULL),
(5, 'cutocolamemlo', 'xnxx', '$2y$10$12wDDKuMiYMyAPI54Eaqye7Ye1NSQBhGchI1fuiTZO2.5anXlnz.y', NULL, 'admin', '2026-04-29 10:57:15', NULL, NULL, NULL, 'locked', NULL, NULL),
(6, NULL, 'sadsa', '$2y$10$rrb9Fl.JSThfMvicwu7TxekwysdSbZvDheiJf0Arya5IRVOhLGy5e', 'asdsadadsad@tdu.edu.vn', 'user', '2026-04-30 13:53:55', NULL, NULL, NULL, 'active', NULL, NULL),
(7, NULL, 'awaken', '$2y$10$sIay8Kkz5S/a2nwsOBZQd.CofzJQBDrI8gilYmHsEefpI3ZU7JQaW', 'abshdbahsjdb@tdu.com', 'user', '2026-04-30 15:26:07', NULL, NULL, NULL, 'locked', NULL, NULL),
(8, 'dasdsa', 'kuro', '$2y$10$sepKm4aXSSgZrydI.tAT5eqGRC4SUfWLD0FcOAsODX1.w0Lb8XWUm', NULL, 'user', '2026-04-30 15:27:34', NULL, NULL, NULL, 'active', NULL, NULL),
(9, NULL, 'user1', '$2y$10$NCe0CVKfbP5.oqLCxdMZCeERlzh0XGwKdz1vNMYvFvbM5D.O0T8We', '123@gmail.com', 'user', '2026-05-01 15:12:16', NULL, NULL, NULL, 'active', NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
