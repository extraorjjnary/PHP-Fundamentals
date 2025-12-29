<h2>Users List</h2>

<a href="/users/create">Create New User</a>

<ul>
  <?php foreach ($users as $user): ?>
    <li>
      <?= htmlspecialchars($user['name']) ?>

      <a href="/users/<?= $user['id'] ?>">View</a>
      <a href="/users/<?= $user['id'] ?>/edit">Edit</a>

      <form method="POST" action="/users/<?= $user['id'] ?>" style="display:inline;">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit">Delete</button>
      </form>
    </li>
  <?php endforeach; ?>
</ul>