<?php

namespace App\Models\Supplier;

use App\Models\Purchase\PurchaseOrders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManagementSupplier extends Model
{
    use HasFactory;
    protected $table = 'management_suppliers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'contact_person',
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrders::class, 'supplier_id');
    }
}
