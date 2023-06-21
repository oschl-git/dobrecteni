# Dobrecteni

<a href="https://github.com/oschl-git/dobrecteni/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=oschl-git/dobrecteni" />
</a>

> *Goodreads if it was a good website.*

 A PHP web application for keeping track of books. A final project for the subject WA at SPŠE Ječná, done in collaboration with Erik Vaněk.
 
 - ability to add, edit and delete books you've read or are planning to read
 - can retrieve book covers from the <a href="https://openlibrary.org/dev/docs/api/books">OpenLibrary Books API</a> if you provide a valid ISBN
 - responsive design
 - stores data in a MariaDB database
 - allows for creating new users, modifying account details, deleting accounts
 - a fancy dark theme ✨

## Used technologies
- PHP
- XAMPP
- MariaDB
- VSCode

## How to run?
It's just like walking, only somewhat faster.

## How to run Dobrecteni on your machine?
1. Install XAMPP
2. Run the `mariadb_export.sql` script in PHPMyAdmin
3. Move everything in the repository to a folder in `xampp/htdocs`
4. The website should be accessible at <a href="https://localhost/dobrecteni/">localhost/dobrecteni</a>
