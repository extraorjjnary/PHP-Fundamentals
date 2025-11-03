<?php require base_path('views/partial/head.php'); ?><?php require base_path('views/partial/nav.php'); ?><?php require base_path('views/partial/banner.php'); ?>

<main>
  <div class="text-white mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <p class="mb-6">
      <a href="/notes" class="text-blue-500 hover:underline">Go back...</a>
    </p>
    <p><?= htmlspecialchars($note['body']) ?></p>

    <footer class="mt-6">
      <a href="/note/edit?id=<?= $note['id'] ?>" class="inline-flex justify-center rounded-md bg-gray-500 hover:bg-indigo-600 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Edit</a>
    </footer>


  </div>
</main>
<?php require base_path('views/partial/foot.php'); ?>