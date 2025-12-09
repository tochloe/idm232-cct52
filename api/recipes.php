<?php
require_once("../db.php");

header('Content-Type: application/json');


$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


if ($method !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'error' => 'Method not allowed',
        'message' => 'Only GET requests are supported'
    ]);
    exit;
}

$conn = db_connect();

if (strpos($path, '/recipes.php') !== false || strpos($path, '/api/recipes') !== false) {
    if (isset($_GET['id'])) {
        handleGetSingleRecipe($conn, $_GET['id']);
    } else {
        handleGetAllRecipes($conn);
    }
} else {
    http_response_code(404);
    echo json_encode([
        'error' => 'Not found',
        'message' => 'Endpoint not found'
    ]);
}

$conn->close();

/*all recipes from filter*/
function handleGetAllRecipes($conn) {
    $searchTerm = $_GET['search'] ?? '';
    $cultureFilter = $_GET['culture'] ?? '';
    
    $sql = "SELECT id, title, subheading, culture, bio, ingredients, tools, recipe, tips, ingredients_image, hero_img, step1_img, step2_img, step3_img, step4_img FROM recipes3";
    $conditions = [];
    $params = [];

    if ($searchTerm !== '') {
        $conditions[] = "(title LIKE ? OR subheading LIKE ? OR culture LIKE ? OR ingredients LIKE ?)";
        $like = "%" . $searchTerm . "%";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    if ($cultureFilter !== '' && $cultureFilter !== 'all') {
        $conditions[] = "culture = ?";
        $params[] = $cultureFilter;
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY title";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Database error',
            'message' => 'Failed to prepare statement'
        ]);
        return;
    }

    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = [
            'id' => (int)$row['id'],
            'title' => $row['title'],
            'subheading' => $row['subheading'],
            'culture' => $row['culture'],
            'hero_img' => $row['hero_img']
        ];
    }

    $stmt->close();

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'count' => count($recipes),
        'data' => $recipes
    ]);
}




/*single id*/
function handleGetSingleRecipe($conn, $id) {
    // Validate ID
    if (!is_numeric($id) || $id < 1) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Bad request',
            'message' => 'Invalid recipe ID'
        ]);
        return;
    }

    $sql = "SELECT * FROM recipes3 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Database error',
            'message' => 'Failed to prepare statement'
        ]);
        return;
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode([
            'error' => 'Not found',
            'message' => "Recipe with ID $id not found"
        ]);
        $stmt->close();
        return;
    }

    $recipe = $result->fetch_assoc();
    $stmt->close();

    $recipe['id'] = (int)$recipe['id'];


    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $recipe
    ]);
}
?>