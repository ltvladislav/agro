<?php

namespace App\Http\Controllers\WithItemsBase;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class BaseWithItemsController extends VoyagerBaseController
{
    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];

        $searchNames = [];
        if ($dataType->server_side) {
            $searchable = SchemaManager::describeTable(app($dataType->model_name)->getTable())->pluck('name')->toArray();
            $dataRow = Voyager::model('DataRow')->whereDataTypeId($dataType->id)->get();
            foreach ($searchable as $key => $value) {
                $displayName = $dataRow->where('field', $value)->first()->getTranslatedAttribute('display_name');
                $searchNames[$value] = $displayName ?: ucwords(str_replace('_', ' ', $value));
            }
        }

        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', null);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $model->{$dataType->scope}();
            } else {
                $query = $model::select('*');
            }

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model)) && Auth::user()->can('delete', app($dataType->model_name))) {
                $usesSoftDeletes = true;

                if ($request->get('showSoftDeleted')) {
                    $showSoftDeleted = true;
                    $query = $query->withTrashed();
                }
            }

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';
                $query->where($search->key, $search_filter, $search_value);
            }

            if ($orderBy && in_array($orderBy, $dataType->fields())) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                $dataTypeContent = call_user_func([
                    $query->orderBy($orderBy, $querySortOrder),
                    $getter,
                ]);
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        // Check if BREAD is Translatable
        if (($isModelTranslatable = is_bread_translatable($model))) {
            $dataTypeContent->load('translations');
        }

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $dataType->default_search_key ?? null;

        // Actions
        $actions = [];
        if (!empty($dataTypeContent->first())) {
            foreach (Voyager::actions() as $action) {
                $action = new $action($dataType, $dataTypeContent->first());

                if ($action->shouldActionDisplayOnDataType()) {
                    $actions[] = $action;
                }
            }
        }

        // Define showCheckboxColumn
        $showCheckboxColumn = false;
        if (Auth::user()->can('delete', app($dataType->model_name))) {
            $showCheckboxColumn = true;
        } else {
            foreach ($actions as $action) {
                if (method_exists($action, 'massAction')) {
                    $showCheckboxColumn = true;
                }
            }
        }

        // Define orderColumn
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + ($showCheckboxColumn ? 1 : 0);
            $orderColumn = [[$index, 'desc']];
            if (!$sortOrder && isset($dataType->order_direction)) {
                $sortOrder = $dataType->order_direction;
                $orderColumn = [[$index, $dataType->order_direction]];
            } else {
                $orderColumn = [[$index, 'desc']];
            }
        }

        $view = 'voyager::base-with-items.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        $visibleMethodName = static::getItemsVisibleMethodName($dataType);
        return Voyager::view($view, compact(
            'actions',
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'sortOrder',
            'searchNames',
            'isServerSide',
            'defaultSearchKey',
            'usesSoftDeletes',
            'showSoftDeleted',
            'showCheckboxColumn',
            'visibleMethodName'
        ));
    }


    private function getColumnByName($dataType, $columnName) {
        foreach ($dataType->editRows as $key => $row) {
            if ($row->field == $columnName) {
                return $row;
            }
        }
        return null;
    }
    private static function getConfig($slug) {
        $dataType = Voyager::model('DataType')->whereSlug($slug)->first();
        $relationships = Voyager::model('DataRow')->whereDataTypeId($dataType->id)->whereType('relationship')->get();

        foreach ($relationships as $key => $value) {
            if (isset($value->details->isItems) && $value->details->isItems) {
                return [
                    'dataType' => $dataType,
                    'itemDataType' => Voyager::model('DataType')->whereSlug($value->details->dataTypeSlug)->first(),
                    'itemsProperty' => $value->details->itemsProperty,
                    'itemsMasterColumn' => $value->details->column
                ];
            }
        }
        return null;
    }
    private static function getItemsVisibleMethodName($dataType) {
        $relationships = Voyager::model('DataRow')->whereDataTypeId($dataType->id)->whereType('relationship')->get();

        foreach ($relationships as $key => $value) {
            if (isset($value->details->isItems) && $value->details->isItems) {
                return isset($value->details->itemsVisibleMethod) ? $value->details->itemsVisibleMethod : null;
            }
        }
        return null;
    }


    //ALL
    public function builder(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $conf = static::getConfig($slug);
        $dataType = $conf['dataType'];
        $itemDataType = $conf['itemDataType'];

        $model = app($dataType->model_name);
        $entity = $model::findOrFail($id);

        $this->authorize('edit', $entity);

        $isModelTranslatable = Voyager::translatable($model);

        $viewName = view()->exists('voyager::' . $slug . '.builder') ?
            'voyager::' . $slug . '.builder' :
            'voyager::base-with-items.builder';
        return Voyager::view($viewName, [
            'master' => $entity,
            'masterItems' => $entity[$conf['itemsProperty']],
            'isModelTranslatable' => $isModelTranslatable,
            'dataType' => $dataType,
            'itemDataType' => $itemDataType,
            'titleColumn' => $this->getColumnByName($itemDataType, $itemDataType->details->order_display_column),
            'itemsMasterColumn' =>$conf['itemsMasterColumn'],
            'childConf' => static::getChildItemsConfig($itemDataType)
        ]);
    }
    //ALL
    public function delete_item(Request $request, $master, $id)
    {
        $slug = $this->getSlug($request);

        $conf = static::getConfig($slug);

        $dataType = $conf['dataType'];
        $itemDataType = $conf['itemDataType'];

        $model = app($itemDataType->model_name);

        $item = $model::findOrFail($id);

        $this->authorize('delete', $item);

        $item->deleteAttributeTranslation($itemDataType->details->order_display_column);

        $item->destroy($id);

        return redirect()
            ->route('voyager.' . $slug . '.builder', [$master])
            ->with([
                'message'    => __('voyager::generic.successfully_restored')." ".$itemDataType->getTranslatedAttribute('display_name_singular'),
                'alert-type' => 'success',
            ]);
    }
    //ALL
    public function add_item(Request $request)
    {
        $slug = $this->getSlug($request);

        $conf = static::getConfig($slug);
        $dataType = $conf['dataType'];
        $itemDataType = $conf['itemDataType'];


        $itemModel = app($itemDataType->model_name);

        $this->authorize('add', $itemModel);

        $data = $this->prepareParameters(
            $request->all()
        );

        unset($data['id']);
        $data['order'] = static::highestOrderItem($itemDataType);

        $_isTranslatable = Voyager::translatable($itemModel);
        if ($_isTranslatable) {
            $trans = $this->prepareItemTranslations($data, $itemDataType);
        }
        $item = $itemModel::create($data);
        if ($_isTranslatable) {
            $item->setAttributeTranslations($itemDataType->details->order_display_column, $trans, true);
        }

        return redirect()
            ->route('voyager.' . $itemDataType->slug . '.edit', ['id' => $item->id])
            ->with([
                'message'    => __('voyager::generic.successfully_added_new')." {$itemDataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
    }
    //ALL
    public function order_item(Request $request)
    {
        $itemOrders = json_decode($request->input('order'));
        $slug = $this->getSlug($request);
        $conf = static::getConfig($slug);
        $dataType = $conf['dataType'];
        $itemDataType = $conf['itemDataType'];
        $itemModel = app($itemDataType->model_name);

        $childConf = static::getChildItemsConfig($itemDataType);

        $this->changeItemOrder($itemOrders, null, $itemModel, $childConf);
    }

    private function getChildItemsConfig($itemDataType) {
        $relationships = Voyager::model('DataRow')->whereDataTypeId($itemDataType->id)->whereType('relationship')->get();
        foreach ($relationships as $key => $value) {
            if (isset($value->details->isChildItems) && $value->details->isChildItems) {
                return [
                    'childItemsProperty' => $value->details->childItemsProperty,
                    'itemsMasterColumn' => $value->details->column
                ];
            }
        }
        return null;
    }

    private function changeItemOrder(array $itemOrders, $parentId, $itemModel, $childConf = null)
    {
        foreach ($itemOrders as $index => $itemOrder) {
            $item = $itemModel::findOrFail($itemOrder->id);
            $item->order = $index + 1;
            if (!is_null($childConf)) {
                $item[$childConf['itemsMasterColumn']] = $parentId;
            }
            $item->save();

            if (isset($itemOrder->children)) {
                $this->changeItemOrder($itemOrder->children, $item->id, $itemModel, $childConf);
            }
        }
    }
    //ALL
    protected function prepareParameters($parameters)
    {
        return $parameters;
    }
    //ALL
    protected function prepareItemTranslations(&$data, $itemDataType)
    {
        $titleColumnName = $itemDataType->details->order_display_column;
        $trans = json_decode($data[$titleColumnName.'_i18n'], true);

        $data[$titleColumnName] = $trans[config('voyager.multilingual.default', 'en')];

        unset($data[$titleColumnName.'_i18n']);
        unset($data['i18n_selector']);

        return $trans;
    }

    //ALL
    protected static function highestOrderItem($itemDataType)
    {
        $itemModel = app($itemDataType->model_name);
        $order = 1;

        $item = $itemModel->orderBy($itemDataType->details->order_column, 'DESC')
            ->first();

        if (!is_null($item)) {
            $order = intval($item[$itemDataType->details->order_column]) + 1;
        }

        return $order;
    }
}
