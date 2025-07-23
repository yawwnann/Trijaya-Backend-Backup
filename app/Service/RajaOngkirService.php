<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Province;
use App\Models\Regency;

class RajaOngkirService
{
    private $apiKey;
    private $baseUrl;
    private $originCityId; // ID kota asal (toko Anda)

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
        $this->baseUrl = config('services.rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        $this->originCityId = config('services.rajaongkir.origin_city_id', '419'); // Default: Kulon Progo
    }

    /**
     * Mapping ID wilayah lokal ke Raja Ongkir
     */
    private function getRajaOngkirMapping()
    {
        return [
            'provinces' => [
                '34' => '5',   // DIY
                '31' => '6',   // DKI Jakarta
                '32' => '9',   // Jawa Barat
                '33' => '10',  // Jawa Tengah
                '35' => '11',  // Jawa Timur
                '36' => '3',   // Banten
                '51' => '1',   // Bali
                '52' => '22',  // Nusa Tenggara Barat
                '53' => '23',  // Nusa Tenggara Timur
                '61' => '12',  // Kalimantan Barat
                '62' => '13',  // Kalimantan Tengah
                '63' => '14',  // Kalimantan Selatan
                '64' => '15',  // Kalimantan Timur
                '65' => '16',  // Kalimantan Utara
                '71' => '31',  // Sulawesi Utara
                '72' => '32',  // Sulawesi Tengah
                '73' => '33',  // Sulawesi Selatan
                '74' => '34',  // Sulawesi Tenggara
                '75' => '35',  // Gorontalo
                '76' => '36',  // Sulawesi Barat
                '81' => '81',  // Maluku
                '82' => '82',  // Maluku Utara
                '91' => '94',  // Papua
                '92' => '95',  // Papua Barat
                '11' => '21',  // Aceh
                '12' => '34',  // Sumatera Utara
                '13' => '32',  // Sumatera Barat
                '14' => '33',  // Riau
                '15' => '35',  // Jambi
                '16' => '36',  // Sumatera Selatan
                '17' => '37',  // Bengkulu
                '18' => '38',  // Lampung
                '19' => '39',  // Kepulauan Bangka Belitung
                '21' => '40',  // Kepulauan Riau
            ],
            'cities' => [
                // DIY
                '3401' => '419', // Kulon Progo
                '3402' => '420', // Bantul
                '3403' => '421', // Gunung Kidul
                '3404' => '422', // Sleman
                '3471' => '423', // Yogyakarta

                // DKI Jakarta
                '3171' => '151', // Jakarta Selatan
                '3172' => '152', // Jakarta Timur
                '3173' => '153', // Jakarta Pusat
                '3174' => '154', // Jakarta Barat
                '3175' => '155', // Jakarta Utara
                '3176' => '156', // Jakarta Kepulauan Seribu

                // Jawa Barat (beberapa contoh)
                '3201' => '424', // Bogor
                '3202' => '425', // Sukabumi
                '3203' => '426', // Cianjur
                '3204' => '427', // Bandung
                '3205' => '428', // Garut
                '3206' => '429', // Tasikmalaya
                '3207' => '430', // Ciamis
                '3208' => '431', // Kuningan
                '3209' => '432', // Cirebon
                '3210' => '433', // Majalengka
                '3211' => '434', // Sumedang
                '3212' => '435', // Indramayu
                '3213' => '436', // Subang
                '3214' => '437', // Purwakarta
                '3215' => '438', // Karawang
                '3216' => '439', // Bekasi
                '3217' => '440', // Bandung Barat
                '3218' => '441', // Pangandaran
                '3271' => '442', // Bogor
                '3272' => '443', // Sukabumi
                '3273' => '444', // Bandung
                '3274' => '445', // Cirebon
                '3275' => '446', // Bekasi
                '3276' => '447', // Depok
                '3277' => '448', // Cimahi
                '3278' => '449', // Tasikmalaya
                '3279' => '450', // Banjar

                // Jawa Tengah (beberapa contoh)
                '3301' => '451', // Cilacap
                '3302' => '452', // Banyumas
                '3303' => '453', // Purbalingga
                '3304' => '454', // Banjarnegara
                '3305' => '455', // Kebumen
                '3306' => '456', // Purworejo
                '3307' => '457', // Wonosobo
                '3308' => '458', // Magelang
                '3309' => '459', // Boyolali
                '3310' => '460', // Klaten
                '3311' => '461', // Sukoharjo
                '3312' => '462', // Wonogiri
                '3313' => '463', // Karanganyar
                '3314' => '464', // Sragen
                '3315' => '465', // Grobogan
                '3316' => '466', // Blora
                '3317' => '467', // Rembang
                '3318' => '468', // Pati
                '3319' => '469', // Kudus
                '3320' => '470', // Jepara
                '3321' => '471', // Demak
                '3322' => '472', // Semarang
                '3323' => '473', // Temanggung
                '3324' => '474', // Kendal
                '3325' => '475', // Batang
                '3326' => '476', // Pekalongan
                '3327' => '477', // Pemalang
                '3328' => '478', // Tegal
                '3329' => '479', // Brebes
                '3371' => '480', // Magelang
                '3372' => '481', // Surakarta
                '3373' => '482', // Salatiga
                '3374' => '483', // Semarang
                '3375' => '484', // Pekalongan
                '3376' => '485', // Tegal

                // Jawa Timur (beberapa contoh)
                '3501' => '486', // Pacitan
                '3502' => '487', // Ponorogo
                '3503' => '488', // Trenggalek
                '3504' => '489', // Tulungagung
                '3505' => '490', // Blitar
                '3506' => '491', // Kediri
                '3507' => '492', // Malang
                '3508' => '493', // Lumajang
                '3509' => '494', // Jember
                '3510' => '495', // Banyuwangi
                '3511' => '496', // Bondowoso
                '3512' => '497', // Situbondo
                '3513' => '498', // Probolinggo
                '3514' => '499', // Pasuruan
                '3515' => '500', // Sidoarjo
                '3516' => '501', // Mojokerto
                '3517' => '502', // Jombang
                '3518' => '503', // Nganjuk
                '3519' => '504', // Madiun
                '3520' => '505', // Magetan
                '3521' => '506', // Ngawi
                '3522' => '507', // Bojonegoro
                '3523' => '508', // Tuban
                '3524' => '509', // Lamongan
                '3525' => '510', // Gresik
                '3526' => '511', // Bangkalan
                '3527' => '512', // Sampang
                '3528' => '513', // Pamekasan
                '3529' => '514', // Sumenep
                '3571' => '515', // Kediri
                '3572' => '516', // Blitar
                '3573' => '517', // Malang
                '3574' => '518', // Probolinggo
                '3575' => '519', // Pasuruan
                '3576' => '520', // Mojokerto
                '3577' => '521', // Madiun
                '3578' => '522', // Surabaya
                '3579' => '523', // Batu
            ]
        ];
    }

