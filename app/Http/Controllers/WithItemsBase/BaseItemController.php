<?php

namespace App\Http\Controllers\WithItemsBase;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class BaseItemController extends VoyagerBaseController
{
    private static function getParentConfig($dataType) {

        $relationships = Voyager::model('DataRow')->whereDataTypeId($dataType->id)->whereType('relationship')->get();

        foreach ($relationships as $key => $value) {
            if (isset($value->details->isItemsParent) && $value->details->isItemsParent) {
                return [
                    'parentDataTypeSlug' => isset($value->details->parentDataTypeSlug) ? $value->details->parentDataTypeSlug : null,
                    'column' => $value->details->column
                ];
            }
        }
        return null;
    }


    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        }

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        event(new BreadDataUpdated($dataType, $data));

        $parentConf = static::getParentConfig($dataType);
        if ($parentConf['parentDataTypeSlug']) {
            return redirect()
                ->route("voyager." . $parentConf['parentDataTypeSlug'] . ".builder", ['master' => $data[$parentConf['column']]])
                ->with([
                    'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }


        if (auth()->user()->can('browse', $model)) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }
}
