<?php

namespace App\Models;

use App\Traits\Multitenantable;
use App\Models\Traits\StorageLimit;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Vcard
 *
 * @property int $id
 * @property string $url_alias
 * @property string $name
 * @property string|null $occupation
 * @property string|null $description
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $region_code
 * @property float|null $phone
 * @property string|null $location
 * @property string|null $location_url
 * @property string|null $made_by
 * @property string|null $made_by_url
 * @property int|null $template_id
 * @property int $share_btn
 * @property int $status
 * @property string|null $company
 * @property string|null $job_title
 * @property string|null $dob
 * @property string|null $password
 * @property int $branding
 * @property string $font_family
 * @property string|null $font_size
 * @property string|null $custom_css
 * @property string|null $custom_js
 * @property string|null $site_title
 * @property string|null $home_title
 * @property string|null $meta_keyword
 * @property string|null $meta_description
 * @property string|null $google_analytics
 * @property string $tenant_id
 * @property string $language_enable
 * @property string|null $default_language
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $alternative_phone
 * @property string|null $alternative_email
 * @property-read Collection|Analytic[] $Analytics
 * @property-read int|null $analytics_count
 * @property-read AppointmentDetail|null $appointmentDetail
 * @property-read Collection|Appointment[] $appointmentHours
 * @property-read int|null $appointment_hours_count
 * @property-read Collection|VcardBlog[] $blogs
 * @property-read int|null $blogs_count
 * @property-read Collection|BusinessHour[] $businessHours
 * @property-read int|null $business_hours_count
 * @property-read Collection|Enquiry[] $enquiry
 * @property-read int|null $enquiry_count
 * @property-read Collection|Gallery[] $gallery
 * @property-read int|null $gallery_count
 * @property-read string $cover_url
 * @property-read string $full_name
 * @property-read string $profile_url
 * @property-read string $profile_url_base64
 *  @property-read string $favicon_url
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read PrivacyPolicy|null $privacy_policy
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read Collection|VcardService[] $services
 * @property-read int|null $services_count
 * @property-read SocialLink|null $socialLink
 * @property-read Collection|Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read Template|null $template
 * @property-read MultiTenant $tenant
 * @property-read TermCondition|null $term_condition
 * @property-read Collection|Testimonial[] $testimonials
 * @property-read int|null $testimonials_count
 * @property-read User|null $user
 *
 * @method static Builder|Vcard newModelQuery()
 * @method static Builder|Vcard newQuery()
 * @method static Builder|Vcard query()
 * @method static Builder|Vcard whereAlternativeEmail($value)
 * @method static Builder|Vcard whereAlternativePhone($value)
 * @method static Builder|Vcard whereBranding($value)
 * @method static Builder|Vcard whereCompany($value)
 * @method static Builder|Vcard whereCreatedAt($value)
 * @method static Builder|Vcard whereCustomCss($value)
 * @method static Builder|Vcard whereCustomJs($value)
 * @method static Builder|Vcard whereDefaultLanguage($value)
 * @method static Builder|Vcard whereDescription($value)
 * @method static Builder|Vcard whereDob($value)
 * @method static Builder|Vcard whereEmail($value)
 * @method static Builder|Vcard whereFirstName($value)
 * @method static Builder|Vcard whereFontFamily($value)
 * @method static Builder|Vcard whereFontSize($value)
 * @method static Builder|Vcard whereGoogleAnalytics($value)
 * @method static Builder|Vcard whereHomeTitle($value)
 * @method static Builder|Vcard whereId($value)
 * @method static Builder|Vcard whereJobTitle($value)
 * @method static Builder|Vcard whereLanguageEnable($value)
 * @method static Builder|Vcard whereLastName($value)
 * @method static Builder|Vcard whereLocation($value)
 * @method static Builder|Vcard whereLocationUrl($value)
 * @method static Builder|Vcard whereMadeBy($value)
 * @method static Builder|Vcard whereMadeByUrl($value)
 * @method static Builder|Vcard whereMetaDescription($value)
 * @method static Builder|Vcard whereMetaKeyword($value)
 * @method static Builder|Vcard whereName($value)
 * @method static Builder|Vcard whereOccupation($value)
 * @method static Builder|Vcard wherePassword($value)
 * @method static Builder|Vcard wherePhone($value)
 * @method static Builder|Vcard whereRegionCode($value)
 * @method static Builder|Vcard whereShareBtn($value)
 * @method static Builder|Vcard whereSiteTitle($value)
 * @method static Builder|Vcard whereStatus($value)
 * @method static Builder|Vcard whereTemplateId($value)
 * @method static Builder|Vcard whereTenantId($value)
 * @method static Builder|Vcard whereUpdatedAt($value)
 * @method static Builder|Vcard whereUrlAlias($value)
 *
 * @mixin Eloquent
 */
