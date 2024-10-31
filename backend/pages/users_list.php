<?php
include_once __DIR__ . '/../db/config.php';

require_once __DIR__ . '/../src/helpers.php';

$users = [];

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $usersPerPage = 10;

    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

    $validSortOrders = ['ASC', 'DESC'];

    if (!in_array($sortOrder, $validSortOrders)) {
        $sortOrder = 'ASC';
    }

    $searchQuery = '';
    if (!empty($searchTerm)) {
        $searchQuery = "WHERE u.name LIKE :search";
    }

    $totalUsersQuery = $pdo->prepare("SELECT COUNT(*) FROM users u $searchQuery");
    if ($searchQuery) {
        $totalUsersQuery->bindValue(':search', "%$searchTerm%", PDO::PARAM_STR);
    }
    $totalUsersQuery->execute();
    $totalUsers = $totalUsersQuery->fetchColumn();

    $totalPages = ceil($totalUsers / $usersPerPage);

    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $currentPage = max(1, min($currentPage, $totalPages));

    $offset = ($currentPage - 1) * $usersPerPage;

    $usersQuery = $pdo->prepare("
    SELECT u.id, u.name, u.avatar, GROUP_CONCAT(c.symbol) AS favorites 
    FROM users u
    LEFT JOIN favorites f ON u.id = f.user_id
    LEFT JOIN cryptocurrencies c ON f.crypto_id = c.id
    $searchQuery
    GROUP BY u.id
    ORDER BY u.name $sortOrder
    LIMIT :offset, :limit
");
    if ($searchQuery) {
        $usersQuery->bindValue(':search', "%$searchTerm%", PDO::PARAM_STR);
    }
    $usersQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
    $usersQuery->bindParam(':limit', $usersPerPage, PDO::PARAM_INT);
    $usersQuery->execute();
    $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../../assets/layout/head.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USER LIST</title>
</head>

<div id="user_list-back">
    <div id="back-section">
        <a href="../index.php">◀</a>
    </div>
    <h1 id="user_list_header">USERS</h1>
</div>

<body>
    <form method="GET" action="">
        <div id="search_sort-users">
            <div id="search-user">
                <input type="text" name="search" placeholder="Search user" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button>Search</button>
            </div>
            <div id="sort-user">
                <select name="order" id="order">
                    <option value="ASC" <?php if ($sortOrder == 'ASC') echo 'selected'; ?>>A-Z</option>
                    <option value="DESC" <?php if ($sortOrder == 'DESC') echo 'selected'; ?>>Z-A</option>
                </select>
                <button>Sort</button>
            </div>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Avatar</th>
                <th>Favorites</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" width="50" height="50"></td>
                    <td><?php echo htmlspecialchars($user['favorites'] ?? ''); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=1&search=<?php echo urlencode($searchTerm); ?>&order=<?php echo urlencode($sortOrder); ?>">« First page</a>
            <a href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($searchTerm); ?>&order=<?php echo urlencode($sortOrder); ?>">« Previous page</a>
        <?php endif; ?>

        <?php
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);

        for ($page = $startPage; $page <= $endPage; $page++): ?>
            <a href="?page=<?php echo $page; ?>&search=<?php echo urlencode($searchTerm); ?>&order=<?php echo urlencode($sortOrder); ?>" <?php if ($page == $currentPage) echo 'class="active"'; ?>>
                <?php echo $page; ?>
            </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($searchTerm); ?>&order=<?php echo urlencode($sortOrder); ?>">Next Page »</a>
            <a href="?page=<?php echo $totalPages; ?>&search=<?php echo urlencode($searchTerm); ?>&order=<?php echo urlencode($sortOrder); ?>">Last page »</a>
        <?php endif; ?>
    </div>
</body>

</html>
