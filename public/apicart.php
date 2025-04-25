<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Import necessary files for the application
require_once(__DIR__ . "/../src/APICart.php");
require_once(__DIR__ . "/include/connection.php");
require_once(__DIR__ . "/../vendor/autoload.php");

// Use the APICart class to fetch, update, or delete the desired data
$cartApi = new APICart($conn);

$app = AppFactory::create();

// Middleware checks
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Set the base path for the API
$app->setBasePath("/apicart/public");

// View all products in a cart
$app->get("/api/carts/{user}", function (Request $request, Response $response, array $args) use ($cartApi) {
    $user = $args['user'];
    // $accept is a variable to check the request header
    $accept = $request->getHeaders()['Accept'][0];
    if (strpos($accept, 'application/json') !== false) {
        $response->getBody()->write(json_encode($cartApi->getCart($user), JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json');
    } else if (strpos($accept, 'text/html') !== false) {
        $cart = $cartApi->getCart($user);
        // HTML output if the user wants to view it in HTML
        echo "<!DOCTYPE html>";
        echo "<html lang='en'>";
            echo "<head>";
                echo "<meta charset='UTF-8'>";
                echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
                echo "<title>Document</title>";
            echo "</head>";
            echo "<body>";
                // user, p.name, price, quantity
                foreach ($cart as $product) {
                    echo "<p>Cart: " . $product['user'] . ". Product name: " . $product['name'] . ". Price: " . $product['total_price'] . ". Quantity: " . $product['quantity'] . "</p>";
                }
            echo "</body>";
        echo "</html>";
        
        return $response->withHeader('Content-Type', 'text/html');
    } else {
        // If the format is not HTML or JSON, return an error message in JSON saying that the format is not supported
        $responseMessage = ['Error' => 'Format not found'];
        $response->getBody()->write(json_encode($responseMessage), JSON_PRETTY_PRINT);
        return $response->withHeader('Content-Type', 'application/json');
    }
});

// View a specific product in a cart
$app->get("/api/carts/product/{user}/{productId}", function (Request $request, Response $response, array $args) use ($cartApi) {
    $user = $args['user'];
    $productId = $args['productId'];
    $list = ['user' => $user, 'productId' => $productId];
    
    $response->getBody()->write(json_encode($cartApi->getCartProduct($list), JSON_PRETTY_PRINT));

    return $response->withHeader('Content-Type', 'application/json');
});

// Add a product to a cart
$app->post("/api/carts/{user}/{productId}/{productQuantity}", function (Request $request, Response $response, array $args) use ($cartApi) {
    $user = $args['user'];
    $productId = $args['productId'];
    $productQuantity = $args['productQuantity'];
    $list = ['user' => $user, 'productId' => $productId, 'productQuantity' => $productQuantity];

    $response->getBody()->write(json_encode($cartApi->addProduct($list), JSON_PRETTY_PRINT));

    return $response->withHeader('Content-Type', 'application/json');
});

// Update the quantity of a product in the cart
$app->put("/api/carts/{user}/{productId}/{totalQuantity}", function (Request $request, Response $response, array $args) use ($cartApi) {
    $user = $args['user'];
    $productId = $args['productId'];
    $totalQuantity = $args['totalQuantity'];
    $list = ['user' => $user, 'productId' => $productId, 'totalQuantity' => $totalQuantity];

    $response->getBody()->write(json_encode($cartApi->updateProductQuantity($user, $list), JSON_PRETTY_PRINT));

    return $response->withHeader('Content-Type', 'application/json');
});

// Delete a product from the cart
$app->delete("/api/carts/{user}/{productId}", function (Request $request, Response $response, array $args) use ($cartApi) {
    $user = $args['user'];
    $productId = $args['productId'];
    $list = ['user' => $user, 'productId' => $productId];

    $response->getBody()->write(json_encode($cartApi->deleteProduct($list), JSON_PRETTY_PRINT));

    return $response->withHeader('Content-Type', 'application/json');
});

// View the total price of the cart
$app->get("/api/totalCart/{user}", function (Request $request, Response $response, array $args) use ($cartApi) {
    $user = $args['user'];
    $response->getBody()->write(json_encode($cartApi->finalPrice($user), JSON_PRETTY_PRINT));
    
    return $response->withHeader('Content-Type', 'application/json');
});

// Delete the cart and all products inside it
$app->get("/api/emptyCart/{user}", function (Request $request, Response $response, array $args) use ($cartApi) {
    $user = $args['user'];
    $response->getBody()->write(json_encode($cartApi->emptyCart($user), JSON_PRETTY_PRINT));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
?>