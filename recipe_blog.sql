SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recipe_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE `administrators` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `administrators`
--

/*
I am leaving the administrators table intentionally blank.
Note that the column "type" must be equal to either "superadmin" or "admin"
*/

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `recipe_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `recipe_type` varchar(10) CHARACTER SET utf8 NOT NULL,
  `calories` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `recipe_name`, `recipe_type`, `calories`, `active`) VALUES
(1, 'Bread Crusted Tilapia', 'Meal', 800, 1),
(3, 'Chocolate Lava Cake', 'Dessert', 800, 1),
(4, 'Fruit Salad', 'Snack', 100, 1),
(5, 'Sweet and Sour Stir Fry', 'Meal', 700, 1),
(18, 'French Toast', 'Meal', 550, 1),
(19, 'Greek Salad', 'Meal', 550, 1),
(25, 'Rice & Beans', 'Meal', 200, 1),
(35, 'Tofu & Root Vegetables', 'Meal', 700, 1),
(36, 'Fruit and Cheese Skewers', 'Snack', 300, 1);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `ingredient_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `amount` varchar(10) CHARACTER SET utf8 NOT NULL,
  `unit` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`id`, `recipe_id`, `ingredient_name`, `amount`, `unit`) VALUES
(1, 1, 'Tilapia', '4', 'pieces'),
(2, 1, 'Bread Crumbs', '2', 'cups'),
(3, 1, 'Lemon Juice', '2', 'cups'),
(4, 1, 'Basil', '2', 'teaspoons'),
(5, 1, 'Oregano', '1', 'teaspoons'),
(11, 18, 'bread', '10', 'pieces'),
(12, 18, 'cinnamon', '3', 'tablespoons'),
(13, 18, 'eggs', '2', 'pieces'),
(14, 19, 'lettuce', '5', 'cups'),
(15, 19, 'tomatoes', '4', 'pieces'),
(16, 19, 'cucumbers', '1', 'units'),
(17, 19, 'feta cheese', '1/2', 'cups'),
(20, 25, 'Brown Rice', '2', 'cups'),
(21, 25, 'Black Beans', '1/2', 'cups'),
(66, 19, 'balsamic vinegar dressing', '2', 'teaspoons'),
(67, 25, 'Spice pack', '4', 'teaspoons'),
(68, 4, 'strawberries', '2', 'cups'),
(69, 4, 'blueberries', '1/2', 'cups'),
(70, 4, 'blackberries', '1', 'cups'),
(71, 4, 'melon', '2', 'cups'),
(72, 4, 'pineapple', '1', 'cups'),
(73, 4, 'grapes', '4', 'cups'),
(74, 4, 'watermelon', '2', 'cups'),
(75, 4, 'cantelope', '3', 'cups'),
(76, 4, 'kiwi', '1', 'pieces'),
(77, 4, 'orange', '1/2', 'pieces'),
(78, 4, 'peaches', '2', 'units'),
(79, 35, 'tofu', '1', 'units'),
(80, 35, 'potatoes', '3', 'units'),
(81, 35, 'sweet potatoes', '1', 'units'),
(82, 35, 'balsamic vinegar', '4', 'tablespoons'),
(83, 35, 'honey', '2', 'teaspoons'),
(84, 35, 'spinach', '1', 'units'),
(85, 35, 'olive oil', '2', 'tablespoons'),
(86, 35, 'salt & pepper', '1', 'teaspoons'),
(87, 3, 'chocolate', '2', 'cups'),
(88, 3, 'flour', '4', 'cups'),
(89, 3, 'butter', '2', 'tablespoons'),
(90, 3, 'sugar', '2', 'cups'),
(91, 36, 'strawberries', '2', 'cups'),
(92, 36, 'green grapes', '2', 'cups'),
(93, 36, 'cheddar cheese', '2', 'cups'),
(94, 36, 'wooden skewers', '10', 'pieces'),
(95, 19, 'black olives', '1/2', 'cups'),
(96, 19, 'red onion', '1/2', 'pieces'),
(97, 5, 'Shrimp - cooked', '2', 'cups'),
(98, 5, 'Green pepper', '1', 'pieces'),
(99, 5, 'Pineapple', '2', 'cups'),
(100, 5, 'Peanuts', '1/4', 'cups'),
(101, 5, 'Carrot', '1', 'pieces'),
(102, 5, 'White rice', '2', 'cups'),
(103, 5, 'Sweet and Sour Sauce', '4', 'tablespoons');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_instructions`
--

CREATE TABLE `recipe_instructions` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `instruction_number` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `instruction` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recipe_instructions`
--

