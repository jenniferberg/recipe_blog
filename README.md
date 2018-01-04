# recipe_blog

Recipe Blog is a website that allows end users to view different recipes, and admins to maintain and add new recipes.

There are three types of users for this website:
1.	Users: Defined by no access in the administrator table. Users can only view recipes.
2.	Admins: Defined by type = admin in the administrator table. Admins can maintain, add, and delete recipes.
3.	Super Admins: Defined by type = superadmin in the administrator table. Super Admins have the same access as Admins, but in addition can maintain, add, and delete other administrators.

Before you can view the website, you'll first need to:
1.	Set up your local database appropriately. I've include a recipe_blog.sql file with the appropriate database import scripts. I've not included a data import for the administrator table, so you will need to write that yourself. I would advise setting up a user and password via an initial insert statement with type = superadmin, and then updating the user from the interface so that the password in properly encrypted.
2.	Set up your DB connection information in the private/classes/database.php class file. You'll need to add your host name, username and password to this file on lines 2, 3, and 4.
3.  Define the beginning of the file path where the images will be saved, stored in the $directory variable in the private/initialize.php file on line 22, based on where you keep this code.

