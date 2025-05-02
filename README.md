# API Cart

## Description

API Cart is a RESTful API designed to manage shopping cart functionalities for e-commerce platforms. It allows users to add, remove, and view products in their cart, as well as complete the checkout process. The API is lightweight, efficient, and easy to integrate with frontend applications.

## Features

- Add items to the cart with quantity and price.
- Update item quantities or details in the cart.
- Remove items from the cart.
- Retrieve the current state of the cart, including total price and item details.
- Complete the checkout process.

## Technologies Used

- **PHP**: Backend logic and API implementation.
- **MySQL**: Database for storing cart and product data.
- **Apache (via XAMPP)**: Local server for hosting the API.
- **Slim Framework**: Lightweight PHP framework for building RESTful APIs.
- **Composer**: Dependency manager for PHP.
- **Postman**: Tool for testing API endpoints.

## Installation

1. **Install XAMPP**  
   Download and install XAMPP from [https://www.apachefriends.org](https://www.apachefriends.org).

2. **Clone the repository**  
   Clone this repository into the `htdocs` directory of XAMPP:
   ```bash
   git clone https://github.com/username/project.git
   ```

3. **Set up the database**  
   - Open phpMyAdmin at [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Import the `cartdb.sql` file located in the `db` folder to set up the database.
   - Create a `.env` file in the root directory of the project and add the following variables:
     ```dotenv
     DB_HOST=your_dbhost
     DB_NAME=your_dbname
     DB_USER=your_user
     DB_PASS=your_secure_password
     ```

4. **Install dependencies**  
   Navigate to the project directory and run:
   ```bash
   cd project
   composer install
   ```

5. **Start XAMPP**  
   Ensure that Apache and MySQL are running from the XAMPP control panel.

6. **Access the API**  
   The API will be available at [http://localhost/apicart/public](http://localhost/apicart/public).

## Usage

Start your local server (e.g., Apache and MySQL using XAMPP) and access the API at [http://localhost/apicart/public](http://localhost/apicart/public). Use Postman or any HTTP client to test the endpoints.

## Contributing

Contributions are welcome! Fork the repository, make your changes, and open a pull request for review.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For questions or support, contact me at [kperez2609@gmail.com](mailto:kperez2609@gmail.com).