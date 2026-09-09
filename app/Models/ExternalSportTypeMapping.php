<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class ExternalSportTypeMapping extends Model
{
    protected $fillable = [
        'sport_type_id',
        'data_source_id',
        'external_name',
        'external_id',
        'external_label',
    ];
 
    public function sportType(): BelongsTo
    {
        return $this->belongsTo(SportType::class);
    }
 
    public function dataSource(): BelongsTo
    {
        return $this->belongsTo(DataSource::class);
    }
}
