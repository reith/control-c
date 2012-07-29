DROP PROCEDURE IF EXISTS add_new_announcement;
DROP PROCEDURE IF EXISTS add_new_course;
DROP PROCEDURE IF EXISTS add_new_user;
DROP PROCEDURE IF EXISTS get_announcement;
DROP PROCEDURE IF EXISTS get_course_data;
DROP PROCEDURE IF EXISTS get_detailed_log;
DROP PROCEDURE IF EXISTS get_exercise_seri_data;
DROP PROCEDURE IF EXISTS get_exercise_seri_results;
DROP PROCEDURE IF EXISTS get_nof_students_in_wl;
DROP PROCEDURE IF EXISTS get_seri_s_exercises;
DROP PROCEDURE IF EXISTS get_student_average_grades;
DROP PROCEDURE IF EXISTS get_student_courses;
DROP PROCEDURE IF EXISTS get_student_exercises;
DROP PROCEDURE IF EXISTS get_teacher_courses;
DROP PROCEDURE IF EXISTS get_top_announcements;
DROP PROCEDURE IF EXISTS get_top_students;
DROP PROCEDURE IF EXISTS get_user_data;
DROP PROCEDURE IF EXISTS un_lock_course;
DROP PROCEDURE IF EXISTS un_verify_course_membership;
DROP PROCEDURE IF EXISTS login;
DROP PROCEDURE IF EXISTS request_course_membership;

DROP PROCEDURE IF EXISTS test;

DROP FUNCTION IF EXISTS user_exists;
DELIMITER //

CREATE PROCEDURE add_new_announcement(IN title VARCHAR(50), IN body TEXT)
	BEGIN
		INSERT INTO `announcement` VALUES (NULL, title, body, NOW() );
		SELECT (LAST_INSERT_ID());
	END //

CREATE PROCEDURE add_new_course( IN u_id INT, IN c_name VARCHAR(40), IN c_lang ENUM('C','CPP','Java','Pascal','none'), IN c_year INT(4) )
	BEGIN
		IF EXISTS( SELECT `id` FROM `teacher` WHERE `id` = u_id LIMIT 1 ) THEN
			INSERT INTO `course` (`id`, `name`, `teacher`, `year`, `language`) VALUES (NULL, c_name, u_id, c_year, c_lang );
			SELECT( LAST_INSERT_ID() );
		END IF;
	END //

CREATE PROCEDURE add_new_user(IN un CHAR(32), IN fn CHAR(64), IN ln CHAR(64), IN em CHAR(255), IN pw CHAR(33), IN prv SET('s', 'c', 't', 'ca', 'a'), IN sn INT, IN rkey CHAR(33))
	BEGIN
		DECLARE result INT DEFAULT 0;
		DECLARE last_uid INT;
		DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
		DECLARE EXIT HANDLER FOR SQLWARNING ROLLBACK;
		
		START TRANSACTION;
		INSERT INTO `user` (`username`, `firstName`, `lastName`, `email`, `password`, `privilege`, `lock`) VALUES (un, fn, ln, em, pw, prv, 0);
		SET last_uid:=LAST_INSERT_ID();
		IF FIND_IN_SET('s', prv) > 0 THEN INSERT INTO `student` (`id`, `number`) VALUES (last_uid, sn ); END IF;
		IF FIND_IN_SET('t', prv) > 0 THEN INSERT INTO `teacher` (`id`) VALUES (last_uid); END IF;
		INSERT INTO `confirmation_waitinglist` (`user`, `key`, `type`) VALUES (last_uid, rkey, 's');
		COMMIT;
		SET result:=1;
		
		SELECT result, last_uid;
	END //

CREATE PROCEDURE login( IN un CHAR(32), IN pass CHAR(33), OUT ret INT, OUT prev SET('s', 'c', 't', 'ca', 'a') )
	MAIN: BEGIN
		DECLARE u_locked BOOLEAN;
		DECLARE u_id INT;
		SET ret := 0;

		SELECT `id`, `lock`, `privilege` INTO u_id, u_locked, prev FROM `user` WHERE `username` = un AND `password` = pass LIMIT 1;
		IF u_id IS NULL THEN LEAVE MAIN; END IF;
		IF u_locked = 1 THEN SET ret := -1; LEAVE MAIN; END IF;
		SET ret := u_id;
		UPDATE `user` SET `lastLogin` = NOW() WHERE `id` = u_id LIMIT 1;
	END //

CREATE PROCEDURE get_announcement( IN a_id INT )
	BEGIN
		SELECT * FROM `announcement` WHERE `id` = a_id LIMIT 1;
	END //

