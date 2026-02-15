<?php
/**
 * TIF Service - Menu Display Example
 * This file demonstrates how to fetch and display menu items from the database
 */

require_once __DIR__ . '/db.php';

// Get all categories
$categories = getCategories();

// Get selected category from URL
$selectedCategory = $_GET['category'] ?? null;

// Get menu items
if ($selectedCategory) {
    $menuItems = getMenuItems($selectedCategory);
} else {
    $menuItems = getMenuItems();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TIF Service - Menu</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; color: #333; }
        .categories { display: flex; justify-content: center; gap: 15px; margin-bottom: 30px; flex-wrap: wrap; }
        .category-btn { padding: 10px 20px; background: #ff6347; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; }
        .category-btn:hover, .category-btn.active { background: #e5533d; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        .menu-item { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .menu-item-image { width: 100%; height: 200px; object-fit: cover; background: #ddd; }
        .menu-item-content { padding: 20px; }
        .menu-item-name { font-size: 1.2em; font-weight: bold; margin-bottom: 10px; color: #333; }
        .menu-item-description { color: #666; margin-bottom: 15px; font-size: 0.9em; }
        .menu-item-price { font-size: 1.3em; color: #ff6347; font-weight: bold; }
        .add-to-cart { width: 100%; padding: 12px; background: #ff6347; color: white; border: none; border-radius: 5px; cursor: pointer; margin-top: 15px; }
        .add-to-cart:hover { background: #e5533d; }
        .no-items { text-align: center; padding: 40px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>TIF Service Menu</h1>
        
        <div class="categories">
            <a href="index.php" class="category-btn <?php echo !$selectedCategory ? 'active' : ''; ?>">All</a>
            <?php foreach ($categories as $category): ?>
                <a href="?category=<?php echo $category['id']; ?>" 
                   class="category-btn <?php echo $selectedCategory == $category['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($menuItems)): ?>
            <div class="no-items">No menu items available.</div>
        <?php else: ?>
            <div class="menu-grid">
                <?php foreach ($menuItems as $item): ?>
                    <div class="menu-item">
                        <?php if ($item['image']): ?>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="menu-item-image">
                        <?php else: ?>
                            <div class="menu-item-image" style="display: flex; align-items: center; justify-content: center; color: #999;">No Image</div>
                        <?php endif; ?>
                        <div class="menu-item-content">
                            <div class="menu-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="menu-item-description"><?php echo htmlspecialchars($item['description'] ?? ''); ?></div>
                            <div class="menu-item-price">$<?php echo number_format($item['price'], 2); ?></div>
                            <button class="add-to-cart" onclick="addToCart(<?php echo $item['id']; ?>)">Add to Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function addToCart(itemId) {
            // Add your cart logic here
            alert('Item added to cart! ID: ' + itemId);
        }
    </script>
</body>
</html>