INSERT INTO `recipe_instructions` (`id`, `recipe_id`, `instruction_number`, `time`, `instruction`) VALUES
(1, 1, 2, 5, 'In a large bowl, mix together the bread crumbs with the lemon juice, basil, and oregano'),
(2, 1, 1, 1, 'Preheat the oven to 400 degrees'),
(3, 1, 3, 5, 'Dip the tilapia in the bread crump mixture, coating each piece entirely. Place fish on a large metal pan.'),
(4, 1, 4, 25, 'Once the oven is heated to 400 degrees, place the pan in the oven and bake.'),
(5, 1, 5, 1, 'Remove pan from the oven and flake fish with a fork to see if fully baked (inside will be white).'),
(6, 1, 6, 1, 'Serve with baked asparagus and long grain rice to make a full meal. Eat and enjoy!'),
(7, 19, 1, 5, 'Chop the lettuce'),
(8, 19, 2, 5, 'Chop the cucumbers into bite-size pieces'),
(9, 19, 3, 5, 'Chop the tomatoes into bite-size pieces'),
(11, 25, 1, 20, 'Cook rice in 4 cups of water.'),
(12, 25, 2, 10, 'Cook means in a pot with 1 cup of water.'),
(13, 25, 3, 1, 'Add spice pack to the rice while it is cooking.'),
(40, 19, 4, 5, 'Chop the onion into thin pieces'),
(41, 25, 4, 1, 'Mix together rice and beans.'),
(42, 18, 1, 2, 'Mix eggs and cinnamon in a large bowl'),
(43, 18, 2, 10, 'Dip bread in mixture and fry on frying pan'),
(44, 18, 3, 5, 'Flip bread once one side is browned'),
(45, 18, 4, 1, 'Serve and eat!'),
(46, 4, 1, 30, 'cut fruit into bite-size pieces'),
(47, 4, 2, 10, 'mix fruit together'),
(48, 35, 1, 20, 'Cut the potatoes and sweet potatoes into small pieces and add to baking pan'),
(49, 3, 1, 2, 'Melt butter in microwave'),
(50, 3, 2, 3, 'Melt chocolate in microwave'),
(51, 3, 3, 5, 'Mix all ingredients together in large bowl'),
(52, 3, 4, 1, 'Pour mixture into cupcake tin'),
(53, 3, 5, 20, 'Bake at 375 degrees.'),
(54, 36, 1, 10, 'Cut the fruit and cheese into bite-size pieces'),
(55, 36, 2, 10, 'Place the fruit and cheese on the skewers'),
(56, 35, 2, 1, 'Add salt & pepper for taste'),
(57, 35, 3, 22, 'Bake the potatoes and sweet potatoes at 400 degrees'),
(58, 35, 4, 5, 'While the potatoes are baking, cut the tofu into bite-size pieces'),
(59, 35, 5, 10, 'Stir fry the tofu in a large pan.  Add the honey and balsamic vinegar on top of the tofu while it stir-fries.'),
(60, 35, 6, 3, 'When the potatoes are done baking, add the spinach to the baking dish and put back in the oven'),
(61, 35, 7, 1, 'Mix the tofu with the potatoes and spinach once all are done.'),
(62, 19, 5, 1, 'Mix ingredients together in a large bowl and serve.'),
(63, 5, 1, 10, 'Chop the green pepper, pineapple, and carrot into bite-size pieces'),
(64, 5, 2, 5, 'Add the sweet and sour sauce to a large pan.  Add the pepper and carrot and begin stir-frying them at medium heat.'),
(65, 5, 3, 5, 'Add the shrimp and pineapple to the pan.'),
(66, 5, 4, 1, 'Add the peanuts to the pan.'),
(67, 5, 5, 10, 'While stir-frying, cook the white rice in a pot of 4 cups of water.'),
(68, 5, 6, 1, 'When everything is done, serve stir-fry ingredients on top of rice.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_recipe_type` (`recipe_type`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipe_instructions`
--
ALTER TABLE `recipe_instructions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
--
-- AUTO_INCREMENT for table `recipe_instructions`
--
ALTER TABLE `recipe_instructions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`);

--
-- Constraints for table `recipe_instructions`
--
ALTER TABLE `recipe_instructions`
  ADD CONSTRAINT `recipe_instructions_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
