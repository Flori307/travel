<?php
// app/Models/AdminLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $primaryKey = 'log_id';
    public $timestamps = true;
    
    protected $fillable = [
        'admin_id',
        'action_type',
        'action',
        'target_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'additional_info'
    ];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
    
    public function admin()
    {
        return $this->belongsTo(Client::class, 'admin_id');
    }
    
    public static function log($adminId, $actionType, $action, $targetId = null, $oldValues = null, $newValues = null, $additionalInfo = null)
    {
        return self::create([
            'admin_id' => $adminId,
            'action_type' => $actionType,
            'action' => $action,
            'target_id' => $targetId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'additional_info' => $additionalInfo
        ]);
    }
}