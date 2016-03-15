# OSC
Ontario Science Centre

# Development Notes

## Directory Strucuture

### controllers
This directory contains PHP code to be run on request of a particular page.
A request for "server.local/do-thing" will run the controller at "controllers/do-thing.controller.php".

### errors
This directory contains controllers for HTTP errors, such as 404.

### config
This directory contains configuration files for database information, etc.

### lib
This directory contains classes that can be used by code in any controller.

### framework
This directory contains classes for the autoloader and router system, as well as some basic utilities.
