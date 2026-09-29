<?php
$pageTitle='Book Search';
require __DIR__.'/config/database.php';

$q=trim($_GET['q']??'');
$rows=[];

if($q!==''){
    $like="%$q%";
    $s=$pdo->prepare("SELECT b.*, c.name category, a.name author,
        (b.total_copies-b.available_copies) issued_copies
        FROM books b
        LEFT JOIN categories c ON c.id=b.category_id
        LEFT JOIN authors a ON a.id=b.author_id
        WHERE b.status='active'
        AND (b.title LIKE ? OR b.isbn LIKE ? OR b.accession_no LIKE ?
             OR a.name LIKE ? OR c.name LIKE ?)
        ORDER BY b.title");
    $s->execute([$like,$like,$like,$like,$like]);
    $rows=$s->fetchAll();
} else {
    $rows=$pdo->query("SELECT b.*, c.name category, a.name author,
        (b.total_copies-b.available_copies) issued_copies
        FROM books b
        LEFT JOIN categories c ON c.id=b.category_id
        LEFT JOIN authors a ON a.id=b.author_id
        WHERE b.status='active'
        ORDER BY b.title")->fetchAll();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Library Book Search</title>
<link rel="stylesheet" href="assets/library-search.css">
</head>
<body>
<div class="library-page">
<header class="library-topbar">
  <div class="inner">
    <div class="brand"><div class="brand-icon">📚</div><span>Library Management System</span></div>
    <a class="staff-btn" href="login.php">Staff Login</a>
  </div>
</header>

<section class="hero">
  <h1>Search Library Books</h1>
  <p>Search the library catalogue and check availability without logging in.</p>
  <form action="search.php" method="get" class="search-box">
    <input name="q" value="<?=h($q)?>" placeholder="Search by title, author, ISBN, accession number or category" aria-label="Search books" autofocus>
    <button type="submit">🔎 Search</button>
  </form>
  <div class="hint">Leave the search box empty to view all available catalogue records.</div>
</section>

<main class="content">
<div class="result-card">
  <div class="result-head">
    <h2><?= $q!=='' ? 'Search Results' : 'Library Catalogue' ?></h2>
    <span class="count"><?=count($rows)?> book<?=count($rows)!==1?'s':''?></span>
  </div>

  <div class="table-wrap">
    <table class="library-table">
      <thead>
        <tr>
          <th>Accession No.</th>
          <th>Book Title</th>
          <th>Author</th>
          <th>Category</th>
          <th>ISBN</th>
          <th>Total</th>
          <th>Issued</th>
          <th>Available</th>
          <th>Shelf</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
<?php foreach($rows as $b): $available=(int)$b['available_copies']; ?>
        <tr>
          <td><?=h($b['accession_no'])?></td>
          <td class="book-title"><?=h($b['title'])?></td>
          <td><?=h($b['author']??'—')?></td>
          <td><?=h($b['category']??'—')?></td>
          <td><?=h($b['isbn']??'—')?></td>
          <td><?=$b['total_copies']?></td>
          <td><?=$b['issued_copies']?></td>
          <td><strong><?=$available?></strong></td>
          <td><?=h($b['shelf_no']??'—')?></td>
          <td>
<?php if($available>0): ?>
            <span class="availability available">● Available</span>
<?php else: ?>
            <span class="availability unavailable">● Not Available</span>
<?php endif; ?>
          </td>
        </tr>
<?php endforeach; ?>

<?php if(!$rows): ?>
        <tr>
          <td colspan="10">
            <div class="empty">
              <div class="empty-icon">🔍</div>
              <strong>No books found</strong><br>
              Try another title, author, ISBN, category or accession number.
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