<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Services;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Requests\CategoryRequest;

/**
 * Class CategoryService
 * @package WebAppId\Content\Services
 */
class CategoryService
{
    
    private $user_id;
    private $container;
    
    /**
     * CategoryService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->user_id = Auth::id() == null ? session('user_id') : Auth::id();
        $this->container = $container;
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @param CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        $request->user_id = $this->user_id;
        
        $request->code = str::slug($request->name);
    
        $result['data'] = $this->container->call([$categoryRepository, 'addCategory'], ['data' => $request]);
        
        return $result;
    }
    
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CategoryRepository $categoryRepository)
    {
        $column = array(
            0 => 'id',
            1 => 'code',
            2 => 'name',
        );
    
        if (isset($request->draw)) {
            $search = $request->search['value'];
            $limit_start = $request->start;
            $limit_length = $request->length;
            $order_column = $column[$request->order[0]['column']];
            $order_dir = $request->order[0]['dir'];
        } else {
            $search = $request->search['value'];
            $limit_start = 0;
            $limit_length = 10;
            $order_column = $column[0];
            $order_dir = 'asc';
        }
    
        $result['data'] = $categoryRepository->getDatatable($search, $order_column, $order_dir, $limit_start, $limit_length);
        $result['recordsTotal'] = $categoryRepository->count();
        $result['recordsFiltered'] = $categoryRepository->getSearchCount($request->search['value']);
    
        return $result;
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, CategoryRepository $categoryRespository)
    {
        if ($id <= 30) {
            abort(403);
        }
    
        $result['category'] = $categoryRespository->getOne($id);
    
        return $result;
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param CategoryRequest $request
     * @param CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function update($id, CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        if ($id <= 30) {
            abort(403);
        }
    
        $request->user_id = Auth::id();
    
        $request->code = str::slug($request->name);
    
        $result['data'] = $categoryRepository->updateCategory($request, $id);
    
        return $result;
    
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param CategoryRepository $categoryRespository
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id, CategoryRepository $categoryRepository)
    {
        if ($id <= 30) {
            abort(403);
        }
    
        $result['data'] = $categoryRepository->deleteCategory($id);
        
        return $result;
    }
}