<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$currentUserId = 1;

// find the corresponding note


$note = $db->query('SELECT * from notes where id = :id', [
  'id' => $_POST['id']
])->findOrFail();

// authorize that the current user can edit the note

authorize($note['user_id'] === $currentUserId);

// validate the form IF POSSIBLE DO THE VALIDATIONS PROHIBITED ALSO IF NOTHING CHANGES IN THE NOTE

$errors = [];

$oldContent = $note['body'];
$newContent = $_POST['body'];

if (!Validator::string($newContent, 1, 1000)) {
  $errors['body'] = 'A body is no more 1,000 characters is required';
}

if (!Validator::checkChanges($oldContent, $newContent)) {
  $errors['body'] = 'Required changes';
}

if (count($errors)) { // Count => Same for !empty()
  return view("notes/edit.view.php", [
    'heading' => 'Edit Note',
    'errors' => $errors,
    'note' => $note
  ]);
}

// if no validation errors, update the record in the notes database table


$db->query('UPDATE notes SET body = :body where id = :id', [
  'body' => $newContent,
  'id' => $_POST['id']
]);

// redirect the user

header('Location: /notes');
exit;
