<?php
$pageTitle='Book Availability Search';
require __DIR__.'/config/database.php';
require __DIR__.'/includes/auth.php';
$q=trim($_GET['q']??''); $rows=[];
if($q!==''){
 $s=$pdo->prepare("SELECT b.*,c.name category,a.name author,(b.total_copies-b.available_copies) issued_copies FROM books b LEFT JOIN categories c ON c.id=b.category_id LEFT JOIN authors a ON a.id=b.author_id WHERE b.status='active' AND (b.title LIKE ? OR b.isbn LIKE ? OR b.accession_no LIKE ? OR a.name LIKE ? OR c.name LIKE ?) ORDER BY b.title");
 $like="%$q%"; $s->execute([$like,$like,$like,$like,$like]); $rows=$s->fetchAll();
}
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Library Book Search</title><link rel="stylesheet" href="assets/library-search.css"></head>
<body><div class="library-page">
<header class="library-topbar"><div class="inner"><div class="brand"><div class="brand-icon">📚</div><span>Library Management System</span></div><a class="staff-btn" href="login.php">Staff Login</a></div></header>
<section class="hero"><h1>Find Your Next Book</h1><p>Search the library catalogue and check book availability before visiting the library.</p>
<form action="search.php" method="get" class="search-box"><input name="q" value="<?=h($q)?>" placeholder="Search by title, author, ISBN, accession no. or category" aria-label="Search books" autofocus><button type="submit">🔎 Search Books</button></form><div class="hint">Example: Computer Networks, Mathematics, ISBN, author name or accession number</div></section>
<main class="content"><div class="result-card"><div class="result-head"><h2><?= $q!=='' ? 'Search Results' : 'Library Catalogue' ?></h2><?php if($q!==''): ?><span class="count"><?=count($rows)?> result<?=count($rows)!==1?'s':''?></span><?php endif; ?></div>
<div class="table-wrap"><table class="library-table"><thead><tr><th>Accession</th><th>Book</th><th>Author</th><th>Category</th><th>Total</th><th>Issued</th><th>Available</th><th>Shelf</th><th>Status</th></tr></thead><tbody>
<?php foreach($rows as $b): $available=(int)$b['available_copies']; ?><tr><td><?=h($b['accession_no'])?></td><td class="book-title"><?=h($b['title'])?></td><td><?=h($b['author']??'—')?></td><td><?=h($b['category']??'—')?></td><td><?=$b['total_copies']?></td><td><?=$b['issued_copies']?></td><td><strong><?=$available?></strong></td><td><?=h($b['shelf_no']??'—')?></td><td><?php if($available>0): ?><span class="availability available">● Available</span><?php else: ?><span class="availability unavailable">● Not Available</span><?php endif; ?></td></tr><?php endforeach; ?>
<?php if($q!==''&&!$rows): ?><tr><td colspan="9"><div class="empty"><div class="empty-icon">🔍</div><strong>No books found</strong><br>Try another title, author, ISBN or category.</div></td></tr><?php elseif($q===''): ?><tr><td colspan="9"><div class="empty"><div class="empty-icon">📖</div><strong>Search the catalogue</strong><br>Enter a book title, author, ISBN or category above to check availability.</div></td></tr><?php endif; ?></tbody></table></div></div><div class="footer-note">Public catalogue • No login required for book availability search</div></main></div></body></html>