<x-filament-panels::page>
    <x-filament-widgets::widgets :widgets="[
        App\Filament\Widgets\StatistikOverview::class,
    ]" :columns="[
        'sm' => 1,
        'md' => 12,
    ]" />
    <x-filament-widgets::widgets :widgets="[
        App\Filament\Widgets\OrdersChart::class,
        App\Filament\Widgets\CustomersChart::class,
    ]" :columns="[
        'sm' => 1,
        'md' => 12,
    ]" />
</x-filament-panels::page>