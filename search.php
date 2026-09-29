<?php
$pageTitle = 'Book Search';
require __DIR__ . '/config/database.php';

$q = trim($_GET['q'] ?? '');
$rows = [];

$sql = "SELECT
            b.accession_no,
            b.title,
            a.name AS author,
            c.name AS category,
            b.available_copies,
            b.status
        FROM books b
        LEFT JOIN authors a ON a.id = b.author_id
        LEFT JOIN categories c ON c.id = b.category_id
        WHERE b.status = 'active'";

$params = [];

if ($q !== '') {
    $like = "%{$q}%";
    $sql .= " AND (
        b.accession_no LIKE ?
        OR b.title LIKE ?
        OR a.name LIKE ?
        OR c.name LIKE ?
    )";
    $params = [$like, $like, $like, $like];
}

$sql .= " ORDER BY b.title";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Library Book Search</title>
<link rel="stylesheet" href="assets/library-search.css">
</head>
<body>
<div class="library-page">

<header class="library-topbar">
  <div class="inner">
    <div class="brand">
      <div class="brand-icon">📚</div>
      <span>Library Management System</span>
    </div>
    <a class="staff-btn" href="login.php">Staff Login</a>
  </div>
</header>

<section class="hero">
  <h1>Search Library Books</h1>
  <form action="search.php" method="get" class="search-box">
    <input
      name="q"
      value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>"
      placeholder="Search by accession no., book title, author or category"
      aria-label="Search books"
      autofocus>
    <button type="submit">🔎 Search</button>
  </form>
</section>

<main class="content">
  <div class="result-card">
    <div class="result-head">
      <h2><?= $q !== '' ? 'Search Results' : 'Library Catalogue' ?></h2>
      <span class="count"><?= count($rows) ?> book<?= count($rows) !== 1 ? 's' : '' ?></span>
    </div>

    <div class="table-wrap">
      <table class="library-table">
        <thead>
          <tr>
            <th>Accession No.</th>
            <th>Book Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($rows as $book): ?>
          <tr>
            <td><?= htmlspecialchars($book['accession_no'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="book-title"><?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($book['author'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($book['category'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
            <td>
<?php if ((int)$book['available_copies'] > 0): ?>
              <span class="availability available">● Available</span>
<?php else: ?>
              <span class="availability unavailable">● Not Available</span>
<?php endif; ?>
            </td>
          </tr>
<?php endforeach; ?>

<?php if (!$rows): ?>
          <tr>
            <td colspan="5">
              <div class="empty">
                <div class="empty-icon">🔍</div>
                <strong>No books found</strong><br>
                Try another accession number, book title, author or category.
              </div>
            </td>
          </tr>
<?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="footer-note">Public Library Catalogue • No login required</div>
</main>

</div>
</body>
</html>
