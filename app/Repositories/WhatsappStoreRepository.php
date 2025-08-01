<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Analytic;
use Carbon\CarbonPeriod;
use App\Models\WhatsappStore;
use App\Models\WpStoreTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Database\Seeders\WhatsAppStoreTemplatesSeeder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class WhatsappStoreRepository extends BaseRepository
{
    public $fieldSearchable = [
        'store_name',
    ];

    /**
     * {@inheritDoc}
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
    public function model()
    {
        return WhatsappStore::class;
    }

    public function store($input)
    {
        $input['url_alias'] = str_replace(' ', '-', strtolower($input['store_name']));
        $input['template_id'] = WpStoreTemplate::first()->id;

        $whatsappStore = WhatsappStore::create($input);

        if (isset($input['logo']) && !empty($input['logo'])) {
            $whatsappStore->addMedia($input['logo'])->toMediaCollection(
                WhatsappStore::LOGO,
                config('app.media_disc')
            );
        }
        if (isset($input['cover_img']) && !empty($input['cover_img'])) {
            $whatsappStore->addMedia($input['cover_img'])->toMediaCollection(WhatsappStore::COVER_IMAGE, config('app.media_disc'));
        }

        return $whatsappStore;
    }

    public function update($whatsappStore, $input)
    {
        try {
            DB::beginTransaction();
            $whatsappStore->update($input);

            if (isset($input['logo']) && !empty($input['logo'])) {
                $whatsappStore->clearMediaCollection(WhatsappStore::LOGO);
                $whatsappStore->addMedia($input['logo'])->toMediaCollection(
                    WhatsappStore::LOGO,
                    config('app.media_disc')
                );
            }
            if (isset($input['cover_img']) && !empty($input['cover_img'])) {
                $whatsappStore->clearMediaCollection(WhatsappStore::COVER_IMAGE);
                $whatsappStore->addMedia($input['cover_img'])->toMediaCollection(WhatsappStore::COVER_IMAGE, config('app.media_disc'));
            }

            DB::commit();

            return $whatsappStore;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function analyticsData(
        $input,
        $whatsappStore
    ): array {
        $analytics = Analytic::where('whatsapp_store_id', $whatsappStore->id)->get();
        if ($analytics->count() > 0) {
            $DataCount = $analytics->count();
            $percentage = 100 / $DataCount;
            $browser = $analytics->groupBy('browser');
            $data = [];
            foreach ($browser as $key => $item) {
                $browser_record[$key]['count'] = $item->count();
                $browser_record[$key]['per'] = $item->count() * $percentage;
            }

            $browser_data = collect($browser_record)->sortBy('count')->reverse()->toArray();

            $data['browser'] = $browser_data;

            $device = $analytics->groupBy('device');

            foreach ($device as $key => $item) {
                $device_record[$key]['count'] = $item->count();
                $device_record[$key]['per'] = $item->count() * $percentage;
            }

            $device_data = collect($device_record)->sortBy('count')->reverse()->toArray();

            $data['device'] = $device_data;

            $country = $analytics->groupBy('country');

            foreach ($country as $key => $item) {
                $country_record[$key]['count'] = $item->count();
                $country_record[$key]['per'] = $item->count() * $percentage;
            }

            $country_data = collect($country_record)->sortBy('count')->reverse()->toArray();

            $data['country'] = $country_data;

            $operating_system = $analytics->groupBy('operating_system');

            foreach ($operating_system as $key => $item) {
                $operating_record[$key]['count'] = $item->count();
                $operating_record[$key]['per'] = $item->count() * $percentage;
            }

            $operating_data = collect($operating_record)->sortBy('count')->reverse()->toArray();

            $data['operating_system'] = $operating_data;

            $language = $analytics->groupBy('language');

            foreach ($language as $key => $item) {
                $language_record[$key]['count'] = $item->count();
                $language_record[$key]['per'] = $item->count() * $percentage;
            }

            $language_data = collect($language_record)->sortBy('count')->reverse()->toArray();

            $data['language'] = $language_data;

            $data['whatsappStoreID'] = $whatsappStore->id;

            return $data;
        }
        $data['noRecord'] = __('messages.common.no_data_available');

        return $data;
    }

    public function chartData(
        $input
    ): array {
        $startDate = isset($input['start_date']) ? Carbon::parse($input['start_date']) : '';
        $endDate = isset($input['end_date']) ? Carbon::parse($input['end_date']) : '';
        $data = [];

        $analytics = Analytic::where('whatsapp_store_id', $input['whatsappStoreID']);
        $visitor = $analytics->addSelect([DB::raw('DAY(created_at) as day,created_at')])
            ->addSelect([DB::raw('Month(created_at) as month,created_at')])
            ->addSelect([DB::raw('YEAR(created_at) as year,created_at')])
            ->orderBy('created_at')
            ->get();
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $data['totalVisitorCount'][] = $visitor->where('day', $date->format('d'))->where(
                'month',
                $date->format('m')
            )->where('year', $date->format('Y'))->count();
            $data['weeklyLabels'][] = $date->format('d-m-y');
        }

        return $data;
    }
}
