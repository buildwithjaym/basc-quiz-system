-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2026 at 07:27 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bscs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

CREATE TABLE `attempts` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `score_mcq` int(11) NOT NULL DEFAULT '0',
  `score_ident` int(11) NOT NULL DEFAULT '0',
  `total_score` int(11) NOT NULL DEFAULT '0',
  `time_seconds` int(11) NOT NULL DEFAULT '0',
  `submitted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `compliment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quiz_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attempts`
--

INSERT INTO `attempts` (`id`, `student_id`, `score_mcq`, `score_ident`, `total_score`, `time_seconds`, `submitted`, `created_at`, `compliment`, `quiz_id`) VALUES
(57, 64, 17, 8, 25, 471, 1, '2026-01-29 02:27:03', '🧠 Clean work! That was a very ‘software engineer’ finish.', 1),
(58, 63, 18, 10, 28, 242, 1, '2026-01-29 02:27:08', '🌈 Good work — practice like this makes you unstoppable.', 1),
(59, 65, 20, 9, 29, 548, 1, '2026-01-29 02:27:10', '🔥 Nice! Your effort is giving ‘top student’ energy.', 1),
(60, 66, 14, 7, 21, 603, 1, '2026-01-29 02:27:14', '🌈 Good work — practice like this makes you unstoppable.', 1),
(61, 67, 14, 7, 21, 559, 1, '2026-01-29 02:27:16', '🧠 Clean work! That was a very ‘software engineer’ finish.', 1),
(62, 68, 18, 8, 26, 504, 1, '2026-01-29 02:27:31', '🏆 Respect! You handled the exam pressure well.', 1),
(63, 69, 18, 8, 26, 491, 1, '2026-01-29 02:27:31', '🔥 Nice! Your effort is giving ‘top student’ energy.', 1),
(64, 70, 13, 6, 19, 538, 1, '2026-01-29 02:27:37', '🏆 Respect! You handled the exam pressure well.', 1),
(65, 71, 20, 9, 29, 402, 1, '2026-01-29 02:27:59', '🧠 Clean work! That was a very ‘software engineer’ finish.', 1),
(66, 72, 20, 9, 29, 376, 1, '2026-01-29 02:28:33', '⭐ Nice! You’re closer to mastery than you think.', 1),
(67, 73, 20, 9, 29, 357, 1, '2026-01-29 02:29:00', '🧃 Smooth finish! You kept your momentum.', 1),
(68, 74, 0, 0, 0, 0, 0, '2026-01-29 03:12:12', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `attempt_answers`
--

CREATE TABLE `attempt_answers` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `q_key` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `q_type` enum('mcq','ident') COLLATE utf8mb4_unicode_ci NOT NULL,
  `given_answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `question_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attempt_answers`
--

