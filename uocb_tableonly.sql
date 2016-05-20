/* server-sided */
CREATE TABLE result (
	result_id integer(50) AUTO_INCREMENT,
    date date,
    time decimal(50,4),
    ranked boolean,
    flagged boolean,
    student_name varchar(50),
    student_gender varchar(1),
    student_grade SMALLINT(10),
    school_name varchar(50),
    PRIMARY KEY (result_id)
    );

