# Catalyst-IT-Australia-Pty-Ltd Felix Niocena Submission

---
##Prerequisites
- PHP 8.1
- MySQL 5.6 higher
- Command line access
- Composer
---
#Part 1
## Initial Setup
- Head into the project
  - Rename config_example.php into config.php
  - Edit line 4 and replace with the database name you'd want
    - Eg: return ['dbname' => 'catalyst'];
  - Save.
- In the command line, head into the project and run composer install
- Run this script
  - php user_upload --create-database -u -p -h
  - In the options, refer to your mysql or mariadb credentials
  - Follow this link, if you'd like to set up your mysql or mariadb
  - https://www.itzgeek.com/how-tos/mini-howtos/securing-mysql-server-with-mysql_secure_installation.html
---
##Usage: user_upload.php
- --create-database -u -p -h
- --create_table -u -p -h
- --file [csv file name] -u -p h
- --dry_run [csv file name]
- --help
## Definitions
- --file [csv file name] process .csv to be stored into mysql
- --create-database  creates database based on config.php file
- --create_table – build users table
- --dry_run – potential rows to be inserted
- -u – MySQL username
- -p – MySQL password
- -h – MySQL host
- --help – output the above list of directives with details.

---
#Part 2 Logic Test
##Usage: foobar.php
- php foobar.php
---
##Definitions
- foobar.php
  - The PHP script is designed to be executed from the command line and performs the following tasks:
  - It outputs numbers from 1 to 100.
  - If a number is divisible by three (3), it replaces the number with the word "foo" in the output.
  - If a number is divisible by five (5), it replaces the number with the word "bar" in the output.
  - If a number is divisible by both three (3) and five (5), it replaces the number with the word "foobar" in the output.


---
#Contact me:
#### If you have any concerns or queries, don't hesitate to reach out to me at
#### - Email: niocenafelix@hotmail.com
#### - Number: 0481704565

###Cheers :)