    /**
     * Dapatkan ID Raja Ongkir berdasarkan ID wilayah lokal
     */
    private function getRajaOngkirId($localId, $type = 'city')
    {
        $mapping = $this->getRajaOngkirMapping();
        $key = null;
        switch ($type) {
            case 'city':
                $key = 'cities';
                break;
            case 'province':
                $key = 'provinces';
                break;
            default:
                $key = $type . 's';
        }
        $localId = (string) $localId; // Paksa jadi string
        Log::info('getRajaOngkirId', [
            'localId' => $localId,
            'type' => $type,
            'mapping_keys' => array_keys($mapping[$key])
        ]);
        return $mapping[$key][$localId] ?? null;
    }

    private function getLocalRegencyIdFromKomerce($komerceId)
    {
        $mapping = [
            '419' => '3471', // Yogyakarta
            '31555' => '3524', // Lamongan
            '68423' => '3471', // Yogyakarta
            // Tambahkan Komerce ID lain di sini jika muncul di log
        ];
        if (!isset($mapping[$komerceId])) {
            Log::warning('Komerce ID belum ada di mapping getLocalRegencyIdFromKomerce', ['komerceId' => $komerceId]);
        }
        return $mapping[$komerceId] ?? null;
    }

    private function getCityNameById($id)
    {
        $localId = $this->getLocalRegencyIdFromKomerce($id);
        Log::info('DEBUG: getCityNameById', [
            'input_id' => $id,
            'local_id' => $localId ?? null
        ]);
        if (!$localId) {
            return ['city_name' => '', 'province' => ''];
        }
        $regency = Regency::find($localId);
        Log::info('DEBUG: getCityNameById regency', [
            'regency' => $regency,
            'province_id' => $regency ? $regency->province_id : null
        ]);
        if ($regency) {
            $provinceModel = Province::find($regency->province_id);
            $provinceName = $provinceModel ? $provinceModel->name : '';
            return [
                'city_name' => $regency->name,
                'province' => $provinceName
            ];
        }
        return [
            'city_name' => '',
            'province' => ''
        ];
    }

    /**
     * Hitung ongkir menggunakan Raja Ongkir
     */
    public function calculateShippingCost($destinationCityId, $weight = 1000, $courier = null)
    {
        Log::info('DEBUG: Masuk calculateShippingCost', [
            'destinationCityId' => $destinationCityId,
            'weight' => $weight,
            'courier' => $courier
        ]);
        try {
            $originId = $this->originCityId;
            $destinationId = $destinationCityId;
            $allCouriers = implode(':', array_keys($this->getAvailableCouriers()));
            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'content-type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post($this->baseUrl . '/calculate/domestic-cost', [
                        'origin' => $originId,
                        'destination' => $destinationId,
                        'weight' => $weight,
                        'courier' => $allCouriers,
                        'price' => 'lowest',
                    ]);
            Log::info('DEBUG: Komerce response', ['body' => $response->body()]);
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']) && is_array($data['data']) && count($data['data']) > 0) {
                    $results = [];
                    foreach ($data['data'] as $cost) {
                        $results[] = $cost;
                    }
                    return [
                        'success' => true,
                        'message' => 'Berhasil mendapatkan data ongkir',
                        'costs' => $results,
                        'origin' => null,
                        'destination' => null
                    ];
                }
            }
            return [
                'success' => false,
                'message' => 'Tidak ada layanan pengiriman yang tersedia',
                'costs' => []
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'costs' => []
            ];
        }
    }

    /**
     * Dapatkan daftar kurir yang tersedia
     */
    public function getAvailableCouriers()
    {
        return [
            'jne' => 'JNE',
            'pos' => 'POS Indonesia',
            'tiki' => 'TIKI',
            'sicepat' => 'SiCepat',
            'anteraja' => 'AnterAja',
            'jnt' => 'J&T Express',
            'ninja' => 'Ninja Xpress',
            'wahana' => 'Wahana'
        ];
    }

    /**
     * Cache hasil perhitungan ongkir
     */
    public function getCachedShippingCost($destinationCityId, $weight = 1000, $courier = 'jne')
    {
        Log::info('DEBUG: Masuk getCachedShippingCost', [
            'destinationCityId' => $destinationCityId,
            'weight' => $weight,
            'courier' => $courier
        ]);
        $cacheKey = "shipping_cost_{$destinationCityId}_{$weight}_{$courier}";

        return Cache::remember($cacheKey, 3600, function () use ($destinationCityId, $weight, $courier) {
            return $this->calculateShippingCost($destinationCityId, $weight, $courier);
        });
    }
}