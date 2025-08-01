<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Plan
 *
 * @property int $id
 * @property string $name
 * @property int|null $no_of_vcards
 * @property int|null $currency_id
 * @property float|null $price
 * @property int $frequency 1 = Month, 2 = Year
 * @property int $is_default
 * @property int $trial_days Default validity will be 7 trial days
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Currency|null $currency
 * @property-read Collection|Subscription[] $hasZeroPlan
 * @property-read int|null $has_zero_plan_count
 * @property-read PlanFeature|null $planFeature
 * @property-read Collection|Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read Collection|Template[] $templates
 * @property-read int|null $templates_count
 *
 * @method static Builder|Plan newModelQuery()
 * @method static Builder|Plan newQuery()
 * @method static Builder|Plan query()
 * @method static Builder|Plan whereCreatedAt($value)
 * @method static Builder|Plan whereCurrencyId($value)
 * @method static Builder|Plan whereFrequency($value)
 * @method static Builder|Plan whereId($value)
 * @method static Builder|Plan whereIsDefault($value)
 * @method static Builder|Plan whereName($value)
 * @method static Builder|Plan whereNoOfVcards($value)
 * @method static Builder|Plan wherePrice($value)
 * @method static Builder|Plan whereTrialDays($value)
 * @method static Builder|Plan whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property int $status
 *
 * @method static Builder|Plan whereStatus($value)
 */
class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'currency_id',
        'price',
        'frequency',
        'is_default',
        'trial_days',
        'no_of_vcards',
        'storage_limit',
        'status',
        'custom_select',
        'no_of_whatsapp_store',
    ];

    protected $casts = [
        'name' => 'string',
        'currency_id' => 'integer',
        'price' => 'double',
        'frequency' => 'integer',
        'is_default' => 'integer',
        'trial_days' => 'integer',
        'no_of_vcards' => 'integer',
        'storage_limit' => 'integer',
    ];

    const IS_DEFAULT = 1;

    const IS_ACTIVE = 1;

    const IS_DEACTIVE = 0;

    const TRIAL_DAYS = 7;

    const MONTHLY = 1;

    const YEARLY = 2;

    const UNLIMITED = 3;

    const DURATION = [
        self::MONTHLY => 'Month',
        self::YEARLY => 'Year',
        self::UNLIMITED => 'Unlimited',
    ];

    const STRIPE = 1;

    const PAYPAL = 2;

    const RAZORPAY = 3;

    const MANUALLY = 4;

    const PAYSTACK = 5;

    const PHONEPE = 6;

    const FLUTTERWAVE = 7;

    const MERCADO_PAGO = 8;

    const PAYFAST = 9;

    const PAYMENT_METHOD = [
        self::STRIPE => 'Stripe',
        self::PAYPAL => 'Paypal',
        self::RAZORPAY => 'Razorpay',
        self::MANUALLY => 'Manually',
        self::PAYSTACK => 'Paystack',
        self::PHONEPE => 'PhonePe',
        self::FLUTTERWAVE => 'Flutterwave',
        self::MERCADO_PAGO => 'Mercadopago',
        self::PAYFAST => 'Payfast',
    ];

    const PAYPAL_MODE = [
        'sandbox' => 'Sandbox',
        'live' => 'Live',
    ];

    const PAYFAST_MODE = [
        'sandbox' => 'Sandbox',
        'live' => 'Live',
    ];
    

    /**
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|min:2|unique:plans,name,',
        'currency_id' => 'required',
        //         'no_of_vcards' => 'required|numeric',
        'custom_vcard_number.*' => 'required_if:custom_select,1|numeric',
        'custom_vcard_price.*' => 'required_if:custom_select,1|numeric',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function planFeature(): HasOne
    {
        return $this->hasOne(PlanFeature::class, 'plan_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Template::class);
    }

    public function hasZeroPlan()
    {
        if (getLogInUser()) {
            return $this->hasMany(Subscription::class)->where('plan_amount', 0)
                ->where('tenant_id', getLogInUser()->tenant_id);
        }

        return $this->hasMany(Subscription::class)->where('plan_amount', 0);
    }

    public function planCustomFields()
    {
        return $this->hasMany(PlanCustomField::class, 'plan_id');
    }
}
