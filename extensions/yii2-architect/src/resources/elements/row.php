<?php

use kilyanov\architect\entity\GroupEntity;

/**
 * @var GroupEntity $element ;
 */

?>

<?php foreach ($element->getItems() as $item): ?>
    <?= $item ?>
<?php endforeach; ?>

