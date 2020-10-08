@if(config('voyager.show_dev_tips'))
    <div class="container-fluid">
        <div class="alert alert-info">
            <strong>{{ __('voyager::generic.how_to_use') }}:</strong>
            <p>Ви можете вивести інфоблок в будь-якому місці вашого сайту, вставивши у сторінку чи новину <code>[infoblock code="{{ !empty($master) ? $master->slug : 'name' }}"]</code></p>
        </div>
    </div>
@endif
