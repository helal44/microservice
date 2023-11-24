# Note Microservice

 ## Table of Contents
  1-Introduction    
  2-Prerequisites  
  3-Installation  
  4-Usage  
  5-API Documentation  
  6-Tests  
  
 ## Introduction
   note microservice is project used to do crud opration , generat api documention ,   
     send message to message broker (rabbitmq) and appyly unit test on the method functionality .  
     

 ## Prerequisites
   1- php 8 or above   
   2- mysql  -> i use xampp 8.2 for both php , mysql  
   3- rabbitmq   

 ## Installation

  ### Clone the repository
    git clone https://github.com/your-username/note-microservice.git

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
 1- register or login from the user side   
 2- take the token and place it on the auth headr of the note
 3- choose bearer token from the token type

 ## API Documentation

  http://127.0.0.1:8000/api/documentation#/default

## Run unit tests
    php artisan test
