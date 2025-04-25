<?php
class APICart {
    // Include connection in the class
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    // Function to get all products from a user's cart
    public function getCart($user) {
        // $user passes the username of the cart to know which cart to check
        try {
            // Query to get all products from a cart
            $stmt = $this->conn->prepare("SELECT user, p.name, (price * quantity) AS total_price, quantity FROM cart_products cp INNER JOIN products p ON p.id=cp.productId INNER JOIN carts c ON c.id=cp.cartId WHERE user = :user");
            $stmt->bindParam(":user", $user, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Check if the cart exists and if the data was passed correctly. If it doesn't exist, return an error message; if it exists, return the result.
            if (empty($result)){
                return ['Error' => 'The cart or products do not exist'];
            } else {
                return $result;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        } finally {
            closeCon($this->conn);
        }
    }
    // Function to view a specific product from the cart by the user and product ID
    public function getCartProduct($data) {
        // $data passes the username of the cart and the product ID we want to check
        try {
            $user = $data['user'];
            $productId = $data['productId'];
            // Query to get a specific product from a cart
            $stmt = $this->conn->prepare("SELECT user, p.name, price, quantity FROM cart_products cp INNER JOIN products p ON p.id=cp.productId INNER JOIN carts c ON c.id=cp.cartId WHERE user = :user AND p.id= :productId");
            $stmt->bindParam(":user", $user, PDO::PARAM_STR);
            $stmt->bindParam(":productId", $productId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Select cart id to check later if it exists
            $stmtCart = $this->conn->prepare("SELECT id FROM carts WHERE user = :user");
            $stmtCart->bindParam(":user", $user, PDO::PARAM_STR);
            $stmtCart->execute();
            $cartId = $stmtCart->fetch(PDO::FETCH_ASSOC);
            // Check if the cart exists and if the data passed exists, if not return an error message, if it exists return the result
            if (empty($cartId)) {
                return ['Error' => 'The cart does not exist'];
            } else {
                return $result;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        } finally {
            closeCon($this->conn);
        }
    }
    // Function to add products to a cart, and if the cart doesn't exist, create one
    public function addProduct($data) {
        // $data passes the user of the cart to add products, the product ID to add, and the quantity of the product
        try {
            $user = $data['user'];
            $productId = $data['productId'];
            $quantity = $data['productQuantity'];

            // Get the cart ID
            $stmtCartId = $this->conn->prepare("SELECT id FROM carts WHERE user = :user");
            $stmtCartId->bindParam(":user", $user, PDO::PARAM_STR);
            $stmtCartId->execute();
            $cartId = $stmtCartId->fetch(PDO::FETCH_ASSOC)['id'];

            // Verify if the product and cart IDs exist
            $stmtExists = $this->conn->prepare("SELECT id FROM products WHERE id = :productId");
            $stmtExists->bindParam(":productId", $productId, PDO::PARAM_INT);
            $stmtExists->execute();
            $productExists = $stmtExists->fetch(PDO::FETCH_ASSOC);
            // Check if the cart exists, if not create one
            if (empty($cartId)) {
                $stmtCreateCart = $this->conn->prepare("INSERT INTO carts (user) VALUES (:user)");
                $stmtCreateCart->bindParam(":user", $user, PDO::PARAM_STR);
                $stmtCreateCart->execute();
            }
            // Check if the product exists, if not return an error message
            if ($productExists) {
                // Insert the product into the cart
                $stmtInsert = $this->conn->prepare("INSERT INTO cart_products (cartId, productId, quantity) VALUES (:cartId, :productId, :quantity)");
                $stmtInsert->bindParam(":cartId", $cartId, PDO::PARAM_INT);
                $stmtInsert->bindParam(":productId", $productId, PDO::PARAM_INT);
                $stmtInsert->bindParam(":quantity", $quantity, PDO::PARAM_INT);
                $stmtInsert->execute();

                // Get updated cart information
                $stmtCart = $this->conn->prepare("SELECT user, name, quantity FROM carts c INNER JOIN cart_products cp ON cp.cartId=c.id INNER JOIN products p ON p.id=cp.productId WHERE user = :user");
                $stmtCart->bindParam(":user", $user, PDO::PARAM_STR);
                $stmtCart->execute();
                $result = $stmtCart->fetchAll(PDO::FETCH_ASSOC);

                $response = ['Completed' => 'Product added', 'Products' => $result];
                return $response;
            } else {
                // If product ID doesn't exist, show an error message
                return ['Error' => 'The product does not exist'];
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        } finally {
            closeCon($this->conn);
        }
    }
    // Function to update the quantity of products in the cart
    public function updateProductQuantity($user, $data) {
        // $data passes the same as the previous exercise since what we want to update is the quantity of the product
        try {
            $totalQuantity = $data['totalQuantity'];
            $productId = $data['productId'];
            // Select cart ID
            $stmtCart = $this->conn->prepare("SELECT id FROM carts WHERE user = :user");
            $stmtCart->bindParam(":user", $user, PDO::PARAM_STR);
            $stmtCart->execute();
            $cartId = $stmtCart->fetch(PDO::FETCH_ASSOC);
            // Select product ID
            $stmtExists = $this->conn->prepare("SELECT id FROM products WHERE id = :productId");
            $stmtExists->bindParam(":productId", $productId, PDO::PARAM_INT);
            $stmtExists->execute();
            $productExists = $stmtExists->fetch(PDO::FETCH_ASSOC);
            // Check if the cart and the product exist, if not return an error message, if they exist, update the quantity
            if (empty($cartId) && empty($productExists)) {
                return ['Error' => 'The cart or product does not exist or the product quantity is invalid'];
            } else {
                // Query to update the quantity
                $stmtUpdate = $this->conn->prepare("UPDATE cart_products SET quantity = :totalQuantity WHERE productId = :productId AND cartId = :cartId");
                $stmtUpdate->bindParam(":totalQuantity", $totalQuantity, PDO::PARAM_INT);
                $stmtUpdate->bindParam(":productId", $productId, PDO::PARAM_INT);
                $stmtUpdate->bindParam(":cartId", $cartId['id'], PDO::PARAM_INT);
                $stmtUpdate->execute();

                // Query to get the product data and show it
                $stmt = $this->conn->prepare("SELECT user, p.name, price, quantity FROM cart_products cp INNER JOIN products p ON p.id=cp.productId INNER JOIN carts c ON c.id=cp.cartId WHERE user = :user AND p.id= :productId");
                $stmt->bindParam(":user", $user, PDO::PARAM_STR);
                $stmt->bindParam(":productId", $productId, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
                $response = ['Completed' => 'Quantity updated', 'Product' => $result];
                return $response;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        } finally {
            closeCon($this->conn);
        }
    }
    // Function to delete products from a cart
    public function deleteProduct($data) {
        // $user indicates the cart to reference, and $id indicates the product to delete
        try {
            $user = $data['user'];
            $productId = $data['productId'];
            // Query to select the cart ID
            $stmtCart = $this->conn->prepare("SELECT id FROM carts WHERE user = :user");
            $stmtCart->bindParam(":user", $user, PDO::PARAM_STR);
            $stmtCart->execute();
            $cartId = $stmtCart->fetch(PDO::FETCH_ASSOC);
            // Check if the cart exists, if not return an error message, and if it exists delete the product
            if (empty($cartId)) {
                return ['Error' => 'Cannot delete a product from a non-existent cart'];
            } else {
                // Query to delete a product from the cart
                $stmt = $this->conn->prepare("UPDATE cart_products SET quantity = quantity - 1 WHERE productId = :productId AND cartId = :cartId");
                $stmt->bindParam(":productId", $productId, PDO::PARAM_INT);
                $stmt->bindParam(":cartId", $cartId, PDO::PARAM_INT);
                $stmt->execute();
                // If the quantity is 0, delete the product from the cart
                $stmtEmptyProduct = $this->conn->prepare("DELETE FROM cart_products WHERE quantity = 0");
                $stmtEmptyProduct->execute();

                $result = ['Completed' => 'Product deleted'];
                return $result;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        } finally {
            closeCon($this->conn);
        }
    }
    // Function to view the total price of the cart
    public function finalPrice($user) {
        // $user is passed to get the final price of the cart
        try {
            // Query to get the total price of the cart by summing up the prices of all the products
            $stmt = $this->conn->prepare("SELECT SUM(price * quantity) AS total_price FROM cart_products cp INNER JOIN products p ON p.id=cp.productId INNER JOIN carts c ON c.id=cp.cartId WHERE user = :user");
            $stmt->bindParam(":user", $user, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        } finally {
            closeCon($this->conn);
        }
    }
    // Function to empty the cart and delete all products inside
    public function emptyCart($user) {
        // $user refers to the cart to delete
        try {
            // Get the cart ID
            $stmtCartCheck = $this->conn->prepare("SELECT id FROM carts WHERE user = :user");
            $stmtCartCheck->bindParam(":user", $user, PDO::PARAM_STR);
            $stmtCartCheck->execute();
            $cartId = $stmtCartCheck->fetch(PDO::FETCH_ASSOC);
            // Check if the cart exists, if not return an error message, and if it exists, delete the cart and its products
            if (empty($cartId)) {
                return ['Error' => 'The cart does not exist'];
            } else {
                // Delete all products in the cart
                $stmt = $this->conn->prepare("DELETE FROM cart_products WHERE cartId IN (SELECT id FROM carts WHERE user = :user)");
                $stmt->bindParam(":user", $user, PDO::PARAM_STR);
                $stmt->execute();
                // Delete the cart itself
                $stmtCart = $this->conn->prepare("DELETE FROM carts WHERE user = :user");
                $stmtCart->bindParam(":user", $user, PDO::PARAM_STR);
                $stmtCart->execute();
                $result = ['Completed' => 'Cart and products deleted'];
                return $result;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        } finally {
            closeCon($this->conn);
        }
    }
}
?>