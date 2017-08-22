**Created For:** CPSC 471 Database Fundamentals

**Description:** A simple Amazon clone front end manipulating a MySQL backend loaded on Amazon's RDS platform. 

**Technologies/Software:**
- Amazon RDS
- PHP
- MySQL
- WAMP Stack
- Microsoft Visio

**Methodologies**
- Used EER model to design relational database schema
- Front end development using PHP/CSS/HTML
- Test driven development 
- Full Stack using WAMP platform
- Use Microsoft Visio to design the database model
- Implement OWASP rules to guard against malicious data.

The main goal of this project was to understand how to design and implement a relational database. Creating a marketplace provided the opportunity to show and explore various types of SQL operations. First an EER Model was created. 

![eer](https://user-images.githubusercontent.com/5299394/29581245-f130c6c6-8735-11e7-989a-eb1ba1a1d472.png)

The database was then created on the Amazon RDS platform with the help of both PHPMyAdmin and MySQL connect via terminal. In order to demonstrate its functionality a nice and simple front end was created to help interact with the database. It is worth nothing that this was not a Web Systems class but a backend Database design class therefore more emphasis was put on the design of the database rather than the design of the front end. It was still fully functional and had full OWASP protection coverage.

The platform was intentionally designed to serve three types of users - seller, buyers, and moderators(employees). The users could login and signup as base buyer users and could apply to upgrade their status to sellers which was controlled by the moderators on the platform.

![bb1](https://user-images.githubusercontent.com/5299394/29581696-6b31606a-8737-11e7-8948-eddea7d19b71.png)

![bb2](https://user-images.githubusercontent.com/5299394/29581697-6b32c770-8737-11e7-9989-fbb76e09e1fc.png)

Special menu available to moderators to approve applications

![bb3](https://user-images.githubusercontent.com/5299394/29581699-6b37d4fe-8737-11e7-8d30-b103037b4644.png)

![bb4](https://user-images.githubusercontent.com/5299394/29581700-6b3a0076-8737-11e7-8466-17b67a380a9d.png)

Both registered and non registered users could search for items on the main page using the search bar which used SQL's LIKE function to find relevant products. This is what the front page of the website looked like. It had all the products listed. 

![bb5](https://user-images.githubusercontent.com/5299394/29581993-53ccaf0a-8738-11e7-90a4-03978a4bf200.png)


Both registered and non registered users could search for items on the main page using the search bar which used SQL's LIKE function to find relevant products. This is what the front page of the website looked like. It had all the products listed. 

![bb5](https://user-images.githubusercontent.com/5299394/29581993-53ccaf0a-8738-11e7-90a4-03978a4bf200.png)

Each product had its own product page along with a fully functional product and seller review section. 

![bb6](https://user-images.githubusercontent.com/5299394/29582135-cb1364f0-8738-11e7-81ff-fce2c5882d3a.png)

![bb7](https://user-images.githubusercontent.com/5299394/29582136-cb188a70-8738-11e7-8cc2-96d1511836e6.png)

![bb8](https://user-images.githubusercontent.com/5299394/29582137-cb1927d2-8738-11e7-8fdd-87f106d1a737.png)




