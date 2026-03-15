## About Project

<p>
<h3> Project Architecture</h3>
<ul>
    <li>PHP 8.1.*+</li>
    <li>Laravel 10+</li>
    <li>Postgres - 13 / 14</li>
    <li> Redis-Server And Redis-client <a href="https://drive.google.com/file/d/1M89JLDfvWeBxNABJpUySujc347oLrhta/view?usp=sharing">Download</a></li>
    <li>K6 <a href="https://drive.google.com/file/d/1OzhSeR9wEaiIXXLcmUcdHsQaIeEl65l6/view?usp=sharing">Download</a></li>
</ul>
</p>

## Project Installation 

<p>
<h3> Step </h3>
<ul>
    <li>Clone this repository : <a href="https://github.com/muhammad-rifqi/nawatech.git">https://github.com muhammad-rifqi/nawatech.git</a> </li>
    <li>the github repo is public repo.</li>
    <li>install XAMPP with PHP version is 8.1+</li>
    <li>install the Redis server & client according Project Architecture</li>
    <li>Make Sure redis server & client in the C:\Redis.*** of path</li>
    <li>install K6 after download from the above link</li>
    <li>after K6 installed open the terminal and write the command "K6" to the make sure running on the enviroment </li>
    <li>then navigate to the path folder "K6" in the nawatech project, and run the *k6 run script.js*</li>
    <li>make sure the infrastucture have been installed</li>
</ul>
</p>

## Setup Instruction 

<p>
<h3> Step </h3>
<ul>
    <li>After Installation, open the project with visual studio code or something else</li>
    <li>open di redis server & redis client and the with "ping" , and the output the "pong"</li>
    <li>Run the k6 app and write the command "k6 run script.js"</li>
    <li>then open terminal in visual studio code , write command composer install</li>
    <li>Run the command "php artisan serve".</li>
    <li>then test with postman or something else from api provide</li>
</ul>
</p>

## Design Decisions:

<p>
<h3> Design Decisions </h3>
<ul>
    <li>Laravel Queue with Redis is used to process payments asynchronously.</li>
    <li>The controller is only responsible for receiving requests and sending jobs to the queue.</li>
    <li>Job Process Payment Job is responsible for processing payment logic.</li>
    <li>PostgreSQL is used as the primary database for storing transactions.</li>
</ul>
</p>


## Performance Considerations.
<p>
<h3> Performance Considerations </h3>
<ul>
    <li>Payment processing is done in a background queue to reduce API response time.</li>
    <li>Redis is used as a queue driver because it has low latency.</li>
    <li>Worker queues allow the system to process multiple transactions in parallel.</li>
    <li>An index is added to the order_id column to speed up transaction searches.</li>
</ul>
</p>