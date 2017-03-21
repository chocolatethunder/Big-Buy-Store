/* Data types setup */

CREATE DOMAIN ID_TYPE AS INTEGER(9);

/* USER TABLE */

CREATE TABLE USERS (
	id ID_TYPE NOT NULL AUTO_INCREMENT,
	username VARCHAR(50) NOT NULL,
	password VARCHAR(100) NOT NULL,
	email VARCHAR(100) NOT NULL,
	PRIMARY KEY (id)
);

/* USER DATA TABLE */

/* We can create a table to specifiy user pemissions for 
	various activities allowed/not allowed on our platform here. 
	Current levels:
	001: Admin
	002: Seller
	003: Buyer
	
	More columns refereing to different permission types can be added later.
*/

/* Setup all the depending tables */

CREATE TABLE USERLVL (
	levelid INTEGER(3) NOT NULL,
	PRIMARY KEY (levelid)
);

INSERT INTO USERLVL VALUE ("001");
INSERT INTO USERLVL VALUE ("002");
INSERT INTO USERLVL VALUE ("003");

CREATE TABLE ADDRESS (
	addrid ID_TYPE,
	sNum INTEGER(9),
	sName VARCHAR(100),
	sType VARCHAR(10),
	unit VARCHAR(5),
	pcode VARCHAR(6),
	city VARCHAR(25),
	province VARCHAR(10),
	country VARCHAR(3), /* ISO 3166-1 alpha-3 Country Code*/
	PRIMARY KEY (addrid)
);

/* Setup the main table */

CREATE TABLE USERINFO (
	id ID_TYPE,
	fname VARCHAR(50) NOT NULL,
	lname VARCHAR(50) NOT NULL,
	lvl INTEGER(3),
	address ID_TYPE,
	emailCode VARCHAR(150)
	FOREIGN KEY (id) REFERENCES USERS(id)
			ON DELETE SET NULL
			ON UPDATE CASCADE,
	FOREIGN KEY (lvl) REFERENCES USERLVL(levelid)
			ON DELETE SET NULL
			ON UPDATE CASCADE,
	FOREIGN KEY (address) REFERENCES ADDRESS(addrid)
			ON DELETE SET NULL
			ON UPDATE CASCADE
);

/* Orders table */

CREATE TABLE ORDERS (
	oid ID_TYPE NOT NULL AUTO_INCREMENT,
	uid ID_TYPE,
	orderData DATETIME,
	shipAddr ID_TYPE,
	status VARCHAR(100),
	PRIMARY KEY (oid),
	FOREIGN KEY (uid) REFERENCES USERS(id)
			ON DELETE SET NULL
			ON UPDATE CASCADE
	FOREIGN KEY (shipAddr) REFERENCES ADDRESS(addrid)
);

/* Department to Product */

CREATE TABLE DEPARTMENT (
	deptid ID_TYPE NOT NULL AUTO_INCREMENT,
	name VARCHAR(150)
	PRIMARY KEY (deptid)
);

CREATE TABLE PRODUCT (
	pid ID_TYPE NOT NULL AUTO_INCREMENT,
	name VARCHAR(150),
	department ID_TYPE,
	PRIMARY KEY (pid),
	FOREIGN KEY (department) REFERENCES DEPARTMENT(deptid)
			ON DELETE SET NULL
			ON UPDATE CASCADE
);

/* Seller products advertizing */

CREATE TABLE LISTS (
	adId ID_TYPE NOT NULL AUTO_INCREMENT,
	listedBy ID_TYPE,
	listedProd ID_TYPE,
	price INTEGER(8) NOT NULL,
	description TEXT NOT NULL,
	units INTEGER(9) DEFAULT 1,
	PRIMARY KEY (adId),
	FOREIGN KEY (listedBy) REFERENCES USERS(id)
			ON DELETE SET NULL
			ON UPDATE CASCADE,
	FOREIGN KEY (listedProd) REFERENCES PRODUCT(pid)
			ON DELETE SET NULL
			ON UPDATE CASCADE
);

/* 1 Shopping cart for 1 customer */

CREATE TABLE SHOPPINGCART (
	scid ID_TYPE NOT NULL AUTO_INCREMENT,
	belongsto ID_TYPE,
	PRIMARY KEY (scid),
	FOREIGN KEY (belongsto) REFERENCES USERS(id)
			ON DELETE SET NULL
			ON UPDATE CASCADE
);

/* 1 Shopping cart can contain many products */

CREATE TABLE USERCART (
	scid ID_TYPE,
	pid ID_TYPE,
	FOREIGN KEY (scid) REFERENCES SHOPPINGCART(scid)
			ON DELETE SET NULL
			ON UPDATE CASCADE,
	FOREIGN KEY (pid) REFERENCES PRODUCT(pid)
			ON DELETE SET NULL
			ON UPDATE CASCADE
);


