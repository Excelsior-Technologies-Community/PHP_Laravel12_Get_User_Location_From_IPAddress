# PHP_Laravel12_Get_User_Location_From_IPAddress

A simple and beginner-friendly Laravel application that retrieves and displays the **current user's location details based on their IP address** using the `stevebauman/location` package. This project demonstrates IP detection, geolocation lookup, and clean data presentation following Laravel best practices.

---

## Project Overview

This repository helps developers understand:

* How to detect a user's IP address in Laravel
* How to fetch geolocation data using a third-party package
* How to display location details in Blade views
* How to configure and switch between multiple IP geolocation drivers

This project is ideal for:

* Beginners learning Laravel packages
* Interview preparation
* Practice and demo projects
* Location-based feature implementation

---

## Features

* Detect user IP address automatically
* Fetch location details such as country, city, region, and coordinates
* Display location data in a clean Bootstrap-based UI
* Easy setup and customization
* Supports multiple IP geolocation drivers

---

## Requirements

* PHP 8.1 or higher
* Laravel 10.x or higher (tested on Laravel 12)
* Composer
* Active internet connection for IP geolocation services

---

## Installation Guide

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-ip-location.git
cd laravel-ip-location
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Install Location Package

```bash
composer require stevebauman/location
```

---

## Optional Configuration

The package works out of the box using the default **ipapi** driver.

To publish the configuration file and change drivers:

```bash
php artisan vendor:publish --provider="Stevebauman\Location\LocationServiceProvider"
```

Update the driver in:

```
config/location.php
```

---

## Usage

### Route Definition

Defined in `routes/web.php`:

```php
use App\Http\Controllers\UserController;

Route::get('/user', [UserController::class, 'index']);
```

---

### Controller Logic

The controller retrieves the user's IP address and fetches location details:

```php
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Illuminate\View\View;

public function index(Request $request): View
{
    // Get IP dynamically
    $ip = $request->ip();

    // Or use static IP for testing
    // $ip = '162.159.24.227';

    $currentUserInfo = Location::get($ip);

    return view('user', compact('currentUserInfo'));
}
```

---

### Blade View

Location data is displayed in:

```
resources/views/user.blade.php
```

Available properties:

* ip
* countryName
* countryCode
* regionName
* cityName
* zipCode
* latitude
* longitude

---

## Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

Open your browser and visit:

```
http://localhost:8000/user
```

---

## Testing with Static IP Address

For development or testing, you can hardcode an IP address:

```php
public function index(Request $request): View
{
    // $ip = $request->ip();
    $ip = '162.159.24.227';

    $currentUserInfo = Location::get($ip);

    return view('user', compact('currentUserInfo'));
}
```

---

## Error Resolution & Troubleshooting

### Common Error

**ParseError: syntax error, unexpected variable**

Cause: Missing semicolon.

Incorrect:

```php
$ip = $request->ip() // missing semicolon
```

Correct:

```php
$ip = $request->ip();
```

---

### Clear Cache

```bash
php artisan optimize:clear
```

Or individually:

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

### Verify Controller Imports

Ensure these imports exist in `UserController.php`:

```php
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Illuminate\View\View;
```

---

### Verify Package Installation

```bash
composer show stevebauman/location
```

---

## Supported Location Drivers

The following IP geolocation drivers are supported:

* ipapi (default)
* ipinfo
* maxmind
* ipdata
* ipgeolocation

You can switch drivers in `config/location.php`.

---

## Project Screenshots

<img width="1729" height="710" alt="image" src="https://github.com/user-attachments/assets/f883065c-f552-4a14-a918-1ddd6e6c6e39" />

---

## Use Cases

* Location-based personalization
* Analytics and logging
* Geo-restricted content
* Learning Laravel package integration

---

## License

This project is open-source and free to use for learning and development purposes.
