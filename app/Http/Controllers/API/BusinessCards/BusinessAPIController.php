<?php

namespace App\Http\Controllers\API\BusinessCards;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\BusinessCards;
use App\Models\Group;
use App\Models\Vcard;
use Illuminate\Http\Request;

class BusinessAPIController extends AppBaseController
{

    public function createBusinessCard(Request $request)
    {
        // Extract alias from the URL
        $url = $request->url_alias;
        $urlAlias = null;
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['path'])) {
            $pathParts = explode('/', $parsedUrl['path']);
            $urlAlias = end($pathParts);
        }

        if (isset($urlAlias)) {
            $vcard = Vcard::where('url_alias', $urlAlias)->first();

            if ($vcard) {
                BusinessCards::create([
                    'tenant_id' => getLogInTenantId(),
                    'vcard_id' => $vcard->id,
                    'url' => route('vcard.show', ['alias' => $vcard->url_alias]),
                    'group_id' => $request->group_id,
                ]);
                return $this->sendSuccess('Business card created successfully.');
            }
        }

        // If no matching Vcard found, create business card with provided data
        BusinessCards::create([
            'tenant_id' => getLogInTenantId(),
            'vcard_id' => $request->id,
            'url' => $url, // Use the provided URL
            'group_id' => $request->group_id,
        ]);

        return $this->sendSuccess('Business card created successfully.');
    }


    public function businessCardData(Request $request)
    {
        $filter = $request->all();

        $businessCards = BusinessCards::with(['vcard', 'groups'])
            ->when(!empty($filter), function ($q) use ($filter) {
                $q->whereIn('group_id', $filter['filter']);
            })
            ->get();

        $data = [];

        foreach ($businessCards as $businessCard) {
            $data[] = [
                'id' => $businessCard->id,
                'vcard_id' => $businessCard->vcard_id,
                'url' => $businessCard->url,
                'name' => $businessCard->vcard ? $businessCard->vcard->name : null,
                'occupation' => $businessCard->vcard ? $businessCard->vcard->occupation : null,
                'created_at' => $businessCard->vcard ? $businessCard->vcard->created_at : null,
                'group_name' => $businessCard->groups->name,
                'phone' =>  $businessCard->vcard->phone ?? null,
                'alternative_phone' =>  $businessCard->vcard->alternative_phone ?? null,
                'vcard_image' => !empty($businessCard->vcard->template) ? $businessCard->vcard->template->template_url : null,
            ];
        }

        return $this->sendResponse($data, 'Business Data Retrieve Successfully.');
    }
}
