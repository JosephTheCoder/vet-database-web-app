/* functions, triggers and procedures*/

/*1 .Write a trigger to update the age of the animal according to the birth date
and the current date. The trigger should fire whenever a NEW consult for
the animal is inserted into the database.*/

DELIMITER $$

CREATE TRIGGER update_age BEFORE INSERT ON consult
FOR EACH ROW
BEGIN
	UPDATE animal
	SET animal.age = TIMESTAMPDIFF(year, animal.birth_year, curdate())
	WHERE (animal.name = NEW.name AND animal.VAT = NEW.VAT_owner);
END$$

DELIMITER ;

/*2 .Write triggers to ensure that an individual that is a veterinary doctor
cannot simultaneously be an assistant in the hospital. */

-- WE ASSUME THAT THIS TRIGGERS ARE UP BEFORE ANY INSERTION
-- (WE DON'T CHECK ALREADY STORED DATA)

DELIMITER $$

CREATE TRIGGER ensure_insert_vet BEFORE INSERT ON veterinary
FOR EACH ROW
BEGIN
	IF (EXISTS(SELECT 1 FROM assistant WHERE assistant.VAT = NEW.VAT)) THEN
	    SIGNAL SQLSTATE VALUE '45000' SET MESSAGE_TEXT = 'INSERT failed due to being an assistant already';
	END IF;
END$$

CREATE TRIGGER ensure_insert_ass BEFORE INSERT ON assistant
FOR EACH ROW
BEGIN
	IF (EXISTS(SELECT 1 FROM veterinary WHERE veterinary.VAT = NEW.VAT)) THEN
	    SIGNAL SQLSTATE VALUE '45000' SET MESSAGE_TEXT = 'INSERT failed due to being an veterinary already';
	END IF;
END$$

CREATE TRIGGER ensure_update_vet BEFORE UPDATE ON veterinary
FOR EACH ROW
BEGIN
	IF (EXISTS(SELECT 1 FROM assistant WHERE assistant.VAT = NEW.VAT)) THEN
	    SIGNAL SQLSTATE VALUE '45000' SET MESSAGE_TEXT = 'UPDATE failed due to being an assistant already';
	END IF;
END$$

CREATE TRIGGER ensure_update_ass BEFORE UPDATE ON assistant
FOR EACH ROW
BEGIN
	IF (EXISTS(SELECT 1 FROM veterinary WHERE veterinary.VAT = NEW.VAT)) THEN
	    SIGNAL SQLSTATE VALUE '45000' SET MESSAGE_TEXT = 'UPDATE failed due to being an veterinary already';
	END IF;
END$$

DELIMITER ;

/*3 .Write triggers to ensure that different individuals cannot have the same
phone number. */

-- ONCE AGAIN ASSUMPTION: NO DATA BEFORE TRIGGER WAS SET
DELIMITER $$

CREATE TRIGGER ensure_insert_phone BEFORE INSERT ON phone_number
FOR EACH ROW
BEGIN
	IF (EXISTS(SELECT 1 FROM phone_number WHERE phone_number.phone = NEW.phone AND phone_number.VAT != NEW.VAT)) THEN
	    SIGNAL SQLSTATE VALUE '45000' SET MESSAGE_TEXT = 'INSERT failed due to being an already existing phone';
	END IF;
END$$

CREATE TRIGGER ensure_update_phone BEFORE UPDATE ON phone_number
FOR EACH ROW
BEGIN
	IF (EXISTS(SELECT 1 FROM phone_number WHERE phone_number.phone = NEW.phone AND phone_number.VAT != NEW.VAT)) THEN
	    SIGNAL SQLSTATE VALUE '45000' SET MESSAGE_TEXT = 'UPDATE failed due to being an already existing phone';
	END IF;
END$$

DELIMITER ;

/*4. Write a function to compute to total number of consults for a given animal,
within a given year (both provided as parameters).*/

DELIMITER $$

CREATE FUNCTION count_consults(animal_name varchar(255), year int)
RETURNS integer
BEGIN
	DECLARE c_count integer;
	SELECT COUNT(consult.name) into c_count
	FROM consult
	WHERE consult.name = animal_name
	AND YEAR(consult.date_timestamp) = year;
	RETURN c_count;	
END $$

DELIMITER ;

/*5 .Write a stored procedure for changing the reference values associated to
all indicators that are measured in milligrams. For all these indicators,
the reporting units should be changed from milligrams to centigrams, and
the reference values (as well as the measured values for all procedures
considering this indicator) should be changed by dividing them by 10.*/

DELIMITER $$

CREATE PROCEDURE change_mg2cg()
BEGIN
	UPDATE indicator, produced_indicator
	SET produced_indicator.value = produced_indicator.value*0.1
	WHERE (indicator.units='mg' AND indicator.name = produced_indicator.indicator_name);
	
	UPDATE indicator
	SET indicator.units = 'cg',
	indicator.reference_value = indicator.reference_value*0.1
	WHERE indicator.units = 'mg';
END $$

DELIMITER ;
