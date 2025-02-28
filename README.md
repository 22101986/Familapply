# Familapply

## Description
**Familapply** is a web application designed to help families manage their daily tasks and organize family photos efficiently. Built with **PHP** using the **Symfony Framework**, it provides an intuitive interface to create, track, and visualize tasks on a dynamic calendar. Additionally, it offers a photo management system with a search functionality that displays family members' photos in a responsive carousel.

## Features
- Task management with CRUD operations (Create, Read, Update, Delete)
- Dynamic task visualization using **JQuery Calendar**
- Family photo management with upload, search, and display features
- Search photos by family member names
- Carousel display for searched photos
- Modern and responsive design

## Technologies Used
- PHP 8.x
- Symfony 6.x
- MySQL
- JQuery
- Bootstrap
- FullCalendar.js
- Slick Carousel

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/22101986/familapply.git
   cd familapply
   ```
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Configure the `.env` file with your database credentials:
   ```env
   DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/familapply
   ```
4. Run database migrations:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```
5. Start the development server:
   ```bash
   symfony serve
   ```

## Usage
1. Register a family account.
2. Add daily tasks via the task management interface.
3. Visualize tasks in the interactive calendar.
4. Upload family photos with member names.
5. Use the search bar to filter photos by family member names and view them in the photo carousel.