CREATE PROCEDURE get_course_data( IN c_id INT )
	BEGIN
		SELECT `c`.*, CONCAT(`t`.firstName, ' ', `t`.lastName) AS `teacherName`, `t`.id AS `teacher`, COUNT(`student`) AS `sc`, AVG(`gradeAverage`) AS `sag`, MAX(`gradeAverage`) AS `smg`
		FROM `course` as `c`, `user` as `t`, `membership` AS `m`
		WHERE `c`.id = c_id AND `t`.id = `c`.teacher AND `m`.`course` = `c`.id AND `m`.`confirm` = 'j' LIMIT 1;
	END //
	
CREATE PROCEDURE get_detailed_log( IN e_id INT, IN s_id INT, IN is_seri BOOLEAN )
	BEGIN
		IF is_seri THEN
			SELECT * FROM `student_upload` WHERE `student` = s_id AND `seri` = e_id LIMIT 1;
		ELSE
			SET @ertable = CONCAT('exercise_result_', e_id);
			SET @sql_com = CONCAT('SELECT `e`.seri, `e`.number, `result`.* FROM ', @ertable, ' as `result`, `exercise` as `e` WHERE `e`.id = ? AND `result`.`student` = ? LIMIT 1');
			SET @e_id = e_id;
			SET @s_id = s_id;
			PREPARE stmt FROM @sql_com;
			EXECUTE stmt USING @e_id, @s_id;
			DEALLOCATE PREPARE stmt;
		END IF;
	END //

CREATE PROCEDURE get_exercise_seri_data( IN seri_id INT )
	BEGIN
		SELECT `c`.`name`, `c`.`id` AS `courseId`, `es`.`createDate`, `es`.`deadlineDate`, `es`.`correctionDate`, `es`.`seri` AS `seri`, `es`.`id` AS `seriId`, `t`.`id` AS `teacherId` ,
  			`es`.`comment`, `es`.`wage`, NOW()>`es`.`deadlineDate` AS `expired`, `es`.`exerciseCount`, NOW()>`es`.`correctionDate` AS `checked`, CONCAT(`t`.`firstName`, ' ', `t`.`lastName`) AS `teacherName`
  		FROM `exercise_seri` AS `es`, `course` AS `c`, `user` AS `t`
  		WHERE `es`.`id` = seri_id AND `c`.`id`=`es`.`course` AND `t`.`id`=`c`.`teacher`;
	END //
	
CREATE PROCEDURE get_nof_students_in_wl( IN t_id INT, IN c_id INT )
	BEGIN
		SELECT COUNT(`id`) FROM `membership` as `m` WHERE `m`.confirm = 'w' AND
		EXISTS (SELECT * FROM `course` as `c` WHERE `c`.teacher=t_id AND `m`.course=`c`.id AND IF (c_id IS NULL, TRUE, `c`.id = c_id) );
	END //

CREATE PROCEDURE get_seri_s_exercises( IN seri_id INT )
	BEGIN
		SELECT `id`, `title`, `wage`, `number` FROM `exercise` WHERE `seri` = seri_id ORDER BY `number` ASC;
	END //
	
CREATE PROCEDURE get_student_average_grades( IN u_id INT )
	BEGIN
		SELECT `m`.`gradeAverage`, `c`.name, `c`.id FROM `membership` AS `m`, `course` AS `c`
		WHERE `m`.student=u_id AND `m`.confirm = 'j' AND `m`.course=`c`.id AND `m`.sentCount>0;
	END //

CREATE PROCEDURE get_student_courses( IN s_id INT )
	BEGIN
		SELECT `m`.course, `c`.name, `m`.`gradeAverage` FROM `membership` as `m`, `course` as `c` WHERE `c`.id=`m`.course AND `m`.`student` = s_id AND `confirm`='j';
	END //

CREATE PROCEDURE get_exercise_seri_results( IN seri_id INT )
	BEGIN
		SELECT COUNT(`id`), MAX(`grade`), AVG(`grade`) FROM `student_upload` WHERE `seri` = seri_id;
	END //
