-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307:3307
-- Generation Time: Jun 04, 2023 at 05:50 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `repawcity`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

USE repawcity;

DROP TABLE IF EXISTS `appointment`;
DROP TABLE IF EXISTS `news`;
DROP TABLE IF EXISTS `pets`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `appointment_type` varchar(255) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `time_slot` varchar(250) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(250) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `home_address` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` varchar(10) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `details` mediumtext NOT NULL,
  `image` varchar(250) NOT NULL,
  `date_published` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_featured` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`news_id`, `title`, `details`, `image`, `date_published`, `is_featured`, `user_id`) VALUES
(1, '\"Pets Bring Joy and Companionship to Families During Challenging Times\"', 'In the midst of trying times, the presence of pets in households has become a source of comfort and companionship for families across the nation. From the smallest apartments to the largest homes, these faithful companions have brought immeasurable joy and emotional support to their human counterparts, proving that the bond between humans and animals is truly unbreakable.\r\n\r\nDuring the global pandemic and subsequent lockdowns, many individuals found themselves feeling isolated and overwhelmed. However, pet ownership offered solace, acting as a constant source of unconditional love and a reason to smile even during the darkest of days. The soothing presence of a loyal dog or the gentle purring of a contented cat served as a balm for anxious hearts.\r\n\r\nAccording to a recent survey conducted by the National Pet Owners Association, pet adoptions skyrocketed over the past year, with shelters and rescue organizations reporting a surge in demand. Families turned to four-legged companions, recognizing their ability to alleviate stress and loneliness, while providing a welcome distraction from the uncertainties of the world.\r\n\r\nDr. Emily Thompson, a veterinarian with years of experience, spoke about the remarkable impact pets have on their owners. \"Pets offer a sense of routine, purpose, and emotional stability. They provide a listening ear, a comforting presence, and a reason to get out of bed every morning. The human-animal bond is truly transformative.\"\r\n\r\nThe benefits of owning a pet extend beyond mere companionship. Studies have shown that interaction with animals can lower blood pressure, reduce anxiety, and even improve cardiovascular health. The responsibility of caring for a pet also fosters a sense of empathy and teaches children valuable life lessons about compassion, responsibility, and commitment.\r\n\r\nIn addition to traditional pets like dogs and cats, other animals have found their way into the hearts and homes of families. Exotic pets such as reptiles, birds, and small mammals have gained popularity, adding diversity to the range of animals that bring joy to households.\r\n\r\nBut it\'s not just about the positive impact on individuals. Pets have also played a significant role in promoting community engagement. Local dog parks have become meeting grounds for neighbors, fostering connections and building a sense of belonging. Social media platforms are flooded with heartwarming stories and adorable pet pictures, creating virtual communities where pet lovers can share their experiences and seek advice.\r\n\r\nAs we navigate these uncertain times, it is evident that pets have become an invaluable source of love and support for their owners. They remind us to live in the present, cherish the simple pleasures, and find solace in the unspoken language of companionship. In a world that can often feel chaotic, our furry friends offer unwavering loyalty, unwavering comfort, and an unconditional love that knows no bounds.', '6476fc017381c.jpg', '2023-06-04 23:16:15', 1, 0),
(2, '\"Pets: Our Furry Companions Who Brighten Our Lives\"', 'Pets have long held a special place in our hearts, and their significance has only grown stronger over time. These beloved companions bring joy, laughter, and an unmatched sense of companionship to millions of households around the world. Whether it\'s a wagging tail, a purring ball of fur, or a chirpy melody, pets have a magical way of making our lives better in countless ways.\r\n\r\nFrom the moment we bring them home, our pets become a cherished part of the family. Their loyalty is unwavering, and their love is unconditional. Regardless of the challenges we face, pets are always there, ready to offer comfort, support, and an endless supply of cuddles.\r\n\r\nResearch has consistently shown the numerous health benefits associated with pet ownership. Interacting with our furry friends has been found to reduce stress, lower blood pressure, and boost overall well-being. Simply petting a dog or stroking a cat can release oxytocin, the \"feel-good\" hormone, and create a calming effect that melts away the worries of the day.\r\n\r\nMoreover, pets play a vital role in enhancing mental health. They provide companionship to those who may feel lonely or isolated, offering a listening ear without judgment. Many therapy animals are trained to help individuals with conditions such as anxiety, depression, and post-traumatic stress disorder, providing comfort and emotional support during challenging times.\r\n\r\nThe positive influence of pets extends beyond their impact on individuals. Families with pets often experience stronger bonds and a greater sense of unity. Children who grow up with pets learn important life lessons about responsibility, empathy, and compassion. Pets teach us about unconditional love and remind us of the importance of nurturing and caring for others.\r\n\r\nIn recent years, there has been a growing trend in unconventional pet choices. From miniature pigs to hedgehogs and even chickens, people are finding unique ways to embrace the joys of pet ownership. These non-traditional companions add a touch of quirkiness to our lives, proving that the love between humans and animals knows no boundaries.\r\n\r\nThe advent of social media has further amplified the presence of pets in our lives. Platforms like Instagram, Facebook, and TikTok have become a hub for showcasing adorable pet moments, sharing heartwarming stories, and connecting with fellow pet enthusiasts worldwide. The internet has become a virtual pet-friendly community, fostering connections and spreading smiles one viral video at a time.\r\n\r\nAs we navigate the complexities of modern life, pets remain steadfast in their ability to bring us back to the present moment. They remind us to appreciate the simple pleasures, find joy in the little things, and celebrate the bond that exists between humans and animals.\r\n\r\nIn a world filled with challenges, our pets shine as beacons of love, happiness, and unwavering companionship. They remind us that life is better when shared with a furry friend by our side. So, let us cherish the wagging tails, the wet kisses, and the gentle purrs that enrich our lives every day. Our pets truly are a gift that brings immeasurable joy and a constant reminder of the beauty of unconditional love.', '6476fc88e150a.jpg', '2023-06-02 19:56:40', 0, 0),
(3, '\"Pets: The Healing Power of Unconditional Love and Companionship\"', 'In a fast-paced and often chaotic world, pets have emerged as steadfast allies, offering a sanctuary of love, support, and unwavering companionship. Beyond the joy they bring to our lives, pets have a remarkable ability to heal, both mentally and physically, proving that the bond between humans and animals is truly extraordinary.\r\n\r\nWhen it comes to mental health, pets provide solace in times of stress, anxiety, and depression. Their presence alone has a calming effect, soothing frazzled nerves and bringing a sense of peace. The simple act of stroking a pet can release endorphins, those natural mood-boosting chemicals, leading to reduced levels of stress hormones and a greater sense of well-being.\r\n\r\nStudies have shown that pets can have a profound impact on individuals struggling with mental health issues. Therapy animals, such as dogs and cats, are trained to provide support to those dealing with conditions like autism, PTSD, and even Alzheimer\'s disease. Their non-judgmental nature and intuitive understanding of human emotions create a safe space where individuals can find comfort, empathy, and emotional healing.\r\n\r\nMoreover, pets play an instrumental role in encouraging physical activity and overall wellness. Dogs, in particular, require regular exercise, prompting their owners to lead more active lifestyles. Whether it\'s a leisurely walk in the park or a spirited game of fetch, pets motivate us to get moving, leading to improved cardiovascular health, increased stamina, and better weight management.\r\n\r\nIn recent times, the pandemic has highlighted the profound impact of pets on individuals and families. As people spent more time at home, the companionship provided by pets became even more essential. They became steadfast confidants, listening to our worries and fears without judgment. Many families found comfort in the routines established with their pets, offering a sense of normalcy and stability during uncertain times.\r\n\r\nThe significance of pets extends beyond the confines of individual households. Animal-assisted therapy programs have gained recognition in hospitals, rehabilitation centers, and nursing homes. Trained therapy animals visit patients, bringing smiles, laughter, and a renewed sense of hope. The presence of these furry visitors has been shown to reduce pain, improve cognitive function, and accelerate the healing process.\r\n\r\nThe impact of pets on children should not be underestimated either. Growing up with pets teaches kids responsibility, empathy, and kindness. Research suggests that children who have pets develop stronger immune systems, are more socially adept, and have a higher sense of self-esteem. The bonds formed between children and their pets often last a lifetime, providing a source of comfort and support through various stages of life.\r\n\r\nAs we navigate the complexities of the modern world, pets remain constant sources of love and healing. Their presence has a profound effect on our mental, emotional, and physical well-being. They remind us to live in the present, find joy in the simplest of moments, and offer a love that knows no bounds.\r\n\r\nIn a society where connection and compassion are more important than ever, let us celebrate the incredible gift of pets. They bring us together, heal our hearts, and inspire us to be better versions of ourselves. So, cherish the wagging tails, the gentle purrs, and the unconditional love that pets so generously provide. They are the heroes that bring light to our lives and make the world a better place, one paw print at a time.', '6476fe0d82557.jpg', '2023-05-31 15:58:05', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `pets_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `type` varchar(250) NOT NULL,
  `breed` varchar(250) NOT NULL,
  `sex` varchar(250) NOT NULL,
  `weight` varchar(250) NOT NULL,
  `age` varchar(250) NOT NULL,
  `date` date DEFAULT NULL,
  `about` text NOT NULL,
  `image` varchar(250) NOT NULL,
  `is_featured` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`pets_id`, `name`, `type`, `breed`, `sex`, `weight`, `age`, `date`, `about`, `image`, `is_featured`, `user_id`) VALUES
(1, 'Alas', 'Dog', 'Labrador Retriever', 'Male', '5-10 lbs', '6 months to 5 years', '2023-05-03', 'Alas is a playful and energetic Labrador Retriever who loves going on long walks and playing fetch. He\'s looking for an active family who can give him plenty of exercise and attention.', '646ae881bbbf9.jpg', '0', 1),
(2, 'Cheryl', 'Dog', 'Shih Tzu', 'Female', 'Less than 5 lbs', 'Less than 6 months', '2023-05-10', 'Cheryl is a loyal and intelligent Shih Tzu who would make a great companion and protector. She enjoys learning new tricks and is eager to please. She\'s looking for a loving home with experienced dog owners.', '646ae9b514fc8.jpg', '0', 1),
(3, 'Chia', 'Dog', 'Chihuahua', 'Female', 'Less than 5 lbs', 'Less than 6 months', '2023-05-11', 'Chia is a tiny and adorable Chihuahua with a big personality. She\'s friendly, affectionate, and loves cuddling on laps. Chia is looking for a loving family who can shower her with lots of attention.', '646ae9f36b768.jpg', '0', 1),
(4, 'Colcol', 'Dog', 'Pomeranian', 'Female', 'Less than 5 lbs', '6 months to 5 years', '2023-05-01', 'Colcol is a highly intelligent and active Pomeranian who excels in agility and obedience training. He needs a home with a large backyard and an owner who can provide mental stimulation and regular exercise.', '646aea3613c7c.jpg', '0', 1),
(5, 'Gia', 'Dog', 'Golden Retriever', 'Female', '10-20 lbs', '5 to 10 years', '2023-04-04', 'Gia is a sweet and gentle Golden Retriever who loves everyone she meets. She enjoys playing fetch, going for swims, and snuggling up with her humans. Gia is looking for a loving family to shower her with affection.', '646aea625b1a5.jpg', '0', 1),
(6, 'Haji', 'Dog', 'Dalmatian', 'Male', '10-20 lbs', '6 months to 5 years', '2023-05-02', 'Haji is a beautiful and adventurous Dalmatian with striking blue eyes. He\'s an active and independent dog who enjoys long hikes and exploring the outdoors. Haji is looking for an experienced owner who understands the needs of his breed.', '646aea9926207.jpg', '0', 1),
(7, 'Kio', 'Dog', 'Shih Tzu', 'Male', '10-20 lbs', '6 months to 5 years', '2023-05-10', 'Kio is a friendly and gentle Shih Tzu who loves being pampered. He enjoys lounging around the house and getting lots of belly rubs. Kio is looking for a calm and loving home where he can be the center of attention.', '646aeb529b730.jpg', '0', 1),
(8, 'Rovie', 'Dog', 'Boxer', 'Male', '20-50 lbs', '6 months to 5 years', '2023-05-09', 'Rovie is a playful and energetic Boxer who always has a wagging tail. He loves playing with toys and going for long runs. Rovie is looking for an active family who can provide him with lots of exercise and playtime.', '646aeb784dd2d.jpg', '0', 1),
(9, 'Tenten', 'Dog', 'Poodle', 'Male', '10-20 lbs', '6 months to 5 years', '2023-05-17', 'Tenten is a stylish and intelligent Poodle who enjoys learning new tricks and showing off his fancy haircuts. He\'s a friendly and sociable dog who gets along well with children and other pets.', '646aec214669c.jpg', '0', 1),
(10, 'Una', 'Dog', 'Beagle', 'Female', '5-10 lbs', '5 to 10 years', '2023-05-09', 'Una is an adorable and curious Beagle who loves following her nose. She enjoys sniffing around and exploring new scents. Una is looking for an owner who can provide her with plenty of mental stimulation and scent-based activities.', '646aec4d43c77.jpg', '4', 1),
(11, 'Xuan', 'Dog', 'Bulldog', 'Female', '10-20 lbs', 'Less than 6 months', '2023-05-11', 'Xuan is a charming and affectionate French Bulldog with a laid-back personality. He enjoys snuggling on the couch and getting belly rubs. Xuan is looking for a loving family who can give him lots of attention and care.', '646aece249f4c.jpg', '3', 1),
(12, 'Yuchi', 'Dog', 'German Shepherd', 'Male', '5-10 lbs', 'Less than 6 months', '2023-05-12', 'Yuchi is an intelligent and active Australian Shepherd who loves having a job to do. He excels in agility and obedience training and enjoys being challenged. Yuchi is looking for an experienced owner who can provide him with mental stimulation and regular exercise.', '646aef5e7c3de.jpg', '2', 1),
(13, 'Cookies', 'Cat', 'British Shorthair', 'Male', 'Less than 5 lbs', '6 months to 5 years', '2023-05-08', 'Cookies is a playful and mischievous Domestic Shorthair who loves chasing toy mice and climbing on cat trees. She\'s looking for a home where she can explore and entertain her humans with her silly antics.', '646aef92c6281.jpg', '1', 1),
(14, 'Daphne', 'Cat', 'Siamese', 'Female', '10-20 lbs', '6 months to 5 years', '2023-05-04', 'Daphne is an elegant and talkative Siamese cat who loves engaging in conversations with her humans. She enjoys being the center of attention and would thrive in a home where she can receive lots of love and affection.', '646aefbb27df2.jpg', '0', 1),
(15, 'Madam', 'Cat', 'Maine Coon', 'Female', '5-10 lbs', '6 months to 5 years', '2023-05-01', 'Madam is a majestic and gentle Maine Coon with a luxurious coat. She\'s a calm and affectionate cat who enjoys being brushed and lounging in sunny spots. Madam is looking for a peaceful and loving home.', '646aefe7498dd.jpg', '0', 1),
(16, 'Maya', 'Cat', 'Ragdoll', 'Female', 'over 50 lbs', 'over 10 years', '2023-05-09', 'Maya is a sweet and docile Ragdoll cat who loves being held and cuddled. She\'s a gentle and easygoing companion who would fit well in a quiet and loving home environment.', '646af05049e4c.jpg', '0', 1),
(17, 'Mitoy', 'Cat', 'Scottish Fold', 'Male', '20-50 lbs', '5 to 10 years', '2023-04-08', 'Mitoy is an adorable and curious Scottish Fold with unique folded ears. He\'s playful and enjoys interactive toys and games. Mitoy is looking for a home where he can explore and satisfy his natural curiosity.', '646af0917bede.jpg', '0', 1),
(18, 'Monami', 'Cat', 'Persian', 'Female', 'Less than 5 lbs', '6 months to 5 years', '2023-05-10', 'Monami is a regal and elegant Persian cat with a luxurious coat. She enjoys a calm and serene environment and loves lounging in her favorite spots. Monami is looking for a patient and dedicated owner who can provide her with the grooming and care she needs.', '646af0c07149d.jpg', '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(250) NOT NULL,
  `lname` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fname`, `lname`, `email`, `password`, `user_type`, `created_at`) VALUES
(1, 'Aian Louise', 'Alfaro', 'admin@gmail.com', '1234', '1', '2023-05-31 07:16:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`pets_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `pets_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
