# Marketplace
Marketplace is a web application that allows users to signup and register for the application with the ability to post products online with title, price, and description.
It allows direct Emailing to other sellers using the SocketLabs API

## Installation

1. clone the repo using:
```bash
git clone https://github.com/Sepehrman/PHP-Marketplace-Project.git
```

2. Make Sure you have a Local Development Server Installed on your Machine (Wamp, Xamp, & etc.) and create the following tables on your mySQL Server
```SQL
CREATE TABLE user (id int primary key auto_increment, firstname varchar(255), lastname varchar(255), email varchar(255), password varchar(255), pinned LONGTEXT, downvotes LONGTEXT);

CREATE TABLE products (id int primary key auto_increment, title varchar(255), price decimal(6,2), description MEDIUMTEXT, picture varchar(255), author varchar(255), author_email varchar(255), downvotes_count int, time_added varchar(255));

```


3. Move the project to the Development Folder and run

## Application Startup

![image](https://user-images.githubusercontent.com/59620701/137554616-924e7eae-59b2-429c-9d0f-05d3226788ec.png)

## Signup, Login & Add An Item For Sale!

<p float="left">
  <img src="https://user-images.githubusercontent.com/59620701/137555057-722868a3-db36-40c3-81ca-be9de0900e44.png" width="500" />
  <img src="https://user-images.githubusercontent.com/59620701/137555315-b17be1e3-956c-4ef6-89d9-11d1b1224d84.png" width="500" /> 
  <img src="https://user-images.githubusercontent.com/59620701/137558424-ddf1fddf-1be6-4275-8afc-9542c73d1d69.png" width="500" />
</p>


## Delete, Rate, & view Products for Sale!
<p float="left">
  <img src="https://user-images.githubusercontent.com/59620701/137558518-2f384401-020f-4f57-825e-422105e58827.png" width="500"/>
  <img src="https://user-images.githubusercontent.com/59620701/137558612-dbe9e243-ade3-46a4-a0f0-3c46f10a7cd2.png" width="500"/>
  <img src="https://user-images.githubusercontent.com/59620701/137558250-f8e1c869-750c-4daf-84ee-ad874bac6358.png" width="500"/>

</p>




## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