class Vcard extends Model implements HasMedia
{
    use InteractsWithMedia, StorageLimit, HasFactory, BelongsToTenant, Multitenantable, Notifiable;

    protected $table = 'vcards';

    /**
     * @var string[]
     */
    protected $fillable = [
        'url_alias',
        'name',
        'occupation',
        'description',
        'first_name',
        'last_name',
        'email',
        'region_code',
        'phone',
        'location',
        'location_url',
        'template_id',
        'share_btn',
        'company',
        'job_title',
        'dob',
        'password',
        'branding',
        'font_family',
        'font_size',
        'custom_css',
        'custom_js',
        'status',
        'tenant_id',
        'qr_code_download_size',
        'site_title',
        'home_title',
        'meta_keyword',
        'meta_description',
        'google_analytics',
        'default_language',
        'language_enable',
        'enable_enquiry_form',
        'enable_download_qr_code',
        'made_by_url',
        'made_by',
        'alternative_email',
        'alternative_phone',
        'alternative_region_code',
        'show_qr_code',
        'iframes',
        'is_verified',
        'enable_affiliation',
        'enable_contact',
        'hide_stickybar',
        'whatsapp_share',
        'services_slider_view',
        'location_type',
        'location_embed_tag',
        'cover_image_type',
        'youtube_link',
        'cover_type',
    ];

    protected $casts = [
        'url_alias' => 'string',
        'name' => 'string',
        'occupation' => 'string',
        'description' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'region_code' => 'string',
        'phone' => 'string',
        'location' => 'string',
        'location_url' => 'string',
        'template_id' => 'integer',
        'share_btn' => 'integer',
        'company' => 'string',
        'job_title' => 'string',
        'dob' => 'string',
        'password' => 'string',
        'branding' => 'integer',
        'font_family' => 'string',
        'font_size' => 'string',
        'custom_css' => 'string',
        'custom_js' => 'string',
        'status' => 'integer',
        'enable_download_qr_code' => 'integer',
        'tenant_id' => 'string',
        'qr_code_download_size' => 'integer',
        'site_title' => 'string',
        'home_title' => 'string',
        'meta_keyword' => 'string',
        'meta_description' => 'string',
        'google_analytics' => 'string',
        'default_language' => 'string',
        'language_enable' => 'string',
        'enable_enquiry_form' => 'integer',
        'made_by_url' => 'string',
        'made_by' => 'string',
        'alternative_email' => 'string',
        'alternative_phone' => 'string',
        'alternative_region_code' => 'string',
        'services_slider_view' => 'integer',
        'cover_image_type' => 'string',
    ];

    const VERIFIED = 1;
    const NOTVERIFIED = 0;
    const SELECT_VERIFIED = 2;

    const STATUS_ARR = [
        self::SELECT_VERIFIED => 'Select Verified',
        self::VERIFIED => 'Verified',
        self::NOTVERIFIED => 'Not Verified',
    ];
    /**
     * @var string[]
     */
    protected $appends = ['profile_url', 'cover_url', 'profile_url_base64', 'full_name', 'favicon_url'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'url_alias' => 'string|min:6|max:100|unique:vcards,url_alias',
        'name' => 'string|min:2',
        'occupation' => 'nullable|string',
        'first_name' => 'string|min:2',
        'description' => 'nullable|string',
        'last_name' => 'string',
        'company' => 'nullable|string',
        'job_title' => 'nullable|string',
        'email' => 'nullable|email:filter',
        'phone' => 'nullable',
        'made_by_url' => 'nullable|url',
        'made_by' => 'nullable|',
        'alternative_email' => 'nullable|email:filter',
        'alternative_phone' => 'nullable',
        'youtube_link' => 'nullable|url',
        'favicon_img' => 'image|dimensions:max_width=16,max_height=16',
    ];

    const PROFILE_PATH = 'vcards/profiles';

    const FAVICON_PATH = 'vcards/favicons';

    const COVER_PATH = 'vcards/covers';

    const LANGUAGE_ENABLE = 1;

    const TEMPLATE_1 = 1;

    const TEMPLATE_2 = 2;

    const TEMPLATE_3 = 3;

    const TEMPLATE_4 = 4;

    const TEMPLATE = [
        self::TEMPLATE_1,
        self::TEMPLATE_2,
        self::TEMPLATE_3,
        self::TEMPLATE_4,
    ];

    const ACTIVE = 1;

    const INACTIVE = 0;
    const SELECT_STATUS = 2;

    const STATUS = [
        self::SELECT_STATUS => 'Select Status',
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Deactive',
    ];