CREATE PROCEDURE get_student_exercises( IN s_id INT, IN which_course CHAR(3), IN expired CHAR(3), IN solved CHAR(3), IN view_seri BOOLEAN, IN sorted_by CHAR(10), IN sorted_asc BOOLEAN, IN c_id INT  )
	BEGIN
		SELECT
			`es`.seri, `c`.name, `es`.createDate, `es`.deadlineDate, `es`.correctionDate,
			IF (NOW() >`es`.deadlineDate, 1, 0) as `expire`,
			IF (NOW() >`es`.correctionDate, 1, 0) as `check` ,
			`es`.`lock`, `es`.id AS seriID,
			`e`.id AS exID, `e`.number,
			`su`.grade as `seriGrade`, `su`.id AS suID, `su`.date as `sentDate`,
			`m`.confirm, `c`.id AS `cid` 
		FROM `exercise` as `e` LEFT JOIN `student_upload` as `su` ON `su`.student = s_id AND `e`.seri=`su`.seri,
			`course` as `c` LEFT JOIN `membership` as `m` ON `m`.student = s_id AND `m`.course=`c`.id, `exercise_seri` as `es`
		WHERE `es`.id = `e`.seri AND `es`.course = `c`.id AND 
			( CASE which_course WHEN 'own' THEN `m`.confirm = 'j' WHEN 'all' THEN TRUE ELSE `c`.id = c_id END) AND
			( CASE expired WHEN 'yes' THEN NOW() > `es`.deadlineDate WHEN 'no' THEN NOW() < `es`.deadlineDate ELSE TRUE END) AND
			( CASE solved WHEN 'yes' THEN `su`.id IS NOT NULL WHEN 'no' THEN `su`.id IS NULL ELSE TRUE END)
		GROUP BY
			IF ( view_seri, `es`.id, `e`.id)
		ORDER BY
			( CASE sorted_by WHEN 'cName' THEN `c`.`name` WHEN 'eSeri' THEN `es`.`seri` WHEN 'cDate' THEN `es`.createDate WHEN 'dDate' THEN `es`.deadlineDate ELSE expire END)
			ASC;
	END //

CREATE PROCEDURE get_teacher_courses( IN t_id INT )
	BEGIN
		SELECT `id`, `name` FROM `course` WHERE teacher = t_id;
	END //

CREATE PROCEDURE get_top_announcements( IN n INT(3) )
	BEGIN
		SET SQL_SELECT_LIMIT = n;
		SELECT `id`, `title` FROM `announcement` ORDER BY `date` DESC;
		SET SQL_SELECT_LIMIT = DEFAULT;
	END //
	
CREATE PROCEDURE get_top_students( IN c_id INT )
	BEGIN
		SELECT `m`.gradeAverage, `s`.id, `s`.username FROM `user` AS `s`, `membership` AS `m`
		WHERE `m`.course = c_id AND `m`.`student` = `s`.id ORDER BY `m`.gradeAverage DESC LIMIT 0, 3;
	END //
	
CREATE PROCEDURE get_user_data( IN u_id INT )
	BEGIN
		SELECT `username`,  CONCAT(`firstName`, ' ', `lastName`), `email`, `lastlogin`, `message`, `privilege` FROM `user` WHERE `id` = u_id LIMIT 1;
	END //



CREATE PROCEDURE request_course_membership(IN u_id INT, in c_id INT)
	BEGIN
		DECLARE cur_stat ENUM('j', 'b', 'w');
		SELECT `confirm` INTO cur_stat FROM `membership` WHERE `student` = u_id AND `course` = c_id LIMIT 1;
		IF cur_stat IS NULL THEN
			INSERT INTO `membership` ( `id` , `student` , `course`, `confirm` ) VALUES (NULL, u_id, c_id, 'w' );
		END IF;
		SELECT cur_stat;
	END //

CREATE PROCEDURE un_lock_course( IN c_id INT, IN u_id INT, IN locking BOOLEAN )
	BEGIN
		UPDATE `course` SET `lock`= locking WHERE `id` = c_id AND `teacher` = u_id LIMIT 1;
		SELECT (ROW_COUNT());
	END //
	
CREATE PROCEDURE un_verify_course_membership( IN m_id INT, IN t_id INT, IN action ENUM('a', 'b', 'd') )
	BEGIN
		-- action list: a -> accept, b -> ban (reject and never annoyed bye this student), d -> delete (temorary reject)
		SET SQL_SELECT_LIMIT = 1;
		IF EXISTS (SELECT `c`.`id` FROM `course` AS `c`, `membership` AS `m` WHERE `m`.`course` = `c`.`id` AND `m`.`id` = m_id AND `c`.`teacher` = t_id ) THEN
			IF action = 'd' THEN
				DELETE FROM `membership` WHERE `id` = m_id;
			ELSEIF action = 'b' THEN
				UPDATE `membership` SET `confirm` = 'b' WHERE `id` = m_id;
			ELSE
				UPDATE `membership` SET `confirm` = 'j' WHERE `id` = m_id;
			END IF;
		END IF;
		SET SQL_SELECT_LIMIT = DEFAULT;
	END //

CREATE FUNCTION user_exists( un CHAR(32), em CHAR(33) ) RETURNS BOOLEAN
	DETERMINISTIC
-- 	READS SQL DATA
	BEGIN
		DECLARE u_id INT;
		SELECT `id` INTO u_id FROM `user` WHERE `username` = un OR `email` = em LIMIT 1;
		IF ( u_id IS NOT NULL ) THEN RETURN(TRUE); ELSE RETURN(FALSE); END IF;
	END //

CREATE PROCEDURE test()
	BEGIN
		SELECT `id`, `firstName` from `user`;
	END //

DELIMITER ;

