<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $inquiries */

use yii\helpers\Html;

$this->title = 'Contact Inquiries';
?>

<h1 class="h3 mb-4">Contact Form Submissions</h1>

<div class="card p-3">
    <?php if (empty($inquiries)): ?>
        <p class="text-muted mb-0 p-3">No contact submissions yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Project Type</th>
                        <th>Message</th>
                        <th>Received</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inquiries as $inq): ?>
                        <tr>
                            <td>
                                <strong><?= Html::encode($inq['name'] ?? '') ?></strong><br>
                                <small><?= Html::encode($inq['email'] ?? '') ?></small><br>
                                <small class="text-muted"><?= Html::encode($inq['phone'] ?? '') ?></small>
                            </td>
                            <td><?= Html::encode($inq['project_type'] ?? '') ?></td>
                            <td style="max-width:340px;"><?= Html::encode($inq['message'] ?? '') ?></td>
                            <td><?= date('d M Y, H:i', (int)($inq['created_at'] ?? time())) ?></td>
                            <td>
                                <span class="badge <?= !empty($inq['is_read']) ? 'bg-secondary' : 'bg-success' ?>">
                                    <?= !empty($inq['is_read']) ? 'Read' : 'New' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <?php if (empty($inq['is_read'])): ?>
                                    <?= Html::beginForm(['/admin/inquiry-mark-read', 'id' => $inq['id']], 'post', ['class' => 'd-inline']) ?>
                                    <?= Html::submitButton('Mark Read', ['class' => 'btn btn-sm btn-outline-dark']) ?>
                                    <?= Html::endForm() ?>
                                <?php endif; ?>
                                <?= Html::beginForm(['/admin/inquiry-delete', 'id' => $inq['id']], 'post', ['class' => 'd-inline']) ?>
                                <?= Html::submitButton('Delete', ['class' => 'btn btn-sm btn-outline-danger', 'onclick' => 'return confirm("Delete this inquiry?")']) ?>
                                <?= Html::endForm() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
