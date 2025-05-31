Simple Image Gallery
A web application to upload and display images using PHP, MySQL, HTML, CSS, and JavaScript.
Prerequisites

XAMPP (or similar) with Apache and MySQL.
PHP GD extension enabled for image resizing.

Setup Instructions

Place the project in htdocs/image-gallery.
Set permissions for assets/images (e.g., chmod 777 assets/images on Linux/Mac or grant full control on Windows).
Create the database:
Start MySQL in XAMPP.
Open phpMyAdmin (http://localhost/phpmyadmin).
Create a database named image_gallery.
Run the SQL from create_database.sql to create the images, admins, and users tables.
Note: Update the hashed passwords in create_database.sql by running the following PHP script and copying the output:<?php
echo password_hash('admin123', PASSWORD_DEFAULT) . "\n";
echo password_hash('user123', PASSWORD_DEFAULT) . "\n";
?>

Enable PHP GD extension:
Edit C:\xampp\php\php.ini and ensure extension=gd is uncommented.
Restart Apache in XAMPP.

Start the server:
Ensure Apache and MySQL are running in XAMPP.
Access the user login at http://localhost/image-gallery/user/login.php.
Access the admin login at http://localhost/image-gallery/admin/login.php.

Default credentials:
Admin: username=admin, password=admin123
User: username=user, password=user123

Upload images (Admin only):
Log in as admin and navigate to http://localhost/image-gallery/admin/upload.php.
Upload multiple JPG, PNG, or GIF files (max 5MB each).

View gallery (Users and Admins):
Log in as a user to view the gallery at http://localhost/image-gallery.

Features

Separate admin and user authentication.
Admins can upload multiple images with resizing.
Users can view images in a responsive grid with a lightbox.
MySQL storage for image metadata and user/admin credentials.
Tailwind CSS for a modern, responsive UI.

Notes

Ensure the PHP GD extension is enabled for image resizing.
The application uses basic session-based authentication. For production, add CSRF protection and stronger password policies.
The assets/images directory must be writable.
