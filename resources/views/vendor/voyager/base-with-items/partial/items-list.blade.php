<ol class="dd-list">

    @foreach ($items as $item)

        <li class="dd-item" data-id="{{ $item->id }}">
            <div class="pull-right item_actions">
                <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $item->id }}">
                    <i class="voyager-trash"></i> {{ __('voyager::generic.delete') }}
                </div>
                <a href="{{ route('voyager.'.$itemDataType->slug.'.edit', $item->{$item->getKeyName()}) }}" class="btn btn-sm btn-primary pull-right edit">
                    <i class="voyager-edit"></i> {{ __('voyager::generic.edit') }}
                </a>
            </div>
            <div class="dd-handle">
                @if($isModelTranslatable)
                    @include('voyager::multilingual.input-hidden', [
                        'isModelTranslatable' => true,
                        '_field_name'         => $titleColumn->field . $item->id,
                        '_field_trans'        => json_encode($item->getTranslationsOf($titleColumn->field))
                    ])
                @endif
                <span>{{ $item[$titleColumn->field] }}</span>
            </div>
            @if($childConf)
                @if(!$item[$childConf['childItemsProperty']]->isEmpty())
                    @if(view()->exists('voyager::' . $dataType->slug . '.partial.items-list'))
                        @include('voyager::' . $dataType->slug . '.partial.items-list', [
                            'items' => $item[$childConf['childItemsProperty']],
                            'isModelTranslatable' => $isModelTranslatable
                        ])
                    @else
                        @include('voyager::base-with-items.partial.items-list', [
                            'items' => $item[$childConf['childItemsProperty']],
                            'isModelTranslatable' => $isModelTranslatable
                        ])
                    @endif
                @endif
            @endif

        </li>

    @endforeach

</ol>
