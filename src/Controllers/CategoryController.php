<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Controllers\Controller;
use WebAppId\Content\Models\Category AS CategoryModel;
use WebAppId\Content\Requests\CategoryRequests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

abstract class CategoryController extends Controller
{
	
	protected abstract function indexResult();
	protected abstract function showResult($result);
	protected abstract function createResult();
	protected abstract function storeResult($result);
	protected abstract function editResult($result);
	protected abstract function updateResult($result);
	protected abstract function destroyResult($result);

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return $this->indexResult();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return $this->createResult();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CategoryRequest $request, CategoryModel $categoryModel)
	{
		$request->user_id = Auth::id();

		$request->code = str::slug($request->name);

		$result['data'] = $categoryModel->addCategoryData($request);

		return $this->storeResult($result);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, CategoryModel $categoryModel)
	{
		$column = array(
			0 => 'id',
			1 => 'code',
			2 => 'name',
		);

		if(isset($request->draw)){
			$search = $request->search['value'];
			$limit_start = $request->start;
			$limit_length = $request->length;
			$order_column = $column[$request->order[0]['column']];
			$order_dir = $request->order[0]['dir'];
		}else{
			$search = $request->search['value'];
			$limit_start = 0;
			$limit_length = 10;
			$order_column = $column[0];
			$order_dir = 'asc';
		}

		$result['data'] = $categoryModel->getDatatable($search, $order_column, $order_dir, $limit_start, $limit_length);
		$result['recordsTotal'] = $categoryModel->count();
		$result['recordsFiltered'] = $categoryModel->getSearchCount($request->search['value']);

		return $this->showResult($result);
	}

	

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id, CategoryModel $categoryModel)
	{
		if ($id <= 30) {
			abort(403);
		}

		$result['category'] = $categoryModel->getOne($id);	

		return $this->editResult($result);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($id, CategoryRequest $request, CategoryModel $categoryModel)
	{
		if ($id <= 30) {
			abort(403);
		}

		$request->user_id = Auth::id();

		$request->code = str::slug($request->name);

		$result['data'] = $categoryModel->updateCategory($request, $id);

		return $this->updateResult($result);
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id, CategoryModel $categoryModel)
	{
		if ($id <= 30) {
			abort(403);
		}

		$result['data'] = $categoryModel->deleteCategory($id);
		
		return $this->destroyResult($result);
	}
}