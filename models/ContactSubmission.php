<?php

declare(strict_types=1);

namespace app\models;

use yii\base\Model;

/**
 * Contact inquiry validation model (database-free).
 */
class ContactSubmission extends Model
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $project_type = '';
    public string $message = '';

    public function rules(): array
    {
        return [
            [['name', 'email', 'message'], 'required'],
            [['name', 'email', 'phone', 'project_type', 'message'], 'trim'],
            [['email'], 'email'],
            [['message'], 'string', 'min' => 5, 'max' => 3000],
            [['name'], 'string', 'max' => 120],
            [['email'], 'string', 'max' => 150],
            [['phone'], 'string', 'max' => 40],
            [['project_type'], 'string', 'max' => 100],
        ];
    }
}
