@extends('voyager::master')

@section('page_title', 'Конструктор '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i>{{ 'Конструктор '.$dataType->getTranslatedAttribute('display_name_singular') }} ({{ $master->name }})
        <div class="btn btn-success add_item"><i class="voyager-plus"></i> {{ 'Новий елемент' }}</div>
    </h1>

    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    @if(view()->exists('voyager::' . $dataType->slug . '.partial.notice'))
        @include('voyager::' . $dataType->slug . '.partial.notice')
    @endif

    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <p class="panel-title" style="color:#777">{{ 'Перетягніть елементи нижче, щоб змінити їх порядок.' }}</p>
                    </div>

                    <div class="panel-body" style="padding:30px;">
                        <div class="dd">
                            @if(view()->exists('voyager::' . $dataType->slug . '.partial.items-list'))
                                @include('voyager::' . $dataType->slug . '.partial.items-list', [
                                    'items' => $masterItems,
                                    'isModelTranslatable' => $isModelTranslatable
                                ])
                            @else
                                @include('voyager::base-with-items.partial.items-list', [
                                    'items' => $masterItems,
                                    'isModelTranslatable' => $isModelTranslatable
                                ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::menu_builder.delete_item_question') }}</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.' . $dataType->slug . '.item.destroy', ['master' => $master->id, 'id' => '__id']) }}"
                          id="delete_form"
                          method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('voyager::menu_builder.delete_item_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal modal-info fade" tabindex="-1" id="create_item_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 id="m_hd_add" class="modal-title hidden"><i class="voyager-plus"></i> {{ __('voyager::menu_builder.create_new_item') }}</h4>
                    <h4 id="m_hd_edit" class="modal-title hidden"><i class="voyager-edit"></i> {{ __('voyager::menu_builder.edit_item') }}</h4>
                </div>
                <form action="" id="m_form" method="POST"
                      data-action-add="{{ route('voyager.' . $dataType->slug . '.item.add', ['master' => $master->id]) }}">

                    <input id="m_form_method" type="hidden" name="_method" value="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        @include('voyager::multilingual.language-selector')
                        <label for="name">{{ $titleColumn->display_name }}</label>
                        @include('voyager::multilingual.input-hidden', ['_field_name' => $titleColumn->field, '_field_trans' => ''])
                        <input type="text" class="form-control" id="m_title" name="{{ $titleColumn->field }}" placeholder="{{ $titleColumn->display_name }}"><br>
                        <input type="hidden" name="{{ $itemsMasterColumn }}" value="{{ $master->id }}">
                        <input type="hidden" name="id" id="m_id" value="">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success pull-right delete-confirm__" value="{{ __('voyager::generic.update') }}">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->




@stop

@section('javascript')

    <script>
        $(document).ready(function () {
            @if ($isModelTranslatable)
            /**
             * Multilingual setup for main page
             */
            $('.side-body').multilingual({
                "transInputs": '.dd-list input[data-i18n=true]'
            });

            /**
             * Multilingual for Add/Edit Menu
             */
            $('#create_item_modal').multilingual({
                "form":          'form',
                "transInputs":   '#create_item_modal input[data-i18n=true]',
                "langSelectors": '.language-selector input',
                "editing":       true
            });
            @endif


            $('.dd').nestable({
                expandBtnHTML: '',
                collapseBtnHTML: ''
            });


            /**
             * Set Variables
             */
            var $m_modal       = $('#create_item_modal'),
                $m_hd_add      = $('#m_hd_add').hide().removeClass('hidden'),
                $m_hd_edit     = $('#m_hd_edit').hide().removeClass('hidden'),
                $m_form        = $('#m_form'),
                $m_form_method = $('#m_form_method'),
                $m_title       = $('#m_title'),
                $m_title_i18n  = $('#title_i18n'),

                //$m_url         = $('#m_url'),

                $m_id          = $('#m_id');

            /**
             * Add item
             */
            $('.add_item').click(function() {
                $m_form.trigger('reset');
                $m_form.find("input[type=submit]").val('{{ __('voyager::generic.add') }}');
                $m_modal.modal('show', {data: null});
            });

            /**
             * Item Modal is Open
             */
            $m_modal.on('show.bs.modal', function(e, data) {
                var _adding      = e.relatedTarget.data ? false : true,
                    translatable = $m_modal.data('multilingual'),
                    $_str_i18n   = '';

                if (_adding) {
                    $m_form.attr('action', $m_form.data('action-add'));
                    $m_form_method.val('POST');
                    $m_hd_add.show();
                    $m_hd_edit.hide();
                   // $m_target.val('_self').change();

                } else {
                    $m_form.attr('action', $m_form.data('action-update'));
                    $m_form_method.val('PUT');
                    $m_hd_add.hide();
                    $m_hd_edit.show();

                    var _src = e.relatedTarget.data, // the source
                        id   = _src.data('id');

                    $m_title.val(_src.data('title'));
                    //$m_url.val(_src.data('url'));
                    $m_id.val(id);

                    if(translatable){
                        $_str_i18n = $("#" + '{{ $titleColumn->field }}' +  id + "_i18n").val();
                    }
                }

                if (translatable) {
                    $m_title_i18n.val($_str_i18n);
                    translatable.refresh();
                }
            });

            /**
             * Delete item
             */
            $('.item_actions').on('click', '.delete', function (e) {
                id = $(e.currentTarget).data('id');
                $('#delete_form')[0].action = '{{ route('voyager.' . $dataType->slug . '.item.destroy', ['master' => $master->id, 'id' => '__id']) }}'.replace('__id', id);
                $('#delete_modal').modal('show');
            });


            /**
             * Reorder items
             */
            $('.dd').on('change', function (e) {
                $.post('{{ route('voyager.' . $dataType->slug . '.order',['master' => $master->id]) }}', {
                    order: JSON.stringify($('.dd').nestable('serialize')),
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    toastr.success("Порядок елементів успішно оновлено");
                });
            });
        });
    </script>
@stop
