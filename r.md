# Book_Store_Website
Book Store Website Design Using PHP, JavaScript, CSS

# #################### data_bases ###################################
# products attributes  id , name , , author ,genre , description, price , pieces , image
# cart attributes id , user_id , name , price , quantity , image
# views attributes id , view ,product_id , user_id
# message attributes id , user_id , name , email , number , message
# orders attributes id , user_id , 

# PHPMyAdmin Tables
SELECT `id`, `name`, `email`, `password`, `user_type` FROM `users` WHERE 1 <br>
SELECT `id`, `name`, `price`, `image` FROM `products` WHERE 1 <br>
SELECT `id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status` FROM `orders` WHERE 1 <br>
SELECT `id`, `user_id`, `name`, `email`, `number`, `message` FROM `message` WHERE 1 <br>
SELECT `id`, `user_id`, `name`, `price`, `quantity`, `image` FROM `cart` WHERE 1 