INSERT INTO `attempt_answers` (`id`, `attempt_id`, `q_key`, `q_type`, `given_answer`, `is_correct`, `question_id`) VALUES
(241, 58, 'mcq_01', 'mcq', 'B', 1, NULL),
(242, 58, 'mcq_02', 'mcq', 'C', 1, NULL),
(243, 58, 'mcq_03', 'mcq', 'B', 1, NULL),
(244, 58, 'mcq_04', 'mcq', 'A', 0, NULL),
(245, 58, 'mcq_05', 'mcq', 'C', 1, NULL),
(246, 58, 'mcq_06', 'mcq', 'B', 1, NULL),
(247, 58, 'mcq_07', 'mcq', 'C', 1, NULL),
(248, 58, 'mcq_08', 'mcq', 'B', 1, NULL),
(249, 58, 'mcq_09', 'mcq', 'B', 1, NULL),
(250, 58, 'mcq_10', 'mcq', 'A', 1, NULL),
(251, 58, 'mcq_11', 'mcq', 'B', 1, NULL),
(252, 58, 'mcq_12', 'mcq', 'B', 1, NULL),
(253, 58, 'mcq_13', 'mcq', 'B', 0, NULL),
(254, 58, 'mcq_14', 'mcq', 'B', 1, NULL),
(255, 58, 'mcq_15', 'mcq', 'B', 1, NULL),
(256, 58, 'mcq_16', 'mcq', 'B', 1, NULL),
(257, 58, 'mcq_17', 'mcq', 'B', 1, NULL),
(258, 58, 'mcq_18', 'mcq', 'B', 1, NULL),
(259, 58, 'mcq_19', 'mcq', 'A', 1, NULL),
(260, 58, 'mcq_20', 'mcq', 'A', 1, NULL),
(261, 58, 'ident_01', 'ident', 'github', 1, NULL),
(262, 58, 'ident_02', 'ident', 'gitlab', 1, NULL),
(263, 58, 'ident_03', 'ident', 'supabase', 1, NULL),
(264, 58, 'ident_04', 'ident', 'mysql', 1, NULL),
(265, 58, 'ident_05', 'ident', 'postgresql', 1, NULL),
(266, 58, 'ident_06', 'ident', 'firebase', 1, NULL),
(267, 58, 'ident_07', 'ident', 'git', 1, NULL),
(268, 58, 'ident_08', 'ident', 'microprocessor', 1, NULL),
(269, 58, 'ident_09', 'ident', 'docker', 1, NULL),
(270, 58, 'ident_10', 'ident', 'vscode', 1, NULL),
(271, 65, 'mcq_01', 'mcq', 'B', 1, NULL),
(272, 65, 'mcq_02', 'mcq', 'C', 1, NULL),
(273, 65, 'mcq_03', 'mcq', 'B', 1, NULL),
(274, 65, 'mcq_04', 'mcq', 'B', 1, NULL),
(275, 65, 'mcq_05', 'mcq', 'C', 1, NULL),
(276, 65, 'mcq_06', 'mcq', 'B', 1, NULL),
(277, 65, 'mcq_07', 'mcq', 'C', 1, NULL),
(278, 65, 'mcq_08', 'mcq', 'B', 1, NULL),
(279, 65, 'mcq_09', 'mcq', 'B', 1, NULL),
(280, 65, 'mcq_10', 'mcq', 'A', 1, NULL),
(281, 65, 'mcq_11', 'mcq', 'B', 1, NULL),
(282, 65, 'mcq_12', 'mcq', 'B', 1, NULL),
(283, 65, 'mcq_13', 'mcq', 'A', 1, NULL),
(284, 65, 'mcq_14', 'mcq', 'B', 1, NULL),
(285, 65, 'mcq_15', 'mcq', 'B', 1, NULL),
(286, 65, 'mcq_16', 'mcq', 'B', 1, NULL),
(287, 65, 'mcq_17', 'mcq', 'B', 1, NULL),
(288, 65, 'mcq_18', 'mcq', 'B', 1, NULL),
(289, 65, 'mcq_19', 'mcq', 'A', 1, NULL),
(290, 65, 'mcq_20', 'mcq', 'A', 1, NULL),
(291, 65, 'ident_01', 'ident', 'github', 1, NULL),
(292, 65, 'ident_02', 'ident', 'gitlab', 1, NULL),
(293, 65, 'ident_03', 'ident', 'suvabase', 0, NULL),
(294, 65, 'ident_04', 'ident', 'mysql', 1, NULL),
(295, 65, 'ident_05', 'ident', 'postgresql', 1, NULL),
(296, 65, 'ident_06', 'ident', 'firebase', 1, NULL),
(297, 65, 'ident_07', 'ident', 'git', 1, NULL),
(298, 65, 'ident_08', 'ident', 'microprocessor', 1, NULL),
(299, 65, 'ident_09', 'ident', 'docker', 1, NULL),
(300, 65, 'ident_10', 'ident', 'vscode', 1, NULL),
(301, 66, 'mcq_01', 'mcq', 'B', 1, NULL),
(302, 66, 'mcq_02', 'mcq', 'C', 1, NULL),
(303, 66, 'mcq_03', 'mcq', 'B', 1, NULL),
(304, 66, 'mcq_04', 'mcq', 'B', 1, NULL),
(305, 66, 'mcq_05', 'mcq', 'C', 1, NULL),
(306, 66, 'mcq_06', 'mcq', 'B', 1, NULL),
(307, 66, 'mcq_07', 'mcq', 'C', 1, NULL),
(308, 66, 'mcq_08', 'mcq', 'B', 1, NULL),
(309, 66, 'mcq_09', 'mcq', 'B', 1, NULL),
(310, 66, 'mcq_10', 'mcq', 'A', 1, NULL),
(311, 66, 'mcq_11', 'mcq', 'B', 1, NULL),
(312, 66, 'mcq_12', 'mcq', 'B', 1, NULL),
(313, 66, 'mcq_13', 'mcq', 'A', 1, NULL),
(314, 66, 'mcq_14', 'mcq', 'B', 1, NULL),
(315, 66, 'mcq_15', 'mcq', 'B', 1, NULL),
(316, 66, 'mcq_16', 'mcq', 'B', 1, NULL),
(317, 66, 'mcq_17', 'mcq', 'B', 1, NULL),
(318, 66, 'mcq_18', 'mcq', 'B', 1, NULL),
(319, 66, 'mcq_19', 'mcq', 'A', 1, NULL),
(320, 66, 'mcq_20', 'mcq', 'A', 1, NULL),
(321, 66, 'ident_01', 'ident', 'github', 1, NULL),
(322, 66, 'ident_02', 'ident', 'gitlab', 1, NULL),
(323, 66, 'ident_03', 'ident', 'suvabase', 0, NULL),
(324, 66, 'ident_04', 'ident', 'mysql', 1, NULL),
(325, 66, 'ident_05', 'ident', 'postgresql', 1, NULL),
(326, 66, 'ident_06', 'ident', 'firebase', 1, NULL),
(327, 66, 'ident_07', 'ident', 'git', 1, NULL),
(328, 66, 'ident_08', 'ident', 'microprocessor', 1, NULL),
(329, 66, 'ident_09', 'ident', 'docker', 1, NULL),
(330, 66, 'ident_10', 'ident', 'vscode', 1, NULL),
(331, 57, 'mcq_01', 'mcq', 'B', 1, NULL),
(332, 57, 'mcq_02', 'mcq', 'C', 1, NULL),
(333, 57, 'mcq_03', 'mcq', 'B', 1, NULL),
(334, 57, 'mcq_04', 'mcq', 'A', 0, NULL),
(335, 57, 'mcq_05', 'mcq', 'C', 1, NULL),
(336, 57, 'mcq_06', 'mcq', 'B', 1, NULL),
(337, 57, 'mcq_07', 'mcq', 'C', 1, NULL),
(338, 57, 'mcq_08', 'mcq', 'B', 1, NULL),
(339, 57, 'mcq_09', 'mcq', 'B', 1, NULL),
(340, 57, 'mcq_10', 'mcq', 'A', 1, NULL),
(341, 57, 'mcq_11', 'mcq', 'B', 1, NULL),
(342, 57, 'mcq_12', 'mcq', 'B', 1, NULL),
(343, 57, 'mcq_13', 'mcq', 'A', 1, NULL),
(344, 57, 'mcq_14', 'mcq', 'B', 1, NULL),
(345, 57, 'mcq_15', 'mcq', 'B', 1, NULL),
(346, 57, 'mcq_16', 'mcq', 'B', 1, NULL),
(347, 57, 'mcq_17', 'mcq', 'D', 0, NULL),
(348, 57, 'mcq_18', 'mcq', 'B', 1, NULL),
(349, 57, 'mcq_19', 'mcq', 'C', 0, NULL),
(350, 57, 'mcq_20', 'mcq', 'A', 1, NULL),
(351, 57, 'ident_01', 'ident', 'github', 1, NULL),
(352, 57, 'ident_02', 'ident', 'git', 0, NULL),
(353, 57, 'ident_03', 'ident', 'supabase', 1, NULL),
(354, 57, 'ident_04', 'ident', 'mysql', 1, NULL),
(355, 57, 'ident_05', 'ident', 'postgresql', 1, NULL),
(356, 57, 'ident_06', 'ident', 'firebase', 1, NULL),
(357, 57, 'ident_07', 'ident', 'gitlab', 0, NULL),
(358, 57, 'ident_08', 'ident', 'micro processor', 1, NULL),
(359, 57, 'ident_09', 'ident', 'docker', 1, NULL),
(360, 57, 'ident_10', 'ident', 'vscode', 1, NULL),
(361, 67, 'mcq_01', 'mcq', 'B', 1, NULL),
(362, 67, 'mcq_02', 'mcq', 'C', 1, NULL),
(363, 67, 'mcq_03', 'mcq', 'B', 1, NULL),
(364, 67, 'mcq_04', 'mcq', 'B', 1, NULL),
(365, 67, 'mcq_05', 'mcq', 'C', 1, NULL),
(366, 67, 'mcq_06', 'mcq', 'B', 1, NULL),
(367, 67, 'mcq_07', 'mcq', 'C', 1, NULL),
(368, 67, 'mcq_08', 'mcq', 'B', 1, NULL),
(369, 67, 'mcq_09', 'mcq', 'B', 1, NULL),
(370, 67, 'mcq_10', 'mcq', 'A', 1, NULL),
(371, 67, 'mcq_11', 'mcq', 'B', 1, NULL),
(372, 67, 'mcq_12', 'mcq', 'B', 1, NULL),
(373, 67, 'mcq_13', 'mcq', 'A', 1, NULL),
(374, 67, 'mcq_14', 'mcq', 'B', 1, NULL),
(375, 67, 'mcq_15', 'mcq', 'B', 1, NULL),
(376, 67, 'mcq_16', 'mcq', 'B', 1, NULL),
(377, 67, 'mcq_17', 'mcq', 'B', 1, NULL),
(378, 67, 'mcq_18', 'mcq', 'B', 1, NULL),
(379, 67, 'mcq_19', 'mcq', 'A', 1, NULL),
(380, 67, 'mcq_20', 'mcq', 'A', 1, NULL),
(381, 67, 'ident_01', 'ident', 'github', 1, NULL),
(382, 67, 'ident_02', 'ident', 'gitlab', 1, NULL),
(383, 67, 'ident_03', 'ident', 'supabase', 1, NULL),
(384, 67, 'ident_04', 'ident', 'mysql', 1, NULL),
(385, 67, 'ident_05', 'ident', 'postgresql', 1, NULL),
(386, 67, 'ident_06', 'ident', 'firebase', 1, NULL),
(387, 67, 'ident_07', 'ident', 'git', 1, NULL),
(388, 67, 'ident_08', 'ident', 'microprocesor', 0, NULL),
(389, 67, 'ident_09', 'ident', 'docker', 1, NULL),
(390, 67, 'ident_10', 'ident', 'vscode', 1, NULL),
(391, 63, 'mcq_01', 'mcq', 'B', 1, NULL),
(392, 63, 'mcq_02', 'mcq', 'C', 1, NULL),
(393, 63, 'mcq_03', 'mcq', 'B', 1, NULL),
(394, 63, 'mcq_04', 'mcq', 'D', 0, NULL),
(395, 63, 'mcq_05', 'mcq', 'C', 1, NULL),
(396, 63, 'mcq_06', 'mcq', 'B', 1, NULL),
(397, 63, 'mcq_07', 'mcq', 'C', 1, NULL),
(398, 63, 'mcq_08', 'mcq', 'B', 1, NULL),
(399, 63, 'mcq_09', 'mcq', 'B', 1, NULL),
(400, 63, 'mcq_10', 'mcq', 'A', 1, NULL),
(401, 63, 'mcq_11', 'mcq', 'D', 0, NULL),
(402, 63, 'mcq_12', 'mcq', 'B', 1, NULL),
(403, 63, 'mcq_13', 'mcq', 'A', 1, NULL),
(404, 63, 'mcq_14', 'mcq', 'B', 1, NULL),
(405, 63, 'mcq_15', 'mcq', 'B', 1, NULL),
(406, 63, 'mcq_16', 'mcq', 'B', 1, NULL),
(407, 63, 'mcq_17', 'mcq', 'B', 1, NULL),
(408, 63, 'mcq_18', 'mcq', 'B', 1, NULL),
(409, 63, 'mcq_19', 'mcq', 'A', 1, NULL),
(410, 63, 'mcq_20', 'mcq', 'A', 1, NULL),
(411, 63, 'ident_01', 'ident', 'Github', 1, NULL),
(412, 63, 'ident_02', 'ident', 'Gitlab', 1, NULL),
(413, 63, 'ident_03', 'ident', 'Supabase', 1, NULL),
(414, 63, 'ident_04', 'ident', 'MySQL', 1, NULL),
(415, 63, 'ident_05', 'ident', 'C-shark', 0, NULL),
(416, 63, 'ident_06', 'ident', 'firebase', 1, NULL),
(417, 63, 'ident_07', 'ident', 'git', 1, NULL),
(418, 63, 'ident_08', 'ident', 'Cpu', 0, NULL),
(419, 63, 'ident_09', 'ident', 'Docker', 1, NULL),
(420, 63, 'ident_10', 'ident', 'vscode', 1, NULL),
(421, 62, 'mcq_01', 'mcq', 'B', 1, NULL),
(422, 62, 'mcq_02', 'mcq', 'C', 1, NULL),
(423, 62, 'mcq_03', 'mcq', 'B', 1, NULL),
(424, 62, 'mcq_04', 'mcq', 'B', 1, NULL),
(425, 62, 'mcq_05', 'mcq', 'C', 1, NULL),
(426, 62, 'mcq_06', 'mcq', 'B', 1, NULL),
(427, 62, 'mcq_07', 'mcq', 'C', 1, NULL),
(428, 62, 'mcq_08', 'mcq', 'B', 1, NULL),
(429, 62, 'mcq_09', 'mcq', 'B', 1, NULL),
(430, 62, 'mcq_10', 'mcq', 'A', 1, NULL),
(431, 62, 'mcq_11', 'mcq', 'B', 1, NULL),
(432, 62, 'mcq_12', 'mcq', 'B', 1, NULL),
(433, 62, 'mcq_13', 'mcq', 'A', 1, NULL),
(434, 62, 'mcq_14', 'mcq', 'B', 1, NULL),
(435, 62, 'mcq_15', 'mcq', 'B', 1, NULL),
(436, 62, 'mcq_16', 'mcq', 'B', 1, NULL),
(437, 62, 'mcq_17', 'mcq', 'A', 0, NULL),
(438, 62, 'mcq_18', 'mcq', 'A', 0, NULL),
(439, 62, 'mcq_19', 'mcq', 'A', 1, NULL),
(440, 62, 'mcq_20', 'mcq', 'A', 1, NULL),
(441, 62, 'ident_01', 'ident', 'GitHub', 1, NULL),
(442, 62, 'ident_02', 'ident', 'GitLab', 1, NULL),
(443, 62, 'ident_03', 'ident', 'Suvabase', 0, NULL),
(444, 62, 'ident_04', 'ident', 'MySQL', 1, NULL),
(445, 62, 'ident_05', 'ident', 'PostgreSQL', 1, NULL),
(446, 62, 'ident_06', 'ident', 'Firebase', 1, NULL),
(447, 62, 'ident_07', 'ident', 'Git', 1, NULL),
(448, 62, 'ident_08', 'ident', 'Microprocessor', 1, NULL),
(449, 62, 'ident_09', 'ident', 'Docker', 1, NULL),
(450, 62, 'ident_10', 'ident', 'Visual Studio Code', 0, NULL),
(451, 59, 'mcq_01', 'mcq', 'B', 1, NULL),
(452, 59, 'mcq_02', 'mcq', 'C', 1, NULL),
(453, 59, 'mcq_03', 'mcq', 'B', 1, NULL),
(454, 59, 'mcq_04', 'mcq', 'B', 1, NULL),
(455, 59, 'mcq_05', 'mcq', 'C', 1, NULL),
(456, 59, 'mcq_06', 'mcq', 'B', 1, NULL),
(457, 59, 'mcq_07', 'mcq', 'C', 1, NULL),
(458, 59, 'mcq_08', 'mcq', 'B', 1, NULL),
(459, 59, 'mcq_09', 'mcq', 'B', 1, NULL),
(460, 59, 'mcq_10', 'mcq', 'A', 1, NULL),
(461, 59, 'mcq_11', 'mcq', 'B', 1, NULL),
(462, 59, 'mcq_12', 'mcq', 'B', 1, NULL),
(463, 59, 'mcq_13', 'mcq', 'A', 1, NULL),
(464, 59, 'mcq_14', 'mcq', 'B', 1, NULL),
(465, 59, 'mcq_15', 'mcq', 'B', 1, NULL),
(466, 59, 'mcq_16', 'mcq', 'B', 1, NULL),
(467, 59, 'mcq_17', 'mcq', 'B', 1, NULL),
(468, 59, 'mcq_18', 'mcq', 'B', 1, NULL),
(469, 59, 'mcq_19', 'mcq', 'A', 1, NULL),
(470, 59, 'mcq_20', 'mcq', 'A', 1, NULL),
(471, 59, 'ident_01', 'ident', 'github', 1, NULL),
(472, 59, 'ident_02', 'ident', 'gitlab', 1, NULL),
(473, 59, 'ident_03', 'ident', 'suwaves', 0, NULL),
(474, 59, 'ident_04', 'ident', 'mysql', 1, NULL),
(475, 59, 'ident_05', 'ident', 'postgresql', 1, NULL),
(476, 59, 'ident_06', 'ident', 'firebase', 1, NULL),
(477, 59, 'ident_07', 'ident', 'git', 1, NULL),
(478, 59, 'ident_08', 'ident', 'microprocessor', 1, NULL),
(479, 59, 'ident_09', 'ident', 'docker', 1, NULL),
(480, 59, 'ident_10', 'ident', 'vscode', 1, NULL),
(481, 64, 'mcq_01', 'mcq', 'B', 1, NULL),
(482, 64, 'mcq_02', 'mcq', 'D', 0, NULL),
(483, 64, 'mcq_03', 'mcq', 'B', 1, NULL),
(484, 64, 'mcq_04', 'mcq', 'A', 0, NULL),
(485, 64, 'mcq_05', 'mcq', 'C', 1, NULL),
(486, 64, 'mcq_06', 'mcq', 'B', 1, NULL),
(487, 64, 'mcq_07', 'mcq', 'C', 1, NULL),
(488, 64, 'mcq_08', 'mcq', 'B', 1, NULL),
(489, 64, 'mcq_09', 'mcq', 'B', 1, NULL),
(490, 64, 'mcq_10', 'mcq', 'A', 1, NULL),
(491, 64, 'mcq_11', 'mcq', 'A', 0, NULL),
(492, 64, 'mcq_12', 'mcq', 'B', 1, NULL),
(493, 64, 'mcq_13', 'mcq', 'A', 1, NULL),
(494, 64, 'mcq_14', 'mcq', 'D', 0, NULL),
(495, 64, 'mcq_15', 'mcq', 'C', 0, NULL),
(496, 64, 'mcq_16', 'mcq', 'B', 1, NULL),
(497, 64, 'mcq_17', 'mcq', 'A', 0, NULL),
(498, 64, 'mcq_18', 'mcq', 'B', 1, NULL),
(499, 64, 'mcq_19', 'mcq', 'B', 0, NULL),
(500, 64, 'mcq_20', 'mcq', 'A', 1, NULL),
(501, 64, 'ident_01', 'ident', 'github', 1, NULL),
(502, 64, 'ident_02', 'ident', 'gitlab', 1, NULL),
(503, 64, 'ident_03', 'ident', 'soperpays', 0, NULL),
(504, 64, 'ident_04', 'ident', 'mysql', 1, NULL),
(505, 64, 'ident_05', 'ident', 'postgresqp', 0, NULL),
(506, 64, 'ident_06', 'ident', 'firebase', 1, NULL),
(507, 64, 'ident_07', 'ident', 'git', 1, NULL),
(508, 64, 'ident_08', 'ident', 'intel core', 0, NULL),
(509, 64, 'ident_09', 'ident', 'doker', 0, NULL),
(510, 64, 'ident_10', 'ident', 'vscode', 1, NULL),
(511, 61, 'mcq_01', 'mcq', 'B', 1, NULL),
(512, 61, 'mcq_02', 'mcq', 'C', 1, NULL),
(513, 61, 'mcq_03', 'mcq', 'B', 1, NULL),
(514, 61, 'mcq_04', 'mcq', 'B', 1, NULL),
(515, 61, 'mcq_05', 'mcq', 'C', 1, NULL),
(516, 61, 'mcq_06', 'mcq', 'B', 1, NULL),
(517, 61, 'mcq_07', 'mcq', 'A', 0, NULL),
(518, 61, 'mcq_08', 'mcq', 'B', 1, NULL),
(519, 61, 'mcq_09', 'mcq', 'A', 0, NULL),
(520, 61, 'mcq_10', 'mcq', 'B', 0, NULL),
(521, 61, 'mcq_11', 'mcq', 'B', 1, NULL),
(522, 61, 'mcq_12', 'mcq', 'B', 1, NULL),
(523, 61, 'mcq_13', 'mcq', 'B', 0, NULL),
(524, 61, 'mcq_14', 'mcq', 'B', 1, NULL),
(525, 61, 'mcq_15', 'mcq', 'B', 1, NULL),
(526, 61, 'mcq_16', 'mcq', 'B', 1, NULL),
(527, 61, 'mcq_17', 'mcq', 'A', 0, NULL),
(528, 61, 'mcq_18', 'mcq', 'A', 0, NULL),
(529, 61, 'mcq_19', 'mcq', 'A', 1, NULL),
(530, 61, 'mcq_20', 'mcq', 'A', 1, NULL),
(531, 61, 'ident_01', 'ident', 'github', 1, NULL),
(532, 61, 'ident_02', 'ident', 'gitlab', 1, NULL),
(533, 61, 'ident_03', 'ident', 'suvabase', 0, NULL),
(534, 61, 'ident_04', 'ident', 'mysql', 1, NULL),
(535, 61, 'ident_05', 'ident', 'postgresql', 1, NULL),
(536, 61, 'ident_06', 'ident', 'git', 0, NULL),
(537, 61, 'ident_07', 'ident', 'git', 1, NULL),
(538, 61, 'ident_08', 'ident', 'micro processor', 1, NULL),
(539, 61, 'ident_09', 'ident', 'docker', 1, NULL),
(540, 61, 'ident_10', 'ident', 'visual studio code', 0, NULL),
(541, 60, 'mcq_01', 'mcq', 'B', 1, NULL),
(542, 60, 'mcq_02', 'mcq', 'C', 1, NULL),
(543, 60, 'mcq_03', 'mcq', 'B', 1, NULL),
(544, 60, 'mcq_04', 'mcq', 'B', 1, NULL),
(545, 60, 'mcq_05', 'mcq', 'C', 1, NULL),
(546, 60, 'mcq_06', 'mcq', 'B', 1, NULL),
(547, 60, 'mcq_07', 'mcq', 'C', 1, NULL),
(548, 60, 'mcq_08', 'mcq', 'B', 1, NULL),
(549, 60, 'mcq_09', 'mcq', 'A', 0, NULL),
(550, 60, 'mcq_10', 'mcq', 'B', 0, NULL),
(551, 60, 'mcq_11', 'mcq', 'B', 1, NULL),
(552, 60, 'mcq_12', 'mcq', 'A', 0, NULL),
(553, 60, 'mcq_13', 'mcq', 'B', 0, NULL),
(554, 60, 'mcq_14', 'mcq', 'B', 1, NULL),
(555, 60, 'mcq_15', 'mcq', 'B', 1, NULL),
(556, 60, 'mcq_16', 'mcq', 'B', 1, NULL),
(557, 60, 'mcq_17', 'mcq', 'A', 0, NULL),
(558, 60, 'mcq_18', 'mcq', 'A', 0, NULL),
(559, 60, 'mcq_19', 'mcq', 'A', 1, NULL),
(560, 60, 'mcq_20', 'mcq', 'A', 1, NULL),
(561, 60, 'ident_01', 'ident', 'github', 1, NULL),
(562, 60, 'ident_02', 'ident', 'gitlab', 1, NULL),
(563, 60, 'ident_03', 'ident', 'suvabase', 0, NULL),
(564, 60, 'ident_04', 'ident', 'mysql', 1, NULL),
(565, 60, 'ident_05', 'ident', 'postgresql', 1, NULL),
(566, 60, 'ident_06', 'ident', 'micro professor', 0, NULL),
(567, 60, 'ident_07', 'ident', 'git', 1, NULL),
(568, 60, 'ident_08', 'ident', 'Microprocessor', 1, NULL),
(569, 60, 'ident_09', 'ident', 'ducket', 0, NULL),
(570, 60, 'ident_10', 'ident', 'vscode', 1, NULL);

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `created_at`) VALUES
(63, 'Mabrouk', 'Said', '2026-01-29 02:26:18'),
(64, 'Andrei', 'Plad', '2026-01-29 02:26:48'),
(65, 'Shada', 'Abdukarim', '2026-01-29 02:26:54'),
(66, 'Shervana', 'Usman', '2026-01-29 02:27:11'),
(67, 'sirin', 'duma', '2026-01-29 02:27:13'),
(68, 'Anilov Vince', 'Gelizon', '2026-01-29 02:27:16'),
(69, 'Kyle', 'Quimson', '2026-01-29 02:27:21'),
(70, 'jharcidy', 'arabani', '2026-01-29 02:27:28'),
(71, 'Ma. lijana', 'Sebastian', '2026-01-29 02:27:34'),
(72, 'Nurwina', 'Causal', '2026-01-29 02:27:38'),
(73, 'Edmir', 'Delos Reyes', '2026-01-29 02:28:49'),
(74, 'Example', 'Lang Ni', '2026-01-29 03:12:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attempts`
--
ALTER TABLE `attempts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_one_attempt` (`student_id`),
  ADD UNIQUE KEY `uniq_student_quiz` (`student_id`,`quiz_id`),
  ADD KEY `idx_attempts_quiz` (`quiz_id`);

--
-- Indexes for table `attempt_answers`
--
ALTER TABLE `attempt_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attempt_id` (`attempt_id`),
  ADD KEY `idx_answers_question` (`question_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_student_name` (`first_name`,`last_name`);



--
-- AUTO_INCREMENT for table `attempts`
--
ALTER TABLE `attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT for table `attempt_answers`
--
ALTER TABLE `attempt_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=571;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `attempts`
--
ALTER TABLE `attempts`
  ADD CONSTRAINT `attempts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attempt_answers`
--
ALTER TABLE `attempt_answers`
  ADD CONSTRAINT `attempt_answers_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE CASCADE;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
