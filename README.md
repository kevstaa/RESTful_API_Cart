# API Cart
## Description
This is a RESTful API for managing a shopping cart. It allows users to add, remove, and view products in their cart, as well as complete the checkout process. The API is designed to integrate with frontend applications or e-commerce platforms.

## Installation

1. **Install XAMPP**  
   Download and install XAMPP from [https://www.apachefriends.org](https://www.apachefriends.org).

2. **Clone the repository**  
   Clone this repository into the `htdocs` directory of XAMPP:
   ```bash
   git clone https://github.com/username/project.git
   ```

3. **Set up the database**  
   The API will automatically create a MySQL database via phpMyAdmin.  
   - Open phpMyAdmin at [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Use the cartdb.sql file located in the db folder to avoid issues.
   
   Then, configure the database connection in the `.env` file. Create a `.env` file in the root directory of the project and add the following variables:
   ```dotenv
   DB_HOST=your_dbhost
   DB_NAME=your_dbname
   DB_USER=your_user
   DB_PASS=your_secure_password
   ```

4. **Install Slim dependencies**  
   Navigate to the project directory and run:
   ```bash
   cd project
   composer install
   ```

5. **Configure Postman**  
   Open Postman and set up requests to interact with the API, using the available endpoints.

6. **Start XAMPP**  
   Ensure that Apache and MySQL are running from the XAMPP control panel.

7. **Access the API**  
   The API will be available at `http://localhost/apicart/public`.

## Contributing

1. Fork the repository.
2. Create a branch for your feature (`git checkout -b feature/new-feature`).
3. Make your changes.
4. Commit and push your branch (`git push origin feature/new-feature`).
5. Open a pull request for review.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact
You can contact me at [Contact me](mailto:kperez2609@gmail.com).