/*
SQLyog Ultimate v8.55 
MySQL - 5.5.34-0ubuntu0.12.04.1 : Database - interview_app
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`interview_app` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `interview_app`;

/*Table structure for table `email_template` */

DROP TABLE IF EXISTS `email_template`;

CREATE TABLE `email_template` (
  `template_id` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `email_template` */

/*Table structure for table `interview_question_log` */

DROP TABLE IF EXISTS `interview_question_log`;

CREATE TABLE `interview_question_log` (
  `interview_question_id` int(20) NOT NULL AUTO_INCREMENT,
  `question_data` text,
  `answers_data` text,
  `created_at` datetime DEFAULT NULL,
  `int_scheduler_id` int(11) DEFAULT NULL,
  `interviewer_data` text,
  PRIMARY KEY (`interview_question_id`),
  KEY `FK_interview_question_log` (`int_scheduler_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `interview_question_log` */

/*Table structure for table `tbl_admin` */

DROP TABLE IF EXISTS `tbl_admin`;

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role_id` int(2) DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL COMMENT '0=>active, 1=>inactive, 2=>block',
  `is_delete` varchar(2) DEFAULT '0' COMMENT '0=>not deleted 1=>Deleted',
  `login_failure_count` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_admin` (`role_id`),
  CONSTRAINT `FK_tbl_admin` FOREIGN KEY (`role_id`) REFERENCES `tbl_admin_role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_admin` */

/*Table structure for table `tbl_admin_role` */

DROP TABLE IF EXISTS `tbl_admin_role`;

CREATE TABLE `tbl_admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_role_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_admin_role` */

/*Table structure for table `tbl_candidate` */

DROP TABLE IF EXISTS `tbl_candidate`;

CREATE TABLE `tbl_candidate` (
  `candidate_id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `int_id` int(11) DEFAULT NULL,
  `status` int(3) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`candidate_id`),
  KEY `FK_tbl_candidate` (`int_id`),
  CONSTRAINT `FK_tbl_candidate` FOREIGN KEY (`int_id`) REFERENCES `tbl_interview` (`int_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_candidate` */

/*Table structure for table `tbl_category` */

DROP TABLE IF EXISTS `tbl_category`;

CREATE TABLE `tbl_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` int(2) DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_category` */

insert  into `tbl_category`(`cat_id`,`name`,`parent_id`,`status`) values (1,'HR',NULL,1),(2,'Finance',NULL,1),(3,'Sr. HR',1,1);

/*Table structure for table `tbl_interview` */

DROP TABLE IF EXISTS `tbl_interview`;

CREATE TABLE `tbl_interview` (
  `int_id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `interview_duration` int(5) DEFAULT NULL,
  `position_aplied_for` int(11) DEFAULT NULL COMMENT 'cat_d = Dipartment + Degination/Role + Techonlogies',
  `exprience` int(11) DEFAULT NULL COMMENT 'exprince in months ',
  `recuritor_id` int(11) DEFAULT NULL COMMENT 'user_id ',
  `number_of_question` int(3) DEFAULT NULL,
  `status` int(2) DEFAULT '0' COMMENT '0= inactive , 1 = active, 3 = Cancled',
  `s_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`int_id`),
  KEY `FK_tbl_interview` (`s_id`),
  CONSTRAINT `FK_tbl_interview` FOREIGN KEY (`s_id`) REFERENCES `tbl_interview_set` (`s_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_interview` */

/*Table structure for table `tbl_interview_question_relation` */

DROP TABLE IF EXISTS `tbl_interview_question_relation`;

CREATE TABLE `tbl_interview_question_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL,
  `int_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_interview_question_relation` (`int_id`),
  CONSTRAINT `FK_tbl_interview_question_relation` FOREIGN KEY (`int_id`) REFERENCES `tbl_interview` (`int_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_interview_question_relation` */

/*Table structure for table `tbl_interview_questions` */

DROP TABLE IF EXISTS `tbl_interview_questions`;

CREATE TABLE `tbl_interview_questions` (
  `int_question_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `option_ids` varchar(255) DEFAULT NULL,
  `is_true` int(3) DEFAULT NULL,
  `int_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `status` int(3) DEFAULT NULL,
  PRIMARY KEY (`int_question_id`),
  KEY `FK_tbl_interview_questions` (`int_id`),
  CONSTRAINT `FK_tbl_interview_questions` FOREIGN KEY (`int_id`) REFERENCES `tbl_interview` (`int_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_interview_questions` */

/*Table structure for table `tbl_interview_results` */

DROP TABLE IF EXISTS `tbl_interview_results`;

CREATE TABLE `tbl_interview_results` (
  `int_result_id` int(11) NOT NULL AUTO_INCREMENT,
  `int_id` int(11) DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `comments` tinytext,
  `exprience` int(2) DEFAULT NULL COMMENT 'number of months.',
  `identity_type` int(3) DEFAULT NULL COMMENT '1= PANCARD, 2 =DRIVING LISCENSE, 3 = VOTER ID CARD, 4 = ADHAR CARD, 5 = PASSPORT.',
  `identity_number` varchar(255) DEFAULT NULL,
  `is_completed` int(1) DEFAULT NULL,
  `search_tags` text,
  PRIMARY KEY (`int_result_id`),
  KEY `FK_tbl_interview_report` (`int_id`),
  CONSTRAINT `FK_tbl_interview_results` FOREIGN KEY (`int_id`) REFERENCES `tbl_interview` (`int_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_interview_results` */

/*Table structure for table `tbl_interview_set` */

DROP TABLE IF EXISTS `tbl_interview_set`;

CREATE TABLE `tbl_interview_set` (
  `s_id` int(11) NOT NULL AUTO_INCREMENT,
  `set_name` varchar(255) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `recuritor_id` int(11) DEFAULT NULL,
  `number_of_question` int(11) DEFAULT NULL,
  `status` int(2) DEFAULT NULL COMMENT '0= Not Approved 1= Approved.',
  PRIMARY KEY (`s_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_interview_set` */

/*Table structure for table `tbl_interview_set_question_relation` */

DROP TABLE IF EXISTS `tbl_interview_set_question_relation`;

CREATE TABLE `tbl_interview_set_question_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_interview_set_question_relation` (`s_id`),
  CONSTRAINT `FK_tbl_interview_set_question_relation` FOREIGN KEY (`s_id`) REFERENCES `tbl_interview_set` (`s_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_interview_set_question_relation` */

/*Table structure for table `tbl_login_history` */

DROP TABLE IF EXISTS `tbl_login_history`;

CREATE TABLE `tbl_login_history` (
  `id` int(225) NOT NULL AUTO_INCREMENT,
  `uid` int(255) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `ip` varchar(40) DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL COMMENT '0=>Success, 1=>fail',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_login_history` (`uid`),
  CONSTRAINT `FK_tbl_login_history` FOREIGN KEY (`uid`) REFERENCES `tbl_admin` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_login_history` */

/*Table structure for table `tbl_option` */

DROP TABLE IF EXISTS `tbl_option`;

CREATE TABLE `tbl_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_title` varchar(255) DEFAULT NULL,
  `is_true` int(2) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`option_id`),
  KEY `FK_tbl_option` (`question_id`),
  CONSTRAINT `FK_tbl_option` FOREIGN KEY (`question_id`) REFERENCES `tbl_question` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_option` */

/*Table structure for table `tbl_permission` */

DROP TABLE IF EXISTS `tbl_permission`;

CREATE TABLE `tbl_permission` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_permission` */

/*Table structure for table `tbl_question` */

DROP TABLE IF EXISTS `tbl_question`;

CREATE TABLE `tbl_question` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text,
  `question_level` int(3) DEFAULT NULL,
  `question_type` int(3) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`question_id`),
  KEY `FK_tbl_question` (`topic_id`),
  CONSTRAINT `FK_tbl_question` FOREIGN KEY (`topic_id`) REFERENCES `tbl_topic` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_question` */

/*Table structure for table `tbl_role_permission_relation` */

DROP TABLE IF EXISTS `tbl_role_permission_relation`;

CREATE TABLE `tbl_role_permission_relation` (
  `role_id` int(11) DEFAULT NULL,
  `permission_id` int(11) DEFAULT NULL,
  KEY `FK_tbl_role_permission_relation` (`role_id`),
  KEY `FK_tbl_role_permission_relation_id` (`permission_id`),
  CONSTRAINT `FK_tbl_role_permission_relation` FOREIGN KEY (`role_id`) REFERENCES `tbl_admin_role` (`id`),
  CONSTRAINT `FK_tbl_role_permission_relation_id` FOREIGN KEY (`permission_id`) REFERENCES `tbl_permission` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_role_permission_relation` */

/*Table structure for table `tbl_technology` */

DROP TABLE IF EXISTS `tbl_technology`;

CREATE TABLE `tbl_technology` (
  `id` int(225) NOT NULL AUTO_INCREMENT,
  `technology` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_technology` */

/*Table structure for table `tbl_technology_category_relation` */

DROP TABLE IF EXISTS `tbl_technology_category_relation`;

CREATE TABLE `tbl_technology_category_relation` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `technology_id` int(255) DEFAULT NULL,
  `cat_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_technology_category_relation` (`technology_id`),
  KEY `FK_tbl_technology_category_relation_1` (`cat_id`),
  CONSTRAINT `FK_tbl_technology_category_relation` FOREIGN KEY (`technology_id`) REFERENCES `tbl_technology` (`id`),
  CONSTRAINT `FK_tbl_technology_category_relation_1` FOREIGN KEY (`cat_id`) REFERENCES `tbl_category` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_technology_category_relation` */

/*Table structure for table `tbl_topic` */

DROP TABLE IF EXISTS `tbl_topic`;

CREATE TABLE `tbl_topic` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_name` varchar(255) DEFAULT NULL,
  `topic_description` text,
  `topic_status` int(1) DEFAULT NULL,
  PRIMARY KEY (`topic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_topic` */

insert  into `tbl_topic`(`topic_id`,`topic_name`,`topic_description`,`topic_status`) values (5,'Magento','',1),(6,'Drupal','',1),(7,'HTML 5','',1),(8,'Drupal','',1),(9,'alok',NULL,1);

/*Table structure for table `tbl_topic_category_relation` */

DROP TABLE IF EXISTS `tbl_topic_category_relation`;

CREATE TABLE `tbl_topic_category_relation` (
  `topic_category_relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) DEFAULT NULL,
  `tech_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`topic_category_relation_id`),
  KEY `FK_tbl_topic_category_relation` (`topic_id`),
  KEY `FK_tbl_topic_category_relation_id` (`tech_id`),
  CONSTRAINT `FK_tbl_topic_category_relation` FOREIGN KEY (`topic_id`) REFERENCES `tbl_topic` (`topic_id`),
  CONSTRAINT `FK_tbl_topic_category_relation_1` FOREIGN KEY (`tech_id`) REFERENCES `tbl_technology` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_topic_category_relation` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`user_id`,`username`,`email`,`display_name`,`password`,`state`) values (1,NULL,'aloknarwaria@gmail.com',NULL,'$2y$14$3ctmYf2LtFebQV1puAeLI.ho3jjwKmh2i8oibFwBdSl2kZIw0g3OS',NULL),(2,NULL,'alok1606@gmail.com',NULL,'$2y$14$yzyVjOwGMyf3iIcgJaGt8u/J8eVNdVxt9OnMrcydivv5VlLl5AGd2',NULL),(3,NULL,'sanjay.shah@stigasoft.com',NULL,'$2y$14$mhCuogXURYW7oEsoeu.wKO7Ukv0OAgOgYcwyoAIUlYxy3kU3UFMru',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
