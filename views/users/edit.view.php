<h2>Edit User</h2>

<form method="POST" action="/users/<?= $user['id'] ?>">
  <input type="hidden" name="_method" value="PATCH">

  <label>
    Name:
    <input type="text" name="name" value="<?= $user['name'] ?>" required>
  </label>
  <br><br>

  <label>
    Email:
    <input type="email" name="email" value="<?= $user['email'] ?>" required>
  </label>
  <br><br>

  <button type="submit">Update</button>
</form>