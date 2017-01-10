Hidalgo County DA Electronic Discovery

## Setup

Git setup to facilitate installation by following this steps:

- Create equivalent of droplet
- CD into destination folder
- Git Clone the project
- Login into Mysql or Mariadb and create empty database that will store project
- Import data from the sql dump located on the root folder of the installation directory called first.sql
- Configure the .env files by adding mysql credentials, email credentials


## Users
- Admin : admin@admin.com,  password
- Manager: admin1@admin.com,  password

System allows for Users permissions to be dynamically changed by creating custom roles that define what permissions a user has and assigning users those roles.  Every new user is automatically assigned an "unconfirmed" role.  This role does allow the user to login but cannot see anything until it manually gets changed by a user that has 'edit users' permission.  

A "normal user" or attorney user should only have: 
- Browse Cases 
- Read Cases 
- Browse Files
- Download Files

An admin user is defined by the permission of Browse Admin.  This allows users to access the 'Admin dashboard' vs the 'User dashboard'.  


## Admin Dashboard

The Admin Dashboard can be reached by appending /admin after the base url:  www.sitename.com/admin.  These route are protected by a package called Laravel Firewall.  This package allows the whitelisting and blacklisting of ip addresses to sections of a site.  The Admin section is defined by the light blue section on the top left of the site.  A User Section is defined by a green colored section. 

To add or a remove the ip addresses allowed to access the admin section you need to ssh in to the project directory and enter:

-To add:
php artisan firewall:whitelist 123.123.123.12

-To see which ip addresses are listed:
php artisan firewall:list

-To remove and ip: 
php artisan firewall:remove 123.123.123.12

-To clear all:
php artisan -f firewall:clear




