<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class PersonTypeDocumentType extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'person_type_document_types';
    protected $fillable = [
        'person_type_id',
        'document_type_id'
    ];

    protected $auditInclude = [
        'person_type_id',
        'document_type_id'
    ];

    public function person_type() : BelongsTo
    {
        return $this->belongsTo(PersonType::class, 'person_type_id');
    }

    public function document_type() : BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }
}
