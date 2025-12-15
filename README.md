# PHP_Laravel12_Get_User_Location_From_IPAddress

A simple Laravel application to retrieve and display the current user's location information based on their IP address using the `stevebauman/location` package. This project demonstrates IP detection, geolocation lookup, and clean data presentation using Laravel best practices.

---

## Features

* Detect user IP address
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

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-ip-location.git
cd laravel-ip-location
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Install Location Package

```bash
composer require stevebauman/location
```

### 5. Optional Configuration

The package works out of the box using the default `ipapi` driver. To change drivers, publish the configuration file:

```bash
php artisan vendor:publish --provider="Stevebauman\Location\LocationServiceProvider"
```

Update your preferred driver in:

```
config/location.php
```

---

## Usage

### Route

The route is already defined in `routes/web.php`:

```php
Route::get('user', [UserController::class, 'index']);
```

### Controller Logic

The `UserController` retrieves the IP address and fetches location details:

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

## Error Resolution

### Common Error

**ParseError: syntax error, unexpected variable**

This usually occurs due to a missing semicolon.

Incorrect example:

```php
$ip = $request->ip() // Missing semicolon
```

Correct example:

```php
$ip = $request->ip();
```

### Clear Cache

If issues persist, clear application cache:

```bash
php artisan optimize:clear
```

Or individually:

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Verify Imports

Ensure the following imports exist in `UserController.php`:

```php
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Illuminate\View\View;
```

### Verify Package Installation

```bash
composer show stevebauman/location
```

---

## Available Location Drivers

The following drivers are supported:

* ipapi (default, free tier available)
* ipinfo
* maxmind
* ipdata
* ipgeolocation

You can switch drivers in `config/location.php` after publishing the configuration file.

---

## Testing with Static IP

For development or testing purposes, you can use a static IP address:

```php
public function index(Request $request): View
{
    // $ip = $request->ip();
    $ip = '162.159.24.227';

    $currentUserInfo = Location::get($ip);

    return view('user', compact('currentUserInfo'));
}
```

## Images

<img width="1729" height="710" alt="image" src="https://github.com/user-attachments/assets/f883065c-f552-4a14-a918-1ddd6e6c6e39" />

---
