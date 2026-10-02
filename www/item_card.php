<?php // Verwacht een variabele $item met de kolommen uit items + categorie_naam ?>
<a class="card" href="item_detail.php?id=<?php echo $item['id']; ?>">
    <div class="card-image">
        <?php if (!empty($item['foto_url'])) : ?>
            <img src="<?php echo htmlspecialchars($item['foto_url']); ?>" alt="<?php echo htmlspecialchars($item['titel']); ?>" loading="lazy">
        <?php else : ?>
            <div class="no-image">Geen foto</div>
        <?php endif; ?>
        <?php if ($item['status'] == 'verkocht') : ?>
            <span class="badge badge-sold">Verkocht</span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <p class="card-category"><?php echo htmlspecialchars($item['categorie_naam']); ?></p>
        <h3 class="card-title"><?php echo $item['titel']; ?></h3>
        <div class="card-meta">
            <span class="price"><?php echo formatPrijs($item['prijs']); ?></span>
            <span class="badge badge-<?php echo htmlspecialchars($item['conditie']); ?>"><?php echo htmlspecialchars(ucfirst($item['conditie'])); ?></span>
        </div>
    </div>
</a>
