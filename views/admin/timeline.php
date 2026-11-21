<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $entries */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Legacy Timeline';
?>

<h1 class="h3 mb-4">Four Generations — Legacy Timeline</h1>

<div class="card p-3">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Image</th>
                <th>Era</th>
                <th>Title</th>
                <th>Subtitle</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entries as $entry): ?>
                <tr>
                    <td>
                        <?php if (!empty($entry['image_path'])): ?>
                            <img src="<?= Html::encode($entry['image_path']) ?>" alt="" style="width:72px;height:48px;object-fit:cover;border-radius:4px;">
                        <?php endif; ?>
                    </td>
                    <td><code><?= Html::encode($entry['era_label']) ?></code></td>
                    <td><strong><?= Html::encode($entry['title']) ?></strong></td>
                    <td><?= Html::encode($entry['subtitle'] ?? '') ?></td>
                    <td class="text-end">
                        <a href="<?= Url::to(['/admin/timeline-update', 'id' => $entry['id']]) ?>" class="btn btn-sm btn-outline-dark">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
