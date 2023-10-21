<?php

namespace App\Http\Controllers\Voyager;

use App\Category;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class CategoriesController extends VoyagerBaseController
{
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        
        if ($dataType->scope && $dataType->scope !== '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
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
        $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        try {
            $data->parent()->associate(Category::findOrFail($request->parent_id))->save();
        } catch (\Exception $exception) {
            logger($exception);
        }

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        event(new BreadDataUpdated($dataType, $data));

        return redirect()
        ->route("voyager.{$dataType->slug}.index")
        ->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
            'alert-type' => 'success',
        ]);
    }

    /**
     * Get BREAD relations data.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function relation(Request $request)
    {
        $slug = $this->getSlug($request);
        $page = $request->input('page');
        $on_page = 50;
        $search = $request->input('search', false);
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        foreach ($dataType->editRows as $row) {
            if ($row->field !== $request->input('type')) {
                continue;
            }

            $options = $row->details;
            $skip = $on_page * ($page - 1);

            // If search query, use LIKE to filter results depending on field label
            if ($search) {
                $total_count = app($options->model)->where($options->label, 'LIKE', '%'.$search.'%')->count();
                $relationshipOptions = app($options->model)->take($on_page)->skip($skip)
                    ->where($options->label, 'LIKE', '%'.$search.'%')
                    ->get();
            } else {
                $total_count = app($options->model)->count();
                $relationshipOptions = app($options->model)->take($on_page)->skip($skip)->get();
            }

            $results[] = [
                'id' => 0,
                'text' => '-- No Parent --'
            ];

            foreach ($relationshipOptions as $relationshipOption) {
                $results[] = [
                    'id'   => $relationshipOption->{$options->key},
                    'text' => $relationshipOption->{$options->label},
                ];
            }

            return response()->json([
                'results'    => $results,
                'pagination' => [
                    'more' => ($total_count > ($skip + $on_page)),
                ],
            ]);
        }

        // No result found, return empty array
        return response()->json([], 404);
    }
}
