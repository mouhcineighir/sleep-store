## Auth
POST /api/register
POST /api/login
POST /api/logout

## Products
GET /api/products
GET /api/products/{id}
POST /api/products        (admin only)
PUT /api/products/{id}     (admin only)
DELETE /api/products/{id}  (admin only)

## Categories
GET /api/categories
POST /api/categories       (admin only)

## Cart
GET /api/cart
POST /api/cart
DELETE /api/cart/{id}

## Wishlist
GET /api/wishlist
POST /api/wishlist
DELETE /api/wishlist/{id}

## Orders
GET /api/orders
POST /api/orders
GET /api/orders/{id}