<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_date',
        'trx_no',
        'serial_no',
        'clerk',
        'term_no',
        'amount_due',
        'cash',
        'change',
        'vat_sales',
        'vat',
        'vat_exempt',
        'vat_zero',
        'total_sales',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'datetime',
            'amount_due' => 'decimal:2',
            'cash' => 'decimal:2',
            'change' => 'decimal:2',
            'vat_sales' => 'decimal:2',
            'vat' => 'decimal:2',
            'vat_exempt' => 'decimal:2',
            'vat_zero' => 'decimal:2',
            'total_sales' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
