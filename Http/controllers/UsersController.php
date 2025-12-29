<?php

namespace Http\controllers;

use Core\App;
use Core\Database;

class UsersController
{

  public function index()
  {
    $db = App::resolve(Database::class);

    $users = $db->query('SELECT * from users')->get();

    view("users/index.view.php", [
      'heading' => 'List of users',
      'users' => $users
    ]);
  }

  public function store()
  {
    $db = App::resolve(Database::class);

    $db->query('INSERT INTO users(name, email) VALUES(:name, :email)', [
      'name' => $_POST['name'],
      'email' => $_POST['email'],
    ]);

    header('location: /users');
    exit();
  }

  // show specific user
  public function show($id)
  {
    $db = App::resolve(Database::class);

    $user = $db->query('SELECT * from users where id = :id', [
      'id' => $id
    ])->findOrFail();

    view("users/show.view.php", [
      'heading' => 'User',
      'user' => $user
    ]);
  }

  public function update($id)
  {
    $db = App::resolve(Database::class);

    $db->query('UPDATE users SET name = :name, email = :email where id = :id', [
      'name' => $_POST['name'],
      'email' => $_POST['email'],
      'id' => $id
    ]);

    header('location: /users');
    exit();
  }

  public function destroy($id)
  {
    $db = App::resolve(Database::class);

    $db->query('delete from users where id = :id', [
      'id' => $id
    ]);

    header('location: /users');
    exit();
  }

  public function create()
  {
    view("users/create.view.php");
  }


  // 
  public function edit($id)
  {
    $db = App::resolve(Database::class);

    $user = $db->query('SELECT * from users where id = :id', [
      'id' => $id
    ])->findOrFail();

    view("users/edit.view.php", [
      'user' => $user
    ]);
  }
}
