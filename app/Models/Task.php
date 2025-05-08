<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property string $title
 * @property int $user_id
 * @property string $content
 * @property string $status
 * @property array $images
 */
class Task extends Model
{
    use HasFactory;
}
