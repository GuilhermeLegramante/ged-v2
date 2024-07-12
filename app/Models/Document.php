<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type_id',
        'description',
        'number',
        'date',
        'path',
        'filename',
        'validity_start',
        'validity_end',
        'tags',
        'note',
        'people',
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class);
    }
}
