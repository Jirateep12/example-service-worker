## Getting started

### Requirements

- php
- composer
- xampp

### Installation

1. **Install php dependencies:**

   ```sh
   composer install
   ```

2. **Set up the database:**
   - Import the `subscription.sql` file into your database.
   - Update your database configuration in the php scripts if necessary.
3. **Configure vapid keys:**
   - Ensure `vapid_keys.json` contains your vapid keys for push notifications.

### Running the application

1. **Start your web server:**
   - Start your apache server using xampp or any other server of your choice.
2. **Access the application:**
   - Open your web browser and navigate to `http://localhost/yourproject`.

## Usage

- **Subscription management:**
  - Use `subscription.php` to handle subscription-related operations.
- **Push notifications:**
  - Use `demo_send.php` to send demo push notifications.
  - Ensure `service-worker.js` is correctly registered in your web application.

## License

This project is licensed under the MIT license - see the [license](license) file for details.
