<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

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

        'document',
        'folder_id',
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

    public function getDocumentUrlAttribute()
    {
        return Storage::url($this->document);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }
}