    const TEMPLATE_URL = [
        self::TEMPLATE_1 => 'assets/images/default_cover_image.jpg',
        self::TEMPLATE_2 => 'assets/images/default_cover_image.jpg',
        self::TEMPLATE_3 => 'assets/images/default_cover_image.jpg',
        self::TEMPLATE_4 => 'assets/images/default_cover_image.jpg',
    ];

    const FONT_FAMILY = [
        'Poppins' => 'Default',
        'Roboto' => 'Roboto',
        'Times New Roman' => 'Times New Roman',
        'Open Sans' => 'Open Sans',
        'Montserrat' => 'Montserrat',
        'Lato' => 'Lato',
        'Raleway' => 'Raleway',
        'PT Sans' => 'PT Sans',
        'Merriweather' => 'Merriweather',
        'Prompt' => 'Prompt',
        'Work Sans' => 'Work Sans',
        'Concert One' => 'Concert One',
    ];

    const LINK = 0;

    const EMBED_TAG = 1;

    const LOCATION_TYPE = [
        self::LINK => 'link',
        self::EMBED_TAG => 'embed_tag',
    ];

    const COVER = 0;

    const CONTAIN = 1;

    const COVER_IMAGE_TYPE = [
        self::COVER => 'cover',
        self::CONTAIN => 'contain',
    ];

    const IMAGE = 0;

    const VIDEO = 1;

    const YOUTUBE_link = 2;

    const COVER_TYPE = [
        self::IMAGE => 'image',
        self::VIDEO => 'video',
        self::YOUTUBE_link => 'youtube_link',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getProfileUrlAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::PROFILE_PATH)->first();
        if ($media !== null) {
            return $media->getFullUrl();
        }

        return asset('web/media/avatars/user2.png');
    }

    public function getProfileUrlBase64Attribute(): string
    {
        $url = asset('web/media/avatars/user2.png');
        /** @var Media $media */
        $media = $this->getMedia(self::PROFILE_PATH)->first();
        if ($media !== null) {
            $url = $media->getFullUrl();
        }

        return base64_encode($url);
    }

    public function getCoverUrlAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::COVER_PATH)->first();
        if ($media !== null) {
            return $media->getFullUrl();
        }

        return asset('assets/images/default_cover_image.jpg');
    }


    public function getFaviconUrlAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::FAVICON_PATH)->first();
        if ($media !== null) {
            return $media->getFullUrl();
        }
        $setting = Setting::where('key', 'favicon')->first();
        if ($setting && !empty($setting->favicon_url)) {
            return $setting->favicon_url;
        }
        return asset('web/media/logos/favicon-infyom.png');
    }
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(VcardService::class, 'vcard_id');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(Gallery::class, 'vcard_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'vcard_id');
    }

    public function InstagramEmbed(): HasMany
    {
        return $this->hasMany(InstagramEmbed::class, 'vcard_id');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'vcard_id');
    }

    public function socialLink(): HasOne
    {
        return $this->hasOne(SocialLink::class, 'vcard_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'tenant_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'tenant_id', 'tenant_id');
    }

    public function appointmentDetail(): HasOne
    {
        return $this->hasOne(AppointmentDetail::class, 'vcard_id');
    }

    public function businessCard(): HasOne
    {
        return $this->hasOne(BusinessCards::class, 'vcard_id');
    }

    public function businessHours(): HasMany
    {
        return $this->hasMany(BusinessHour::class, 'vcard_id', 'id');
    }

    public function appointmentHours(): HasMany
    {
        return $this->hasMany(Appointment::class, 'vcard_id', 'id');
    }

    public function enquiry(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'vcard_id');
    }

    public function Analytics(): HasMany
    {
        return $this->hasMany(Analytic::class, 'vcard_id');
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(VcardBlog::class, 'vcard_id');
    }

    public function privacy_policy(): HasOne
    {
        return $this->hasOne(PrivacyPolicy::class, 'vcard_id');
    }

    public function term_condition(): HasOne
    {
        return $this->hasOne(TermCondition::class, 'vcard_id');
    }

    public function schedule_appointments(): HasMany
    {
        return $this->hasMany(ScheduleAppointment::class, 'vcard_id');
    }
    public function subscriptions_email(): HasMany
    {
        return $this->hasMany(VcardEmailSubscription::class, 'vcard_id');
    }
    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    public function iframes()
    {
        return $this->hasMany(Iframe::class);
    }
    public function dynamic_vcard(): HasMany
    {
        return $this->hasMany(DynamicVcard::class, 'vcard_id');
    }

    public function customeLink(): HasMany
    {
        return $this->hasMany(CustomLink::class, 'vcard_id');
    }

    public function Qrcode()
    {
        return $this->hasMany(QrcodeEdit::class);
    }

    public function routeNotificationForSlack(Notification $notification): string
    {
        // return $this->webhook_url;
        return config('services.slack.webhook_url', $this->webhook_url);
    }
}
