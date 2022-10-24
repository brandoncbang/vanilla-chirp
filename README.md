# vanilla-chirp
A simple but complete Vanilla PHP microblogging app

## Requirements
- PHP 8.1
- MySQL 8.0
- Apache, NGinx, or PHP's test server

## Overview
The goal for this project is to implement a microblogging app (like Twitter) in PHP without the use of a framework. In addition, the goal is also to find a clear structure that Vanilla PHP projects can follow without adding too many abstractions, using as much of what PHP already provides to us as possible. Some notable patterns used in this project are detailed below.

### Routing
Dynamic routing is accomplished through a match expression, combined with the helper function `param_path`. This allows for good enough routing for many situations without a complex stack. Pattern matching is done on the request method & path to match the corresponding controller & action.

### Controllers
Controllers are very simple classes with each method corresponding to an action, which responds with a view or redirect. Commonly, the constructor is used to perform a common task for each controller action, such as ensuring the user is logged in.

### Models
Models take advantage of PDO's option to return results as an array of class instances. Model classes include methods to allow for easy querying for many situations. Loading relations is left up to each model to implement, but there are methods provided in the `LoadsRelations` trait that abstract over the process of loading many common relations. 

### Views
Views are just good old PHP templates with variable scope limited to what is passed in through a helper function as an associative array. Sometimes a PHPDoc comment is added to let IDEs and static analysis know what variables exist in the template.

### Authentication
The authentication implementation is simple, and utilizes PHP's native sessions plus the `password_hash()` and `password_verify()` functions to store and check password hashes using BCrypt. Helper functions allow for things like getting the current user, logging in and out, etc.
