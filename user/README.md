# User Microservice

 ## Table of Contents
  1-Introduction    
  2-Prerequisites  
  3-Installation  
  4-Usage  
  5-API Documentation  
  6-Tests  
  
 ## Introduction
   user microservice is project used to register user , generat api documention ,   
    recive  message from (rabbitmq) and appyly unit test on the method functionality .  
     

 ## Prerequisites
   1- php 8 or above   
   2- mysql  -> i use xampp 8.2 for both php , mysql  
   3- rabbitmq   

 ## Installation

  ### Clone the repository
    git clone https://github.com/helal44/microservice.git

  ### Navigate to the project directory
    cd microservice/note

  ### Install dependencies
    composer install

  ### Copy the environment file  
    cp .env.example .env  
   #### Edit the .env file with your configurations  
   
  ### Set up the database
    php artisan migrate

## Usage
### i use postmant to test api .  
 1- register user by enter (name,emai,password) in the rquest body  , you will recive token as response.    
 2- log user by enter (emai,password) in the rquest body , you will recive token as response.    

 ## API Documentation

  http://127.0.0.1:8000/api/documentation#/default

## Run unit tests
    php artisan test
