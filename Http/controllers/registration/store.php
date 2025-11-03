<?php

use Core\Validator;
use Core\App;
use Core\Database;

$email = $_POST['email'];
$password = $_POST['password'];

// Validate the form inputs.

$errors = [];

if (!Validator::email($email)) {
  $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password, 7, 255)) {
  $errors['password'] = 'Please provide a password at least seven characters';
}

if (!empty($errors)) {
  return view('registration/create.view.php', [
    'errors' => $errors
  ]);
}

$db = App::resolve(Database::class);
// Check if the email is already exist in database.
$user = $db->query('select * from users where email = :email', [
  'email' => $email
])->find();

if ($user) {
  // then someone with that email already exist and has an account.
  // if yes, redirect to the page.
  header('location: /');
  exit();
} else {
  // if note, save one to the database, and then log the user in, and redirect.
  $db->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
    'email' => $email,
    'password' => password_hash($password, PASSWORD_BCRYPT)
  ]);

  login([
    'email' => $email
  ]);

  header('location: /');
  exit();
}
