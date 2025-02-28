<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\InvoiceSettings;
use App\Http\Controllers\Controller;
use App\Services\BusinessSettingService;
use App\Traits\FileManagerTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceSettingsController extends Controller
{
    use FileManagerTrait {
        delete as deleteFile;
        update as updateFile;
    }
    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
        private readonly BusinessSettingService $businessSettingService,
    )
    {
    }
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getView();
    }

    public function getView(): View
    {
        $invoiceSettings = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type'=>'invoice_settings']));
        if(!isset($invoiceSettings)){
            $invoiceSettings = json_encode($this->businessSettingService->getInvoiceSettingsData(request:null,imageArray:null));
            $this->businessSettingRepo->updateOrInsert(type: 'invoice_settings', value: $invoiceSettings);
            clearWebConfigCacheKeys();
        }else{
            $invoiceSettings = $invoiceSettings->value;
        }
        $invoiceSettings = json_decode($invoiceSettings);
        return view(InvoiceSettings::VIEW[VIEW],compact('invoiceSettings'));
    }
    public function update(Request $request):JsonResponse
    {
        $invoiceSettings = json_decode(json: $this->businessSettingRepo->getFirstWhere(params: ['type'=>'invoice_settings'])->value, associative: true);

        $image = isset($invoiceSettings->image) && $request->has('image') ?
            $this->updateFile(dir: 'company/', oldImage: (is_array($invoiceSettings['image']) ? $invoiceSettings['image']['image_name'] : $invoiceSettings['image'] ), format: 'webp', image: $request->file('image'))
            : ($request->has('image') ? $this->upload(dir: 'company/', format: 'webp', image: $request->file('image')): $invoiceSettings?->image);
        $imageArray = [
            'image_name' => $image ,
            'storage' => config('filesystems.disks.default') ?? 'public'
        ];
        $value = $this->businessSettingService->getInvoiceSettingsData(request:$request,imageArray:$imageArray);
        $this->businessSettingRepo->updateOrInsert(type: 'invoice_settings', value: json_encode($value));
        clearWebConfigCacheKeys();
        return response()->json(['message'=>translate('invoice_settings_update_successfully')]);
    }
}
