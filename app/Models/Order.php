<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Order extends Model
{
    public const STATUSES = [
        'pendente' => 'Pendente',
        'completed' => 'Concluído',
        'cancelado' => 'Cancelado',
    ];

    protected $fillable = [
        'customer_id',
        'valor_total',
        'status',
    ];

    public static function label(?string $status): string
    {
        return self::STATUSES[$status] ?? ($status ? ucfirst($status) : '—');
    }

    public function holdsStock(): bool
    {
        return $this->status !== 'cancelado';
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function syncReservation(int $customerId, string $status, ?int $quantity = null): void
    {
        DB::transaction(function () use ($customerId, $status, $quantity) {
            $this->load('items.product');

            $before = $this->holdsStock() ? $this->reservedQuantities() : [];

            if ($quantity !== null && $this->items->count() === 1) {
                $item = $this->items->first();
                $item->quantidade = $quantity;
                $item->save();
                $this->valor_total = round((float) $item->preco_unitario * $quantity, 2);
            }

            $this->customer_id = $customerId;
            $this->status = $status;

            $after = $this->holdsStock() ? $this->reservedQuantities() : [];
            $this->applyStockDelta($before, $after);
            $this->save();
        });
    }

    public function deleteReturningStock(): void
    {
        DB::transaction(function () {
            $this->load('items.product');

            if ($this->holdsStock()) {
                $this->applyStockDelta($this->reservedQuantities(), []);
            }

            $this->delete();
        });
    }

    private function reservedQuantities(): array
    {
        $quantities = [];

        foreach ($this->items as $item) {
            $quantities[$item->product_id] = ($quantities[$item->product_id] ?? 0) + $item->quantidade;
        }

        return $quantities;
    }

    private function applyStockDelta(array $before, array $after): void
    {
        foreach (array_unique([...array_keys($before), ...array_keys($after)]) as $productId) {
            $delta = ($after[$productId] ?? 0) - ($before[$productId] ?? 0);

            if ($delta === 0) {
                continue;
            }

            $product = Product::query()->lockForUpdate()->find($productId);

            if (! $product) {
                continue;
            }

            if ($delta > 0 && $product->estoque < $delta) {
                throw ValidationException::withMessages([
                    'quantity' => 'Estoque insuficiente para '.$product->name.'. Disponível: '.$product->estoque.'.',
                ]);
            }

            $delta > 0
                ? $product->decrement('estoque', $delta)
                : $product->increment('estoque', abs($delta));
        }
    }
}
