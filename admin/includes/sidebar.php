<!-- Sidebar Filters -->
  <link rel="stylesheet" href="assets/css/style.css">
<div class="sidebar">
    <form method="GET" action="index.php">
        <div class="filter-section">
            <h3 class="filter-title"><i class="fas fa-list"></i> Categories</h3>
            <form method="GET" class="filter-form">
                <label for="category">Filter by Category:</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <option value="tools">Tools</option>
                    <option value="equipments">Equipments</option>
                    <option value="fertilizers">Fertilizers</option>
                    <option value="pesticides">Pesticides</option>
                    <option value="herbicides">Herbicides</option>
                    <option value="insecticides">Insecticides</option>
                    <option value="seeds">Seeds</option>
                    <option value="crops">Crops</option>
                </select>
            </form>
        </div>
        
    </form>
</div>