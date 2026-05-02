<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','customer_name','phone','email',
        'address_line1','address_line2','ward','district','province',
        'status','payment_method',
        'subtotal','discount','delivery_fee',
        'deposit_amount','remaining_amount','total',
        'paid_at','delivered_at','notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // Tính tổng tiền
    public function recalcTotals(): void
    {
        $subtotal = $this->items->sum(fn($i) => $i->line_total);

        $this->subtotal = $subtotal;
        $this->total = $subtotal - $this->discount + $this->delivery_fee;

        // Nếu có cọc
        $this->remaining_amount = $this->total - $this->deposit_amount;
    }
}
