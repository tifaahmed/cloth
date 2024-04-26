<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Relations
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// Scopes
use App\Scopes\ScopeMySession; // my_session / MySession
use App\Scopes\ScopeItems; // items / Items
use App\Scopes\ScopeAuthUser; // auth_user  

class Cart extends Model
{
    use HasFactory, ScopeMySession, ScopeItems, ScopeAuthUser;
    protected $table = 'carts';

    protected $fillable = [
        'session_id',
        'item_id',
        'price',
        'qty'
    ];

    public $searchable = [
    ];
    public $scopes = [
        'auth_user',
    ];
    // func
        public static function cartSumOnlyPrices($model)
        {
            $total = 0;
            foreach ($model as $key => $value) {
                $total += $value->price * $value->qty;
            }
            return  $total;
        }
        public static function decreaseItemQuantity($itemId, $vendorId, $sessionId, $quantity = 1)
        {
            $cartItem = self::where(['session_id' => $sessionId, 'vendor_id' => $vendorId, 'item_id' => $itemId])
                ->first();

            if ($cartItem) {
                if ($cartItem->qty > 1) {
                    // Decrease the quantity
                    $newQuantity = max(0, $cartItem->qty - $quantity);
                    $cartItem->qty = $newQuantity;
                    $cartItem->save();
                } else {
                    // Remove the item from the cart
                    $cartItem->delete();
                }
            }
        }
        // Method to remove an item from the cart
        public static function removeItem($sessionId, $itemId, $vendorId)
        {
            self::where(['session_id' => $sessionId, 'vendor_id' => $vendorId, 'item_id' => $itemId])
                ->delete();
        }

    // Relations    
        // Item
        public function item()
        {
            return $this->belongsTo(Item::class, 'item_id');
        }
        
}
