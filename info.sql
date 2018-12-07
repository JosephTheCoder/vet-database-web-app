INSERT INTO person(VAT, name, address_street, address_city, address_zip) 
VALUES 
('1234','Johnson Hanibal','Cosemes street','Land', '133-13124');


INSERT INTO client(VAT)
VALUES 
('1234');

INSERT INTO animal(name, VAT, species_name, colour, gender, birth_year, age) 
VALUES 
('Bart','1234','Cat','Black', 'Male', '2015-12-18', 1);

INSERT INTO consult(name , VAT_owner , date_timestamp , s , o , a , p , VAT_client , VAT_vet , weight)
VALUES 
('Bart', '1234', '2016-12-19 13:17:17', 's', 'o', 'a', 'p', '41235', '23424', 1.2);
