<?php
$pageTitle='Dashboard';
require __DIR__.'/config/database.php';
require __DIR__.'/includes/header.php';

$stats=[
 'Book Copies'=>(int)$pdo->query("SELECT COALESCE(SUM(total_copies),0) FROM books")->fetchColumn(),
 'Available'=>(int)$pdo->query("SELECT COALESCE(SUM(available_copies),0) FROM books")->fetchColumn(),
 'Members'=>(int)$pdo->query("SELECT COUNT(*) FROM members WHERE status='active'")->fetchColumn(),
 'Issued'=>(int)$pdo->query("SELECT COUNT(*) FROM loans WHERE status='issued'")->fetchColumn(),
 'Overdue'=>(int)$pdo->query("SELECT COUNT(*) FROM loans WHERE status='issued' AND due_date<CURDATE()")->fetchColumn()
];
?>
<h2 class="mb-4">Library Dashboard</h2>

<div class="row g-3">
<?php foreach($stats as $k=>$v): ?>
<div class="col-md-3 col-lg">
  <div class="card shadow-sm h-100"><div class="card-body">
    <div class="text-muted"><?=h($k)?></div><div class="fs-2 fw-bold"><?=$v?></div>
  </div></div>
</div>
<?php endforeach; ?>
</div>

<div class="row g-3 mt-2">
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-header fw-bold">Quick Book Search</div>
      <div class="card-body">
        <form action="search.php" method="get" class="input-group">
          <input class="form-control" name="q" placeholder="Search by title, ISBN, accession number or author" aria-label="Search books">
          <button class="btn btn-primary" type="submit">Search</button>
        </form>
        <div class="mt-3"><a href="search.php" class="btn btn-outline-primary btn-sm">Advanced Search</a></div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card shadow-sm h-100">
      <div class="card-header fw-bold">Reports</div>
      <div class="card-body">
        <p class="text-muted">View current loans and overdue books.</p>
        <a class="btn btn-dark" href="reports.php">Open Reports</a>
      </div>
    </div>
  </div>
</div>

<div class="mt-4">
<a class="btn btn-primary me-2" href="books.php">Books</a>
<a class="btn btn-success me-2" href="members.php">Members</a>
<a class="btn btn-warning me-2" href="issue.php">Issue / Return</a>
<a class="btn btn-dark" href="reports.php">Reports</a>
</div>

<?php require __DIR__.'/includes/footer.php';