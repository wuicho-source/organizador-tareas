<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskImageModel extends Model
{
    protected $table            = 'task_images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['task_id', 'image_path'];
    protected $useTimestamps    = false;
}
