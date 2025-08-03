<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitChangeHistory extends Model
{
    protected $fillable = [
        'master_unit_id',
        'field_name',
        'old_value',
        'new_value',
        'action',
        'changed_by',
        'description'
    ];

    /**
     * Relasi ke MasterUnit
     */
    public function masterUnit(): BelongsTo
    {
        return $this->belongsTo(MasterUnit::class);
    }

    /**
     * Format nilai untuk display
     */
    public function getFormattedOldValueAttribute()
    {
        return $this->formatValue($this->field_name, $this->old_value);
    }

    /**
     * Format nilai untuk display
     */
    public function getFormattedNewValueAttribute()
    {
        return $this->formatValue($this->field_name, $this->new_value);
    }

    /**
     * Format nilai berdasarkan field name
     */
    private function formatValue($fieldName, $value)
    {
        if ($value === null) return 'Kosong';
        
        switch ($fieldName) {
            case 'nilai_aset':
                return 'Rp ' . number_format($value, 0, ',', '.');
            case 'is_active':
                return $value ? 'Aktif' : 'Tidak Aktif';
            case 'penanggung_jawab_id':
                if ($value) {
                    $user = User::find($value);
                    return $user ? $user->name : 'User tidak ditemukan';
                }
                return 'Kosong';
            default:
                return $value;
        }
    }
}
