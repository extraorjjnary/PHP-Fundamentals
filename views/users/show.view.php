<h2>User Details</h2>

<p>
  <strong>ID:</strong> <?= $user['id'] ?>
</p>

<p>
  <strong>Name:</strong> <?= htmlspecialchars($user['name']) ?>
</p>

<p>
  <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
</p>

<a href="/users">Back to list</a>
<a href="/users/<?= $user['id'] ?>/edit">Edit</a>